<script setup>
import {ref, onMounted} from 'vue'
import axios from 'axios'
import WorkingHoursForm from '@components/workingHours/WorkingHoursForm.vue'

const workingHours = ref([])

const fetchHours = async () => {
    const res = await axios.get('/api/working-hours/list')
    workingHours.value = normalizeHours(res.data.working_hours || [])
}
const normalizeHours = (raw) => {
    const result = []

    // Days from Monday (1) to Sunday (0)
    const orderedDays = [1, 2, 3, 4, 5, 6, 0]

    for (const dayIndex of orderedDays) {
        const dayData = raw[dayIndex]
        if (dayData) {
            result.push({...dayData})
        } else {
            result.push({
                day_of_week: dayIndex,
                start_time: null,
                end_time: null,
            })
        }
    }

    console.log(result);

    return result
}

onMounted(fetchHours)

</script>

<template>
    <div class="working-hours-manager">
        <div class="working-hours-list">
            <WorkingHoursForm v-for="day in workingHours" :key="day.day_of_week"
                              :day-of-week="day.day_of_week"
                              :start-time="day.start_time"
                              :end-time="day.end_time"
            />
        </div>
    </div>
</template>

<style scoped>
.working-hours-list {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1.5rem;
}
</style>
