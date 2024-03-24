<template>
    <Main :action="'create'" />
</template>

<script>
import Main from "./Components/Main.vue";

export default {
    name: "WorkshopTemplatesCreate",
    components: {
        Main,
    },
};
</script>

<style scoped>
.color-blue {
    color: #2957a4;
}
.bg-blue {
    background-color: #2957a4;
}

th,
td {
    border: 1px solid black;
}
</style>
