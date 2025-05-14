<template>
    <div class="max-w-md mx-auto mt-20 p-6 bg-white rounded shadow text-center">
        <h2 class="text-2xl font-bold text-green-600 mb-4">Email confirmed ✅</h2>
        <p class="mb-6 text-gray-700">
            Your email has been successfully verified. You can now access your dashboard.
        </p>
        <RouterLink
            to="/dashboard"
            class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded"
        >
            Go to Dashboard
        </RouterLink>
    </div>
</template>
