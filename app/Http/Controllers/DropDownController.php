<?php

namespace App\Http\Controllers;

use App\DropDown;
use Illuminate\Http\Request;

class DropDownController extends Controller
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
        if ($request->add_interview_for){
            $check=array();
            $sub_add_interview_for=explode(',',$request->add_interview_for);
            foreach ($sub_add_interview_for as $items) {
                $check[]=DropDown::where('interview_for',$items)->first();
                $success=DropDown::create([
                    'interview_for'=>
                        $items
                ]);
            }
            if (count($check)>0){
                return redirect('applicants/lists')->with('status','Some of interview for drop down have already exist');

            }
        if ($success){
            return redirect('applicants/lists')->with('status','Interview drop down updated');


        }
        }else{
            return redirect('applicants/lists')->with('status','Interview drop down failed to update');

        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DropDown  $dropDown
     * @return \Illuminate\Http\Response
     */
    public function show(DropDown $dropDown)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DropDown  $dropDown
     * @return \Illuminate\Http\Response
     */
    public function edit(DropDown $dropDown)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DropDown  $dropDown
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DropDown $dropDown)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DropDown  $dropDown
     * @return \Illuminate\Http\Response
     */
    public function destroy(DropDown $dropDown)
    {
        //
    }
}
