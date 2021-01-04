<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Role_has_student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class StudentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $student = Student::all();
        $students = DB::table('role_has_students')
        ->join('students', 'role_has_students.students_id', '=', 'students.id')
        ->join('roles', 'role_has_students.roles_id', '=', 'roles.id')
        ->join('years', 'role_has_students.years_id', '=', 'years.id')
        ->where('years.status','=',1)
        ->select('students.*','roles.name as roles_name','years.name as years_name')
        ->get();
        return $this->successResponse($students);
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
        $validator = Validator::make($request->all(), [
            "nrp" => "required|unique:students",
            "name" => "required",
            "email" => "regex:/^.+@.+$/i",
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $student = Student::create([
                'nrp' => $request->get('nrp'),
                'name' => $request->get('name'),
                'password' => md5($request->get('password')),
                'email' => $request->get('email'),
                'gender' => $request->get('gender'),
            ]);
            $years = DB::table('years')
            ->where('years.status','=',1)
            ->first();
            $yearId = null;
            if($years != null){
                $yearId = $years->id;
            }
            $RoleStudent = Role_has_student::create([
                'roles_id' => 1,
                'students_id' => $student->id,
                'years_id' => $yearId,
            ]);
            return $this->successResponse($student, 'Student Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            $student = Student::findOrFail($id);
            return $this->successResponse($student, 'Student Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be found', 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            "nrp" => "required",
            "name" => "required",
            "email" => "regex:/^.+@.+$/i",
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            if($request->last_password == $request->password){
                $student = Student::findOrFail($id);
                $student->nrp = $request->nrp;
                $student->name = $request->name;
                // $student->password = md5($request->password);
                $student->email = $request->email;
                $student->gender = $request->gender;
                $student->save();
            } else{
                $student = Student::findOrFail($id);
                $student->nrp = $request->nrp;
                $student->name = $request->name;
                $student->password = md5($request->password);
                $student->email = $request->email;
                $student->gender = $request->gender;
                $student->save();
            }
            return $this->successResponse($student, 'Student Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $student = Student::findOrFail($id);
            $student->delete();
            return $this->successResponse(null, 'Student Deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    public function login(Request $request)
    {
        $post = $request->all();
        $nrp = $post['nrp'];
        $password = $post['password'];
        $students = DB::table('role_has_students')
            ->join('students', 'role_has_students.students_id', '=', 'students.id')
            ->join('roles', 'role_has_students.roles_id', '=', 'roles.id')
            ->join('years', 'role_has_students.years_id', '=', 'years.id')
            ->where('students.nrp', '=', $nrp)
            ->where('students.password', '=', md5($password))
            ->select('students.*','roles.name as roles_name','years.name as years_name')
            ->get();
        try {
            $students = DB::table('role_has_students')
                ->join('students', 'role_has_students.students_id', '=', 'students.id')
                ->join('roles', 'role_has_students.roles_id', '=', 'roles.id')
                ->join('years', 'role_has_students.years_id', '=', 'years.id')
                ->where('students.nrp', '=', $nrp)
                ->where('students.password', '=', md5($password))
                ->select('students.*','roles.name as roles_name','years.name as years_name')
                ->get();
            if(count($students) != 0){
                return $this->successResponse($students);
            }else {
                return $this->errorResponse('Cannot find the user.', 400);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Something has been wrong.', 400);
        }
    }

    public function addPanitia(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            "nrp" => "required|unique:students",
            "name" => "required",
            "email" => "regex:/^.+@.+$/i",
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $student = Student::create([
                'nrp' => $request->get('nrp'),
                'name' => $request->get('name'),
                'password' => md5($request->get('password')),
                'email' => $request->get('email'),
                'gender' => $request->get('gender'),
            ]);
            $years = DB::table('years')
            ->where('years.status','=',1)
            ->first();
            $yearId = null;
            if($years != null){
                $yearId = $years->id;
            }
            $RoleStudent = Role_has_student::create([
                'roles_id' => 2,
                'students_id' => $student->id,
                'years_id' => $yearId,
            ]);
            return $this->successResponse($student, 'Student Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    public function getPanitia()
    {
        //
        // $student = Student::all();
        $students = DB::table('role_has_students')
        ->join('students', 'role_has_students.students_id', '=', 'students.id')
        ->join('roles', 'role_has_students.roles_id', '=', 'roles.id')
        ->join('years', 'role_has_students.years_id', '=', 'years.id')
        ->where('years.status','=',1)
        ->where('roles.id','=',2)
        ->select('students.*','roles.name as roles_name','years.name as years_name')
        ->get();
        return $this->successResponse($students);
    }

    public function getMahasiswa()
    {
        //
        // $student = Student::all();
        $students = DB::table('role_has_students')
        ->join('students', 'role_has_students.students_id', '=', 'students.id')
        ->join('roles', 'role_has_students.roles_id', '=', 'roles.id')
        ->join('years', 'role_has_students.years_id', '=', 'years.id')
        ->where('years.status','=',1)
        ->where('roles.id','=',1)
        ->select('students.*','roles.name as roles_name','years.name as years_name')
        ->get();
        return $this->successResponse($students);
    }
}
