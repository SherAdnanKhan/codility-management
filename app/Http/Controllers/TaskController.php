<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public $start_date;
    public $end_date;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $user=User::whereHas('role',function ($q){$q->whereIn('name',['Administrator']);})->get();
//        dd($user);
        if (Auth::user()->isEmployee()) {
            $tasks = User::findOrFail(Auth::id())->tasks()->orderBy('id', 'desc')->paginate(10);
            return view('Task.index', compact('tasks'));
        }
        else{
            $tasks =Task::orderBy('id', 'desc')->paginate(10);
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
        if (Auth::user()->isAdmin()){
            $this->validate($request, [
                'time_taken' => 'required',
                'description' => 'required',
                'employee'    =>  'required',
                'date'        => 'required|date'
            ]);
        }elseif(Auth::user()->isEmployee()) {
            $this->validate($request, [
                'description' => 'required',
                'date'        => 'required|date'
            ]);
        }
        $consume_time = $request->time_taken?Carbon::parse($request->time_taken)->timestamp:null;
        $date = Carbon::parse($request->date)->timestamp;
        Task::create([
            'user_id' => Auth::user()->isEmployee()?Auth::id():$request->employee,
            'time_take' => $consume_time?$consume_time:0,
            'date' => $date,
            'description' => $request->description
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
        if (Auth::user()->isAdmin()) {
            $this->validate($request, [
                'date' => 'required|date',
                'time_taken' => 'required',
                'description' => 'required',
            ]);
            $consume_time = Carbon::parse($request->time_taken)->timestamp;
            $date = Carbon::parse($request->date)->timestamp;

            Task::whereId($id)->update([
                'time_take' => $consume_time,
                'date' => $date,
                'description' => $request->description
            ]);
            return redirect()->route('task.index');
        }elseif(Auth::user()->isEmployee()){
            $this->validate($request, [
                'time_taken' => 'required',
            ]);
            $consume_time = Carbon::parse($request->time_taken)->timestamp;
            $date = Carbon::parse($request->date)->timestamp;

            Task::whereId($id)->update([
                'time_take' => $consume_time,

            ]);
            return redirect()->route('task.index');
        }
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
    public function search(Request $request){

        $this->validate($request,[
            'start_date' =>'required_if:filter,custom',
            'end_date'  =>'required_if:filter,custom',

        ]);
        if($request->filter == 'custom') {
            $this->start_date = Carbon::parse($request->start_date)->timestamp;
            $this->end_date   = Carbon::parse($request->end_date)->timestamp;
        }else{
            $start_date = Carbon::now();
            $this->start_date=$request->filter=='today'?$start_date->startOfDay()->timestamp:($request->filter == 'week'?$start_date->startOfWeek()->timestamp:($request->filter == 'month'?$start_date->startOfMonth()->timestamp:($request->filter =='year'?$start_date->startOfYear()->timestamp:'')));
            $this->end_date=Carbon::now()->timestamp;
        }
        $name=$request->name?$request->name:'';
        if (Auth::user()->isAdmin()) {
            $user = User::whereName($name)->first();
            $tasks = Task::whereBetween('date', [$this->start_date, $this->end_date])->orWhere('user_id', $user != null ? $user->id : '')->paginate(10);
            $tasks->withPath("task?filter=year&start_date=$request->start_date=&end_date=$request->end_date&name=$name");
            return view('Task.admin_index', compact('tasks'));
        }
        else{
            $tasks = Task::whereBetween('date', [$this->start_date, $this->end_date])->where('user_id', Auth::user()->id)->orWhere('description',$request->description)->paginate(10);
            $tasks->withPath("task?filter=year&start_date=$request->start_date=&end_date=$request->end_date&name=$name");
            return view('Task.index', compact('tasks'));
        }

    }
}
