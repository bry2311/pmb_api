<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CommentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $comment = Comment::all();
        return $this->successResponse($comment);
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
            "description" => "required",
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
            $comment = Comment::create([
                'date' => $request->get('date'),
                'description' => $request->get('description'),
                'students_id' => $request->get('students_id'),
                'forums_id' => $request->get('forums_id'),
                'commentscol' => 'a',
                'years_id' => $yearId
            ]);
            return $this->successResponse($comment, 'Comment Created', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be created', 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            "description" => "required"
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $comment = Comment::findOrFail($id);
            $comment->date = $request->date;
            $comment->description = $request->description;
            $comment->students_id = $request->students_id;
            $comment->forums_id = $request->forums_id;
            $comment->save();
            return $this->successResponse($comment, 'Comment Updated', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $comment = Comment::findOrFail($id);
            $comment->delete();
            return $this->successResponse(null, 'Comment Deleted');
        } catch (Exception $e) {
            return $this->errorResponse('Cannot be updated', 400);
        }
    }

    public function getAllCommentByIdForum($id)
    {
        $comment = DB::table('comments')
        ->join('students', 'comments.students_id', '=', 'students.id')
        ->join('years', 'comments.years_id', '=', 'years.id')
        ->join('forums', 'forums.years_id', '=', 'years.id')
        ->where('comments.forums_id','=',$id)
        ->select('comments.*','students.name as student_name','years.name as year_name','forums.name as forum_name')
        ->get();
        return $this->successResponse($comment);
    }
}
