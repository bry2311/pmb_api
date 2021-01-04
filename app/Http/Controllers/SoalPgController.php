<?php

namespace App\Http\Controllers;

use App\Models\SoalPg;
use App\Models\JawabanUserPg;
use App\Models\JawabanUserIsian;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SoalPgController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $soalPg = SoalPg::all();
        $soalPg = DB::table('soal_pgs')
        ->join('cts', 'soal_pgs.cts_id', '=', 'cts.id')
        ->select('soal_pgs.*','cts.name as cts_name')
        ->get();
        return $this->successResponse($soalPg);
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
            $soalPg = SoalPg::create([
                'number' => $request->get('number'),
                'question' => $request->get('question'),
                'A' => $request->get('A'),
                'B' => $request->get('B'),
                'C' => $request->get('C'),
                'D' => $request->get('D'),
                'E' => $request->get('E'),
                'key' => $request->get('key'),
                'cts_id' => $request->get('cts_id'),
            ]);
            return $this->successResponse($soalPg, 'SoalPg Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\soalPg  $soalPg
     * @return \Illuminate\Http\Response
     */
    public function show(soalPg $soalPg)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\soalPg  $soalPg
     * @return \Illuminate\Http\Response
     */
    public function edit(soalPg $soalPg)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\soalPg  $soalPg
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
            $soalPg = SoalPg::findOrFail($id);
            $soalPg->number = $request->number;
            $soalPg->question = $request->question;
            $soalPg->A = $request->A;
            $soalPg->B = $request->B;
            $soalPg->C = $request->C;
            $soalPg->D = $request->D;
            $soalPg->E = $request->E;
            $soalPg->key = $request->key;
            $soalPg->cts_id = $request->cts_id;
            $soalPg->save();
            return $this->successResponse($soalPg, 'SoalPg Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\soalPg  $soalPg
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $soalPg = SoalPg::findOrFail($id);
            $soalPg->delete();
            return $this->successResponse(null, 'SoalPg Deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    public function getAllSoalPgByIdCts($id,$userId)
    {
        
        $soalPg = DB::table('soal_pgs')
        ->join('cts', 'soal_pgs.cts_id', '=', 'cts.id')
        ->where('soal_pgs.cts_id','=',$id)
        ->select('soal_pgs.*','cts.name as ct_name')
        ->get();
        // $jawabanUser = DB::table('jawaban_user_pgs')
        // ->join('cts', 'jawaban_user_pgs.cts_id', '=', 'cts.id')
        // ->where([
        //     ['jawaban_user_pgs.cts_id','=',$id],
        //     ['jawaban_user_pgs.students_id','=',$userId]
        //     ])
        // ->get();
        $tmpArray = [];
        $tmpArray = $soalPg->toArray();
        if(count($tmpArray) != null){
            for($i=0; $i<count($tmpArray);$i++){
                $cek = false;
                $userid = "";
                $answer = "";
                // foreach($jawabanUser as $ju){
                //     if($ju->number == $tmpArray[$i]->number){
                //         $cek = true;
                //         $userid = $ju->students_id;
                //         $answer = $ju->answer;
                //     }
                // }
                // if($cek == true){
                //     $tmpArray[$i]->jawaban_user = $answer;
                //     $tmpArray[$i]->user_id = $userid;
                // } else{
                    $tmpArray[$i]->jawaban = "";
                    $tmpArray[$i]->user_id = $userId;
                // }
                // array_push($tmpArray[$i], ["jawaban_user" => ""]);
            }
        }
        
        // dd($tmpArray);exit;
        return $this->successResponse($tmpArray);
    }

    public function storeAnswerIsian(Request $request)
    {
        // var_dump($request->all());exit;

        $tmpArray = $request->all();

        for($i=0; $i<count($tmpArray);$i++){
            $jawabanpg = JawabanUserIsian::create([
                'answer'=>$tmpArray[$i]['jawaban'],
                'cts_id'=>$tmpArray[$i]['cts_id'],
                'students_id'=>$tmpArray[$i]['user_id'],
                'number'=>$tmpArray[$i]['number']
            ]);

        }
    }

    public function storeAnswerPG(Request $request)
    {
        // return var_dump($request->all());

        // var_dump($request->all());exit;
        $tmpArray = json_encode($request);
        var_dump($tmpArray);exit;

        for($i=0; $i<count($tmpArray);$i++){
            $jawabanpg = JawabanUserPg::create([
                'answer'=>$tmpArray[$i]['jawaban'],
                'cts_id'=>$tmpArray[$i]['cts_id'],
                'students_id'=>$tmpArray[$i]['user_id'],
                'number'=>$tmpArray[$i]['number']
            ]);

        }
    }

    public function storeAnswerPGGet($jwb)
    {
        // return var_dump($request->all());

        // var_dump($jwb);exit;
        $tmpArray = json_decode($jwb);
        // var_dump($tmpArray);exit;
        for($i=0; $i<count($tmpArray);$i++){
            if($tmpArray[$i]->jawaban == $tmpArray[$i]->key){
                $jawabanpg = JawabanUserPg::create([
                    'answer'=>$tmpArray[$i]->jawaban,
                    'cts_id'=>$tmpArray[$i]->cts_id,
                    'correctness'=>1,
                    'students_id'=>$tmpArray[$i]->user_id,
                    'number'=>$tmpArray[$i]->number
                ]);
            }else{
                $jawabanpg = JawabanUserPg::create([
                    'answer'=>$tmpArray[$i]->jawaban,
                    'cts_id'=>$tmpArray[$i]->cts_id,
                    'correctness'=>0,
                    'students_id'=>$tmpArray[$i]->user_id,
                    'number'=>$tmpArray[$i]->number
                ]);
            }
        }
    }

    public function storeAnswerIsianGet($jwb)
    {
        // var_dump($request->all());exit;
        // var_dump(json_decode($jwb));exit;

        $tmpArray = json_decode($jwb);
        // dd($jwb);
        for($i=0; $i<count($tmpArray);$i++){
            $jawabanpg = JawabanUserIsian::create([
                'answer'=>$tmpArray[$i]->jawaban,
                'cts_id'=>$tmpArray[$i]->cts_id,
                'students_id'=>$tmpArray[$i]->user_id,
                'number'=>$tmpArray[$i]->number
            ]);

        }
    }

    public function getScorePG($id,$userid)
    {
        $jawabanUser = DB::table('jawaban_user_pgs')
        ->join('cts', 'jawaban_user_pgs.cts_id', '=', 'cts.id')
        ->where([
            ['jawaban_user_pgs.cts_id','=',$id],
            ['jawaban_user_pgs.students_id','=',$userid]
            ])
        ->get();

        $totalbenar = 0;
        foreach($jawabanUser as $j){
            if($j->correctness == 1){
                $totalbenar += 1;
            }
        }
        return $this->successResponse($totalbenar);
    }
}
