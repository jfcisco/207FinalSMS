
@extends('layouts.app')

@section('content')
<div class="container-fluid widget-details-container">
    <div class="row">
       

        <!--MAIN SIDE BAR START-->
          <div class="col-lg-1 col-sm-1 mainsidebar">
            <a href="/home">
              <ion-icon class="main-menu-icon" name="mail-outline"></ion-icon>
              <span class="menutitle">Messaging</span>
            </a>
            <a href="/reports">
              <ion-icon class="main-menu-icon" name="bar-chart-outline"></ion-icon>
              <span class="menutitle">Reporting</span>
            </a>
            <a href="/widgets" class="activemenu">
              <ion-icon name="copy-outline" class="main-menu-icon"></ion-icon>
              <span class="menutitle">Widget</span>
            </a>
          </div>
        <!--MAIN SIDE BAR END-->

        
        <div class="col-lg-11 col-sm-11 sidebar widget-details-form">
            <form action="/widgets/{{$currentWidget->_id}}/update" method="POST">
                
                {{ csrf_field() }}
                @if ($errors->any())
                <div class="row">
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                </div>
                @endif

                @if(Auth::user()->role !== 'admin')
                <div class="row">
                    <p class="text-warning">This page is view-only. You are not authorized to make changes to the widget settings.</p>                    
                </div>
                @endif

                <div class="row">
                    <div class="col-lg-5">
                        <h2 class="chat-header">Chat Widgets</h2>

                        @if (count($widgets) === 0)
                        <p class="no-widget-prompt">You have no widgets yet!</p>
                        @else
                        <div class="widget-block">
                            <label class="chat-label" for="widget-name">Widget Name</label>
                            <input @class([
                                    'form-control',
                                    'form-control-widget' => Auth::user()->role === 'admin',
                                    'form-control-widget-full' => Auth::user()->role !== 'admin'
                                ]) 
                                name="name" type="text" 
                                @if (Auth::user()->role !== 'admin')    
                                    readonly 
                                @endif
                            value="{{ old('name', $currentWidget->name) }}">

                            @if (Auth::user()->role === 'admin')    
                            <button class="save-button">Save</button>
                            @endif
                        </div>
    
                        <div class="widget-block">
                            <label class="chat-label" for="widget-status">Widget Status</label>
                            <select class="form-select form-control-widget-full" type="dropdownbutton" name="is_active">
                                @if (Auth::user()->role !== 'admin')
                                    disabled
                                @endif

                            <option value="1"
                                @if (old('is_active', $currentWidget->is_active) == true)
                                selected
                                @endif >
                                Active </option>

                            <option value="0"
                                @if (old('is_active', $currentWidget->is_active) == false)
                                selected
                                @endif >
                                Inactive </option>
                            </select>
                        </div>
                        <div class="widget-block">
                            <label class="chat-label" for="widget-id">Widget ID</label>
                            <input class="form-control form-control-widget-full" type="text" name="widget-id" id="widget-id" readonly value="{{ $currentWidget->_id }}">
                        </div>
                            
                        <div class="widget-block">
                            <label class="chat-label" for="chat-link">Widget Icon</label>
                            <input class="form-control" type="file" name="widget-icon" id="widget-icon">
                        </div>

                        <div class="widget-block">
                            <label class="chat-label">Widget Color</label>  
                            <input class="form-control form-control-color form-control-widget-full" type="color" name="widget-color" id="widget-color" >
                        </div>

                        <div class="widget-block">
                            <span class="chat-label">File Sharing Enabled</span>
                            <label class="switch">
                                <input type="checkbox" name="enable_file_sharing" 
                                    @if (old('enable_file_sharing', $currentWidget->enable_file_sharing) === true)
                                        checked="checked"
                                    @endif

                                    @if (Auth::user()->role !== 'admin')
                                        disabled
                                    @endif
                                >
                                <span class="slider round"></span>
                            </label> 
                        </div>
                    </div>
    
                    <div class="col-lg-6">
                        <!--CODE WIDGET LINK-->
                        {{-- This is the part that generates the HTML code of the widget --}}
                        <div class="row marginallowance2">
                            <h4 class="chat-subheader-2 mb-0">Widget Code</h4>
                            <p class="chat-label-2 mb-0"><strong>Copy and paste the HTML code below</strong> into your website's HTML document to give it chat functionality.</p>
                            
                            <textarea class="widget-code bg-dark p-3" rows="12" readonly 
                                data-bs-toggle="tooltip" data-bs-placement="top" 
                                title="Click me to copy the code." style="overflow-y: scroll">{{ $template }}</textarea>
                        </div>
                        <div id="unavailable-clipboard" class="row mb-2 d-none">
                            <div class="alert alert-danger" role="alert">
                                We are unable to copy the code to your clipboard. Instead, please copy with <strong>CTRL + C</strong> or <strong>CMD + C</strong>. We have selected the widget code for you.
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    
                    <div class="col-lg-5">
                        <h4 class="chat-subheader">Scheduler</h4>
                    </div>                    
                    <div class="col-lg-6">
                        <h4 class="chat-subheader-3">Availability Restrictions</h4> 
                    </div>
                </div> 
                
                <div class="row">
                        
                    <div class="col-lg-5 scheduler-scroll" style="overflow-y: scroll; overflow-x: hidden;">
                        
                        {{-- Custom scheduler component  --}}
                        <widget-scheduler-picker
                            editable="{{ Auth::user()->role === 'admin' }}"

                            initial-enabled="{{ old('sched_enabled', $currentWidget->sched_enabled) }}"

                            :timezones="{{json_encode($timezones)}}"

                            initial-selected-timezone="{{old('availability_timezone', $currentWidget->availability_timezone)}}"

                            monday-enabled="{{ old('sched_monday_enabled', $currentWidget->sched_monday_enabled) }}"

                            monday-avail-start="{{ old('sched_monday_avail_start', $currentWidget->sched_monday_avail_start ? $currentWidget->sched_monday_avail_start->setTimezone('UTC')->format('H:i') : "") }}"

                            monday-avail-end="{{ 
                            old('sched_monday_avail_end', $currentWidget->sched_monday_avail_end ? $currentWidget->sched_monday_avail_end->setTimezone('UTC')->format('H:i') : "") }}"

                            tuesday-enabled="{{ old('sched_tuesday_enabled', $currentWidget->sched_tuesday_enabled) }}"

                            tuesday-avail-start="{{ old('sched_tuesday_avail_start', $currentWidget->sched_tuesday_avail_start ? $currentWidget->sched_tuesday_avail_start->setTimezone('UTC')->format('H:i') : "") }}"

                            tuesday-avail-end="{{ old('sched_tuesday_avail_end', $currentWidget->sched_tuesday_avail_end ? $currentWidget->sched_tuesday_avail_end->setTimezone('UTC')->format('H:i') : "" )}}"

                            wednesday-enabled="{{ old('sched_wednesday_enabled', $currentWidget->sched_wednesday_enabled) }}"

                            wednesday-avail-start="{{ old('sched_wednesday_avail_start', $currentWidget->sched_wednesday_avail_start ? $currentWidget->sched_wednesday_avail_start->setTimezone('UTC')->format('H:i') : "" ) }}"

                            wednesday-avail-end="{{ old('sched_wednesday_avail_end', $currentWidget->sched_wednesday_avail_end ? $currentWidget->sched_wednesday_avail_end->setTimezone('UTC')->format('H:i') : "" ) }}"

                            thursday-enabled="{{ old('sched_thursday_enabled', $currentWidget->sched_thursday_enabled) }}"

                            thursday-avail-start="{{ old('sched_thursday_avail_start', $currentWidget->sched_thursday_avail_start ? $currentWidget->sched_thursday_avail_start->setTimezone('UTC')->format('H:i') : "") }}"

                            thursday-avail-end="{{ old('sched_thursday_avail_end', $currentWidget->sched_thursday_avail_end ? $currentWidget->sched_thursday_avail_end->setTimezone('UTC')->format('H:i') : "" ) }}"

                            friday-enabled="{{ old('sched_friday_enabled', $currentWidget->sched_friday_enabled) }}"

                            friday-avail-start="{{ old('sched_friday_avail_start', $currentWidget->sched_friday_avail_start ? $currentWidget->sched_friday_avail_start->setTimezone('UTC')->format('H:i') : "" ) }}"

                            friday-avail-end="{{ old('sched_friday_avail_end', $currentWidget->sched_friday_avail_end ? $currentWidget->sched_friday_avail_end->setTimezone('UTC')->format('H:i') : "" ) }}"

                            saturday-enabled="{{ old('sched_saturday_enabled', $currentWidget->sched_saturday_enabled) }}"

                            saturday-avail-start="{{ old('sched_saturday_avail_start', $currentWidget->sched_saturday_avail_start ? $currentWidget->sched_saturday_avail_start->setTimezone('UTC')->format('H:i') : "" ) }}"

                            saturday-avail-end="{{ old('sched_saturday_avail_end', $currentWidget->sched_saturday_avail_end ? $currentWidget->sched_saturday_avail_end->setTimezone('UTC')->format('H:i') : "" ) }}"

                            sunday-enabled="{{ old('sched_sunday_enabled', $currentWidget->sched_sunday_enabled) }}"

                            sunday-avail-start="{{ old('sched_sunday_avail_start', $currentWidget->sched_sunday_avail_start ? $currentWidget->sched_sunday_avail_start->setTimezone('UTC')->format('H:i') : "" ) }}"

                            sunday-avail-end="{{ old('sched_sunday_avail_end', $currentWidget->sched_sunday_avail_end ? $currentWidget->sched_sunday_avail_end->setTimezone('UTC')->format('H:i') : "" ) }}"
                        ></widget-scheduler-picker>
                    </div>


                    
                    <div class="col-lg-6 scheduler-scroll" style="overflow-y: scroll; overflow-x: hidden;">
                        
                        <widget-domains-picker
                            editable="{{ Auth::user()->role === 'admin' }}"

                            initial-domains-list-json="{{old('allowed_domains', json_encode($currentWidget->allowed_domains ?? array("")))}}"
                        ></widget-domains-picker>
                    </div>

                @if (Auth::user()->role === "admin")
                <div class="row my-4 widget-save">
                    <div class="col-4">
                        <button type="submit" class="btn btn-light w-100 widget-save-button">Save Changes</button>
                    </div>
                </div>
                @endif
                
            </form>
        </div>
            @endif
        </section>
    </div>
</div>
@endsection