<template>
    <div class="card my-3">
        <div class="card-body">
            <div class="consulting-statistics-main-div">
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <!-- employee details -->

                        <div class="flex items-center w-full">
                            <div
                                :style="{
                                    backgroundImage:
                                        'url(' +
                                        (userProfilePictures?.[
                                            statistics?.employee?.userId
                                        ]?.profile_image ?? '') +
                                        ')',
                                }"
                                class="flex justify-center items-center relative mr-3"
                                style="
                                    background-position: center center;
                                    background-repeat: no-repeat;
                                    background-size: cover;
                                    height: 80px;
                                    width: 80px;
                                    border-radius: 50%;

                                    background-color: #2957a4;
                                    color: white;
                                    overflow: hidden;
                                "
                            >
                                <p
                                    v-if="
                                        isTeam
                                            ? statistics?.name
                                            : statistics?.employee?.name &&
                                              !userProfilePictures?.[
                                                  statistics?.employee?.userId
                                              ]
                                    "
                                    class="text-xl"
                                >
                                    {{
                                        getInitials(
                                            isTeam
                                                ? statistics?.name
                                                : statistics?.employee?.name ??
                                                      ""
                                        )
                                    }}
                                </p>
                            </div>
                            <h3 class="card-title">
                                {{
                                    isTeam
                                        ? statistics?.name
                                        : statistics?.employee?.name ?? ""
                                }}
                            </h3>
                            <!-- if isTeam then show the department and team lead -->
                        </div>
                        <div class="text-left">
                            <div v-if="isTeam">
                                <p class="text-gray-500">
                                    {{ $t("Department") }}:
                                    {{ statistics?.department ?? "" }}
                                </p>
                                <p class="text-gray-500">
                                    {{ $t("Team Lead") }}:
                                    {{ statistics?.teamLead ?? "" }}
                                </p>
                            </div>
                            <div class="" v-else>
                                <h3 class="card-title">
                                    {{ statistics?.employee?.jobTitle ?? "" }}
                                </h3>
                                <p class="text-gray-500">
                                    <span class="text-gray-400">{{
                                        $t("Personal Number") + ":"
                                    }}</span
                                    >&nbsp;
                                    {{
                                        statistics?.employee?.personalNumber ??
                                        ""
                                    }}
                                </p>
                                <p class="text-gray-500">
                                    {{ statistics?.employee?.location }}
                                </p>
                            </div>
                            <p class="text-gray-500">
                                <span class="text-gray-600"
                                    >{{ $t("Target Value Month") }}:&nbsp;</span
                                >{{ statistics?.targetValueMonth }}
                            </p>
                            <p class="text-gray-500">
                                <span class="text-gray-600"
                                    >{{ $t("Target Value") }}:&nbsp;</span
                                >{{
                                    typeof statistics?.targetValueUser ===
                                        "number" ||
                                    typeof statistics?.targetValueUser ===
                                        "string"
                                        ? parseFloat(
                                              statistics.targetValueUser
                                          ).toFixed(2)
                                        : 0
                                }}

                                {{ $t("hr(s)") }}
                            </p>
                            <p class="text-gray-500">
                                <span class="text-gray-600"
                                    >{{ $t("Real Working Time") }}:&nbsp;</span
                                >{{ statistics?.workingTime }} {{ $t("hr(s)") }}
                            </p>
                            <p class="text-gray-500">
                                <span class="text-gray-600"
                                    >{{
                                        $t("Expected Working Time")
                                    }}:&nbsp;</span
                                >{{ statistics?.expectedWorkingTime }}
                                {{ $t("hr(s)") }}
                            </p>
                        </div>
                    </div>
                    <div class="w-full">
                        <div>
                            <h3 class="card-title"
                                >{{ $t("Time Tracker Summary") }}:</h3
                            >
                            <apexchart
                                class="mt-5"
                                type="pie"
                                width="400"
                                :options="chartOptionsTimeTracker"
                                :series="seriesTimeTracker"
                            ></apexchart>
                        </div>
                    </div>
                </div>

                <div class="grid items-center grid-cols-3 gap-6 mt-5">
                    <div class="w-full">
                        <h3 class="card-title"
                            >{{ $t("Total Utilization Average") }}:</h3
                        >
                        <apexchart
                            class="mt-5"
                            type="radialBar"
                            width="400"
                            :options="chartOptionsUtilization"
                            :series="seriesUtilization"
                        ></apexchart>
                    </div>
                    <div class="w-full">
                        <h3 class="card-title">{{ $t("Target Value") }}:</h3>
                        <apexchart
                            class="mt-5"
                            type="radialBar"
                            width="400"
                            :options="chartOptionsUtilization"
                            :series="seriesTargetValue"
                        ></apexchart>
                    </div>
                    <div class="w-full">
                        <h3 class="card-title">{{ $t("Target Value Absolute") }}:</h3>
                        <apexchart
                            class="mt-5"
                            type="radialBar"
                            width="400"
                            :options="chartOptionsUtilization"
                            :series="seriesTargetValueAbsolute"
                        ></apexchart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import { mapGetters } from "vuex";

export default {
    props: {
        // checks if the statistics to be loaded are for a team
        // if this is true then we must modify how the employee details are shown
        isTeam: {
            type: Boolean,
            default: false,
        },
        // the statistics object contains all the information used in charts
        statistics: {
            type: Object,
            default: () => ({}),
            required: true,
        },
    },
    components: {
        apexchart: VueApexCharts,
    },
    methods: {
        /**
         * Grabs the initials from the 'name', and returns the converted string after uppercasing
         * @param {name} the string from which we extract initials from
         */
        getInitials(name) {
            const tokens = name?.split(" ");
            if (tokens)
                return `${tokens?.[0]?.[0] ?? ""}${
                    tokens?.[1]?.[0] ?? ""
                }`.toUpperCase();
            else return "";
        },
    },
    computed: {
        ...mapGetters("auth", ["userProfilePictures"]),
        // series data for the time tracker pie chart
        seriesTimeTracker() {
            return [
                this.statistics?.customerTime ?? 0,
                this.statistics?.kulanzTime ?? 0,
                this.statistics?.internalTime ?? 0,
            ];
        },
        // chart options for time tracker pie chart
        chartOptionsTimeTracker() {
            return {
                chart: {
                    width: 400,
                    type: "pie",
                },
                labels: [
                    this.$t("Customer Time"),
                    this.$t("Kulanz Time"),
                    this.$t("Internal Time"),
                ],
                plotOptions: {
                    pie: {
                        dataLabels: {
                            offset: -20,
                        },
                    },
                },
                dataLabels: {
                    enabled: true,
                    formatter: (val, { w, seriesIndex }) => {
                        // show the hours along with percentages in the chart
                        return [
                            val.toFixed(2) + "%",
                            (w?.config?.series?.[seriesIndex] ?? 0) +
                                " " +
                                this.$t("h"),
                        ];
                    },
                },
                legend: {
                    formatter: (seriesName, opts) => {
                        return [
                            seriesName,
                            " - ",
                            opts.w.globals.series[opts.seriesIndex] +
                                this.$t("h"),
                        ];
                    },
                },
                responsive: [
                    {
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 400,
                            },
                            legend: {},
                        },
                    },
                ],
            };
        },
        // series data for the total utilization average pie semi radial chart
        seriesUtilization() {
            // calculates utilization by dividing targetValue with the sum of customerTime and kulanzTime
            const utilization = this.statistics?.utilization;
            // check if the utilization is finite and not NaN, if the check fails then return 0
            return [
                isFinite(utilization) && !isNaN(utilization) ? utilization : 0,
            ];
        },
        seriesTargetValue() {
            const targetValue = this.statistics?.targetValue;
            return [
                isFinite(targetValue) && !isNaN(targetValue)
                    ? (+targetValue).toFixed(2)
                    : 0,
            ];
        },
        seriesTargetValueAbsolute() {
            const targetValueAbsolute = this.statistics?.targetValueAbsolute;
            return [
                isFinite(targetValueAbsolute) && !isNaN(targetValueAbsolute)
                    ? (+targetValueAbsolute).toFixed(2)
                    : 0,
            ];
        },
        // chart options for total utilization average pie semi radial chart
        chartOptionsUtilization() {
            return {
                chart: {
                    width: 400,
                    type: "radialBar",
                    offsetY: -20,
                    sparkline: {
                        enabled: true,
                    },
                },
                dataLabels: {
                    show: true,
                    formatter: (val, { w, seriesIndex }) => {
                        // show the hours along with percentages in the chart
                        return [
                            val.toFixed(2) + "%",
                            (w?.config?.series?.[seriesIndex] ?? 0) +
                                " " +
                                this.$t("h"),
                        ];
                    },
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#e7e7e7",
                            strokeWidth: "97%",
                            margin: 5, // margin is in pixels
                            dropShadow: {
                                enabled: true,
                                top: 2,
                                left: 0,
                                color: "#999",
                                opacity: 1,
                                blur: 2,
                            },
                        },
                        dataLabels: {
                            name: {
                                show: false,
                            },
                            value: {
                                offsetY: -2,
                                fontSize: "22px",
                            },
                        },
                    },
                },
                grid: {
                    padding: {
                        top: -10,
                    },
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shade: "light",
                        shadeIntensity: 0.4,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 53, 91],
                    },
                },
                labels: ["Average Results"],
            };
        },
    },
};
</script>
