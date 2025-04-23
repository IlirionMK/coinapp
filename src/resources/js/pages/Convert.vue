<template>
    <section class="space-y-4">
        <h1 class="text-2xl font-bold">{{ t('converter') }}</h1>
        <p>{{ t('converter_intro') }}</p>
        <!-- Форма конвертации -->
    </section>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>
