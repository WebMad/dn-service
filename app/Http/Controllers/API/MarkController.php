<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarkRequest;
use App\Operations\MarkOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkController extends Controller
{
    public function marks(MarkRequest $request, MarkOperation $markOperation)
    {
        $params = $request->all(['start_date', 'end_date']);
        $user = Auth::user();
        return response()->json($markOperation->getMyMarksByPersonAndSchools($user->person_id, $user->school_id, $params['start_date'], $params['end_date']));
    }
}
