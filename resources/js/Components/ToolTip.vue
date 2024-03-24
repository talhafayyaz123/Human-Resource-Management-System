<template>
    <div class="relative flex flex-col items-center group">
        <div>
            <slot />
        </div>
        <div
            class="absolute bottom-0 flex flex-col items-center hidden mb-6 group-hover:flex"
        >
            <span
                class="relative z-10 p-2 w-[max-content] w-max-[150px] text-xs leading-none text-white whitespace-no-wrap bg-[#2957a4] shadow-lg"
                >{{ text }}</span
            >
            <div class="w-3 h-3 -mt-2 rotate-45 bg-[#2957a4]"></div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        text: { type: String, default: "" },
    },
};
</script>

<style scoped></style>
