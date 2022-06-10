@extends('layouts.app')
<?php
$crm = isset($_GET['crm']) ? $_GET['crm'] : null;
$cnv = isset($_GET['cnv']) ? $_GET['cnv'] : null;
?>
@section('content')
    <div class="container-fluid">
        <chats :user="{{ Auth::user() }}" crm="{{ $crm }}" cnv="{{ $cnv }}"></chats>
    </div>
@endsection
