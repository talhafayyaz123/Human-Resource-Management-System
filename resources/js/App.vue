<template>
    <router-view />
</template>

<script>
export default {
    watch: {
        $route() {
            this.$store.commit("errors", {});
        },
    },
};
</script>
