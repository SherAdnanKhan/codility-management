<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\State;
use App\Status;
use App\TestInterview;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use App\Applicants;
use File;
use Response;
use Illuminate\Support\Str;
class ApplicantsController extends Controller
{
    public function home(Request $request)
    {

        $statuses= Status::where('is_deleted',false)->orderBy('id','desc')->get();
        $query=$request->input('query');
        $source=$request->input('source');
        $applicant_id=$request->input('applicant_id');
        if (!(Carbon::parse($request->input('date_of_birth'))->isToday())) {
            $dob = Carbon::parse($request->input('date_of_birth'));
        }else{
            $dob="";
        }
        $email=$request->input('email');
        $first_name=$request->input('first_name');
        $last_name=$request->input('last_name');
        $interview_search=$request->input('search_for_interview');
        $test_serial_number=$request->input('serial_number')?$request->input('serial_number'):1;
        if ($applicant_id != "" && $source != ""){
            $applicants=Applicants::where('source',$source)->where('applicantId',$applicant_id)->orderBy('id','desc')->paginate(10);

            if(count($applicants)>0)
            {
                return view('Admin.applicant_list',['applicants'=>$applicants,'query' => $query,'statuses'=>$statuses]);
            }else
            {
                return redirect('applicants/lists')->with('status','Sorry,Nothing Found.');
            }
        }
        if($query != "")
        {

            $applicants=Applicants::where('cvData','LIKE','%'.$query.'%')->orderBy('id','desc')->paginate(10);
            if(count($applicants)>0)
            {
                return view('Admin.applicant_list',['applicants'=>$applicants,'query' => $query,'statuses'=>$statuses]);
            }else
            {
                return redirect('applicants/lists')->with('status','Sorry,Nothing Found.');
            }
        }elseif($source != ""){
            $applicants=Applicants::where('source',$source)->orderBy('id','desc')->paginate(10);
            if(count($applicants)>0)
            {
                return view('Admin.applicant_list',['applicants'=>$applicants,'source' => $source,'statuses'=>$statuses]);
            }else
            {
                return redirect('applicants/lists')->with('status','Sorry,Nothing Found.');
            }
        }elseif($applicant_id != ""){
            $applicants=Applicants::where('applicantId',$applicant_id)->orderBy('id','desc')->paginate(10);
            if($applicants)
            {
                return view('Admin.applicant_list',['applicants'=>$applicants,'applicant_id' => $applicant_id,'source' => $source,'statuses'=>$statuses]);
            }else
            {
                return redirect('applicants/lists')->with('status','Sorry,Nothing Found.');
            }
        }elseif($dob != ""){
            $applicants=Applicants::where('dob',$dob)->orderBy('id','desc')->paginate(10);
            if($applicants)
            {
                return view('Admin.applicant_list',['applicants'=>$applicants,'applicant_id' => $applicant_id,'source' => $source,'statuses'=>$statuses]);
            }else
            {
                return redirect('applicants/lists')->with('status','Sorry,Nothing Found.');
            }
        }elseif($first_name != ""){

            $applicants=Applicants::where('firstName',$first_name)->orderBy('id','desc')->paginate(10);
            if($applicants)
            {
                return view('Admin.applicant_list',['applicants'=>$applicants,'applicant_id' => $applicant_id,'source' => $source,'statuses'=>$statuses]);
            }else
            {
                return redirect('applicants/lists')->with('status','Sorry,Nothing Found.');
            }
        }elseif($last_name != ""){
            $applicants=Applicants::where('lastName',$last_name)->orderBy('id','desc')->paginate(10);
            if($applicants)
            {
                return view('Admin.applicant_list',['applicants'=>$applicants,'applicant_id' => $applicant_id,'source' => $source,'statuses'=>$statuses]);
            }else
            {
                return redirect('applicants/lists')->with('status','Sorry,Nothing Found.');
            }
        }elseif($interview_search != ""){
            $applicants=Applicants::where('interview_for',$interview_search)->orderBy('id','desc')->paginate(10);
            if($applicants)
            {
                return view('Admin.applicant_list',['applicants'=>$applicants,'applicant_id' => $applicant_id,'source' => $source,'statuses'=>$statuses]);
            }else
            {
                return redirect('applicants/lists')->with('status','Sorry,Nothing Found.');
            }
        }elseif($email != ""){
            $applicants=Applicants::where('email',$email)->orderBy('id','desc')->paginate(10);
            if($applicants)
            {
                return view('Admin.applicant_list',['applicants'=>$applicants,'applicant_id' => $applicant_id,'source' => $source,'statuses'=>$statuses]);
            }else
            {
                return redirect('applicants/lists')->with('status','Sorry,Nothing Found.');
            }
        }elseif($test_serial_number != 1){
            $get_test=TestInterview::whereSerialNumber($test_serial_number)->first();
            if ($get_test != null) {
                $applicants = $get_test->applicants()->paginate(10);
                if ($applicants) {
                    return view('Admin.applicant_list', ['applicants' => $applicants, 'applicant_id' => $applicant_id, 'source' => $source, 'statuses' => $statuses]);
                } else {
                    return redirect('applicants/lists')->with('status', 'Sorry,Nothing Found.');
                }
            }else{
                return redirect('applicants/lists')->with('status', 'Sorry,Serial Number not found.');
            }
        }else{
            $applicants=Applicants::orderBy('id','desc')->paginate(10);
            return view('Admin.applicant_list',compact('applicants','statuses'))->with('status','Search Field Empty.');

        }
    }

    public function uploadCsv()
    {
        return view('uploadCsv');
    }
    public function uploadCsvPost(Request $request)
    {
        if($request->hasFile('csvFile') && ($request->csvFile->getClientOriginalExtension()=="xlsx" || $request->csvFile->getClientOriginalExtension()=="xls"))
        {
            $fileUrl= Storage::putFile('public',$request->file('csvFile'));
            $name = pathinfo($fileUrl, PATHINFO_FILENAME);
            Storage::move($fileUrl,$name.".".$request->csvFile->getClientOriginalExtension()); // 		changing extension to xls
            $filePath=storage_path("app//".$name.".".$request->csvFile->getClientOriginalExtension());
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $counter=0;
            foreach ($sheetData as $key => $value) {
                $counter++;
                if($counter!=1){
                    $check=Applicants::where('applicantId',$value['B'])->first();
                    if ($check== null) {
                        $applicant = new Applicants;
                        $applicant->applicantId = $value['B'];
                        $applicant->date = Carbon::parse($value['C'])->format('Y-m-d');
                        $applicant->firstName = $value['D'];
                        $applicant->middleName = $value['E'];
                        $applicant->lastName = $value['F'];
                        $applicant->gender = strtolower($value['G']);
                        $applicant->age = $value['H'];
                        $applicant->nationality = $value['I'];
                        $applicant->phoneNumber = $value['J'];
                        $applicant->dob = Carbon::parse($value['K'])->format('Y-m-d');
                        $applicant->currentSalary = $value['L'];
                        $applicant->expectedSalary = $value['M'];
                        $applicant->city = $value['N'];
                        $applicant->country = $value['O'];
                        $applicant->source = $value['R'];
                        $applicant->save();
                    }
                }
                }
            return response()->json("File uploaded successfully.");
        }else
        {
            return response()->json("File Type is incorrect");
        }

    }

    public function uploadCvPost(Request $request)
    {

        if($request->hasFile('uploadedCv') && ($request->uploadedCv->getClientOriginalExtension()=="doc" || $request->uploadedCv->getClientOriginalExtension()=="docx" || $request->uploadedCv->getClientOriginalExtension()=="pdf") )
        {
            $user=Applicants::where('id',$request->userId)->first();

            $slug = Str::slug($user->firstName." ".$user->LastName.time());
            if($user->cvUrl){
                $check_file_exists=Storage::exists('Public/Resumes/'.$user->cvSlug.'.'.$user->cvExt);
                if ($check_file_exists == true){
                    Storage::delete('Public/Resumes/'.$user->cvSlug.'.'.$user->cvExt);
                }
            }
            $count = Applicants::whereRaw("cvSlug RLIKE '^{$slug}(-[0-9]+)?$'")->count();
            $finalFileName= $count ? "{$slug}-{$count}" : $slug;
            $file = $request->file('uploadedCv')->storeAs('Public/Resumes', $finalFileName.'.'.$request->uploadedCv->getClientOriginalExtension() ,'local');
            $name = pathinfo($file, PATHINFO_FILENAME);
            $filePath=storage_path("app/Public/Resumes/".$finalFileName.".".$request->uploadedCv->getClientOriginalExtension());
            //pdf parsing

            if( $request->uploadedCv->getClientOriginalExtension()=="pdf")
            {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf    = $parser->parseFile($filePath);
                $text = $pdf->getText();
            }else
            {

                $text=array();
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
                $sections = $phpWord->getSections();
                foreach ($sections as $key => $value) {
                    $sectionElement = $value->getElements();
                    foreach ($sectionElement as $elementKey => $elementValue) {
                        if ($elementValue instanceof \PhpOffice\PhpWord\Element\TextRun) {
                            $secondSectionElement = $elementValue->getElements();
                            foreach ($secondSectionElement as $secondSectionElementKey => $secondSectionElementValue) {

                                if ($secondSectionElementValue instanceof \PhpOffice\PhpWord\Element\Text) {
                                    $text[]= $secondSectionElementValue->getText()."";
                                    $check=null;
                                }
                            }
                        }
                    }
                }
            }
            if ($request->uploadedCv->getClientOriginalExtension()!="pdf") {
                if ($check == null) {
                    $string_version = implode(' ', $text);
                }
            }

            $user->cvSlug=$finalFileName;
            $user->cvExt=$request->uploadedCv->getClientOriginalExtension();
            $user->cvUrl=$file;
            $user->cvData=isset($string_version)?$string_version:$text;
            $user->save();

            return redirect()->route('applicant_list')->with('status','Uploaded Resume of Applicant');

        }else{
            return "File Type Incorrect.";
        }
    }

    public function viewCv($id)
    {
        $user=Applicants::where('id',$id)->first();
        if($user->cvUrl != "")
        {
            if ($user->cvExt != 'pdf') {
                return response()->download(storage_path('app/' . $user->cvUrl));
            }else{
                return response()->file(storage_path('app/' . $user->cvUrl));

            }
        }else
        {
            return redirect('/applicants/lists')->with('status','Resume not Found.');
        }
    }

    public function delete($id)
    {
        $applicant = Applicants::find($id);
        $applicant->delete();
        return redirect('/applicants/lists')->with('status','Employee Deleted.');
    }

    public function addApplicant(Request $request){

        $this->validate($request,[
            'date'              => 'date|required',
            'dob'               => 'date|required',
            'firstName'         => 'required',
            'gender'            => 'required',
            'nationality'       => 'required',
            'phoneNumber'       => 'required|integer',
            'country'           => 'required',
            'sources'           => 'required',
            'email'             => 'required|email',
        ]);

        //check
        if (!($request->force_submit)) {
            $check_applicant = Applicants::where('email', $request->email)->first();
            $check_applicants = Applicants::where('firstName', $request->firstName)->where('LastName', $request->lastName)->first();
            if ($check_applicant != null || $check_applicants != null) {
                \Session::put("applicantId", $request->applicantId ? $request->applicantId : null);
                \Session::put("date", $request->date ? $request->date : null);
                \Session::put("firstName", $request->firstName ? $request->firstName : null);
                \Session::put("middleName", $request->middleName ? $request->middleName : null);
                \Session::put("LastName", $request->lastName ? $request->lastName : null);
                \Session::put("gender", $request->gender ? $request->gender : null);
                \Session::put("age", $request->age ? $request->age : null);
                \Session::put("nationality", $request->nationality ? $request->nationality : null);
                \Session::put("phoneNumber", $request->phoneNumber ? $request->phoneNumber : null);
                \Session::put("dob", $request->dob ? $request->dob : null);
                \Session::put("currentSalary", $request->currentSalary ? $request->currentSalary : null);
                \Session::put("expectedSalary", $request->expectedSalary ? $request->expectedSalary : null);
                \Session::put("city", $request->city ? $request->city : null);
                \Session::put("country", $request->country ? Country::where('id', $request->country)->pluck('name')->first() : null);
                \Session::put("source", $request->sources ? $request->sources : null);
                \Session::put("interview_for", $request->applicant_interview_for ? $request->applicant_interview_for : null);
                \Session::put("expertise_in", $request->expertise_in ? $request->expertise_in : null);
                \Session::put("email", $request->email ? $request->email : null);
                \Session::put("already_exist",true);
                \Session::put("duplicate_status",$check_applicant?'email':'name');
                return redirect()->back()->with('error', 'Applicant Insert UnSuccessful, Please Try Again !');
            }
            }
            if ($request->force_submit){
                \Session::forget('applicantId');
                \Session::forget('date');
                \Session::forget('firstName');
                \Session::forget('middleName');
                \Session::forget('LastName');
                \Session::forget('gender');
                \Session::forget('nationality');
                \Session::forget('phoneNumber');
                \Session::forget('dob');
                \Session::forget('age');
                \Session::forget('currentSalary');
                \Session::forget('expectedSalary');
                \Session::forget('country');
                \Session::forget('source');
                \Session::forget('interview_for');
                \Session::forget('expertise_in');
                \Session::forget('email');
                \Session::forget('already_exist');
                \Session::forget('duplicate_status');

            }
        //check
        $applicant=Applicants::create([
            'applicantId'           => $request->applicantId?$request->applicantId:null,
            'date'                  => $request->date ? Carbon::parse($request->date)->format('Y-m-d'):null,
            'firstName'             => $request->firstName ? $request->firstName:null,
            'middleName'            => $request->middleName ? $request->middleName:null,
            'LastName'              => $request->lastName ? $request->lastName:null,
            'gender'                => $request->gender ? $request->gender:null ,
            'age'                   => $request->age ? $request->age:null,
            'nationality'           => $request->nationality?$request->nationality:null,
            'phoneNumber'           => $request->phoneNumber ? $request->phoneNumber:null,
            'dob'                   => $request->dob ? Carbon::parse($request->dob)->format('Y-m-d') :null,
            'currentSalary'         => $request->currentSalary ? $request->currentSalary:null,
            'expectedSalary'        => $request->expectedSalary ? $request->expectedSalary:null,
            'city'                  => $request->city ? $request->city:(\Session::get("city")?\Session::get("city"):null),
            'country'               => $request->country ? Country::where('id',$request->country)->pluck('name')->first():null,
            'source'                => $request->sources ? $request->sources:null,
            'interview_for'         => $request->applicant_interview_for ? $request->applicant_interview_for:null,
            'expertise_in'          => $request->expertise_in ? $request->expertise_in:null,
            'email'                => $request->email ? $request->email:null,

        ]);
        \Session::forget('city');
        if ($request->applicantId == null || $request->sources == 'Codility' ){
            Applicants::whereId($applicant->id)->update([ 'applicantId'=>$applicant->id]);
        }
        if ($applicant) {
            return redirect('/applicants/lists')->with('status', 'Applicant Insert Successfully');
        }else{
            return redirect('/applicants/lists')->with('status', 'Applicant Insert UnSuccessful, Please Try Again !');

        }
    }
    public function getState($id){
        $result=State::where('country_id',$id)->get();
        return \response()->json($result);
    }
    public function getCity($id){
        $result=City::where('state_id',$id)->get();
        return \response()->json($result);
    }
    public function get_single_applicant($id){
        $applicant=Applicants::where('id',$id)->first();
        if($applicant != null)
        {
            return view('Admin.applicant_list_search',['applicant'=>$applicant]);
        }else
        {
            return redirect('applicants/lists')->with('status','Sorry,Nothing Found.');
        }
    }
    public function reject(Request $request){
        if ($request->reject){
            \Session::forget('applicantId');
            \Session::forget('date');
            \Session::forget('firstName');
            \Session::forget('middleName');
            \Session::forget('LastName');
            \Session::forget('gender');
            \Session::forget('nationality');
            \Session::forget('phoneNumber');
            \Session::forget('dob');
            \Session::forget('currentSalary');
            \Session::forget('expectedSalary');
            \Session::forget('country');
            \Session::forget('source');
            \Session::forget('interview_for');
            \Session::forget('expertise_in');
            \Session::forget('email');
            \Session::forget('already_exist');
            \Session::forget('duplicate_status');
            \Session::forget('city');
            \Session::forget('age');

            return redirect('applicants/lists')->with('status','Reject user successfully');
        }
    }
}
