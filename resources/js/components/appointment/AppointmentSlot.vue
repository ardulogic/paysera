<template>
    <button @click="select">
        <span>{{ timeFrom }}</span>
        <span>–</span>
        <span>{{ timeTo }}</span>
    </button>
</template>

<script setup>
import {computed} from 'vue'
import AppointmentForm from '@components/appointment/AppointmentForm.vue'
import {useDialog} from 'naive-ui'
import dayjs from 'dayjs'

const dialog = useDialog()
import {h} from 'vue'

const props = defineProps({
    startAt: {type: Date, required: true}, // e.g., "2025-07-30 10:00"
    endAt: {type: Date, required: true},
})

const emit = defineEmits(['bookSuccess'])
const timeFrom = computed(() => formatTime(props.startAt))
const timeTo = computed(() => formatTime(props.endAt))

const select = () => {
    showFormDialog();
}

const formatTime = (dateObj) => {
    // Display in user's local timezone
    return dayjs.utc(dateObj).tz(dayjs.tz.guess()).format('HH:mm')
}

const showFormDialog = () => {
    const d = dialog.create({
        title: 'Book Appointment',
        content: () =>
            h(AppointmentForm, {
                startAt: props.startAt,
                endAt: props.endAt,
                onClose: () => d.destroy(),
                onSuccess: () => {
                    emit('bookSuccess')
                    d.destroy()
                }
            }),
        showIcon: false,
        footer: null
    })
}

</script>
<style scoped>
label {
    margin: 1em;
}

button {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 4px;
    width: 100%;
    padding: 0.75rem 1rem;
    font-size: 14px;
    background-color: #f9fafb;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    text-align: center;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.2s ease, border-color 0.2s ease;
    color: #111827;
}

button:hover {
    background-color: #e5e7eb;
    border-color: #9ca3af;
}

button:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.4); /* blue ring */
    border-color: #3b82f6;
}
</style>
