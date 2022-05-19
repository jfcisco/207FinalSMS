@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!--MAIN SIDE BAR-->
        <div class="col-lg-1 mainsidebar">
            <a href="/home"><ion-icon name="mail-outline"></ion-icon><span class="menutitle">Messaging</span></a>
            <a href="#"><ion-icon name="bar-chart-outline"></ion-icon><span class="menutitle">Reporting</span></a>
            <a class="activemenu" href="/widgets"><ion-icon name="copy-outline"></ion-icon><span class="menutitle">Widget</span></a>
        </div>

        <section class="col py-4 px-5" style="background-color: #627894; color: whitesmoke;">
            <h1 class="mb-4">Chat Widgets</h1>
            @if (count($widgets) === 0)
            <p>You have no widgets yet!</p>
            @else
            {{-- TODO: Add widget selection component --}}
            {{-- TODO: Add link to create a widget --}}
            <div class="row gx-5 gy-2">
                <div class="col-lg">
                    <div class="row mb-3">
                        <label class="fs-5" for="widget-name">Widget Name</label>
                        <input class="form-control" name="widget-name" type="text" readonly value="{{ $currentWidget->name }}">
                    </div>
        
                    <div class="row mb-3">
                        <label class="fs-5" for="widget-id">Widget ID</label>
                        <input class="form-control" type="text" name="widget-id" id="widget-id" readonly value="{{ $currentWidget->_id }}">
                    </div>
        
                    {{-- <div class="row mb-2">
                        <div class="form-check">
                            <label class="col form-check-label" for="widget-status">Widget Status</label>
                            <input class="col form-check-input" type="checkbox" name="widget-status" id="widget-status" checked="{{ $currentWidget->is_active }}">
                        </div>
                    </div> --}}
        
                    {{-- TODO: Fill out the rest of the widget form's fields --}}
                </div>
                <div class="col-lg">
                    {{-- This is the part that generates the HTML code of the widget --}}
                    <div class="row mb-2">
                        <h3 class="fs-4">Widget Code</h3>
                        <p class="mb-0"><strong>Copy and paste the HTML code below</strong> into your website's HTML document to give it chat functionality.</p>
                        
                        <pre class="widget-code bg-dark p-3 rounded">{{ $template }}</pre>
                    </div>
                </div>
            </div>
            @endif
        </section>
    </div>
</div>
@endsection