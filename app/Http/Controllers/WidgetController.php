<?php

namespace App\Http\Controllers;

use App\Models\ChatWidget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        ]);
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
        
        // Check request origin with widget's allowed domains
        $origin = $request->header('origin');
        $allowedDomains = collect(ChatWidget::find($widgetId)->allowed_domains);

        if ($allowedDomains->count() > 0) {
            // Reject any requests from unauthorized domains with 403 Forbidden
            if (!$origin || $allowedDomains->doesntContain($origin)) {
                Log::warning("Blocked request from $origin for widget ID $widgetId");
                abort(403);
            }
        }

        // Generate a view for the script
        $view = view()->make('widget.widget-script', [
            'baseUrl' => env('APP_URL'),
            'userId' => $userId,
            'widgetId' => $widgetId
        ])->withHeaders([
            // Make browsers interpret this as JavaScript
            'Content-Type' => 'application/javascript',

            // Enable CORS for this resource
            'Access-Control-Allow-Origin' => '*'
        ]);

        return $view->render();

        // Reference: https://laracasts.com/discuss/channels/laravel/returning-a-dynamic-compiled-javascript-file-from-a-laravel-route
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
}
