<?php

namespace App\DataTables;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RolesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function ($role) {
                return  view('roles.action', compact('role'));
            })
            ->editColumn('permissions', function ($role) {
                return $role->permissions->count();
            })
            // member count
            ->editColumn('members', function ($role) {
                return $role->users->count();
            })
            ->editColumn('created_at', function ($role) {
                return $role->created_at->format('d-m-Y');
            })
            //team
            ->addColumn('team', function ($role) {
                if($role->team)
                {
                    return '<a href="' . route('teams.show', $role->team->id) . '">' . $role->team->name . '</a>';
                }
                return __('No ' . config('team.team_label'));
            })
            ->rawColumns(['team', 'action'])
            ->setRowId('id')->setRowClass(function ($role) {
                return $role->id % 2 === 0 ? 'alert-success' : 'alert-warning';
            });
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery()->with(['team', 'permissions', 'users']);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('roles-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(0)
            ->selectStyleSingle()
            ->buttons([
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);

    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('name'),
            Column::make('team'),
            Column::make('permissions'),
            Column::make('members'),
            Column::make('created_at'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Roles_' . date('YmdHis');
    }
}
