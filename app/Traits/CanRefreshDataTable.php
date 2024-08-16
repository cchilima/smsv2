<?php

namespace App\Traits;

trait CanRefreshDataTable
{
    public function refreshTable(string $tableName)
    {
        $this->dispatch('pg:eventRefresh-' . $tableName);
    }
}
