<?php

namespace App\Http\Controllers;

use App\Models\Ct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CtController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ct = Ct::all();
        return $this->successResponse($ct);
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
            "name" => "required",
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $years = DB::table('years')
            ->where('years.status','=',1)
            ->first();
            $yearId = null;
            if($years != null){
                $yearId = $years->id;
            }
            $ct = Ct::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'date' => $request->get('date'),
                'start' => $request->get('start'),
                'end' => $request->get('end'),
                'duration' => $request->get('duration'),
                'years_id' => $yearId
            ]);
            return $this->successResponse($ct, 'Ct Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ct  $ct
     * @return \Illuminate\Http\Response
     */
    public function show(ct $ct)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ct  $ct
     * @return \Illuminate\Http\Response
     */
    public function edit(ct $ct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ct  $ct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            "name" => "required"
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $ct = Ct::findOrFail($id);
            $ct->name = $request->name;
            $ct->description = $request->description;
            $ct->date = $request->date;
            $ct->start = $request->start;
            $ct->end = $request->end;
            $ct->duration = $request->duration;
            $ct->save();
            return $this->successResponse($ct, 'Ct Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ct  $ct
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $ct = Ct::findOrFail($id);
            $ct->delete();
            return $this->successResponse(null, 'Ct Deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    //sementara sebelum ganti db abis testing user aja
    public function getCtsByUser($userid)
    {
        $years = DB::table('years')
        ->where('years.status','=',1)
        ->first();
        $yearId = null;
        if($years != null){
            $yearId = $years->id;
        }

        $dataCT = DB::table('cts')
        ->where('cts.years_id','=',$yearId)
        ->select()
        ->get();
        
        $tmpArray = [];
        $i = 0;
        foreach($dataCT as $ct){
            $jawabanUserPG = DB::table('jawaban_user_pgs')
            ->where([
                ['jawaban_user_pgs.cts_id','=',$ct->id],
                ['jawaban_user_pgs.students_id','=',$userid]
                ])
            ->get();
            $jawabanUserIsian = DB::table('jawaban_user_isians')
            ->where([
                ['jawaban_user_isians.cts_id','=',$ct->id],
                ['jawaban_user_isians.students_id','=',$userid]
                ])
            ->get();
            // kalo user tsb ga ada data pernah ngerjain cts ini, tampilkan
            if(count($jawabanUserPG) == 0 && count($jawabanUserIsian) == 0){
                $tmpArray[$i] = $ct;
                $i++;
            }
        }
        return $this->successResponse($tmpArray);
    }


    public function getPengerjaanMhsByCT($ct)
    {
        $years = DB::table('years')
        ->where('years.status','=',1)
        ->first();
        $yearId = null;
        if($years != null){
            $yearId = $years->id;
        }
        $tmpArray = [];
        $jawabanUserPG = DB::table('jawaban_user_pgs')
        ->join('students', 'jawaban_user_pgs.students_id', '=', 'students.id')
        ->join('cts', 'jawaban_user_pgs.cts_id', '=', 'cts.id')
        ->where('jawaban_user_pgs.cts_id','=',$ct)
        ->groupBy('students.id')
        ->get(['students.name as name','students.nrp as nrp','jawaban_user_pgs.cts_id as cts_id','cts.name as ctsName']);
        // kalo ga ad pg cari di isian
        if(count($jawabanUserPG) > 0){
            $jawabanUserIsian = DB::table('jawaban_user_isians')
            ->join('students', 'jawaban_user_isians.students_id', '=', 'students.id')
            ->join('cts', 'jawaban_user_isians.cts_id', '=', 'cts.id')
            ->where('jawaban_user_isians.cts_id','=',$ct)
            ->groupBy('students.id')
            ->get(['students.name as name','students.nrp as nrp','jawaban_user_isians.cts_id as cts_id','cts.name as ctsName']);
            $tmpArray = $jawabanUserIsian->toArray();
        }else{
            $tmpArray = $jawabanUserPG->toArray();
        }
        return $this->successResponse($tmpArray);
    }

    
    public function getJawaban($ct)
    {
        $years = DB::table('years')
        ->where('years.status','=',1)
        ->first();
        $yearId = null;
        if($years != null){
            $yearId = $years->id;
        }
        $tmpArray = [];
        $jawabanUserPG = DB::table('jawaban_user_pgs')
        ->join('students', 'jawaban_user_pgs.students_id', '=', 'students.id')
        ->join('cts', 'jawaban_user_pgs.cts_id', '=', 'cts.id')
        ->where('jawaban_user_pgs.cts_id','=',$ct)
        ->groupBy('students.id')
        ->get(['students.name as name','students.nrp as nrp','jawaban_user_pgs.cts_id as cts_id','cts.name as ctsName']);
        // kalo ga ad pg cari di isian
        if(count($jawabanUserPG) > 0){
            $jawabanUserIsian = DB::table('jawaban_user_isians')
            ->join('students', 'jawaban_user_isians.students_id', '=', 'students.id')
            ->join('cts', 'jawaban_user_isians.cts_id', '=', 'cts.id')
            ->where('jawaban_user_isians.cts_id','=',$ct)
            ->groupBy('students.id')
            ->get(['students.name as name','students.nrp as nrp','jawaban_user_isians.cts_id as cts_id','cts.name as ctsName']);
            $tmpArray = $jawabanUserIsian->toArray();
        }else{
            $tmpArray = $jawabanUserPG->toArray();
        }
        return $this->successResponse($tmpArray);
    }

    public function getJawabanPerMahasiswaPerCT($ct,$userId)
    {
        $years = DB::table('years')
        ->where('years.status','=',1)
        ->first();
        $yearId = null;
        if($years != null){
            $yearId = $years->id;
        }

        //belom beres
        $tmpArray = [];
        $tmpArray2 = [];
        $tmpArray3 = [];
        
        // $jawabanUserPG = DB::table('cts')
        // ->join('jawaban_user_pgs', 'cts.id', '=', 'jawaban_user_pgs.cts_id')
        // ->join('soal_pgs', 'cts.id', '=', 'soal_pgs.cts_id')
        // ->join('students', 'jawaban_user_pgs.students_id', '=', 'students.id')
        // ->where([
        //     ['cts.id','=',$ct],
        //     ['students.id','=',$userId]])
        // ->get();

        $jawabanUserPG = DB::table('cts')
        ->join('jawaban_user_pgs', 'cts.id', '=', 'jawaban_user_pgs.cts_id')
        ->join('students', 'jawaban_user_pgs.students_id', '=', 'students.id')
        ->where('jawaban_user_pgs.cts_id','=',$ct)
        ->where('jawaban_user_pgs.students_id','=',$userId)
        ->get();

        // $jawabanUserPG = DB::table('jawaban_user_pgs')
        // ->join('students', 'jawaban_user_pgs.students_id', '=', 'students.id')
        // ->join('cts', 'jawaban_user_pgs.cts_id', '=', 'cts.id')
        // ->join('soal_pgs', 'soal_pgs.cts_id', '=', 'cts.id')
        // ->where([
        //     ['jawaban_user_pgs.cts_id','=',$ct],
        //     ['jawaban_user_pgs.students_id','=',$userId]])
        // ->get(['students.name as name','students.nrp as nrp','jawaban_user_pgs.cts_id as cts_id','cts.name as ctsName','soal_pgs.key as key','soal_pgs.question as question','jawaban_user_pgs.answer as answer','jawaban_user_pgs.correctness as correctness']);
        $jawabanUserIsian = DB::table('jawaban_user_isians')
        ->join('students', 'jawaban_user_isians.students_id', '=', 'students.id')
        ->join('cts', 'jawaban_user_isians.cts_id', '=', 'cts.id')
        ->join('soal_isians', 'cts.id', '=', 'soal_isians.cts_id')
        ->where([
            ['jawaban_user_isians.cts_id','=',$ct],
            ['jawaban_user_isians.students_id','=',$userId]])
        ->get(['students.name as name','students.nrp as nrp','jawaban_user_isians.cts_id as cts_id','cts.name as ctsName','soal_isians.question as question','jawaban_user_isians.answer as answer']);

        if(count($jawabanUserPG) > 0 && count($jawabanUserIsian) > 0){
            // kalau gabungan 22 nya ada
            $tmpArray = $jawabanUserPG->toArray();
            for($i=0; $i<count($tmpArray);$i++){
                $tmpArray[$i]->jenis = "pilihan ganda";
            }
            $tmpArray2 = $jawabanUserIsian->toArray();
            for($i=0; $i<count($tmpArray2);$i++){
                $tmpArray2[$i]->jenis = "isian";
                $tmpArray2[$i]->correctness = "isian";
                $tmpArray2[$i]->key = "-";
            }
            $tmpArray3 = array_merge($tmpArray,$tmpArray2);
        }else if(count($jawabanUserIsian) > 0 && count($jawabanUserPG) == 0){
            // kalo isian doang yang ada
            $tmpArray = $jawabanUserIsian->toArray();
            for($i=0; $i<count($tmpArray2);$i++){
                $tmpArray2[$i]->jenis = "isian";
                $tmpArray2[$i]->correctness = "isian";
                $tmpArray2[$i]->key = "-";
            }
            $tmpArray3 = $tmpArray;
        }else if(count($jawabanUserIsian) == 0 && count($jawabanUserPG) > 0){
            // kalo pg doang yg ada
            $tmpArray = $jawabanUserPG->toArray();
            for($i=0; $i<count($tmpArray);$i++){
                $tmpArray[$i]->jenis = "pilihan ganda";
            }
            $tmpArray3 = $tmpArray;
        }
        // dd($jawabanUserPG);
        return $this->successResponse($tmpArray);
    }
}
