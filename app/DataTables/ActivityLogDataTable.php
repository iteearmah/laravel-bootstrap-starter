<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ActivityLogDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('subject_id', function ($activity) {
                return $activity->subject->name ?? __('N/A');
            })
            ->editColumn('description', function ($activity) {
                return $activity->description ?? __('N/A');
            })
            ->editColumn('log_name', function ($activity) {
                return $activity->log_name ?? __('N/A');
            })
            ->editColumn('causer_id', function ($activity) {
                return $activity->causer->name ?? __('N/A');
            })
            ->editColumn('created_at', function ($activity) {
                return $activity->created_at->format('d/m/Y H:i:s');
            })
            ->addColumn('action', function ($activity) {
                return view('activity-log.action', compact('activity'));
            })
            ->rawColumns(['properties', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Activity $model): QueryBuilder
    {
        return $model->newQuery()->with(['causer', 'subject'])->orderBy('created_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('activitylog-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
                    ->selectStyleSingle();

    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::make('id'),
            Column::make('causer_id')->title('User'),
            Column::make('log_name')->title('Log Name'),
            Column::make('description')->title('Description'),
            Column::make('subject_id')->title('Subject'),
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
        return 'ActivityLog_' . date('YmdHis');
    }
}
