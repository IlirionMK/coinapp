<template>
    <section class="space-y-4">
        <h1 class="text-2xl font-bold">{{ t('converter.title') }}</h1>
        <p class="text-gray-600">{{ t('converter.description') }}</p>

        <!-- Конвертер -->
        <ConverterPreview />
    </section>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import ConverterPreview from "@/components/ConverterPreview.vue";

const { t } = useI18n()
</script>
