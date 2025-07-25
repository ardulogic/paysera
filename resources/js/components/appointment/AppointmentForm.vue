<script setup>
import FormField from '@js/components/base/form/FormField.vue'
import FormWrapper from '@js/components/base/form/FormWrapper.vue'
import {appointmentSchema} from '@js/validation/appointment.js'
import axios from "axios";
import {computed} from 'vue'
import dayjs from 'dayjs'

const props = defineProps({
    startAt: {type: Date, required: true},
    endAt: {type: Date, required: true},
})

const initialValues = computed(() => {
    // Display in user's local timezone
    return {
        start_at: dayjs.utc(props.startAt).tz(dayjs.tz.guess()).format('YYYY-MM-DD HH:mm'),
        end_at: dayjs.utc(props.endAt).tz(dayjs.tz.guess()).format('YYYY-MM-DD HH:mm'),
    }
});

const submitHandler = async (values) => {
    // Always send UTC ISO strings to backend
    const response = await axios.post('/api/appointment/store', {
        email: values.email,
        start_at: dayjs(props.startAt).utc().toISOString(),
        end_at: dayjs(props.endAt).utc().toISOString()
    })
    return { 
        message: 'Appointment has been created!',
        pageMessage: 'Appointment successfully booked!'
    }
};

</script>

<template>
    <FormWrapper
        submit-label="Reserve"
        :submit-handler="submitHandler"
        :val-schema="appointmentSchema"
        :initial-values="initialValues"
    >
        <FormField name="start_at" label="From" type="text" readonly/>
        <FormField name="end_at" label="To" type="text" readonly/>
        <FormField name="email" label="Email" type="email"/>
    </FormWrapper>
</template>
