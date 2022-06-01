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
    hasScheduledAvailability: ("<?php echo $hasScheduledAvailability; ?>" === "true"),
    schedulerEnabledForToday: ("<?php echo $enabledForToday; ?>" === "true"),
    availabilityStartTime: new Date("<?php echo $availabilityStartTime; ?>"),
    availabilityEndTime: new Date("<?php echo $availabilityEndTime; ?>"),

    // Widget appearance variables
    color: "<?php echo $color; ?>",
    icon: "<?php echo $icon; ?>", 

    // Feature variables
    isFileSharingEnabled: ("<?php echo $isFileSharingEnabled ?>" === "true")
});