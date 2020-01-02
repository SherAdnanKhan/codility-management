<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Http\Requests\EmployeeRequest;
use App\Role;
use App\TimeTable;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employees = Employee::get_employees($request->auto_complete_search,'name');
            return $employees;
        } elseif($request->search) {
            $employees = Employee::get_employees($request->auto_complete_search)->paginate(10);
            return view('Employee.index', compact('employees'));
        } else {
            $employees = Employee::get_employees()->paginate(10);
            return view('Employee.index', compact('employees'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Employee.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EmployeeRequest $employeeRequest
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $employeeRequest)
    {
        $time_table  = TimeTable::getAllowedDays();
        $employee = User::create([
            'name'                  => $employeeRequest->name,
            'email'                 => $employeeRequest->email,
            'password'              => bcrypt($employeeRequest->password),
            'address'               => $employeeRequest->address,
            'qualification'         => $employeeRequest->qualification,
            'designation'           => $employeeRequest->designation,
            'phoneNumber'           => $employeeRequest->phoneNumber,
            'joiningDate'           => $employeeRequest->joiningDate,
            'checkInTime'           => Carbon::parse($time_table['time_table']->start_time)->timestamp,
            'checkOutTime'          => Carbon::parse($time_table['time_table']->end_time)->timestamp,
            'breakAllowed'          => Carbon::parse($time_table['time_table']->non_working_hour)->timestamp,
            'workingDays'           => $time_table['days_count'],
            'cnic_no'               => $employeeRequest->cnic?$employeeRequest->cnic:null,
            'ntn_no'                => $employeeRequest->ntn?$employeeRequest->ntn:null,
            'bank_account_no'       => $employeeRequest->account_no?$employeeRequest->account_no:null,
            'blood_group'           => $employeeRequest->blood_group?$employeeRequest->blood_group:null,
            'allotted_leaves'       => 17,
            'shift_time'            => 1

        ]);
        $role = Role::findOrFail(1);
        $employee->role()->attach($role);
        return view('Admin.index');
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
