<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;
use App\Operations\ScheduleOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function schedule(ScheduleRequest $request, ScheduleOperation $scheduleOperation)
    {
        $params = $request->all(['start_date', 'end_date']);
        $user = Auth::user();

        return response()->json($scheduleOperation->getScheduleByEduGroupAndPerson($user->eg_id, $user->person_id, $params['start_date'], $params['end_date']));
    }
}
