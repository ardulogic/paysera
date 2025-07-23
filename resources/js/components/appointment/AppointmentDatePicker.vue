<template>
    <Datepicker
        v-model="selected"
        :min-date="new Date()"
        :hide-time-header="true"
        :disabled-dates="isDateDisabled"
        @update:model-value="emitDate"
        :enable-time-picker="false"
        placeholder="Select a date"
    />
</template>

<script setup>
import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import axios from 'axios'
import { ref, onMounted } from 'vue'

const emit = defineEmits(['dateSelected'])
const selected = ref(null)
const allowedWeekdays = ref([])

const emitDate = (value) => {
    emit('dateSelected', value)
}

const isDateDisabled = (date) => {
    const dow = date.getDay()

    return !allowedWeekdays.value.includes(dow)
}

onMounted(async () => {
    try {
        const res = await axios.get('/api/working-hours/list')
        const workingHours = res.data.working_hours
        allowedWeekdays.value = Object.keys(workingHours).map(Number);
    } catch (err) {
        console.error('Failed to load working days:', err)
    }
})
</script>
