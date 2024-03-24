<template>
    <div>
        <h1 class="header mb-8 text-3xl font-bold secondary-color">
            <router-link class="secondary-color" to="/travel-expenses">{{
                $t("Travel Expenses")
            }}</router-link>
            <span class="secondary-color font-medium">/</span>
            <span class="text-color">{{ $t("Show") }}</span>
        </h1>

        <div
            class="flex justify-between mb-4 border-b border-gray-200 dark:border-gray-700"
        >
            <div class="flex flex-wrap mx-auto">
                <a
                    @click="activeTab = 'request-details'"
                    :class="
                        activeTab === 'request-details'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("Request Details") }}
                </a>
                <a
                    @click="activeTab = 'bill'"
                    :class="
                        activeTab === 'bill' ? activeClasses : inactiveClasses
                    "
                >
                    {{ $t("Bills") }}
                </a>
                <a
                    @click="activeTab = 'transportation'"
                    :class="
                        activeTab === 'transportation'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("Private Transportation") }}
                </a>
                <a
                    @click="activeTab = 'day'"
                    :class="
                        activeTab === 'day' ? activeClasses : inactiveClasses
                    "
                >
                    {{ $t("Day Specifications") }}
                </a>
            </div>
        </div>
        <div id="myTabContent">
            <div
                v-show="activeTab === 'request-details'"
                class=""
                id="request-details"
                role="tabpanel"
                aria-labelledby="request-details-tab"
            >
                <RequestDetails :travelExpenseDetail="travelExpenseData" />
            </div>
            <div
                v-show="activeTab === 'bill'"
                class=""
                id="bill"
                role="tabpanel"
                aria-labelledby="bill-tab"
            >
                <Bill :show="true"/>
            </div>
            <div
                v-show="activeTab === 'transportation'"
                class="p-4"
                id="transportation"
                role="tabpanel"
                aria-labelledby="transportation-tab"
            >
                <Transportation
                    :transportationData="transportationData"
                    :errors="errors"
                    :show="true"
                />
            </div>
            <div
                v-show="activeTab === 'day'"
                class="p-4"
                id="day"
                role="tabpanel"
                aria-labelledby="day-tab"
            >
                <DaySpecification
                    :daySpecificationData="daySpecificationData"
                    :errors="errors"
                    :show="true"
                />
            </div>
        </div>
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import RequestDetails from "./RequestDetails.vue";
import Bill from "./Bill.vue";
import Transportation from "./Transportation.vue";
import DaySpecification from "./DaySpecification.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("travelExpenseRequestType", ["requestTypes"]),
    },
    components: {
        LoadingButton,
        RequestDetails,
        Bill,
        Transportation,
        DaySpecification,
    },
    data() {
        return {
            activeClasses:
                "cursor-pointer inline-flex items-center justify-center w-1/2 py-3 font-medium leading-none tracking-wider text-indigo-500 bg-gray-100 border-b-2 border-indigo-500 rounded-t sm:px-6 sm:w-auto sm:justify-start title-font",
            inactiveClasses:
                "cursor-pointer inline-flex items-center justify-center w-1/2 py-3 font-medium leading-none tracking-wider border-b-2 border-gray-200 sm:px-6 sm:w-auto sm:justify-start title-font hover:text-gray-900",
            activeTab: "request-details",
            travelExpenseData: [],
            transportationData: [
                {
                    drivenKilometers: "",
                    licenseNumber: "",
                },
            ],
            daySpecificationData: [
                {
                    active: false,
                    date: "18-6-2018",
                    breakfast: false,
                    lunch: false,
                    dinner: false,
                    fromTime: "",
                    toTime: "",
                    meal: "",
                },
            ],
        };
    },
    async mounted() {
        await this.refresh();
    },
    methods: {
        /**
         * get job daya by user
         */
        async fetchUserJob() {
            try {
                // Fetch the profile response and assign it to the data property
                this.profileResponse = await this.$store.dispatch(
                    "userProfile/showJobByUser",
                    this.user.id
                );
            } catch (e) {
                console.log(e);
            }
        },
        async refresh() {
            try {
                await this.$store.dispatch("travelExpenseRequestType/list", {
                    perPage: 10,
                    page: 1,
                });
                const response = await this.$store.dispatch(
                    "travelExpense/show",
                    this.$route.params.id
                );
                this.travelExpenseData = response?.data?.data ?? {};
                let requestType =
                    this.requestTypes?.data?.find(
                        (type) => type.id == this.travelExpenseData.requestType
                    ) ?? "";
                if (!!requestType)
                    this.travelExpenseData["requestTypeName"] =
                        requestType.name;
                if (this.user.id) {
                    this.fetchUserJob();
                }
            } catch (e) {
            } finally {
                this.isLoaded = true;
            }
        },
    },
};
</script>

<style scoped>
.table-layout-fixed {
    table-layout: fixed;
}

.disabled {
    color: lightgray;
    cursor: not-allowed;
}
</style>
