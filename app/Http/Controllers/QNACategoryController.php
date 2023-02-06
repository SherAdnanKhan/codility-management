<?php

namespace App\Http\Controllers;

use App\QNACategory;
use Illuminate\Http\Request;

class QNACategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = QNACategory::orderBy('id','desc')->paginate(10);
        return view('QNA.Category.index',compact('categories'))->with('status','Please Select Categories For Questionare! Three Question will be selected randomly from each of your selected category');
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
        $this->validate($request, [
            'name' => 'required',
        ]);
        $category=QNACategory::create(['name'=>$request->name]);
        if ($category){
            return redirect()->route('category.index')->with('info','Category Create successfully.');

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
        if ($id){
            $result=QNACategory::whereId($id)->first();
            return \response()->json($result);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = QNACategory::whereId($id)->first();
        $data= view('layouts.modal-view',compact('category'))->render();
        return \response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $category=QNACategory::whereId($id)->update(['name'=>$request->name]);

        if ($category ==true){
            return redirect()->route('category.index')->with('status','QNA`s Category Updated !');

        }else{
            return redirect()->route('category.index')->with('status','QNA`s Category Not Updated !');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $attendance= QNACategory::whereId($id)->delete();
        return redirect()->route('category.index')->with('status','QNA`s Category Deleted !');
    }

}
