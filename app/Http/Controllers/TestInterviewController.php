<?php

namespace App\Http\Controllers;

use App\TestInterview;
use Illuminate\Http\Request;

class TestInterviewController extends Controller
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
            'applicant_original_id'     => 'required|integer',
            'image'                     => 'required',
            'status'                    => 'required',
//            'marks'                     => 'required',
        ]);
        if ($file = $request->file('image')) {
            $name = time() . $file->getClientOriginalName();
            $file->move('images/test', $name);
        }
        $status=$request->status == 10 ? 10 :true;

        $interview=TestInterview::create([
            'applicant_id'      =>  $request->applicant_original_id,
            'image'             => isset($name)?$name:null,
            'status'            =>  $status?$status:null,
            'marks'             =>  $request->marks?$request->marks:null,
            'note'              =>  $request->description?$request->description:null,
            'serial_number'     =>  $request->serial_number?$request->serial_number:null
        ]);
        if ($interview){
            return redirect('applicants/lists')->with('status','Test record insert successfully');
        }else{
            return redirect('applicants/lists')->with('status','Test record insert unsuccessful ');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TestInterview  $testInterview
     * @return \Illuminate\Http\Response
     */
    public function show(TestInterview $testInterview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TestInterview  $testInterview
     * @return \Illuminate\Http\Response
     */
    public function edit(TestInterview $testInterview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TestInterview  $testInterview
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TestInterview $testInterview)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TestInterview  $testInterview
     * @return \Illuminate\Http\Response
     */
    public function destroy(TestInterview $testInterview)
    {
        //
    }
}
