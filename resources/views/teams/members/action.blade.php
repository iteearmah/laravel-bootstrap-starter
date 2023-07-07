<!-- team member -->
<div class="dropdown">
    <button class="btn btn-sm btn-secondary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a class="dropdown-item" href="{{ route('users.show', $member->user_id) }}">
                <i class="bi bi-eye"></i>
                View</a></li>
            <form class="w-100" action="{{ route('teams.remove-member', ['team' => $team, 'user' => $member->user_id]) }}" method="POST" style="display: inline-block;">
                @csrf
                <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure?')">
                    <i class="bi bi-person-dash"></i>
                    {{ __('Remove') }}
                </button>
            </form>
        </li>
    </ul>
</div>
