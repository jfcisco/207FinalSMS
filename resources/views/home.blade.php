@extends('layouts.app')
<?php
$crm = isset($_GET['crm']) ? $_GET['crm'] : null;
$strt = isset($_GET['strt']) ? $_GET['strt'] : null;
?>
@section('content')
    <div class="container-fluid">
        <chats :user="{{ Auth::user() }}" crm="{{ $crm }}" strt="{{ $strt }}"></chats>
    </div>
@endsection
