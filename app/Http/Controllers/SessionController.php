<?php

namespace App\Http\Controllers;

use App\Http\Resources\SessionResource;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response(['data' => SessionResource::collection(Session::all())], 200);
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

        $session = new Session();
        $session->chat_widget_id = $request->chat_widget_id;
        $session->visitor_id = $request->visitor_id;
        $session->socket_id = $request->socket_id;
        $session->ip_address = $request->ip_address;
        $session->browser = $request->browser;
        $session->webpage_source = $request->webpage_source;
        $session->save();

        return response(['data' => new SessionResource($session)], 200);
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'chat_widget_id' => ['required'],
            'visitor_id' => ['required'],
            'socket_id' => ['required'],
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
        $session = Session::find($id);
        return response(['data' => new SessionResource($session)], 200);
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
