<template>
    
    <div class="relative mb-4">
        <div
            class="flex items-center justify-between text-white w-full h-16 bg-[#2957A4] px-12 py-4 rounded-md"
        >
            <p class="">
                <span class="font-bold">Name : </span>{{ tenantRepo.name }}
            </p>
            <p class="repo-data-size">
                <span class="font-bold">Data Size : </span
                >{{ tenantRepo.data_size }}MB
            </p>
            <p class="repo-database-size">
                <span class="font-bold">Database Size : </span
                >{{ tenantRepo.database_size }}MB
            </p>
            <div @click="toggleDropdown" class="cursor-pointer">
                <Icon
                    v-if="!isDropdownOpen"
                    name="downwhiteIcon"
                    width="40px"
                    height="40px"
                />
                <Icon v-else name="upwhiteIcon" width="40px" height="40px" />
            </div>
        </div>

        <div
            v-if="isDropdownOpen && tenantRepo?.pods?.length > 0"
            class="mt-2 w-full flex flex-wrap gap-y-8 p-4 bg-white rounded-md shadow-lg"
        >
            <div
                v-for="pod in tenantRepo.pods"
                :key="pod.name"
                class="sm:w-full md:w-1/2 lg:w-1/3 p-4"
            >
                <div
                    class="flex flex-col w-[400px] bg-white rounded shadow-md p-4"
                >
                    <div class="flex justify-between mb-8">
                        <div class="font-bold">
                            {{ pod.display_name }}
                        </div>
                        <div :class="getStatusClass(pod.status)">
                            {{ pod.status }}
                        </div>
                    </div>
                    <div class="mb-8">
                        <strong>Memory:</strong> {{ pod.memory }}
                    </div>
                    <loading-button
                        @click.prevent="
                            refreshCloudWithRepo(pod.name, tenantRepo)
                        "
                        :loading="isLoading"
                        class="secondary-btn cursor-pointer"
                    >
                        <span class="mr-1">
                            <CustomIcon name="updateIcon" />
                        </span>
                        {{ $t("Restart") }}
                    </loading-button>
                    <div v-if="pod.urls && pod.urls.length > 0" class="mt-4">
                        <strong>URLs:</strong>
                        <div
                            v-for="(url, index) in pod.urls"
                            :key="index"
                            class="mb-2 mt-2"
                        >
                            <a
                                :href="url.url"
                                target="_blank"
                                class="text-blue-500 hover:underline"
                            >
                                {{ url.name }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            v-else-if="isDropdownOpen && tenantRepo?.pods?.length === 0"
            class="absolute mt-2 w-full flex flex-wrap justify-center gap-y-8 p-4 bg-white shadow-lg z-20"
        >
            No Data Available!
        </div>
    </div>
</template>

<script>
import Icon from "@/Components/Icon.vue";

export default {
    components: {
        Icon,
    },
    props: {
        tenantRepo: {
            type: Object,
            default: {},
        },
        refreshCloudWithRepo: {
            type: Function,
        },
    },
    data() {
        return {
            isDropdownOpen: false,
        };
    },
    methods: {
        toggleDropdown() {
            this.isDropdownOpen = !this.isDropdownOpen;
        },
        getStatusClass(status) {
            return status === "Running" ? "text-green-500" : "text-red-500";
        },
    },
};
</script>
