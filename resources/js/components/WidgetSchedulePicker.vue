<template>
    <div>
        <div class="widget-block">
            <label style="font-size: 1rem;">Enabled? 
                <input type="checkbox" v-model="enabled">
            </label>  
        </div>
        
        <template v-if="enabled">
            <div class="widget-block">
                <label class="chat-label">Time Zone</label>  
                <select name="availability_timezone" id="time-zone" class="form-select form-control-widget-full" v-model="selectedTimezone" required>
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

            <div class="widget-block">
                <label class="chat-label" for="availability-start-time">Start Time</label>
                <input class="form-control form-control-widget-full" type="time" name="availability_start_time" id="availability-start-time" v-model="startTime" required>
            </div>

            <div class="widget-block">
                <label class="chat-label" for="availability-end-time">End Time</label>
                <input class="form-control form-control-widget-full" type="time" name="availability_end_time" id="availability-end-time" v-model="endTime" required>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    props: [
        'timezones',
        'initialSelectedTimezone',
        'initialStartTime',
        'initialEndTime',
    ],

    data() {
        return {
            enabled: this.initialStartTime && this.initialEndTime,
            startTime: this.initialStartTime,
            endTime: this.initialEndTime,
            selectedTimezone: this.initialSelectedTimezone,
        }
    }
};
</script>
