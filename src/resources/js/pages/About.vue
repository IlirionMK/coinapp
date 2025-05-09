<template>
    <section class="space-y-6 max-w-3xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-green-600">{{ t('about.title') }}</h1>

        <p class="text-gray-700 leading-relaxed">
            {{ t('about.description1') }}
        </p>

        <p class="text-gray-700 leading-relaxed">
            {{ t('about.description2') }}
        </p>

        <p class="text-gray-700 leading-relaxed">
            {{ t('about.description3') }}
        </p>

        <p class="text-gray-700 leading-relaxed">
            {{ t('about.stack') }}
        </p>

        <ul class="list-disc list-inside text-gray-700 space-y-1">
            <li>Laravel (PHP)</li>
            <li>Vue 3 + Vue Router</li>
            <li>Tailwind CSS</li>
            <li>Docker</li>
            <li>PostgreSQL</li>
            <li>Redis</li>
        </ul>
    </section>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>
