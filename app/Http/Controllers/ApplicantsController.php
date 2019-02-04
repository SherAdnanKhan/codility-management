<?php

namespace App\Http\Controllers;

use App\Status;
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
        if($query != "")
        {
            $applicants=Applicants::where('cvData','LIKE','%'.$query.'%')->paginate(10);
            if(count($applicants)>0)
            {
                return view('applicants/lists',['applicants'=>$applicants,'query' => $query,'statuses'=>$statuses]);
            }else
            {
                return redirect('applicants/lists')->with('status','Sorry,Nothing Found.');
            }
        }else{
            $applicants=Applicants::paginate(10);
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
            if (isset($check)){
                if ($check == null){
                $string_version = implode(' ', $text);
                }
            }
            $user->cvSlug=$finalFileName;
            $user->cvExt=$request->uploadedCv->getClientOriginalExtension();
            $user->cvUrl=$file;
            $user->cvData=isset($string_version)?$string_version:null;
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
            'city'              => 'required',
            'country'           => 'required',
            'source'            => 'required',
            'applicantId'       => 'unique:applicants,applicantId',

        ]);
        $applicant=Applicants::create([
            'applicantId'           => $request->applicantId?$request->applicantId:null,
            'date'                  => $request->date ? Carbon::parse($request->date)->format('Y-m-d'):null,
            'firstName'             => $request->firstName ? $request->firstName:null,
            'middleName'            => $request->middleName ? $request->middleName:null,
            'lastName'              => $request->lastName ? $request->lastName:null,
            'gender'                => $request->gender ? $request->gender:null ,
            'age'                   => $request->age ? $request->age:null,
            'nationality'           => $request->nationality?$request->nationality:null,
            'phoneNumber'           => $request->phoneNumber ? $request->phoneNumber:null,
            'dob'                   => $request->dob ? Carbon::parse($request->dob)->format('Y-m-d') :null,
            'currentSalary'         => $request->currentSalary ? $request->currentSalary:null,
            'expectedSalary'        => $request->expectedSalary ? $request->expectedSalary:null,
            'city'                  => $request->city ? $request->city:null,
            'country'               => $request->country ? $request->country:null,
            'source'                => $request->source ? $request->source:null,
        ]);
        if ($applicant) {
            return redirect('/applicants/lists')->with('status', 'Applicant Insert Successfully');
        }else{
            return redirect('/applicants/lists')->with('status', 'Applicant Insert UnSuccessful, Please Try Again !');

        }
    }
}
