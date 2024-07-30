<?php

namespace App\Repositories\Reports\Audits;

use OwenIt\Auditing\Models\Audit;

class AuditReportsRepository
{


    public function getAll($order = 'title')
    {
        return Audit::with('user')->get();
    }
}
