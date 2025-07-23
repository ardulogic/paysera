<template>
    <SectionBox>
        <div class="date-picker-wrapper">
            <h1>Book an Appointment:</h1>
            <DatePicker @dateSelected="onDateSelected"/>
        </div>

        <SlotPicker v-if="selectedDate" :date="selectedDate"></SlotPicker>
    </SectionBox>
</template>

<script setup>
import {ref} from 'vue'
import DatePicker from '@components/appointment/AppointmentDatePicker.vue'
import SlotPicker from '@components/appointment/AppointmentSlotPicker.vue'
import SectionBox from "@components/base/layout/sections/SectionBox.vue";

const selectedDate = ref('')


const onDateSelected = function (value) {
    selectedDate.value = value;
}

</script>
