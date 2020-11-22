<?php

namespace App\Http\Controllers;

use App\Models\Student;
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
        $student = Student::all();
        return $this->successResponse($student);
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
                'years_id' => $request->get('years_id'),
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
            $student = Student::findOrFail($id);
            $student->nrp = $request->nrp;
            $student->name = $request->name;
            $student->password = md5($request->password);
            $student->email = $request->email;
            $student->gender = $request->gender;
            $student->years_id = $request->years_id;
            $student->save();
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
        try {
            $student = DB::table('students')
                ->select('students.*')
                ->where('students.nrp', '=', $nrp)
                ->where('students.password', '=', md5($password))
                ->get();
            if(count($student) != 0){
                return $this->successResponse($student);
            }else {
                return $this->errorResponse('Cannot find the user.', 400);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Something has been wrong.', 400);
        }
    }
}
