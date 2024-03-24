<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="header flex items-center justify-end mb-6">
            <search-filter
                :isFilter="false"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
            >
            </search-filter>
        </div>
        <div class="grid grid-cols-4 gap-4">
            <div class="card milage-card">
                <h3>Vehicles with low mileage</h3>
                <div class="flex items-center mt-4">
                    <div class="icon mr-3">
                        <CustomIcon name="downArrowIcon" />
                    </div>
                    <span class="">{{
                        mileageMonitoring.carsWithLessMileage
                    }}</span>
                </div>
            </div>
            <div class="card milage-card">
                <h3>{{
                    $t("Vehicles with more kilometers")
                }}</h3>
                <div class="flex items-center mt-4">
                    <div class="icon mr-3">
                        <CustomIcon name="upArrowIcon" />
                    </div>
                    <span class="">{{
                        mileageMonitoring.carsWithMoreKilometers
                    }}</span>
                </div>
            </div>
            <div class="card milage-card">
                <h3>{{
                    $t("Vehicles Ok")
                }}</h3>
                <div class="flex items-center mt-4">
                    <div class="icon mr-3">
                        <CustomIcon name="checkmilIcon" />
                    </div>
                    <span class="">{{
                        mileageMonitoring.carsWithExactKilometers
                    }}</span>
                </div>
            </div>
            <div class="card milage-card">
                <h3>{{
                    $t("Savings potential")
                }}</h3>
                <div class="flex items-center mt-4">
                    <span class="">{{
                            $formatter(
                                mileageMonitoring.totalSavingPotential,
                                globalLanguage
                            )
                        }}</span>
                </div>
            </div>
        </div>
        <div class="table-responsive mt-3">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th
                            style="width: 150px"
                            class="cursor-pointer"
                            @click="sort('licenceNumber', 'mileageMonitoring')"
                        >
                            {{ $t("Licence plate") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'licenceNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            class="cursor-pointer"
                            @click="sort('vim', 'mileageMonitoring')"
                        >
                            {{ $t("VIM") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'vim'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            class="cursor-pointer"
                            @click="sort('milesPerYear', 'mileageMonitoring')"
                        >
                            {{ $t("Contract") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'milesPerYear'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            class="cursor-pointer"
                            @click="
                                sort('totalMileageTillNow', 'mileageMonitoring')
                            "
                        >
                            {{ $t("Total Mileage") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'totalMileageTillNow'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="" style="width: 380px">
                            {{ $t("Extrapolation of kilometers") }}
                        </th>
                        <th
                            class="cursor-pointer"
                            @click="sort('totalPotential', 'mileageMonitoring')"
                        >
                            {{ $t("Saving Potential") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'totalPotential'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="fleetCar in mileageMonitoring?.data"
                        :key="fleetCar.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                    >
                        <td class="">
                            <div class="grid grid-col-1 gap-1">
                                <a
                                    href="javascript:void(0)"
                                    class="licence-tag"
                                >
                                    {{ fleetCar.licenceNumber }}
                                </a>
                            </div>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ fleetCar.vim }}
                            </a>
                        </td>

                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ fleetCar.milesPerYear }}
                                <div class="text-sm text-gray-500">
                                    for 12 Months
                                </div>
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ fleetCar.totalMileageTillNow }} Km
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                <div class="graph-bar-container">
                                    <div class="graph-origin"></div>

                                    <div
                                        class="graph-bar"
                                        :class="{
                                            green:
                                                fleetCar.totalPotential === 0,
                                        }"
                                        :style="computeBarStyles(fleetCar)"
                                    ></div>
                                </div>
                            </a>
                        </td>

                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    $formatter(
                                        fleetCar.totalPotential,
                                        globalLanguage
                                    )
                                }}
                            </a>
                        </td>
                    </tr>
                    <tr v-if="mileageMonitoring?.data?.length === 0">
                        <td class="text-center" colspan="9">
                            {{ $t("No fleet cars found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import SearchFilter from "@/Components/SearchFilter.vue";

import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
export default {
    components: { SearchFilter, PageHeader },
    computed: {
        ...mapGetters("mileageMonitoring", ["mileageMonitoring"]),
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Mileage Monitoring",
                    active: true,
                },
            ],
            form: {
                search: "",
                sortBy:
                    localStorage.getItem("sort_mileageMonitoring_column") ??
                    "licenceNumber",
                sortOrder:
                    localStorage.getItem("sort_mileageMonitoring_order") ??
                    "asc",
            },
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortOrder"(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch("mileageMonitoring/list", {
                    ...this.form,
                });
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            await this.$store.dispatch("mileageMonitoring/list", {
                ...this.form,
            });
        },
        computeBarStyles(fleetCar) {
            let totalPotential = fleetCar.totalPotential;
            const graphWidth = Math.abs(totalPotential) + "px";
            const graphOrigin = fleetCar.isGreen
                ? "calc(50% - " + totalPotential + "px)"
                : "calc(50% - 1px)";
            const graphDirection = fleetCar.isGreen ? "left" : "right";

            return {
                background: fleetCar.totalPotential === 0 ? "green" : "red",
                width: fleetCar.totalPotential === 0 ? "20px" : graphWidth,
                left:
                    fleetCar.totalPotential === 0
                        ? "calc(50% - 1px)"
                        : graphOrigin,
                right:
                    fleetCar.totalPotential === 0
                        ? "calc(50% - 1px)"
                        : graphOrigin,
                direction: graphDirection,
            };
        },
    },
};
</script>

<style lang="scss">
.milage-card {
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    height: 172px;
    h3 {
        color: #38414a;
        font-size: 18px;
        font-style: normal;
        font-weight: 700;
        line-height: 28.616px;
    }
    .icon {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        background: rgba(245, 150, 48, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: rgba(245, 150, 48, 0.15);
    }
    span {
        color: #38414a;
        font-size: 24.528px;
        font-style: normal;
        font-weight: 500;
        line-height: 28.616px;
    }
}
.graph-bar-container {
    position: relative;
    overflow: hidden;
    width: 350px;
    height: 30px;
    flex-shrink: 0;
    border-radius: 34px;
    background: rgba(245, 150, 48, 0.15);
}

.graph-bar {
    position: absolute;
    top: 50%;
    transform: translate(0, -50%);
    bottom: 0;
    height: 100%;
    background: red;
}

.graph-bar.green {
    background: green;
}

.graph-origin {
    position: absolute;
    left: 50%;
    top: 0;
    width: 1px;
    height: 30px;
    background: rgba(56, 65, 74, 0.5);
    transform: translateX(-50%);
    z-index: 1;
}

.licence-tag {
    border: 0.461px solid #1c1e1d;
    box-shadow: -0.461px -0.461px 0.461px 0px rgba(0, 0, 0, 0.25),
        0.461px 0.461px 0.461px 0px rgba(0, 0, 0, 0.25);
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    background: #e6ebf1;
    color: #181617;
    text-shadow: 0px 0.461px 0.461px rgba(0, 0, 0, 0.25),
        0px 0.461px 0.461px rgba(0, 0, 0, 0.25);
    font-size: 14px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    letter-spacing: 0.8px;
    text-align: center;
}
</style>
