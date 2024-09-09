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
        try {
            return $this->schools->getAll();
        } catch (\Throwable $th) {
            return response()->json('Failed to get schools: ' . $th->getMessage(), 400);
        }
    }

    public function findBySlug($slug)
    {
        try {
            return response()->json($this->schools->findBySlug($slug));
        } catch (\Throwable $th) {
            return response()->json('Failed to get school: ' . $th->getMessage(), 400);
        }
    }

    public function getDepartmentsBySchoolSlug($slug)
    {
        try {
            return response()->json($this->schools->getDepartmentsBySchoolSlug($slug));
        } catch (\Throwable $th) {
            return response()->json('Failed to get departments: ' . $th->getMessage(), 400);
        }
    }
}
