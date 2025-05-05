<template>
    <section class="space-y-4">
        <h1 class="text-2xl font-bold">{{ t('converter.title') }}</h1>
        <p class="text-gray-600">{{ t('converter.description') }}</p>

        <!-- Конвертер -->
        <Converter />
    </section>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import Converter from '@/components/Converter.vue'

const { t } = useI18n()
</script>
