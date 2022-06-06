/**
 * Entrypoint for the Tawk.js Application
 */

import { Tawk } from './Tawk';

/*
    Notes: The strings below are filled out by the Laravel application.

    For more details, consult the App\Http\Controllers\WidgetController::generateScript() function.
*/

const tawk = new Tawk({
    baseUrl: "<?php echo $baseUrl; ?>",
    widgetId: "<?php echo $widgetId; ?>",
    
    // Scheduler configuration variables
    hasScheduledAvailability: JSON.parse("<?php echo $hasScheduledAvailability; ?>"),
    schedulerEnabledForToday: JSON.parse("<?php echo $enabledForToday; ?>"),
    availabilityStartTime: new Date("<?php echo $availabilityStartTime; ?>"),
    availabilityEndTime: new Date("<?php echo $availabilityEndTime; ?>"),
    afterHoursMessage: "<?php echo $afterHoursMessage; ?>",

    // Widget appearance variables
    color: "<?php echo $color; ?>",
    icon: "<?php echo $icon; ?>", 
});

console.log(tawk);