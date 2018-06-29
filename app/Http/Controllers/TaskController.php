<?php

namespace App\Http\Controllers;

use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->isEmployee()) {
            $tasks = User::findOrFail(Auth::id())->tasks()->orderBy('id', 'desc')->get();
            return view('Task.index', compact('tasks'));
        }
        else{
            $tasks =Task::all()->sortByDesc('id');
            return view('Task.admin_index',compact('tasks'));
        }
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('Task.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
                'time_taken' =>'required',
                'description'=>'required',
            ]);
            $consume_time = Carbon::parse($request->time_taken)->timestamp;
            $date = Carbon::parse($request->date)->timestamp;
            Task::create([
                'user_id'=>Auth::id(),
                'time_take'=>$consume_time,
                'date'       =>$date,
                'description'=>$request->description
            ]);
            return redirect()->route('task.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        return view('Task.edit',compact('task'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'time_taken' =>'required',
            'description'=>'required',
        ]);
        $consume_time = Carbon::parse($request->time_taken)->timestamp;
        $date = Carbon::parse($request->date)->timestamp;

        Task::whereId($id)->update([
            'user_id'    =>Auth::id(),
            'time_take'  =>$consume_time,
            'date'       =>$date,
            'description'=>$request->description
        ]);
        return redirect()->route('task.index');
    }

    public function modal($id)
    {
        $result=Task::findOrFail($id)->first();
        return \response()->json($result);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        Task::findOrFail($id)->delete();
        return redirect()->route('task.index');
    }
}
