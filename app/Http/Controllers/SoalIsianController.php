<?php

namespace App\Http\Controllers;

use App\Models\SoalIsian;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SoalIsianController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $soalIsian = SoalIsian::all();
        $soalIsian = DB::table('soal_isians')
        ->join('cts', 'soal_isians.cts_id', '=', 'cts.id')
        ->select('soal_isians.*','cts.name as cts_name')
        ->get();
        return $this->successResponse($soalIsian);
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
            "question" => "required",
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $soalIsian = SoalIsian::create([
                'number' => $request->get('number'),
                'question' => $request->get('question'),
                'score' => $request->get('score'),
                'cts_id' => $request->get('cts_id'),
            ]);
            return $this->successResponse($soalIsian, 'SoalIsian Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\soalIsian  $soalIsian
     * @return \Illuminate\Http\Response
     */
    public function show(soalIsian $soalIsian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\soalIsian  $soalIsian
     * @return \Illuminate\Http\Response
     */
    public function edit(soalIsian $soalIsian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\soalIsian  $soalIsian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            "question" => "required"
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $soalIsian = SoalIsian::findOrFail($id);
            $soalIsian->number = $request->number;
            $soalIsian->question = $request->question;
            $soalIsian->score = $request->score;
            $soalIsian->cts_id = $request->cts_id;
            $soalIsian->save();
            return $this->successResponse($soalIsian, 'SoalIsian Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\soalIsian  $soalIsian
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $soalIsian = SoalIsian::findOrFail($id);
            $soalIsian->delete();
            return $this->successResponse(null, 'SoalIsian Deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    public function getAllSoalIsianByIdCT($id,$userid)
    {
        $soalIsian = DB::table('soal_isians')
        ->where('cts_id','=',$id)
        ->select('soal_isians.*')
        ->get();
        $jawabanUser = DB::table('jawaban_user_isians')
        ->join('cts', 'jawaban_user_isians.cts_id', '=', 'cts.id')
        ->where([
            ['jawaban_user_isians.cts_id','=',$id],
            ['jawaban_user_isians.students_id','=',$userid]
            ])
        ->get();
        $tmpArray = [];
        $tmpArray = $soalIsian->toArray();
        if(count($tmpArray) != null){
            for($i=0; $i<count($tmpArray);$i++){
                $cek = false;
                $userid = "";
                $answer = "";
                foreach($jawabanUser as $ju){
                    if($ju->number == $tmpArray[$i]->number){
                        $cek = true;
                        $userid = $ju->students_id;
                        $answer = $ju->answer;
                    } 
                }
                if($cek == true){
                    $tmpArray[$i]->jawaban_user = $answer;
                    $tmpArray[$i]->user_id = $userid;
                } else{
                    $tmpArray[$i]->jawaban_user = "";
                    $tmpArray[$i]->user_id = "";
                }
                // array_push($tmpArray[$i], ["jawaban_user" => ""]);
            }
        }
        // var_dump($soalPg);exit;
        return $this->successResponse($tmpArray);
    }
}