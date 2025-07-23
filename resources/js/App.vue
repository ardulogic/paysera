<script setup>
import {RouterView} from 'vue-router'
import HeaderNav from '@/components/base/layout/Header.vue'
import {NDialogProvider} from 'naive-ui'
import {NMessageProvider} from 'naive-ui'

</script>

<template>
    <HeaderNav></HeaderNav>
    <n-message-provider>
        <n-dialog-provider>
            <router-view/>
        </n-dialog-provider>
    </n-message-provider>

</template>

<style scoped>

</style>
