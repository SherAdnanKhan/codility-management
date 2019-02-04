<?php

namespace App\Http\Controllers;

use App\Status;
use App\SubStatus;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {

        $this->validate($request,[
            'status' => 'required|unique:statuses,status_name'
        ]);
        $project=Status::create([
            'status_name'=>
                $request->status
        ]);

        if ($request->sub_status){

            $sub_status=explode(',',$request->sub_status);
            foreach ($sub_status as $items) {
                $project->sub_status()->create(['name'=>$items]);
            }

        }
        return redirect('applicants/lists')->with('status','Status added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function show(Status $status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function edit(Status $status)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        //
    }

    public function sub_status(Request $request,$id)
    {

        $projects =SubStatus::where('status_id',$id)->get();

        return \response()->json($projects);

    }
}
