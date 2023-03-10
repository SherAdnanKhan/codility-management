<?php

namespace App\Http\Controllers;

use App\Applicants;
use App\Interview;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class InterviewController extends Controller
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
            'status_get'                => 'required',
            'date'                      => 'required|date',
            'description'               => 'required',

        ]);
        $interview=Interview::create([

            'applicant_id'      =>  $request->applicant_original_id,
            'status_id'         =>  $request->status_get?$request->status_get:null,
            'sub_status_id'     =>  $request->sub_status?$request->sub_status:null,
            'date'              =>  $request->date?Carbon::parse($request->date)->timestamp:null,
            'note'              =>  $request->description?$request->description:null,
        ]);
        if ($interview){
            return redirect('applicants/lists')->with('status','Interview schedule successfully');
        }else{
            return redirect('applicants/lists')->with('status','Interview schedule unsuccessful ');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Interview  $interview
     * @return \Illuminate\Http\Response
     */
    public function show(Interview $interview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Interview  $interview
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $applicant = Applicants::whereId($id)->first();
        $interview = $applicant->interview()->orderBy('id','desc')->first();
        $data= view('layouts.modal-view',compact('interview'))->render();
        return \response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Interview  $interview
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'sub_status'                 => 'required',
            'description'                => 'required',
        ]);
        $interview=Interview::whereId($id)->update([
            'sub_status_id'     =>  $request->sub_status?$request->sub_status:null,
            'note'              =>  $request->description?$request->description:null,
        ]);
        if ($interview){
            return redirect('applicants/lists')->with('status','Interview schedule successfully');
        }else{
            return redirect('applicants/lists')->with('status','Interview schedule unsuccessful ');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Interview  $interview
     * @return \Illuminate\Http\Response
     */
    public function destroy(Interview $interview)
    {
        //
    }
}
