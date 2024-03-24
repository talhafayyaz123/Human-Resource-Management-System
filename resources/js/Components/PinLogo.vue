<template>
    <img class="logo" :src="LogoImage" alt="logo" />
</template>

<script>
import Logo from "@/assets/images/logo.svg";

export default {
    computed: {
        LogoImage() {
            return Logo;
        },
    },
};
</script>

<style scoped>
.logo {
    height: 100px;
    width: 206px;
    object-fit: cover;
    overflow: hidden;
}
</style>
