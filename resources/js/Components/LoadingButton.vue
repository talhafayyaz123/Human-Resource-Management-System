<template>
    <button
        :class="disabled ? 'disable-loading-button' : ''"
        :disabled="loading"
        class="flex items-center"
    >
        <div v-if="loading" class="btn-spinner mr-2" />
        <slot />
    </button>
</template>

<script>
export default {
    props: {
        loading: Boolean,
        disabled: {
            type: Boolean,
            default: false,
        },
    },
};
</script>
<style scoped>
.disable-loading-button {
    pointer-events: none;
    cursor: not-allowed;
    opacity: 0.65;
    filter: alpha(opacity=65);
    -webkit-box-shadow: none;
    box-shadow: none;
}
</style>
