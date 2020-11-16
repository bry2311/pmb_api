<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class LecturerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $lecturer = Lecturer::all();
        return $this->successResponse($lecturer);
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
            "nip" => "required|unique:lecturers",
            "name" => "required",
            "email" => "regex:/^.+@.+$/i",
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $lecturer = Lecturer::create([
                'nip' => $request->get('nip'),
                'name' => $request->get('name'),
                'password' => md5($request->get('password')),
                'email' => $request->get('email'),
                'status' => $request->get('status'),
                'genre' => $request->get('genre'),
                'jabatan' => $request->get('jabatan'),
            ]);
            return $this->successResponse($lecturer, 'Lecturer Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lecturer  $lecturer
     * @return \Illuminate\Http\Response
     */
    public function show(Lecturer $lecturer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lecturer  $lecturer
     * @return \Illuminate\Http\Response
     */
    public function edit(Lecturer $lecturer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lecturer  $lecturer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            "nip" => "required|unique:posts",
            "name" => "required",
            "email" => "regex:/^.+@.+$/i",
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        try {
            $lecturer = Lecturer::findOrFail($id);
            $lecturer->nip = $request->nip;
            $lecturer->name = $request->name;
            $lecturer->password = md5($request->password);
            $lecturer->email = $request->email;
            $lecturer->status = $request->status;
            $lecturer->genre = $request->genre;
            $lecturer->jabatan = $request->jabatan;
            $lecturer->save();
            return $this->successResponse($lecturer, 'Lecturer Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lecturer  $lecturer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $lecturer = Lecturer::findOrFail($id);
            $lecturer->delete();
            return $this->successResponse(null, 'Lecturer Deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    public function login(Request $request)
    {
        $post = $request->all();
        $nip = $post['nip'];
        $password = $post['password'];
        try {
            $lecturer = DB::table('lecturers')
                ->select('lecturers.*')
                ->where('lecturers.nip', '=', $nip)
                ->where('lecturers.password', '=', md5($password))
                ->get();
            if(count($lecturer) != 0){
                return $this->successResponse($lecturer);
            }else {
                return $this->errorResponse('Cannot find the user.', 400);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Something has been wrong.', 400);
        }
    }
}
