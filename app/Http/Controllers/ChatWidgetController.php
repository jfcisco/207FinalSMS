<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatWidgetResource;
use App\Models\ChatWidget;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatWidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['data' => ChatWidgetResource::collection(ChatWidget::all())], 200);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response([
                'error' => $validator->errors(),
            ], 400);
        }

        $chatWidget = new ChatWidget();
        $chatWidget->created_by_id = $request->created_by_id;
        $chatWidget->name = $request->name;
        $chatWidget->color = $request->color;
        $chatWidget->position = $request->position;
        $chatWidget->hide_when_offline = $request->hide_when_offline;
        $chatWidget->hide_when_on_desktop = $request->hide_when_on_desktop;
        $chatWidget->hide_when_on_mobile = $request->hide_when_on_mobile;
        $chatWidget->enable_emojis = $request->enable_emojis;
        $chatWidget->availability_start_time = $request->availability_start_time;
        $chatWidget->availability_end_time = $request->availability_end_time;
        $chatWidget->generated_code = $request->generated_code;
        $chatWidget->direct_chat_link = $request->direct_chat_link;
        $chatWidget->is_active = $request->is_active;
        $chatWidget->save();

        $user = User::find($request->created_by_id);
        $user->chat_widgets()->attach($chatWidget->_id);
        $user->save();


        return response(['data' => new ChatWidgetResource($chatWidget)], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $chatWidget = ChatWidget::find($id);
        return response(['data' => new ChatWidgetResource($chatWidget)], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $chatWidget = ChatWidget::find($id);
        $chatWidget->name = $request->name;
        $chatWidget->color = $request->color;
        $chatWidget->hide_when_offline = $request->hide_when_offline;
        $chatWidget->hide_when_on_desktop = $request->hide_when_on_desktop;
        $chatWidget->hide_when_on_mobile = $request->hide_when_on_mobile;
        $chatWidget->enable_emojis = $request->enable_emojis;
        $chatWidget->availability_start_time = $request->availability_start_time;
        $chatWidget->availability_end_time = $request->availability_end_time;
        $chatWidget->allowed_domains = $request->allowed_domains;
        $chatWidget->generated_code = $request->generated_code;
        $chatWidget->direct_chat_link = $request->direct_chat_link;
        $chatWidget->is_active = $request->is_active;
        $chatWidget->save();

        return response([
            'message' => 'Successfully updated chat widget.',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validator(array $data)
    {
        return Validator::make($data, [
            'created_by_id' => ['required'],
            'name' => ['required'],
        ]);
    }
}
