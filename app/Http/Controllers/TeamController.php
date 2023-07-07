<?php

namespace App\Http\Controllers;

use App\DataTables\TeamsDataTable;
use App\DataTables\TeamMembersDataTable;
use App\Facades\Helper;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Models\Team;
use App\Models\User;
use App\Notifications\InviteMemberNotification;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TeamsDataTable $dataTable)
    {
        return $dataTable->render('teams.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $team = new Team();
        $roles = $this->getRoles();

        return view('teams.create', compact('team', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeamRequest $request): RedirectResponse
    {
        $data = $this->getData($request);
        Team::query()->create($data);
        flash()->addSuccess(__('Team created successfully'));

        return redirect()->route('teams.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Team $team, Builder $builder): View|Application|Factory|JsonResponse|\Illuminate\Contracts\Foundation\Application
    {
        $columns = [
            ['data' => 'name', 'name' => 'name', 'title' => __('Name')],
            [
                'data' => 'role', 'name' => 'role', 'title' => __('Role'), 'render' => null, 'orderable' => false,
                'searchable' => false, 'exportable' => false, 'printable' => false, 'footer' => '',
            ],
            [
                'data' => 'action', 'name' => 'action', 'title' => __('Action'), 'render' => null, 'orderable' => false, 'searchable' => false,
                'exportable' => false, 'printable' => false, 'footer' => '',
            ]
        ];
        if ($request->ajax()) {
            $members = $team->members();
            return DataTables::eloquent($members)
                ->editColumn('name', function ($member) {
                    return '<a href="' . route('users.show', $member->user_id) . '">' . $member->name . '</a>';
                })
                ->editColumn('role', function ($member) use ($team) {
                    return Helper::getRoleNames($member->getRoleNames());
                })
                ->addColumn('action', function ($member) use ($team) {
                    return  view('teams.members.action', compact('member', 'team'));
                })
                ->rawColumns(collect($columns)->pluck('data')->toArray())->toJson();
        }

        $dataTable = $builder->columns($columns)->buttons([])->dom('frtip');

        $roles = $this->getRoles();
        $roles = $roles->pluck('name', 'id');

        return view('teams.show', compact('team', 'dataTable', 'roles'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $roles = $this->getRoles();

        return view('teams.edit', compact('team', 'roles'));
    }

    private  function getRoles()
    {
        return Role::query()->where('name', '!=', 'super-admin')->get()->filter(fn($role) => $role->name !== 'super-admin');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTeamRequest $request, Team $team): RedirectResponse
    {
        $data = $this->getData($request);
        $team->update($data);

        // create team roles if not exists

        $team->createTeamRoles($team->id, $request->roles);

        flash()->addSuccess(__('Team updated successfully'));

        return redirect()->route('teams.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team): RedirectResponse
    {
        $team->delete();

        flash()->addSuccess(__('Team deleted successfully'));

        return redirect()->route('teams.index');
    }

    public function selectTeam()
    : View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $teams = auth()->user()->teams;

        return view('teams.select-team', compact('teams'));
    }

    public function switchTeam(Team $team)
    : RedirectResponse {
        if (auth()->user()->teams->contains($team))
        {
            session()->put('team_id', $team->id);

            setPermissionsTeamId($team->id);
        }

        return redirect()->route('dashboard');
    }

    //upload logo to public folder

    public function uploadLogo($request, $fileName = null): string
    {
        if ($fileName)
        {
            $imageName = $fileName.'.'.$request->file('logo')->extension();
        } else
        {
            $imageName = time().'_'.$request->file('logo')->getClientOriginalName();
        }

        // use filesystem name to store the image
        $request->file('logo')->storeAs(Team::LOGOS_DIRECTORY, $imageName, 'public_uploads');

        return $imageName;
    }

    /**
     * @param  UpdateTeamRequest|StoreTeamRequest  $request
     * @return mixed
     */
    public function getData(UpdateTeamRequest|StoreTeamRequest $request)
    : mixed {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;

        //delete_existing_logo
        if ($request->has('delete_existing_logo'))
        {
            $data['logo'] = null;

            //delete the logo from the public folder
            Helper::deleteFileFromPublicFolder(Team::LOGOS_DIRECTORY, $request->logo_name);
        }

        if ($request->hasFile('logo'))
        {
            $data['logo'] = $this->uploadLogo($request);
        }

        return $data;
    }

    public function inviteMember(Request $request, Team $team): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'username' => 'nullable',
            'roles' => 'required|exists:roles,id',
        ]);


        $user = User::query()->where('email', $request->email)->first();
        if ($user)
        {
            if ($team->members->contains($user))
            {
                flash()->addWarning(__('This user is already a member of this ' . config('team.team_label')));

                return redirect()->route('teams.show', $team);
            }

            $user->assignRole($request->roles);
            $user->attachTeam($team);
            flash()->addSuccess(__('User added successfully'));

            return redirect()->route('teams.show', $team);
        }

        $user = User::query()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Str::random(8),
        ]);

        $user->assignRole($request->roles);

        //InviteMemberNotification
        $broker = Password::broker();
        $user->notify(new InviteMemberNotification($team, $user));

        flash()->addSuccess(__('Invitation sent successfully'));


        return redirect()->route('teams.show', $team);
    }

    //Accept Invitation to join a team from user email

    public function acceptInvitation(Team $team, User $user, $token): RedirectResponse
    {
        $user->attachTeam($team);
        flash()->addSuccess(__('You have joined the team successfully'));

        return redirect()->route('password.reset', ['token' => $token, 'email' => $user->email]);
    }

    //Reject Invitation to join a team from user email

    public function rejectInvitation(Team $team, User $user): RedirectResponse
    {
        $user->detachTeam($team);
        flash()->addSuccess(__('You have rejected the invitation'));

        return redirect()->route('dashboard');
    }

    public function showInvitationForm(Request $request, Team $team, User $user, $token): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('teams.invitation', compact('team', 'user', 'token'));
    }

    //Remove member from team

    public function removeMember(Team $team, User $user): RedirectResponse
    {
        $user->detachTeam($team);
        flash()->addSuccess(__('User removed successfully'));

        return redirect()->route('teams.show', $team);
    }
}
