<template>
    <section class="max-w-2xl mx-auto px-4 py-6 space-y-6">
        <div>
            <h1 class="text-2xl font-bold">{{ t('converter.title') }}</h1>
            <p class="text-gray-600 dark:text-gray-400">
                {{ t('converter.description') }}
            </p>
        </div>

        <ConverterPreview variant="page" />
    </section>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
import ConverterPreview from '@/components/ConverterPreview.vue'

const { t } = useI18n()
</script>
