
@extends('layouts.app')

@section('content')
<div class="container-fluid widget-details-container">
    <div class="row  widget-details-row">
        <!--MAIN SIDE BAR-->
        <div class="col-lg-2 mainsidebar  widget-details-sidebar">
            <a href="/home"><ion-icon name="mail-outline"></ion-icon><span class="menutitle">Messaging</span></a>
            <a href="/reports"><ion-icon name="bar-chart-outline"></ion-icon><span class="menutitle">Reporting</span></a>
            <a class="activemenu" href="/widgets"><ion-icon name="copy-outline"></ion-icon><span class="menutitle">Widget</span></a>
        </div>
        <!--END MAIN SIDE BAR-->

        
        <div class="col-lg-10 sidebar widget-details-form">
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
                <div class="row">
                    <div class="col-lg-5">
                        <h2 class="chat-header">Chat Widgets</h2>
                        @if (count($widgets) === 0)
                        <p class="no-widget-prompt">You have no widgets yet!</p>
                        @else
                        {{-- TODO: Add widget selection component --}}
                        {{-- TODO: Add link to create a widget --}}
    
    
                        <div class="widget-block">
                            <label class="chat-label" for="widget-name">Widget Name</label>
                            <input class="form-control form-control-widget" name="widget-name" type="text" readonly value="{{ $currentWidget->name }}">
                            <button class="save-button">Save</button>
                        </div>
    
                        <div class="widget-block">
                            <label class="chat-label" for="widget-status">Widget Status</label>
                            <input class="form-control form-control-widget-full" type="text" name="widget-status" id="widget-status" value="Enabled">
                        </div>
                        <div class="widget-block">
                            <label class="chat-label" for="widget-id">Widget ID</label>
                            <input class="form-control form-control-widget-full" type="text" name="widget-id" id="widget-id" readonly value="{{ $currentWidget->_id }}">
                        </div>
                        {{-- <div class="widget-block">
                            <label class="chat-label" for="chat-link">Direct Chat Link</label>
                            <input class="form-control form-control-widget-full" type="text" name="chat-link" id="chat-link" readonly value="{{ $currentWidget->_id }}">
                        </div> --}}
                    </div>
    
                    <div class="col-lg-6">
                        <!--CODE WIDGET LINK-->
                        {{-- This is the part that generates the HTML code of the widget --}}
                        <div class="row marginallowance">
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
                    {{-- <div class="col-lg-5">
                    <h4 class="chat-subheader">Widget Appearance</h4> 
                        <div class="widget-block">
                            <label class="chat-label">Widget Color</label>  
                            <input class="form-control form-control-color form-control-widget-full" type="color" name="widget-color" id="widget-color" readonly>
    
                        </div>
    
                        <div class="widget-block">
                            <label class="chat-label">Language</label>  
                            <input class="form-control form-control-widget-full" type="text" name="widget-color" id="widget-color" readonly value="{{ $currentWidget->_id }}">
                        </div>
                    </div> --}}
    
                    <div class="col-lg-5">
                        <h4 class="chat-subheader">Scheduler</h4> 
                        {{-- Custom scheduler component  --}}
                        <widget-scheduler-picker
                            :timezones="{{json_encode($timezones)}}"

                            initial-selected-timezone="{{old('availability_timezone', $currentWidget->availability_timezone)}}"
                            
                            initial-start-time="{{ $currentWidget->availability_start_time ? $currentWidget->availability_start_time->setTimezone('UTC')->format('H:i') : "" }}"

                            initial-end-time="{{ $currentWidget->availability_end_time ? $currentWidget->availability_end_time->setTimezone('UTC')->format('H:i') : "" }}"
                        ></widget-scheduler-picker>
                    </div>


                    
                    <div class="col-lg-6">
                        <h4 class="chat-subheader-3">Availability Restrictions</h4> 
                        <widget-domains-picker
                            initial-domains-list-json="{{old('allowed_domains', json_encode($currentWidget->allowed_domains ?? array("")))}}"
                        ></widget-domains-picker>
                    </div>

                    {{-- Widget Behavior Settings --}}
                    {{-- <div class="col-lg-6">
                        <h4 class="chat-subheader">Widget Behavior</h4> 
                        <div class="widget-block">
                            <label class="chat-label" for="visiibility-settings">Visibility Settings</label>
                        </div>
    
                        <!-- not sure how to visualize this? can you give a sample? did this based on what i saw sa tawk.to-->
    
                        <div class="widget-block">
                            <label class="switch">
                                <input disabled type="checkbox" value="hide-widget-offline"
                                    @if ($currentWidget->hide_when_offline)
                                        checked
                                    @endif
                                >
                                <span class="slider round"></span>
                            </label> 
                            <span class="chat-label">Hide widget when offline</span>
    
                            <label class="switch">
                                <input disabled type="checkbox"
                                    @if ($currentWidget->hide_when_on_desktop)
                                        checked
                                    @endif
                                >
                                <span class="slider round"></span>
                            </label> 
                            <span class="chat-label">Hide widget on load in desktop</span>
    
                            <label class="switch">
                                <input disabled type="checkbox"
                                    @if ($currentWidget->hide_when_on_mobile)
                                        checked
                                    @endif 
                                >
                                <span class="slider round"></span>
                            </label> 
                            <span class="chat-label">Hide widget on load in mobile</span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="widget-block">
                            <label class="chat-label" for="feature-settings">Feature Settings</label>
                        </div>
    
                        <!-- not sure how to visualize this? can you give a sample? did this based on what i saw sa tawk.to-->
    
                        <div class="widget-block">
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider round"></span>
                            </label> 
                            <span class="chat-label">Option</span>
    
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider round"></span>
                            </label> 
                            <span class="chat-label">Option</span>
    
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider round"></span>
                            </label> 
                            <span class="chat-label">Option</span>
                        </div>
                    </div>
                </div> --}}
    
                <div class="row">
                    {{--<h4 class="chat-subheader-3">Availability Restrictions</h4> 
                    
                    <div class="col-lg-4">
                        <widget-domains-picker
                            initial-domains-list-json="{{old('allowed_domains', json_encode($currentWidget->allowed_domains ?? array("")))}}"
                        ></widget-domains-picker>
                    </div>--}}

                    {{-- Platform and Country Restrictions --}}
                    {{-- <div class="col-lg-4">
                        <div class="widget-block">
                            <label class="chat-label" for="platform-restriction">Platform Restriction</label>
                        </div>
                        <div class="widget-block">
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider round"></span>
                            </label> 
                            <span class="chat-label">Disabled</span>
                            
                        </div>
                        <div class="widget-block">
                            
                            <p class="chat-label-2 mb-0">By default, the widget will be shown to all visitors. To show or hide the widget for visitors from specific platforms, enable this functionality and specify the platforms.</p>
                        </div>
                    </div>
    
                    <div class="col-lg-4">
                        <div class="widget-block">
                            <label class="chat-label" for="country-restriction">Country Restriction</label>
                        </div>
                        <div class="widget-block">
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider round"></span>
                            </label> 
                            <span class="chat-label">Disabled</span>
                            
                        </div>
                        <div class="widget-block">
                            
                            <p class="chat-label-2 mb-0">By default, the widget will be shown to all visitors. To show or hide the widget for visitors from specific countris, enable this functionality and specify the countries.</p>
                        </div>
                    </div> --}}
                </div>

                @if (Auth::user()->role === "admin")
                <div class="row my-4 mx-auto">
                    <div class="col-4">
                        <button type="submit" class="btn btn-light w-100">Save Changes</button>
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