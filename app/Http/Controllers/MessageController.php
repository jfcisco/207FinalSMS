<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response(['data' => MessageResource::collection(Message::all())], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response([
                'error' => $validator->errors(),
            ], 400);
        }

        $message = new Message();
        $message->session_id = $request->session_id;
        $message->created_by_id = $request->created_by_id;
        $message->attachment_path = $request->attachment_path;
        $message->message = $request->message;
        $message->is_whisper = $request->is_whisper;
        $message->save();

        return response(['data' => new MessageResource($message)], 200);
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'session_id' => ['required'],
            'created_by_id' => ['required'],
            'message' => ['required'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $message = Message::find($id);
        return response(['data' => new MessageResource($message)], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
