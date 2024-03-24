<template>
    <div>
        <div class="page-header">
            <div class="page-title">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 items-center">
                        <li class="breadcrumb-item">
                            <div class="flex items-center">
                                <router-link to="/dashboard" class=""
                                    >Admin Portal</router-link
                                >
                            </div>
                        </li>
                        <li class="breadcrumb-item">
                            <div class="flex items-center">
                                <span>Self Service</span>
                            </div>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="self-service grid grid-cols-[1fr,1fr,1fr,1fr,1fr] gap-5">
            <div
                v-if="$can('time-tracker.list')"
                @click="$router.push('/time-trackers')"
                class="self-service-box"
            >
                <font-awesome-icon
                    style="color: #2957a4; font-size: 2.5rem"
                    icon="fa-solid fa-business-time mb-5"
                />
                <p>{{ $t("Time Tracker") }}</p>
            </div>
            <div
                v-if="$can('vacation-request.list')"
                @click="$router.push('/vacation-request')"
                class="self-service-box"
            >
                <font-awesome-icon
                    style="color: #2957a4; font-size: 2.5rem"
                    icon="fa-solid fa-umbrella-beach mb-5"
                />
                <p>{{ $t("Vacation Request") }}</p>
            </div>
            <div
                v-if="$can('travel-expenses.list')"
                @click="$router.push('/travel-expenses')"
                class="self-service-box"
            >
                <font-awesome-icon
                    style="color: #2957a4; font-size: 2.5rem"
                    icon="fa-solid fa-plane mb-5"
                />
                <p>{{ $t("Travel Expenses") }}</p>
            </div>
            <div v-if="$can('asset-store.list')" class="self-service-box">
                <font-awesome-icon
                    style="color: #2957a4; font-size: 2.5rem"
                    icon="fa-solid fa-box mb-5"
                />
                <p>{{ $t("Asset Store") }}</p>
            </div>
            <div @click="$router.push('/my-assets')" class="self-service-box">
                <font-awesome-icon
                    style="color: #2957a4; font-size: 2.5rem"
                    icon="fa-solid fa-money-check-dollar mb-5"
                />
                <p>{{ $t("My Assets") }}</p>
            </div>
            <div
                v-if="$can('personal-requests.list')"
                @click="$router.push('/personal-requests')"
                class="self-service-box"
            >
                <font-awesome-icon
                    style="color: #2957a4; font-size: 2.5rem"
                    icon="fa-solid fa-clipboard-question mb-5"
                />
                <p>{{ $t("Personal Request") }}</p>
            </div>
            <div
                v-if="$can('modify-my-data.list')"
                @click="$router.push('/personal-modification-processes')"
                class="self-service-box"
            >
                <font-awesome-icon
                    style="color: #2957a4; font-size: 2.5rem"
                    icon="fa-solid fa-database mb-5"
                />
                <p>{{ $t("Modify My Data") }}</p>
            </div>
            <div
                v-if="$can('access-to-personal-files.list')"
                class="self-service-box"
            >
                <font-awesome-icon
                    style="color: #2957a4; font-size: 2.5rem"
                    icon="fa-solid fa-file-shield mb-5"
                />
                <p>{{ $t("Access To Personal Files") }}</p>
            </div>
            <div v-if="$can('invest-request.list')" class="self-service-box">
                <font-awesome-icon
                    style="color: #2957a4; font-size: 2.5rem"
                    icon="fa-solid fa-money-bill-trend-up mb-5"
                />
                <p>{{ $t("Invest Request") }}</p>
            </div>
            <div
                v-if="$can('continuous-improvement-process.list')"
                @click="$router.push('/continuous-improvement-process')"
                class="self-service-box text-center"
            >
                <font-awesome-icon
                    style="color: #2957a4; font-size: 2.5rem"
                    icon="fa-solid fa-chart-line mb-5"
                />
                <p>
                    {{ $t("Continuous Improvement Process (CIP)") }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import Icon from "../../Components/Icon.vue";

export default {
    components: {
        Icon,
    },
    data() {
        return {};
    },
    methods: {},
};
</script>
