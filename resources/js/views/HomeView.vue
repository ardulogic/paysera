<!-- All appointment times are displayed in the user's local timezone -->
<template>
    <CenteredView>
        <div class="date-picker-wrapper">
            <h1>Book an Appointment:</h1>
            <DatePicker @dateSelected="onDateSelected"/>
        </div>

        <SlotPicker v-if="selectedDate" :date="selectedDate"></SlotPicker>
    </CenteredView>
</template>

<script setup>
import {ref} from 'vue'
import DatePicker from '@components/appointment/AppointmentDatePicker.vue'
import SlotPicker from '@components/appointment/AppointmentSlotPicker.vue'
import CenteredView from "@components/base/layout/views/CenteredView.vue";

const selectedDate = ref('')

const onDateSelected = function (value) {
    selectedDate.value = value;
}
</script>
