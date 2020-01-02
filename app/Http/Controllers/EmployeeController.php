<?php

namespace App\Http\Controllers;

use App\Employee;
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
        if ($employee->ajax()) {
            $employees = Employee::get_employees($employee->auto_complete_search,'name');
            return $employees;
        } elseif($employee->search) {
            $employees = Employee::get_employees($employee->auto_complete_search)->paginate(10);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Employee $employee)
    {
        $timing  = TimeTable::whereId(1)->first();
        $user = Employee::create([
            'name'                  => $employee->name,
            'email'                 => $employee->email,
            'password'              => bcrypt($employee->password),
            'address'               => $employee->address,
            'qualification'         => $employee->qualification,
            'designation'           => $employee->designation,
            'phoneNumber'           => $employee->phoneNumber,
            'joiningDate'           => $employee->joiningDate,
            'checkInTime'           => Carbon::parse($timing->start_time)->timestamp,
            'checkOutTime'          => Carbon::parse($timing->end_time)->timestamp,
            'breakAllowed'          => Carbon::parse($timing->non_working_hour)->timestamp,
            'workingDays'           => $count,
            'cnic_no'               => $employee->cnic?$employee->cnic:null,
            'ntn_no'                => $employee->ntn?$employee->ntn:null,
            'bank_account_no'       => $employee->account_no?$employee->account_no:null,
            'blood_group'           => $employee->blood_group?$employee->blood_group:null,
            'allotted_leaves'       => 17,
            'shift_time'            => 1

        ]);
        $role = Role::findOrFail(1);
        $user->role()->attach($role);
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
