@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <chats :user="{{ Auth::user() }}"></chats>
</div>

@endsection
