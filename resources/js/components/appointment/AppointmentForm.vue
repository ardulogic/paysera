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
    return {
        start_at: dayjs(props.startAt).format('YYYY-MM-DD HH:mm'),
        end_at: dayjs(props.endAt).format('YYYY-MM-DD HH:mm'),
    }
});

const submitHandler = async (values) => {
    const response = await axios.post('/api/appointment/store', {
        email: values.email,
        start_at: props.startAt.toISOString(),
        end_at: props.endAt.toISOString()
    })

    return { message: 'Appointment has been created!' }
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
