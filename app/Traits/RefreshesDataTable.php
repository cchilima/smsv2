<?php

namespace App\Traits;

trait RefreshesDataTable
{
    public function refreshTable(string $tableName)
    {
        $this->dispatch('pg:eventRefresh-' . $tableName);
    }
}
