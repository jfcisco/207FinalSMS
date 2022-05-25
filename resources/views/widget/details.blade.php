
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!--MAIN SIDE BAR-->
        <div class="col-lg-1 mainsidebar">
            <a href="/home"><ion-icon name="mail-outline"></ion-icon><span class="menutitle">Messaging</span></a>
            <a href="/reports"><ion-icon name="bar-chart-outline"></ion-icon><span class="menutitle">Reporting</span></a>
            <a class="activemenu" href="/widgets"><ion-icon name="copy-outline"></ion-icon><span class="menutitle">Widget</span></a>
        </div>
        <!--END MAIN SIDE BAR-->

        
        <div class="col-lg-11 sidebar">
            <div class="row">
                <div class="col-lg-6">
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
                        <input class="form-control form-control-widget-full" type="text" name="widget-status" id="widget-status"  checked="{{ $currentWidget->is_active }}">
                    </div>
                    <div class="widget-block">
                        <label class="chat-label" for="widget-id">Widget ID</label>
                        <input class="form-control form-control-widget-full" type="text" name="widget-id" id="widget-id" readonly value="{{ $currentWidget->_id }}">
                    </div>
                    <div class="widget-block">
                        <label class="chat-label" for="chat-link">Direct Chat Link</label>
                        <input class="form-control form-control-widget-full" type="text" name="chat-link" id="chat-link" readonly value="{{ $currentWidget->_id }}">
                    </div>
                </div>

                <div class="col-lg-5">
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
                
                <div class="col-lg-5">
                <h4 class="chat-subheader">Widget Appearance</h4> 
                    <div class="widget-block">
                        <label class="chat-label">Widget Color</label>  
                        <input class="form-control form-control-widget-full" type="text" name="widget-color" id="widget-color" readonly value="{{ $currentWidget->_id }}">

                    </div>

                    <div class="widget-block">
                        <label class="chat-label">Language</label>  
                        <input class="form-control form-control-widget-full" type="text" name="widget-color" id="widget-color" readonly value="{{ $currentWidget->_id }}">
                    </div>
                </div>

                <div class="col-lg-6">
                    <h4 class="chat-subheader">Scheduler</h4> 
                    <div class="widget-block">
                        <label class="chat-label">TimeZone</label>  
                        <select name="time-zone" id="time-zone" class="form-control form-control-widget-full">
                                    <option value="-12">(UTC-12:00) International Date Line West</option>
                                    <option value="-11">(UTC-11:00) Coordinated Universal Time-11</option>
                                    <option value="-10">(UTC-10:00) Hawaii</option>
                                    <option value="-9">(UTC-09:00) Alaska</option>
                                    <option value="-7">(UTC-08:00) Baja California</option>
                                    <option value="-7">(UTC-07:00) Pacific Time (US &amp; Canada)</option>
                                    <option value="-8">(UTC-08:00) Pacific Time (US &amp; Canada)</option>
                                    <option value="-7">(UTC-07:00) Arizona</option>
                                    <option value="-6">(UTC-07:00) Chihuahua, La Paz, Mazatlan</option>
                                    <option value="-6">(UTC-07:00) Mountain Time (US &amp; Canada)</option>
                                    <option value="-6">(UTC-06:00) Central America</option>
                                    <option value="-5">(UTC-06:00) Central Time (US &amp; Canada)</option>
                                    <option value="-5">(UTC-06:00) Guadalajara, Mexico City, Monterrey</option>
                                    <option value="-6">(UTC-06:00) Saskatchewan</option>
                                    <option value="-5">(UTC-05:00) Bogota, Lima, Quito</option>
                                    <option value="-4">(UTC-05:00) Eastern Time (US &amp; Canada)</option>
                                    <option value="-4">(UTC-05:00) Indiana (East)</option>
                                    <option value="-4.5">(UTC-04:30) Caracas</option>
                                    <option value="-4">(UTC-04:00) Asuncion</option>
                                    <option value="-3">(UTC-04:00) Atlantic Time (Canada)</option>
                                    <option value="-4">(UTC-04:00) Cuiaba</option>
                                    <option value="-4">(UTC-04:00) Georgetown, La Paz, Manaus, San Juan</option>
                                    <option value="-4">(UTC-04:00) Santiago</option>
                                    <option value="-2.5">(UTC-03:30) Newfoundland</option>
                                    <option value="-3">(UTC-03:00) Brasilia</option>
                                    <option value="-3">(UTC-03:00) Buenos Aires</option>
                                    <option value="-3">(UTC-03:00) Cayenne, Fortaleza</option>
                                    <option value="-3">(UTC-03:00) Greenland</option>
                                    <option value="-3">(UTC-03:00) Montevideo</option>
                                    <option value="-3">(UTC-03:00) Salvador</option>
                                    <option value="-2">(UTC-02:00) Coordinated Universal Time-02</option>
                                    <option value="-1">(UTC-02:00) Mid-Atlantic - Old</option>
                                    <option value="0">(UTC-01:00) Azores</option>
                                    <option value="-1">(UTC-01:00) Cape Verde Is.</option>
                                    <option value="1">(UTC) Casablanca</option>
                                    <option value="0">(UTC) Coordinated Universal Time</option>
                                    <option value="0">(UTC) Edinburgh, London</option>
                                    <option value="1">(UTC+01:00) Edinburgh, London</option>
                                    <option value="1">(UTC) Dublin, Lisbon</option>
                                    <option value="0">(UTC) Monrovia, Reykjavik</option>
                                    <option value="2">(UTC+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                    <option value="2">(UTC+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                    <option value="2">(UTC+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                    <option value="2">(UTC+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                    <option value="1">(UTC+01:00) West Central Africa</option>
                                    <option value="1">(UTC+01:00) Windhoek</option>
                                    <option value="3">(UTC+02:00) Athens, Bucharest</option>
                                    <option value="3">(UTC+02:00) Beirut</option>
                                    <option value="2">(UTC+02:00) Cairo</option>
                                    <option value="3">(UTC+02:00) Damascus</option>
                                    <option value="3">(UTC+02:00) E. Europe</option>
                                    <option value="2">(UTC+02:00) Harare, Pretoria</option>
                                    <option value="3">(UTC+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                    <option value="3">(UTC+03:00) Istanbul</option>
                                    <option value="3">(UTC+02:00) Jerusalem</option>
                                    <option value="2">(UTC+02:00) Tripoli</option>
                                    <option value="3">(UTC+03:00) Amman</option>
                                    <option value="3">(UTC+03:00) Baghdad</option>
                                    <option value="3">(UTC+02:00) Kaliningrad</option>
                                    <option value="3">(UTC+03:00) Kuwait, Riyadh</option>
                                    <option value="3">(UTC+03:00) Nairobi</option>
                                    <option value="3">(UTC+03:00) Moscow, St. Petersburg, Volgograd, Minsk</option>
                                    <option value="4">(UTC+04:00) Samara, Ulyanovsk, Saratov</option>
                                    <option value="4.5">(UTC+03:30) Tehran</option>
                                    <option value="4">(UTC+04:00) Abu Dhabi, Muscat</option>
                                    <option value="5">(UTC+04:00) Baku</option>
                                    <option value="4">(UTC+04:00) Port Louis</option>
                                    <option value="4">(UTC+04:00) Tbilisi</option>
                                    <option value="4">(UTC+04:00) Yerevan</option>
                                    <option value="4.5">(UTC+04:30) Kabul</option>
                                    <option value="5">(UTC+05:00) Ashgabat, Tashkent</option>
                                    <option value="5">(UTC+05:00) Yekaterinburg</option>
                                    <option value="5">(UTC+05:00) Islamabad, Karachi</option>
                                    <option value="5.5">(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                    <option value="5.5">(UTC+05:30) Sri Jayawardenepura</option>
                                    <option value="5.75">(UTC+05:45) Kathmandu</option>
                                    <option value="6">(UTC+06:00) Nur-Sultan (Astana)</option>
                                    <option value="6">(UTC+06:00) Dhaka</option>
                                    <option value="6.5">(UTC+06:30) Yangon (Rangoon)</option>
                                    <option value="7">(UTC+07:00) Bangkok, Hanoi, Jakarta</option>
                                    <option value="7">(UTC+07:00) Novosibirsk</option>
                                    <option value="8">(UTC+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                    <option value="8">(UTC+08:00) Krasnoyarsk</option>
                                    <option value="8">(UTC+08:00) Kuala Lumpur, Singapore</option>
                                    <option value="8">(UTC+08:00) Perth</option>
                                    <option value="8">(UTC+08:00) Taipei</option>
                                    <option value="8">(UTC+08:00) Ulaanbaatar</option>
                                    <option value="8">(UTC+08:00) Irkutsk</option>
                                    <option value="9">(UTC+09:00) Osaka, Sapporo, Tokyo</option>
                                    <option value="9">(UTC+09:00) Seoul</option>
                                    <option value="9.5">(UTC+09:30) Adelaide</option>
                                    <option value="9.5">(UTC+09:30) Darwin</option>
                                    <option value="10">(UTC+10:00) Brisbane</option>
                                    <option value="10">(UTC+10:00) Canberra, Melbourne, Sydney</option>
                                    <option value="10">(UTC+10:00) Guam, Port Moresby</option>
                                    <option value="10">(UTC+10:00) Hobart</option>
                                    <option value="9">(UTC+09:00) Yakutsk</option>
                                    <option value="11">(UTC+11:00) Solomon Is., New Caledonia</option>
                                    <option value="11">(UTC+11:00) Vladivostok</option>
                                    <option value="12">(UTC+12:00) Auckland, Wellington</option>
                                    <option value="12">(UTC+12:00) Coordinated Universal Time+12</option>
                                    <option value="12">(UTC+12:00) Fiji</option>
                                    <option value="12">(UTC+12:00) Magadan</option>
                                    <option value="13">(UTC+12:00) Petropavlovsk-Kamchatsky - Old</option>
                                    <option value="13">(UTC+13:00) Nuku'alofa</option>
                                    <option value="13">(UTC+13:00) Samoa</option>
                        </select>
                    </div>

                    <div class="widget-block">
                        <label class="chat-label" for="schedule">Schedule</label>
                        <input class="form-control form-control-widget-full" type="text" name="schedule" id="schedule" readonly value="{{ $currentWidget->_id }}">
                    </div>
                </div>
            </div>

            
            <div class="row">
                <h4 class="chat-subheader">Widget Behavior</h4> 

                <div class="col-lg-6">
                    <div class="widget-block">
                        <label class="chat-label" for="visiibility-settings">Visibility Settings</label>
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
            </div>

            <div class="row">
                <h4 class="chat-subheader-3">Availability Restrictions</h4> 
                <div class="col-lg-4">
                    <div class="widget-block">
                        <label class="chat-label" for="domain-restriction">Domain Restriction</label>
                    </div>
                    <div class="widget-block">
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label> 
                        <span class="chat-label">Disabled</span>
                        
                    </div>
                    <div class="widget-block">
                        
                        <p class="chat-label-2 mb-0">By default, the code will work on all the domains and URLs where it has been inserted. To show or hide the widget on one or morer specific domain or URLs, enable this functionality and specify the rule.</p>
                    </div>
                </div>

                <div class="col-lg-4">
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
                </div>
            </div>

        </div>
        

        
                

                    
                    
                    

                  
            @endif
        </section>
    </div>
</div>
@endsection