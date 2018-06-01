<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Applicants;
use File;
use Response;
use Illuminate\Support\Str;
class ApplicantsController extends Controller
{
    public function home(Request $request)
    {
        $query=$request->input('query');
        if($query != "")
        {
            $applicants=Applicants::where('cvData','LIKE','%'.$query.'%')->paginate(10);
            if(count($applicants)>0)
            {
                return view('home',['applicants'=>$applicants,'query' => $query]);
            }else
            {
                return redirect('home')->with('status','Sorry,Nothing Found.');
            }
        }else{
            $applicants=Applicants::paginate(10);
            return view('Admin.applicant_list',['applicants'=>$applicants])->with('status','Search Field Empty.');
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
                    $applicant=new Applicants;
                    $applicant->applicantId=$value['B'];
                    $applicant->date=date("Y-m-d", strtotime($value['C']));
                    $applicant->firstName=$value['D'];
                    $applicant->middleName=$value['E'];
                    $applicant->lastName=$value['F'];
                    $applicant->gender=$value['G'];
                    $applicant->age=$value['H'];
                    $applicant->nationality=$value['I'];
                    $applicant->phoneNumber=$value['J'];
                    $applicant->dob=date("Y-m-d", strtotime($value['K']));
                    $applicant->currentSalary=$value['L'];
                    $applicant->expectedSalary=$value['M'];
                    $applicant->city=$value['N'];
                    $applicant->country=$value['O'];
                    $applicant->save();
                }

            }

            return redirect()->route('applicant_list')->with('info','File uploaded successfully.');
        }else
        {
            return redirect('/upload-csv')->with('info','File upload error.');
        }

    }


    public function uploadCvPost(Request $request)
    {
        if($request->hasFile('uploadedCv') && ($request->uploadedCv->getClientOriginalExtension()=="doc" || $request->uploadedCv->getClientOriginalExtension()=="docx" || $request->uploadedCv->getClientOriginalExtension()=="pdf") )
        {
            $user=Applicants::where('id',$request->userId)->first();


            $slug = Str::slug($user->firstName." ".$user->LastName);
            $count = Applicants::whereRaw("cvSlug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            $finalFileName= $count ? "{$slug}-{$count}" : $slug;

            $file = $request->file('uploadedCv')->storeAs('Public/Resumes', $finalFileName.'.'.$request->uploadedCv->getClientOriginalExtension() ,'local');

            $name = pathinfo($file, PATHINFO_FILENAME);
            $filePath=storage_path("app\\Public\\Resumes\\".$finalFileName.".".$request->uploadedCv->getClientOriginalExtension());
            //pdf parsing
            if( $request->uploadedCv->getClientOriginalExtension()=="pdf")
            {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf    = $parser->parseFile($filePath);
                $text = $pdf->getText();
            }else
            {
                $text="";
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
                $sections = $phpWord->getSections();
                foreach ($sections as $key => $value) {
                    $sectionElement = $value->getElements();
                    foreach ($sectionElement as $elementKey => $elementValue) {
                        if ($elementValue instanceof \PhpOffice\PhpWord\Element\TextRun) {
                            $secondSectionElement = $elementValue->getElements();
                            foreach ($secondSectionElement as $secondSectionElementKey => $secondSectionElementValue) {
                                if ($secondSectionElementValue instanceof \PhpOffice\PhpWord\Element\Text) {
                                    $text= $secondSectionElementValue->getText()."<br>";
                                }
                            }
                        }
                    }
                }
            }
            $user->cvSlug=$finalFileName;
            $user->cvExt=$request->uploadedCv->getClientOriginalExtension();
            $user->cvUrl=$file;
            $user->cvData=$text;
            $user->save();

            return redirect('Admin.applicant_list');


        }else{
            return "File Type Incorrect.";
        }


    }

    public function viewCv($id)
    {
        $user=Applicants::where('id',$id)->first();
        if($user->cvUrl != "")
        {

            return response()->file(storage_path('app/'.$user->cvUrl));

        }else
        {
            return redirect('Admin.applicant_list')->with('status','Resume not Found.');
        }
    }

    public function delete($id)
    {
        $applicant = Applicants::find($id);
        $applicant->delete();
        return redirect('Admin.applicant_list')->with('status','Employee Deleted.');
    }
}
