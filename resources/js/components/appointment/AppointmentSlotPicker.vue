<template>
    <div class="appointment-slot-picker">
        <div class="slots-wrapper">
            <AppointmentSlot v-for="slot in slots" :key="slot"
                             :start-at="slot.start_at"
                             :end-at="slot.end_at"
                             @book-success="fetchSlots"
            />
        </div>
        <p class="no-slots-available" v-if="slots.length === 0">
            Sorry, no slots are available.
        </p>
    </div>
</template>

<script setup>
import {ref, watch} from 'vue'
import axios from 'axios'
import AppointmentSlot from "@components/appointment/AppointmentSlot.vue";

const props = defineProps({
    date: {
        type: Date,
        required: true
    },
})

const slots = ref([])
const selectedTime = ref(null)

const fetchSlots = async () => {
    selectedTime.value = null
    slots.value = []

    if (!props.date) return

    try {
        const res = await axios.get('/api/appointment/slots', {
            params: {date: dayjs.utc(props.date).format('YYYY-MM-DD')},
        })

        // Convert ISO strings to Date objects (assume UTC)
        slots.value = (res.data.slots || []).map(slot => ({
            ...slot,
            start_at: dayjs.utc(slot.start_at).toDate(),
            end_at: dayjs.utc(slot.end_at).toDate(),
        }))
    } catch (err) {
        slots.value = [];
        console.error('Failed to fetch slots:', err)
    }
}

watch(() => props.date, fetchSlots, {immediate: true})
</script>

<style scoped>
.slots-wrapper {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-top: 1rem;
}
</style>

