<script setup>
import {defineProps, watch} from 'vue'
import {useForm} from 'vee-validate'
import {ref} from 'vue'
import {useMessage} from "naive-ui";
import FormButton from "@components/base/form/FormButton.vue";

const props = defineProps({
        submitHandler: {type: Function, required: true},
        valSchema: Object,
        hideFormOnSuccess: {type: Boolean, default: true},
        submitLabel: {
            type: String,
            default: () => 'Submit'
        },
        initialValues: {
            type: Object,
            default: () => ({})
        },
    }
)

const emit = defineEmits(['success'])
const errorMessage = ref('')
const successMessage = ref('')
const pageMessage = useMessage()

const {
    errors,
    handleSubmit,
    isSubmitting,
    setErrors,
    resetForm
} = useForm({
    validationSchema: props.valSchema,
    initialValues: props.initialValues
})
const onSubmit = handleSubmit(async (values) => {
    errorMessage.value = ''
    successMessage.value = ''

    try {
        const result = await props.submitHandler(values)
        emit('success', result)
        successMessage.value = result?.message ?? ''

        if (result?.pageMessage) {
            pageMessage.success(result?.pageMessage)
        }
    } catch (err) {
        const response = err.response?.data

        if (response?.errors) {
            console.log('Server validation errors:', response.errors)
            setErrors(response.errors)
        }
        console.error(err);
        errorMessage.value = response?.message ?? 'Oops. An error occurred.'
    }
})

defineExpose({
    resetForm,
    setErrors
})

watch(errors, (newErrors) => {
    console.log('Current validation errors:', newErrors)
})
</script>

<template>
    <form @submit.prevent="onSubmit" novalidate>
        <template v-if="!hideFormOnSuccess || !successMessage">
            <!-- Form Fields Slot -->
            <slot/>

            <slot name="buttons">
                <FormButton :is-submitting="isSubmitting" :label="submitLabel"/>
            </slot>
        </template>

        <transition name="slide-fade">
            <p v-if="errorMessage" class="form-error">{{ errorMessage }}</p>
        </transition>

        <transition name="slide-fade">
            <p v-if="successMessage" class="form-success">{{ successMessage }}</p>
        </transition>
    </form>
</template>


<style scoped lang="scss">
@use "@style/anims" as *;
@use "@styleVars/base" as *;

.form-error {
    color: $color-error-light;
    margin-top: 1rem;
    font-size: 0.95rem;
    text-align: center;
}

.form-success {
    color: $color-success;
    margin-top: 1rem;
    font-size: 0.95rem;
    text-align: center;
}
</style>
