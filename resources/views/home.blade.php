@extends('layouts.app')
<?php
$crm = isset($_GET['crm']) ? $_GET['crm'] : null;
?>
@section('content')
    <div class="container-fluid">
        <chats :user="{{ Auth::user() }}" crm="{{ $crm }}"></chats>
    </div>
@endsection
