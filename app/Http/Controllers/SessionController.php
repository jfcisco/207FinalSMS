<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $input = $request->all();
        $validator = $this->validator($input);

        if ($validator->fails()) {
            return response([
                'error' => $validator->errors(),
            ], 400);
        }

        $session = new Session();
        $session->chat_widget_id = $input['chat_widget_id'];
        $session->visitor_id =$input['visitor_id'];
        $session->save();
        $session->load('chat_widget');

        return response(['data' => new SessionResource($session)], 200);
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


    public function validator(array $data)
    {
        return Validator::make($data, [
            'chat_widget_id' => ['required'],
            'visitor_id' => ['required'],
        ]);
    }
}
