<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Repositories\Academics\SchooolRepository;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    protected $schools;

    public function __construct(SchooolRepository $schools)
    {
        $this->schools = $schools;
    }

    public function getAll()
    {
        return $this->schools->getAll();
    }

    public function findBySlug($slug)
    {
        return response()->json($this->schools->findBySlug($slug));
    }

    public function getDepartmentsBySchoolSlug($slug)
    {
        return response()->json($this->schools->getDepartmentsBySchoolSlug($slug));
    }
}
