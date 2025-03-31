<template>
    <div class="p-4">
        <h1 class="text-2xl">Hello from Vue!</h1>
        <p>{{ message }}</p>
    </div>
</template>

<script>
export default {
    data() {
        return {
            message: 'This is rendered by Vue!',
        };
    },
};
</script>
