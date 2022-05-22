
@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <reports :user="{{ Auth::user() }}"></reports>
    </div>
@endsection
