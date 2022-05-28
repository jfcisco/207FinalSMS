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
    hasScheduledAvailability: ("<?php echo $hasScheduledAvailability; ?>" === "true"),
    availabilityStartTime: new Date("<?php echo $availabilityStartTime; ?>"),
    availabilityEndTime: new Date("<?php echo $availabilityEndTime; ?>"),
});

console.log("<?php echo $hasScheduledAvailability; ?>" === "true");
console.log(new Date("<?php echo $availabilityStartTime; ?>"));
console.log(new Date("<?php echo $availabilityEndTime; ?>"));