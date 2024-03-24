<template>
    <div class="search-filters">
        <div class="search-filter">
            <dropdown
                v-if="isFilter"
                :auto-close="false"
                class="focus:z-10 px-4 hover:bg-gray-100 border-r focus:border-white rounded-l focus:ring md:px-6"
                placement="bottom-start"
            >
                <template #default>
                    <div class="flex items-baseline">
                        <span class="hidden text-gray-700 md:inline"
                            >Filter</span
                        >
                        <svg
                            class="w-2 h-2 fill-gray-700 md:ml-2"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 961.243 599.998"
                        >
                            <path
                                d="M239.998 239.999L0 0h961.243L721.246 240c-131.999 132-240.28 240-240.624 239.999-.345-.001-108.625-108.001-240.624-240z"
                            />
                        </svg>
                    </div>
                </template>
                <template #dropdown>
                    <div
                        class="mt-2 px-4 py-6 w-screen bg-white rounded shadow-xl"
                        :style="{ maxWidth: `${maxWidth}px` }"
                    >
                        <slot />
                    </div>
                </template>
            </dropdown>
            <input
                class=""
                autocomplete="off"
                type="text"
                name="search"
                :placeholder="$t('Search…')"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            />
            <button class="ml-3 text-gray-500 hover:text-gray-700 focus:text-indigo-500 text-sm"
                type="button"
                @click="$emit('reset')"
            >
                {{ $t("Reset") }}
            </button>
        </div>
    </div>
</template>

<script>
import Dropdown from "./Dropdown.vue";

export default {
    components: {
        Dropdown,
    },
    props: {
        modelValue: String,
        maxWidth: {
            type: Number,
            default: 300,
        },
        isFilter: {
            type: Boolean,
            default: true,
        },
    },
    emits: ["update:modelValue", "reset"],
};
</script>
