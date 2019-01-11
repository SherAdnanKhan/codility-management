<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\ProjectTask;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects= ProjectTask::where('is_deleted',false)->orderBy('id','desc')->paginate(10);
        $users = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Employee']);
        })->where('abended', false)->get();
        return view('Admin.Project.index',compact('projects','users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $projectRequest)
    {

        $project=ProjectTask::create([
            'project_name'=>
                $projectRequest->project_title
        ]);

        if ($projectRequest->sub_projects){
            $sub_projects=explode(',',$projectRequest->sub_projects);
            foreach ($sub_projects as $items) {

                $project->sub_projects()->create(['name'=>$items]);
            }

        }
        return redirect()->route('project.index')->with('status','Project Created successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $projects =ProjectTask::where('id',$id)->first();
        $data= view('layouts.modal-view',compact('projects'))->render();
        return \response()->json($data);
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
//        dd($request->project_name);
        ProjectTask::whereId($id)->update(['project_name'=>$request->project_title]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProjectTask::whereId($id)->update(['is_deleted'=>true]);
        return redirect()->back();
    }

    public function search(Request $request){
        $this->validate($request, [
            'project_name' => 'required',
        ]);

        $projects=ProjectTask::where('project_name','Like','%'.$request->project_name.'%')->paginate(10);
        $users = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['Employee']);
        })->where('abended', false)->get();

        return view('Admin.Project.index',compact('projects','users'));
    }

    public function print_report(Request $request)
    {

        if ($request->project_id){
            $projects=ProjectTask::where('id',$request->project_id)->first();
            $get_task=$projects->project_tasks;

            $total_minutes=0;
            foreach ($get_task as $task){
                // check if employee task have time
                if ($task->time_take) {

                    $explode = explode(':', $task->time_take);
                    $total_minutes +=($explode[0]*60) + ($explode[1])  ;

                }

            }
            //
            return view('Report.task_report',compact('total_minutes','get_task'));
            //
        }else{

            return redirect()->back('status','project not found');
        }
    }
    public function project($id)
    {
        if ($id){
            $project=ProjectTask::where('id',$id)->first();
            if ($project->sub_projects){
                $result=$project->sub_projects
                    ->toArray();
                return $result;
            }
        }
    }

}
