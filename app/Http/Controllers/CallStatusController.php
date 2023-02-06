<?php

namespace App\Http\Controllers;

use App\Applicants;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CallStatusController extends Controller
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
            'call_date' => 'required|date',
            'description'=> 'required',
            'applicant_original_id' =>'required|exists:applicants,id'
        ]);
        $get_applicant=Applicants::whereId($request->applicant_original_id)->first();
        if ($get_applicant != null){
            $call_status=$get_applicant->call_statuses()->create([
                'date' => Carbon::parse($request->call_date)->timestamp,
                'description'=>$request->description
            ]);
            if($call_status){
              return redirect()->back()->with('status',"Call status for applicant $get_applicant->firstName has saved successfully");
            }
        }else{
            return redirect()->back()->with('status','This Applicant is not exist');
        }
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
