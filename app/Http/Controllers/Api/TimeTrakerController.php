<?php

//namespace App\Http\Controllers\Api;
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\TimeTraker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;


class TimeTrakerController extends Controller
{
    public function insertTimeTraker(Request $request){



            $getEndTime=Carbon::now()->format('h:i A');
            $getTime=last($request->slots);
            $getMinutes=$getTime['time'];
            $explodeMinutes=explode(':',$getMinutes);
            $getTimeForPrevious=$explodeMinutes[0];
            $getStartTime=Carbon::now()->subMinutes($getTimeForPrevious)->format('h:i A');
            TimeTraker::create([
                'user_id' => $request->auth->id,
                'start_time'=>$getStartTime,
                'end_time'=>$getEndTime,
//                'date'=>Carbon::now()->format('d/m/Y'),
//                'slots'=>$request->slots

            ]);

    }
}
