<template>
    <div>
        <div class="widget-block">
            <label class="chat-label" for="availability-schedule">Availability Schedule</label>
        </div>
        <div class="widget-block">
            <label class="switch">
                <input type="checkbox" name="sched_enabled" v-model="enabled" :disabled="!editable">
                <span class="slider round"></span>
            </label> 
            <span class="chat-label">
                {{ enabled ? "Enabled" : "Disabled" }}
            </span>
        </div>
        
        <template v-if="enabled">
            <div class="widget-block">
                <label class="chat-label">Time Zone</label>  
                <select name="availability_timezone" id="time-zone" class="form-select form-control-widget-full" v-model="selectedTimezone" required :disabled="!editable">
                    <!-- Default Option -->
                    <option disabled>
                        -- Select a timezone --
                    </option>

                    <!-- List of Timezones -->
                    <optgroup v-for="(timezones, region) in timezones" :key="region" :label="region">
                        <option v-for="(name, technicalName) in timezones" :key="technicalName" :value="technicalName">
                            {{name}}
                        </option>
                    </optgroup>
                </select>
            </div>

            <div class="widget-block flex-wrap">
                <label class="chat-label" for="after-hours-message">After Hours Message</label>
                <textarea class="form-control" name="after-hours-message" id="after-hours-message" style="height: 5rem;" v-model="afterHoursMessage"></textarea>
                
                <div class="w-100"></div>
                <small>Write a note you want your customers to see when they visit outside scheduled hours.</small>
            </div>

            <!-- Monday Availability -->
            <div class="widget-block">
                <span class="chat-label">
                    Monday
                </span>
                <label class="switch">
                    <input type="checkbox" name="sched_monday_enabled" v-model="weekdays.monday.enabled" :disabled="!editable" id="sched_monday_enabled">
                    <span class="slider round"></span>
                </label> 
            </div>

            <template v-if="weekdays.monday.enabled">
                <div class="widget-block ms-4">
                    <label class="chat-label" for="sched_monday_avail_start">Start Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_monday_avail_start" id="sched_monday_avail_start" v-model="weekdays.monday.startTime" required :disabled="!editable">
                </div>

                <div class="widget-block ms-4 mb-5">
                    <label class="chat-label" for="sched_monday_avail_end">End Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_monday_avail_end" id="sched_monday_avail_end" v-model="weekdays.monday.endTime" required :disabled="!editable">
                </div>
            </template>
            <!-- End: Monday Availability -->

            <!-- Tuesday Availability -->
            <div class="widget-block mb-2">
                <span class="chat-label">
                    Tuesday
                </span>
                <label class="switch">
                    <input type="checkbox" name="sched_tuesday_enabled" v-model="weekdays.tuesday.enabled" :disabled="!editable" id="sched_tuesday_enabled">
                    <span class="slider round"></span>
                </label> 
            </div>

            <template v-if="weekdays.tuesday.enabled">
                <div class="widget-block">
                    <label class="chat-label" for="sched_tuesday_avail_start">Start Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_tuesday_avail_start" id="sched_tuesday_avail_start" v-model="weekdays.tuesday.startTime" required :disabled="!editable">
                </div>

                <div class="widget-block mb-5">
                    <label class="chat-label" for="sched_tuesday_avail_end">End Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_tuesday_avail_end" id="sched_tuesday_avail_end" v-model="weekdays.tuesday.endTime" required :disabled="!editable">
                </div>
            </template>
            <!-- End: Tuesday Availability -->

            <!-- Wednesday Availability -->
            <div class="widget-block mb-2">
                <span class="chat-label">
                    Wednesday
                </span>
                <label class="switch">
                    <input type="checkbox" name="sched_wednesday_enabled" v-model="weekdays.wednesday.enabled" :disabled="!editable" id="sched_wednesday_enabled">
                    <span class="slider round"></span>
                </label> 
            </div>

            <template v-if="weekdays.wednesday.enabled">
                <div class="widget-block">
                    <label class="chat-label" for="sched_wednesday_avail_start">Start Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_wednesday_avail_start" id="sched_wednesday_avail_start" v-model="weekdays.wednesday.startTime" required :disabled="!editable">
                </div>

                <div class="widget-block mb-5">
                    <label class="chat-label" for="sched_wednesday_avail_end">End Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_wednesday_avail_end" id="sched_wednesday_avail_end" v-model="weekdays.wednesday.endTime" required :disabled="!editable">
                </div>
            </template>
            <!-- End: Wednesday Availability -->

            <!-- Thursday Availability -->
            <div class="widget-block mb-2">
                <span class="chat-label">
                    Thursday
                </span>
                <label class="switch">
                    <input type="checkbox" name="sched_thursday_enabled" v-model="weekdays.thursday.enabled" :disabled="!editable" id="sched_thursday_enabled">
                    <span class="slider round"></span>
                </label> 
            </div>

            <template v-if="weekdays.thursday.enabled">
                <div class="widget-block">
                    <label class="chat-label" for="sched_thursday_avail_start">Start Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_thursday_avail_start" id="sched_thursday_avail_start" v-model="weekdays.thursday.startTime" required :disabled="!editable">
                </div>

                <div class="widget-block mb-5">
                    <label class="chat-label" for="sched_thursday_avail_end">End Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_thursday_avail_end" id="sched_thursday_avail_end" v-model="weekdays.thursday.endTime" required :disabled="!editable">
                </div>
            </template>
            <!-- End: Thursday Availability -->

            <!-- Friday Availability -->
            <div class="widget-block mb-2">
                <span class="chat-label">
                    Friday
                </span>
                <label class="switch">
                    <input type="checkbox" name="sched_friday_enabled" v-model="weekdays.friday.enabled" :disabled="!editable" id="sched_friday_enabled">
                    <span class="slider round"></span>
                </label> 
            </div>

            <template v-if="weekdays.friday.enabled">
                <div class="widget-block">
                    <label class="chat-label" for="sched_friday_avail_start">Start Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_friday_avail_start" id="sched_friday_avail_start" v-model="weekdays.friday.startTime" required :disabled="!editable">
                </div>

                <div class="widget-block mb-5">
                    <label class="chat-label" for="sched_friday_avail_end">End Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_friday_avail_end" id="sched_friday_avail_end" v-model="weekdays.friday.endTime" required :disabled="!editable">
                </div>
            </template>
            <!-- End: Friday Availability -->

            <!-- Saturday Availability -->
            <div class="widget-block mb-2">
                <span class="chat-label">
                    Saturday
                </span>
                <label class="switch">
                    <input type="checkbox" name="sched_saturday_enabled" v-model="weekdays.saturday.enabled" :disabled="!editable" id="sched_saturday_enabled">
                    <span class="slider round"></span>
                </label> 
            </div>

            <template v-if="weekdays.saturday.enabled">
                <div class="widget-block">
                    <label class="chat-label" for="sched_saturday_avail_start">Start Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_saturday_avail_start" id="sched_saturday_avail_start" v-model="weekdays.saturday.startTime" required :disabled="!editable">
                </div>

                <div class="widget-block mb-5">
                    <label class="chat-label" for="sched_saturday_avail_end">End Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_saturday_avail_end" id="sched_saturday_avail_end" v-model="weekdays.saturday.endTime" required :disabled="!editable">
                </div>
            </template>
            <!-- End: Saturday Availability -->

            <!-- Sunday Availability -->
            <div class="widget-block mb-2">
                <span class="chat-label">
                    Sunday
                </span>
                <label class="switch">
                    <input type="checkbox" name="sched_sunday_enabled" v-model="weekdays.sunday.enabled" :disabled="!editable" id="sched_sunday_enabled">
                    <span class="slider round"></span>
                </label> 
            </div>

            <template v-if="weekdays.sunday.enabled">
                <div class="widget-block">
                    <label class="chat-label" for="sched_sunday_avail_start">Start Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_sunday_avail_start" id="sched_sunday_avail_start" v-model="weekdays.sunday.startTime" required :disabled="!editable">
                </div>

                <div class="widget-block mb-5">
                    <label class="chat-label" for="sched_sunday_avail_end">End Time</label>
                    <input class="form-control form-control-widget-full" type="time" name="sched_sunday_avail_end" id="sched_sunday_avail_end" v-model="weekdays.sunday.endTime" required :disabled="!editable">
                </div>
            </template>
            <!-- End: Sunday Availability -->
        </template>
    </div>
</template>

<script>
export default {
    props: [
        'timezones',
        'initialSelectedTimezone',
        'initialAfterHoursMessage',
        'editable',
        'initialEnabled',
        'mondayEnabled',
        'mondayAvailStart',
        'mondayAvailEnd',
        'tuesdayEnabled',
        'tuesdayAvailStart',
        'tuesdayAvailEnd',
        'wednesdayEnabled',
        'wednesdayAvailStart',
        'wednesdayAvailEnd',
        'thursdayEnabled',
        'thursdayAvailStart',
        'thursdayAvailEnd',
        'fridayEnabled',
        'fridayAvailStart',
        'fridayAvailEnd',
        'saturdayEnabled',
        'saturdayAvailStart',
        'saturdayAvailEnd',
        'sundayEnabled',
        'sundayAvailStart',
        'sundayAvailEnd',
    ],

    data() {
        return {
            enabled: this.initialEnabled,
            startTime: this.initialStartTime,
            endTime: this.initialEndTime,
            selectedTimezone: this.initialSelectedTimezone,
            afterHoursMessage: this.initialAfterHoursMessage,

            weekdays: {
                monday: {
                    enabled: this.mondayEnabled,
                    startTime: this.mondayAvailStart,
                    endTime: this.mondayAvailEnd
                },
                tuesday: {
                    enabled: this.tuesdayEnabled,
                    startTime: this.tuesdayAvailStart,
                    endTime: this.tuesdayAvailEnd
                },
                wednesday: {
                    enabled: this.wednesdayEnabled,
                    startTime: this.wednesdayAvailStart,
                    endTime: this.wednesdayAvailEnd
                },
                thursday: {
                    enabled: this.thursdayEnabled,
                    startTime: this.thursdayAvailStart,
                    endTime: this.thursdayAvailEnd
                },
                friday: {
                    enabled: this.fridayEnabled,
                    startTime: this.fridayAvailStart,
                    endTime: this.fridayAvailEnd
                },
                saturday: {
                    enabled: this.saturdayEnabled,
                    startTime: this.saturdayAvailStart,
                    endTime: this.saturdayAvailEnd
                },
                sunday: {
                    enabled: this.sundayEnabled,
                    startTime: this.sundayAvailStart,
                    endTime: this.sundayAvailEnd
                }
            }
        }
    }
};
</script>
