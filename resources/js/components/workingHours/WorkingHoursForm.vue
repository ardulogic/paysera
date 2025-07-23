<script setup>
import {ref} from 'vue'
import FormField from '@js/components/base/form/FormField.vue'
import {workingHoursSchema} from "@js/validation/workingHours.js";
import FormWrapper from "@components/base/form/FormWrapper.vue";
import {Field} from 'vee-validate'
import axios from "axios";

const days = [
    'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
]

const props = defineProps({
    dayOfWeek: {type: Number, required: true},
    startTime: {type: [String, null], required: true},
    endTime: {type: [String, null], required: true},
})

const initialValues = {
    start_time: props.startTime,
    end_time: props.endTime,
    day_of_week: props.dayOfWeek,
    // closed: !props.startTime || !props.endTime,
    closed: !props.startTime || !props.endTime,
}

const submitHandler = async (values) => {
    await axios.put('/api/working-hours/update', values)

    return {message: 'Working hours have been updated!'}
};

const dayIsClosed = ref(initialValues.closed)

const onClosedChanged = function (e) {
    dayIsClosed.value = e.target.checked;
}

</script>

<template>
    <FormWrapper
        submit-label="Update"
        :submit-handler="submitHandler"
        :val-schema="workingHoursSchema"
        :initial-values="initialValues"
        :hide-form-on-success="false"
    >
        <div class="weekday-label">{{ days[dayOfWeek] }}</div>

        <Field name="day_of_week" type="hidden"/>

        <FormField
            name="start_time"
            type="time"
            label="Open Time"
            :disabled="dayIsClosed"
        />

        <FormField
            name="end_time"
            type="time"
            label="Close Time"
            :disabled="dayIsClosed"
        />

        <FormField
            name="closed"
            type="checkbox"
            label="Closed"
            :value="true"
            @change="onClosedChanged"
        />
    </FormWrapper>
</template>

<style scoped lang="scss">
.weekday-label {
    width: 100px;
    font-weight: bold;
}
</style>
