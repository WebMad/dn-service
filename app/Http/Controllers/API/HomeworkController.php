<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomeworkRequest;
use App\Operations\HomeworkOperation;
use Illuminate\Support\Facades\Auth;

class HomeworkController extends Controller
{
    public function homework(HomeworkRequest $request, HomeworkOperation $homeworkOperation)
    {
        $params = $request->all(['start_date', 'end_date']);
        $user = Auth::user();
        return $homeworkOperation->getMyHomeworkBySchool($user->school_id, $params['start_date'], $params['end_date']);
    }
}
