<template>
    <main class="min-h-screen bg-gray-100 dark:bg-gray-900 py-10 px-4 transition-colors">
        <div class="max-w-3xl mx-auto space-y-10">
            <section class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md transition-colors">
                <h1 class="text-2xl font-bold mb-6 text-center text-gray-900 dark:text-white">
                    {{ t('about.title') }}
                </h1>

                <div class="space-y-4 text-gray-800 dark:text-gray-200 text-lg leading-relaxed text-center sm:text-left">
                    <p>{{ t('about.description1') }}</p>
                    <p>{{ t('about.description2') }}</p>
                    <p>{{ t('about.description3') }}</p>
                    <p class="font-semibold mt-6">{{ t('about.stack') }}</p>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-6">
                    <a
                        v-for="tech in techStack"
                        :key="tech.name"
                        :href="tech.url"
                        target="_blank"
                        class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg text-center text-gray-900 dark:text-white transform transition hover:scale-105 hover:shadow-lg"
                    >
                        {{ tech.name }}
                    </a>
                </div>
            </section>

            <section class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-md text-center transition-colors">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Open Source</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">
                    This project is open source and available on GitHub.
                </p>
                <a
                    href="https://github.com/IlirionMK/coinapp"
                    target="_blank"
                    class="inline-flex items-center gap-2 bg-gray-900 text-white dark:bg-white dark:text-gray-900 px-4 py-2 rounded hover:opacity-90 transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 .5C5.65.5.5 5.65.5 12c0 5.1 3.3 9.4 7.9 10.9.6.1.8-.3.8-.6v-2.1c-3.2.7-3.9-1.6-3.9-1.6-.6-1.5-1.3-1.9-1.3-1.9-1-.6.1-.6.1-.6 1.2.1 1.9 1.3 1.9 1.3 1 .1 1.8-.7 2.2-1.1.1-.7.4-1.1.7-1.4-2.6-.3-5.4-1.3-5.4-5.8 0-1.3.5-2.3 1.2-3.2-.1-.3-.5-1.5.1-3.1 0 0 1-.3 3.2 1.2.9-.2 1.9-.4 2.8-.4s1.9.1 2.8.4c2.1-1.5 3.2-1.2 3.2-1.2.6 1.6.2 2.8.1 3.1.7.9 1.2 2 1.2 3.2 0 4.5-2.8 5.5-5.5 5.8.4.3.7.9.7 1.9v2.8c0 .3.2.7.8.6 4.6-1.5 7.9-5.8 7.9-10.9C23.5 5.65 18.35.5 12 .5z"
                        />
                    </svg>
                    View on GitHub
                </a>
            </section>
        </div>
    </main>
</template>

<script setup>
import { useI18n } from 'vue-i18n'
const { t } = useI18n()

const techStack = [
    { name: 'Laravel (PHP)', url: 'https://laravel.com' },
    { name: 'Vue 3 + Vue Router', url: 'https://vuejs.org' },
    { name: 'Tailwind CSS', url: 'https://tailwindcss.com' },
    { name: 'Docker', url: 'https://www.docker.com' },
    { name: 'PostgreSQL', url: 'https://www.postgresql.org' },
    { name: 'Redis', url: 'https://redis.io' }
]
</script>
