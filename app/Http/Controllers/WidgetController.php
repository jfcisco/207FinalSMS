<?php

namespace App\Http\Controllers;

use App\Models\ChatWidget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use \DateTimeZone;
use \DateTime;

/**
 * User-facing Functionalities for Widget Management
 */
class WidgetController extends Controller
{
    /**
     * Shows the widget details page and the generated HTML for it
     */
    public function index($widgetId = null)
    {
        // Get APP_URL from the environment configuration
        $appUrl = env('APP_URL');

        $userId = Auth::user()->_id;

        $widgets = ChatWidget::all();

        // Handle situation where no widgets have been created yet
        if ($widgets->count() === 0) {
            return view('widget.details', [
                'widgets' => [],
            ]);
        }

        // Get the widget to be shown
        if (is_null($widgetId)) {
            // Show the first available widget
            $widget = $widgets->first();
        } else {
            $widget = $widgets->find($widgetId);
        }

        // If no widget is found, respond with a 404 Not Found error
        if (is_null($widget)) {
            abort(404);
        }

        return view('widget.details', [
            'baseUrl' => $appUrl,
            'userId' => $userId,
            'template' => $this->generateTemplate($appUrl, $userId, $widget->_id),
            'currentWidget' => $widget,
            'widgets' => $widgets,
            'timezones' => $this->generateTimezoneDropdownList()
        ]);
    }

    private function generateTimezoneDropdownList() {
        // Source: https://gist.github.com/Xeoncross/1204255
        $regions = array(
            'Africa' => DateTimeZone::AFRICA,
            'America' => DateTimeZone::AMERICA,
            'Antarctica' => DateTimeZone::ANTARCTICA,
            'Asia' => DateTimeZone::ASIA,
            'Atlantic' => DateTimeZone::ATLANTIC,
            'Europe' => DateTimeZone::EUROPE,
            'Indian' => DateTimeZone::INDIAN,
            'Pacific' => DateTimeZone::PACIFIC
        );
        
        $timezones = array();
        foreach ($regions as $name => $mask)
        {
            $zones = DateTimeZone::listIdentifiers($mask);
            foreach($zones as $timezone)
            {
                // Get current time for this timezone
                $time = new DateTime(NULL, new DateTimeZone($timezone));

                // Generate AM/PM format for current time
                $currentTime = $time->format('g:i a');

                // Remove region name
                $timezones[$name][$timezone] = substr($timezone, strlen($name) + 1) . ' [Time: ' . $currentTime . ']';
            }
        }
        
        return $timezones;
    }

    private function generateTemplate($baseUrl, $userId, $widgetId)
    {
        return <<<TEMPLATE
        <!--Start of Widget Script-->
        <script type="text/javascript">
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.charset='UTF-8';
        s1.src='$baseUrl/embed/$userId/$widgetId';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        </script>
        <!--End of Widget Script-->
        TEMPLATE;
    }

    /**
     * Generates the script file that loads and displays a widget
     */
    public function generateScript(Request $request, $userId, $widgetId)
    {
        // Note: For this to work, laravel-mix (through npm) should have generated a widget-script.php file in the resources/views/widget folder

        $widget = ChatWidget::find($widgetId);

        // Widget cannot be found
        if (!$widget) {
            abort(404);
        }

        // Check request origin with widget's allowed domains
        $origin = $request->header('origin');
        $allowedDomains = collect($widget->allowed_domains);

        if ($allowedDomains->count() > 0) {
            // Reject any requests from unauthorized domains with 403 Forbidden
            if (!$origin || $allowedDomains->doesntContain($origin)) {
                Log::warning("Blocked request from $origin for widget ID $widgetId");
                abort(403);
            }
        }

        $widgetAvailabilitySchedule = $this->getAvailabilitySchedule($widget);

        // Generate a view for the script
        $view = view()->make('widget.widget-script', [
            // App Details
            'baseUrl' => env('APP_URL'),

            // Widget and Creator User IDs
            'widgetId' => $widgetId,
            'userId' => $userId,

            // Availability Schedule Information
            // Note: Times are converted to the ISO 8601 format because this is compatible with JavaScript
            'hasScheduledAvailability' => json_encode($widgetAvailabilitySchedule['isActive']),
            'availabilityStartTime' => ($widgetAvailabilitySchedule['availStart'] 
                ? $widgetAvailabilitySchedule['availStart']->toIso8601String()
                : ""),
            'availabilityEndTime' => ($widgetAvailabilitySchedule['availEnd']
                ? $widgetAvailabilitySchedule['availEnd']->toIso8601String()
                : ""),
        ])->withHeaders([
            // Make browsers interpret this as JavaScript
            'Content-Type' => 'application/javascript',

            // Enable CORS for this resource
            'Access-Control-Allow-Origin' => '*'
        ]);

        return $view->render();

        // Reference: https://laracasts.com/discuss/channels/laravel/returning-a-dynamic-compiled-javascript-file-from-a-laravel-route
    }

    /**
     * Checks if the present time falls within the widget's availability start and end time.
     */
    private function getAvailabilitySchedule($widget) 
    {    
        // Notes: Start time and End time are assumed to be stored in UTC. Their date parts must be the same, suggestion: 1/1/1970

        // Widget doesn't have scheduler enabled
        if (!$widget->sched_enabled) {
            return [
                'isActive' => false,
                'availStart' => null,
                'availEnd' => null
            ];
        }

        // Create carbon instances of widget availability schedules
        $timeZone = $widget->availability_timezone ?? "Asia/Hong_Kong";
        
        // Get the available start time and available end time for the day of the week
        $weekday = Carbon::now($timeZone)->dayOfWeek;

        // Retrieve avail start and end times, converting them to UTC for uniformity
        if ($weekday === Carbon::SUNDAY) {
            $schedEnabled = $widget->sched_sunday_enabled;

            if ($schedEnabled) {
                $availStart = $widget->sched_sunday_avail_start->setTimezone('UTC');
                $availEnd = $widget->sched_sunday_avail_end->setTimezone('UTC');
            }
            else {
                $availStart = null;
                $availEnd = null;
            }
        }
        else if ($weekday === Carbon::MONDAY) {
            $schedEnabled = $widget->sched_monday_enabled;

            if ($schedEnabled) {
                $availStart = $widget->sched_monday_avail_start->setTimezone('UTC');
                $availEnd = $widget->sched_monday_avail_end->setTimezone('UTC'); 
            }
            else {
                $availStart = null;
                $availEnd = null;
            }
        }
        else if ($weekday === Carbon::TUESDAY) {
            $schedEnabled = $widget->sched_tuesday_enabled;

            if ($schedEnabled) {
                $availStart = $widget->sched_tuesday_avail_start->setTimezone('UTC');
                $availEnd = $widget->sched_tuesday_avail_end->setTimezone('UTC'); 
            }
            else {
                $availStart = null;
                $availEnd = null;
            }
        }
        else if ($weekday === Carbon::WEDNESDAY) {
            $schedEnabled = $widget->sched_wednesday_enabled;

            if ($schedEnabled) {
                $availStart = $widget->sched_wednesday_avail_start->setTimezone('UTC');
                $availEnd = $widget->sched_wednesday_avail_end->setTimezone('UTC'); 
            }
            else {
                $availStart = null;
                $availEnd = null;
            }
        }
        else if ($weekday === Carbon::THURSDAY) {
            $schedEnabled = $widget->sched_thursday_enabled;

            if ($schedEnabled) {
                $availStart = $widget->sched_thursday_avail_start->setTimezone('UTC');
                $availEnd = $widget->sched_thursday_avail_end->setTimezone('UTC'); 
            }
            else {
                $availStart = null;
                $availEnd = null;
            }
        }
        else if ($weekday === Carbon::FRIDAY) {
            $schedEnabled = $widget->sched_friday_enabled;
            
            if ($schedEnabled) {
                $availStart = $widget->sched_friday_avail_start->setTimezone('UTC');
                $availEnd = $widget->sched_friday_avail_end->setTimezone('UTC'); 
            }
            else {
                $availStart = null;
                $availEnd = null;
            }
        }
        else if ($weekday === Carbon::SATURDAY) {
            $schedEnabled = $widget->sched_saturday_enabled;

            if ($schedEnabled) {
                $availStart = $widget->sched_saturday_avail_start->setTimezone('UTC');
                $availEnd = $widget->sched_saturday_avail_end->setTimezone('UTC'); 
            }
            else {
                $availStart = null;
                $availEnd = null;
            }
        }

        // Lastly, convert them to date-time before handing them over to the view
        $startTime = Carbon::createFromTime($availStart->hour, $availStart->minute, 0, $timeZone);
        $endTime = Carbon::createFromTime($availEnd->hour, $availEnd->minute, 0, $timeZone);

        return [
            'isActive' => true,
            // TODO: Only check time if schedEnabled is true, otherwise hidden the entire day
            'schedEnabled' => $schedEnabled,
            'availStart' => $startTime,
            'availEnd' => $endTime,
        ];
    }

    public function create()
    {
        return view('widget.create');
    }

    public function store()
    {
        // TODO: Implement Create Widget form
        $widget = ChatWidget::create([
            'name' => 'Sample Widget'
        ]);
    }

    public function update(Request $request, $widgetId)
    {
        $widgetToUpdate = ChatWidget::find($widgetId);

        if (is_null($widgetToUpdate)) {
            abort(404);
        }

        // Validate incoming form data
        $request->validate([
            'name' => 'string|required',
            'is_active' => 'boolean|required',
            
            // Availability data
            'availability_timezone' => 'timezone',
            // 'availability_start_time' => 'nullable|regex:/^\d\d:\d\d$/|required_with:availability_end_time',
            // 'availability_end_time' => 'nullable|regex:/^\d\d:\d\d$/|required_with:availability_start_time|after:availability_start_time',
            
            // Allowed Domains data
            'allowed_domains' => [
                'nullable',
                'json',
                function ($attribute, $value, $fail) {
                    $domainArray = json_decode($value);
                    $allAreValidDomainValues = collect($domainArray)
                        ->every(function($value, $key) {
                            return preg_match("/https?\:\/\/.+(\:\d+)?/i", $value);
                        });

                    if (!$allAreValidDomainValues) {
                        $fail("An invalid domain has been provided. Please double check your domain restrictions.");
                    }
                }
            ]
        ]);

        // Update widget name and status
        $widgetToUpdate->name = $request->input('name');
        $widgetToUpdate->is_active = $request->boolean('is_active');
        
        // Update Allowed Domains
        if ($request->filled('allowed_domains')) {
            $allowedDomains = json_decode($request->input('allowed_domains'));
            $widgetToUpdate->allowed_domains = $allowedDomains;
        }
        else {
            $widgetToUpdate->unset('allowed_domains');
        }

        // Update Availability Schedule
        if ($request->filled('sched_enabled') and $request->boolean('sched_enabled')) {
            $widgetToUpdate->sched_enabled = true;

            $widgetToUpdate->availability_timezone = $request->input('availability_timezone');

            $widgetToUpdate->sched_monday_enabled = $request->boolean('sched_monday_enabled');

            if ($widgetToUpdate->sched_monday_enabled) {
                $widgetToUpdate->sched_monday_avail_start = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' . $request->input('sched_monday_avail_start'), 'UTC');
                $widgetToUpdate->sched_monday_avail_end = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' . $request->input('sched_monday_avail_end'), 'UTC');
            }
            else {
                $widgetToUpdate->unset('sched_monday_avail_start');
                $widgetToUpdate->unset('sched_monday_avail_end');
            }
            
            $widgetToUpdate->sched_tuesday_enabled = $request->boolean('sched_tuesday_enabled');

            if ($widgetToUpdate->sched_tuesday_enabled) {
                $widgetToUpdate->sched_tuesday_avail_start = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' . $request->input('sched_tuesday_avail_start'), 'UTC');
                $widgetToUpdate->sched_tuesday_avail_end = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' . $request->input('sched_tuesday_avail_end'), 'UTC');
            }
            else {
                $widgetToUpdate->unset('sched_tuesday_avail_start');
                $widgetToUpdate->unset('sched_tuesday_avail_end');
            }
            
            $widgetToUpdate->sched_wednesday_enabled = $request->boolean('sched_wednesday_enabled');

            if ($widgetToUpdate->sched_wednesday_enabled) {
                $widgetToUpdate->sched_wednesday_avail_start = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' . $request->input('sched_wednesday_avail_start'), 'UTC');
                $widgetToUpdate->sched_wednesday_avail_end = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' . $request->input('sched_wednesday_avail_end'), 'UTC');
            }
            else {
                $widgetToUpdate->unset('sched_wednesday_avail_start');
                $widgetToUpdate->unset('sched_wednesday_avail_end');
            }
            
            $widgetToUpdate->sched_thursday_enabled = $request->boolean('sched_thursday_enabled');

            if ($widgetToUpdate->sched_thursday_enabled) {
                $widgetToUpdate->sched_thursday_avail_start = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' . $request->input('sched_thursday_avail_start'), 'UTC');
                $widgetToUpdate->sched_thursday_avail_end = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' . $request->input('sched_thursday_avail_end'), 'UTC');
            }
            else {        
                $widgetToUpdate->unset('sched_thursday_avail_start');
                $widgetToUpdate->unset('sched_thursday_avail_end');
            }
            
            $widgetToUpdate->sched_friday_enabled = $request->boolean('sched_friday_enabled');

            if ($widgetToUpdate->sched_friday_enabled) {
                $widgetToUpdate->sched_friday_avail_start = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' . $request->input('sched_friday_avail_start'), 'UTC');
                $widgetToUpdate->sched_friday_avail_end = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' . $request->input('sched_friday_avail_end'), 'UTC');
            }
            else {        
                $widgetToUpdate->unset('sched_friday_avail_start');
                $widgetToUpdate->unset('sched_friday_avail_end');
            }
            
            $widgetToUpdate->sched_saturday_enabled = $request->boolean('sched_saturday_enabled');

            if ($widgetToUpdate->sched_saturday_enabled) {
                $widgetToUpdate->sched_saturday_avail_start = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' .  $request->input('sched_saturday_avail_start'), 'UTC');
                $widgetToUpdate->sched_saturday_avail_end = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' .  $request->input('sched_saturday_avail_end'), 'UTC');
            }
            else {    
                $widgetToUpdate->unset('sched_saturday_avail_start');
                $widgetToUpdate->unset('sched_saturday_avail_end');
            }
            
            $widgetToUpdate->sched_sunday_enabled = $request->boolean('sched_sunday_enabled');

            if ($widgetToUpdate->sched_sunday_enabled) {
                $widgetToUpdate->sched_sunday_avail_start = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' .   $request->input('sched_sunday_avail_start'), 'UTC');
                $widgetToUpdate->sched_sunday_avail_end = Carbon::createFromFormat('Y-m-d H:i', '1970-01-01 ' .   $request->input('sched_sunday_avail_end'), 'UTC');
            }
            else {
                $widgetToUpdate->unset('sched_sunday_avail_start');
                $widgetToUpdate->unset('sched_sunday_avail_end');
            }
        }
        else {
            $widgetToUpdate->sched_enabled = false;

            $widgetToUpdate->unset('availability_timezone');

            $widgetToUpdate->unset('sched_monday_enabled');
            $widgetToUpdate->unset('sched_monday_avail_start');
            $widgetToUpdate->unset('sched_monday_avail_end');
            
            $widgetToUpdate->unset('sched_tuesday_enabled');
            $widgetToUpdate->unset('sched_tuesday_avail_start');
            $widgetToUpdate->unset('sched_tuesday_avail_end');
            
            $widgetToUpdate->unset('sched_wednesday_enabled');
            $widgetToUpdate->unset('sched_wednesday_avail_start');
            $widgetToUpdate->unset('sched_wednesday_avail_end');
            
            $widgetToUpdate->unset('sched_thursday_enabled');
            $widgetToUpdate->unset('sched_thursday_avail_start');
            $widgetToUpdate->unset('sched_thursday_avail_end');
            
            $widgetToUpdate->unset('sched_friday_enabled');
            $widgetToUpdate->unset('sched_friday_avail_start');
            $widgetToUpdate->unset('sched_friday_avail_end');
            
            $widgetToUpdate->unset('sched_saturday_enabled');
            $widgetToUpdate->unset('sched_saturday_avail_start');
            $widgetToUpdate->unset('sched_saturday_avail_end');
            
            $widgetToUpdate->unset('sched_sunday_enabled');
            $widgetToUpdate->unset('sched_sunday_avail_start');
            $widgetToUpdate->unset('sched_sunday_avail_end');
        }

        $widgetToUpdate->save();
        return redirect()->route("widget-details");
    }
}
