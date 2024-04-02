<?php

namespace App\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\Services\DataTable;

abstract class BaseDataTable extends DataTable
{
    public function html(): \Yajra\DataTables\Html\Builder
    {
        return $this->builder()
            ->autoWidth(false)
            ->responsive(false)
            ->scrollY(false)
            ->scrollX(true)
            // add javascript
            ->initComplete(
                "function () {
                        const dropdown = $('td .dropdown');
                        dropdown.on('show.bs.dropdown', function() {
                            $('.dataTables_scroll, .dataTables_scrollHead').css( \"overflow\", \"inherit\" );
                        });

                        dropdown.on('hide.bs.dropdown', function() {
                            $('.dataTables_scroll, .dataTables_scrollHead').css( \"overflow-x\", \"auto\" );
                        });
                    }"
            )
            ->fixedHeader(false);
    }

    /**
     * Get the dataTable columns definition.
     */
    abstract public function getColumns(): array;

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'datatable_' . time();
    }
}
