<?php

namespace App\Traits;

trait CanRefreshDataTable
{
    /**
     * Refreshes the specified PowerGrid datatables.
     *
     * @param array $tableNames The names of the tables to refresh.
     * @return void
     */
    public function refreshTables(array $tableNames): void
    {
        foreach ($tableNames as $tableName) {
            $this->refreshTable($tableName);
        }
    }

    /**
     * Refreshes the specified PowerGrid datatable.
     *
     * @param string $tableName The name of the table to refresh.
     * @return void
     */
    public function refreshTable(string $tableName): void
    {
        $this->dispatch('pg:eventRefresh-' . $tableName);
    }
}
