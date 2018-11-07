<?php

namespace App\Http\Controllers;

use App\QNACategory;
use App\QuestionAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use PDF;


class QuestionAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $question_answers = QuestionAnswer::orderBy('id','desc')->paginate(10);
        return view('QNA.Q&A.index',compact('question_answers'));
    }

    public function printView()
    {
        $category = QNACategory::orderBy('id','desc')->get();

        return view('QNA.Q&A.print',compact('category'));
    }
    public function printCreate(Request $request)
    {

        $this->validate($request, [
            'print'    => 'required',
        ]);

        $all_categories=$request->print;

        $category=QNACategory::whereIn('id',$request->print)->get();
        $pdf=PDF::loadView('QNA.Q&A.view',compact('category','all_categories'));
        return $pdf->stream('CodilityTest.pdf');
        return view('QNA.Q&A.print',compact('category','all_categories'));


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
            'answer'    => 'required',
            'category'  => 'required|exists:q_n_a_categories,id',
            'question'  => 'required',
            'marks'     => 'required|integer|max:5'

        ]);
        $category=QNACategory::whereId($request->category)->first();
        if ($category){
            $questions=str_replace( "*", "&#42;", $request->question);
            $qna=$category->qNA()->create([
                'question'  =>  $questions,
                'answer'    =>  $request->answer,
                'marks'     =>  $request->marks
            ]);


            $question=QuestionAnswer::whereId($qna->id)->update(['variation'=>$qna->id]);

            $remove[] = "*";// just as another example

            $findQuesiton = str_replace( $remove, "&#42;", $qna->question);
            $remove_charachter[]="is";
            $remove_charachter[]="the";
            $remove_charachter[]="am";
            $remove_charachter[]="are";
            $remove_charachter[]="they";
            $remove_charachter[]="?";
            $remove_charachter[]="no";
            $remove_charachter[]="what";
            $remove_charachter[]="why";
            $remove_charachter[]="how";
            $remove_charachter[]="can";
            $findQuesiton = str_replace( $remove_charachter, " ", $findQuesiton);

            $question_answers=QuestionAnswer::WhereRaw(" MATCH(question) Against('".$findQuesiton."')")->paginate(30);
            if($question_answers){
                return redirect()->route('question-answers.index',compact('question_answers'))->with('status','Question with Created  Successfully.The Following Question and Answer are belongs to newly created Question. Please edit VARIATION TYPE according to same to question');
            }else{
                return redirect()->route('question-answers.index',compact('question_answers'))->with('status','Question with Created  Successfully.');
            }


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
            $result=QuestionAnswer::whereId($id)->first();
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
        $question = QuestionAnswer::whereId($id)->first();
        $data= view('layouts.modal-view',compact('question'))->render();
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
            'answer'    => 'required',
            'category'  => 'required|exists:q_n_a_categories,id',
            'question'  => 'required',
            'marks'     => 'required|integer',
            'variation' => 'required|integer',
        ]);
        $question=QuestionAnswer::whereId($id)->update([
            'question'  =>  $request->question,
            'answer'    =>  $request->answer,
            'marks'     =>  $request->marks,
            'variation' =>  $request->variation
        ]);

        if ($question ==true){
            return redirect()->route('question-answers.index')->with('status','Question and Answer  Updated !');

        }else{
            return redirect()->route('question-answers.index')->with('status','Question and Answer Not Updated !');

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
        $attendance= QuestionAnswer::whereId($id)->delete();
        return redirect()->route('question-answers.index')->with('status','Question and Answer Deleted !');
    }
    public function showEmployeeTestSearch($id)
    {
        $data = QuestionAnswer::whereId($id)->first();
        if ($data == null){
            return redirect()->route('QNA.Q&A.search')->with('status','You Can Search Question!');
        }
        return \response()->json($data);
    }
    public function getPage()
    {
        return view('QNA.Q&A.search')->with('status','You Can Search Question!');
    }


}
