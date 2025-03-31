<template>
    <h1 class="text-2xl font-bold text-center">
        {{ t('welcome') }}
    </h1>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>
