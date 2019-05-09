<?php

namespace App\Http\Controllers;

use App\Applicants;
use App\EmailTemplate;
use App\Helper\Helper;
use App\Mail\SendApplicantEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailTemplateController extends Controller
{
    public $replace_header;
    public $replace;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $email_templates=EmailTemplate::paginate(10);
        return view('Admin.EmailTemplates.admin_index',compact('email_templates'));
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
            'email_body' => 'required'
        ]);

        $email_template=EmailTemplate::create([

            'body'      => $request->email_body?$request->email_body:null,
            'header'    => $request->email_subject?$request->email_subject:null
        ]);
        if ($email_template){
            return redirect()->route('email_template.index')->with('success','Email Template Added successful');
        }else{
            return redirect()->route('email_template.index')->with('success','Email Template Added unsuccessful');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if ($id){
            $result=EmailTemplate::whereId($id)->first();
            return \response()->json($result);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        //
    }
    public function select_template($id){

        if(\Session::get('applicant_id') != null){
            \Session::forget('applicant_id');
        }
        if (\Session::get('applicant_id') == null){
            \Session::put('applicant_id',$id);
        }
        if (\Session::get('previous_url_applicant') == null){
            \Session::put('previous_url_applicant',url()->previous());
        }
        $email_templates=EmailTemplate::all();
        return view('Admin.EmailTemplates.selection_template',compact('email_templates'));
    }
    public function sendEmail(Request $request){

        $this->validate($request,[
            'selected_template' => 'required'
            ]);
        $user_id=\Session::get('applicant_id');
        if($user_id){
            $get_applicant=Applicants::whereId($user_id)->first();
            $applicant=array();
            $email_template=EmailTemplate::whereId($request->selected_template)->first();

            $applicant['email']=$get_applicant->email?$get_applicant->email:null;
            $applicant['name']=$get_applicant->email?$get_applicant->firstName . $get_applicant->middleName . $get_applicant->LastName:null;
            $email_send=Mail::send(new SendApplicantEmail($email_template->body ,$applicant,$email_template->header));

            if (\Session::get('previous_url_applicant') != null){
                $url=\Session::get('previous_url_applicant');
                \Session::forget('previous_url_applicant');
                return redirect($url)->with('status',"Email Send to $get_applicant->firstName");
            }else{
                return redirect()->route('applicant_list')->with('status',"Email Send to $get_applicant->firstName");
            }

        }
    }
    public function sendCustomizedEmail(Request $request){

        $user_id=\Session::get('applicant_id');
        if($user_id){
            $get_applicant=Applicants::whereId($user_id)->first();

            $get_date = Helper::get_string_between($request->email_body,'@','@');
            $get_time = Helper::get_string_between($request->email_body,'*','*');
            $get_job_title=Helper::get_string_between($request->email_body,'#','#');
            $get_job_position=Helper::get_string_between($request->email_body,'%','%');
            $get_address=Helper::get_string_between($request->email_body,'$','$');
            $get_date_header = Helper::get_string_between($request->email_header,'@','@');
            $get_time_header = Helper::get_string_between($request->email_header,'*','*');
            $get_job_title_header=Helper::get_string_between($request->email_header,'#','#');
            $get_job_position_header=Helper::get_string_between($request->email_header,'%','%');
            $get_address_header=Helper::get_string_between($request->email_header,'$','$');

            // email header start
            if($get_date_header != false && strtotime($get_date_header) == true ){
                $date_header=Carbon::parse($get_date_header);
                if ($date_header->isSunday()) {
                    $date_header->addDay(1);
                }elseif ($date_header->isSaturday()){
                    $date_header->addDay(2);
                }
                $this->replace_header=Helper::replace_between($request->email_header,'@','@',$date_header->format('jS \o\f F, Y'),$get_date_header);

                if (strtotime($get_time_header) == true){
                    $time_header= Carbon::parse($get_time_header);
                    $this->replace_header=Helper::replace_between($this->replace_header,'*','*',$time_header->format('g:i a'),$get_time_header);

                }


            }
            if (strtotime($get_time_header) == true && strtotime($get_date_header) == false){
                $time_header= Carbon::parse($get_time_header);
                $this->replace_header=Helper::replace_between($request->email_header,'*','*',$time_header->format('g:i a'),$get_time_header);

            }
            if (isset($get_job_title_header) && $get_job_title_header == true){
                $this->replace_header=Helper::replace_between(isset($this->replace_header)?$this->replace_header:$request->email_header,'#','#',"$get_job_title_header",$get_job_title_header);
            }
            if (isset($get_job_position_header) && $get_job_position_header == true){
                $this->replace_header=Helper::replace_between(isset($this->replace_header)?$this->replace_header:$request->email_header,'%','%',"$get_job_position_header",$get_job_position_header);
            }
            if (isset($get_address_header) && $get_address_header == true){
                $this->replace_header=Helper::replace_between(isset($this->replace_header)?$this->replace_header:$request->email_header,'$','$',"$get_address_header",$get_address_header);
            }
            // email header end

            if($get_date != false && strtotime($get_date) == true ){
                $date=Carbon::parse($get_date);
                if ($date->isSunday()) {
                    $date->addDay(1);
                }elseif ($date->isSaturday()){
                    $date->addDay(2);
                }

                $this->replace=Helper::replace_between($request->email_body,'@','@',"<strong>".$date->format('jS \o\f F, Y')."</strong>",$get_date);

                if (strtotime($get_time) == true){
                    $time= Carbon::parse($get_time);
                    $this->replace=Helper::replace_between($this->replace,'*','*',"<strong>".$time->format('g:i a')."</strong>",$get_time);

                }


            }
            if (strtotime($get_time) == true && strtotime($get_date) == false){
                $time= Carbon::parse($get_time);
                $this->replace=Helper::replace_between($request->email_body,'*','*',"<strong>".$time->format('g:i a')."</strong>",$get_time);

            }

            if (isset($get_job_title) && $get_job_title == true){
                $this->replace=Helper::replace_between(isset($this->replace)?$this->replace:$request->email_body,'#','#',"<strong>".$get_job_title."</strong>",$get_job_title);
            }


            if (isset($get_job_position) && $get_job_position == true){
                $this->replace=Helper::replace_between(isset($this->replace)?$this->replace:$request->email_body,'%','%',"<strong>".$get_job_position."</strong>",$get_job_position);
            }

            if (isset($get_address) && $get_address == true){
                $this->replace=Helper::replace_between(isset($this->replace)?$this->replace:$request->email_body,'$','$',"<strong>".$get_address."</strong>",$get_address);
            }
            if ($this->replace == true || $this->replace_header == true){
                $applicant=array();
                $applicant['email']=$get_applicant->email?$get_applicant->email:null;
                $applicant['name']=$get_applicant->email?$get_applicant->firstName . $get_applicant->middleName . $get_applicant->LastName:null;
                if ($applicant['email'] != null) {
                    $email_header=isset($this->replace_header)?$this->replace_header:$request->email_header;
                    $email_send=Mail::send(new SendApplicantEmail($this->replace?$this->replace:$request->email_body ,$applicant,$email_header));

                    if (\Session::get('previous_url_applicant') != null){
                        $url=\Session::get('previous_url_applicant');
                        \Session::forget('previous_url_applicant');
                        return redirect($url)->with('status',"Email Send to $get_applicant->firstName");
                    }else{
                        return redirect()->route('applicant_list')->with('status',"Email Send to $get_applicant->firstName");
                    }

                }else{
                    if (\Session::get('previous_url_applicant') != null){
                        $url=\Session::get('previous_url_applicant');
                        \Session::forget('previous_url_applicant');
                        return redirect($url)->with('status',"Email is not Send to $get_applicant->firstName");
                    }else{
                        return redirect()->route('applicant_list')->with('status',"Email is not Send to $get_applicant->firstName");
                    }
                }

            }else{
                $applicant=array();
                $applicant['email']=$get_applicant->email?$get_applicant->email:null;
                $applicant['name']=$get_applicant->email?$get_applicant->firstName . $get_applicant->middleName . $get_applicant->LastName:null;
                if ($applicant['email'] != null) {
                    $email_header=isset($this->replace_header)?$this->replace_header:$request->email_header;
                    $this->replace=$request->email_body;
                    $email_send=Mail::send(new SendApplicantEmail($this->replace ,$applicant,$email_header));

                    if (\Session::get('previous_url_applicant') != null){
                        $url=\Session::get('previous_url_applicant');
                        \Session::forget('previous_url_applicant');
                        return redirect($url)->with('status',"Email Send to $get_applicant->firstName");
                    }else{
                        return redirect()->route('applicant_list')->with('status',"Email Send to $get_applicant->firstName");
                    }

                }else{
                    if (\Session::get('previous_url_applicant') != null){
                        $url=\Session::get('previous_url_applicant');
                        \Session::forget('previous_url_applicant');
                        return redirect($url)->with('status',"Email is not Send to $get_applicant->firstName");
                    }else{
                        return redirect()->route('applicant_list')->with('status',"Email is not Send to $get_applicant->firstName");
                    }
                }
            }
        }
    }
}
