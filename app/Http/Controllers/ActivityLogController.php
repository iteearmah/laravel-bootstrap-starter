<?php

namespace App\Http\Controllers;

use App\DataTables\ActivityLogDataTable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(ActivityLogDataTable $dataTable)
    {
        return $dataTable->render('activity-log.index');
    }

    public function show(Activity $activity): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('activity-log.show', compact('activity'));
    }
}
