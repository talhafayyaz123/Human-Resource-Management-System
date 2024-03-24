<template>
    <div
        class="grid bg-white rounded-md shadow overflow-hidden margin-bottom-3rem gap-2"
        style="grid-template-columns: 10% 25% 25% 40%"
    >
        <div class="p-2">
            <div class="p-2">
                <label for="">License</label>
                <p style="font-size: 1.5rem">
                    {{
                        $formatter(
                            salePriceTotalSum,
                            globalLanguage,
                            "EUR",
                            false, // currency
                            2, // minimum fraction digits
                            2 // maximum fraction digits
                        )
                    }}
                </p>
            </div>
            <hr />
            <div class="p-2">
                <label for="">Maintenance</label>
                <p style="font-size: 1.5rem">
                    {{
                        $formatter(
                            maintenanceRateTotalSum,
                            globalLanguage,
                            "EUR",
                            false, // currency
                            2, // minimum fraction digits
                            2 // maximum fraction digits
                        )
                    }}
                </p>
            </div>
            <hr />
            <div class="p-2">
                <label for="">Lifetime</label>
                <p style="font-size: 1.5rem">
                    {{
                        $formatter(
                            netTotalInvoices,
                            globalLanguage,
                            "EUR",
                            false, // currency
                            2, // minimum fraction digits
                            2 // maximum fraction digits
                        )
                    }}
                </p>
            </div>
        </div>
        <div class="p-4">
            <label for="">Expansion</label>
            <apexchart
                class="mt-5"
                type="radialBar"
                :options="chartOptionsExpansion"
                :series="seriesExpansion"
            ></apexchart>
        </div>
        <div class="p-2">
            <div>
                <label for="">Software</label>
                <apexchart
                    class="mt-5"
                    type="pie"
                    width="300"
                    :options="chartOptionsSoftware"
                    :series="seriesSoftware"
                ></apexchart>
            </div>
            <div v-if="false">
                <label for="">Business Unit</label>
                <apexchart
                    class="mt-5"
                    type="pie"
                    width="300"
                    :options="chartOptionsBusinessUnit"
                    :series="seriesBusinessUnit"
                ></apexchart>
            </div>
        </div>
        <div class="p-2">
            <label for="">License Timeline</label>
            <div
                class="grid place-items-center mt-5"
                style="
                    grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
                "
            >
                <p
                    v-for="(year, index) in Object.keys(
                        licenseTimelineStatisticsByYear
                    )"
                    :key="'year-' + index"
                    @click="selectedYear = year"
                    :class="[
                        'text-sm',
                        'px-2',
                        'cursor-pointer',
                        'border-r-2 border-gray-500',
                        selectedYear == year ? 'font-bold' : 'text-gray-600',
                    ]"
                >
                    {{ year }}
                </p>
            </div>
            <apexchart
                type="line"
                :options="chartOptionsLicenseTimeline"
                :series="seriesLicenseTimeline"
                width="90%"
            ></apexchart>
        </div>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import SelectInput from "../../Components/SelectInput.vue";

export default {
    props: {
        netTotalInvoices: {
            type: Number,
            default: 0,
        },
        maintenanceRateTotalSum: {
            type: Number,
            default: 0,
        },
        salePriceTotalSum: {
            type: Number,
            default: 0,
        },
        softwareStatistics: {
            type: Array,
            default: [],
        },
        licenseTimelineStatisticsByYear: {
            type: Object,
            default: () => ({}),
        },
    },
    components: {
        apexchart: VueApexCharts,
        SelectInput,
    },
    computed: {
        seriesSoftware() {
            return this.softwareStatistics.map((stat) => stat.count);
        },
        chartOptionsSoftware() {
            return {
                chart: {
                    width: 300,
                    type: "pie",
                },
                labels: this.softwareStatistics.map((stat) => stat.name),
                responsive: [
                    {
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200,
                            },
                            legend: {
                                position: "bottom",
                            },
                        },
                    },
                ],
            };
        },
        seriesLicenseTimeline() {
            let filteredStatisticsByCurrentMonth = [];
            // filter the licenseTimelineStatisticsByYear based on the currentMonth. By filter we mean that the months after the currentMonth must be set to null
            for (let key in this.licenseTimelineStatisticsByYear?.[
                this.selectedYear
            ] ?? {}) {
                if (
                    key <= this.currentMonth ||
                    this.selectedYear < new Date().getFullYear() // if selectedYear is in the past then the month check should be false
                )
                    filteredStatisticsByCurrentMonth.push(
                        this.licenseTimelineStatisticsByYear[this.selectedYear][
                            key
                        ]
                    );
                else {
                    filteredStatisticsByCurrentMonth.push(null);
                }
            }
            return [
                {
                    name: "Sale Price",
                    data: filteredStatisticsByCurrentMonth.map(
                        (item) => item?.salePrice ?? null
                    ),
                },
                {
                    name: "Maintenance Price",
                    data: filteredStatisticsByCurrentMonth.map(
                        (item) => item?.maintenancePrice ?? null
                    ),
                },
            ];
        },
        chartOptionsLicenseTimeline() {
            return {
                chart: {
                    type: "line",
                    zoom: {
                        enabled: false,
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: "straight",
                },
                title: {
                    text: "",
                    align: "left",
                },
                grid: {
                    row: {
                        colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
                        opacity: 0.5,
                    },
                },
                colors: ["#2957a4", "#f59630"],
                xaxis: {
                    labels: {
                        rotate: 0,
                    },
                    categories: Object.keys(
                        this.licenseTimelineStatisticsByYear?.[
                            this.selectedYear
                        ] ?? {}
                    ).map((month) => this.monthNumbers[month]), // get month names array from "licenseTimelineStatisticsByYear" and map the month number to month name using monthNumbers object
                },
                yaxis: {
                    labels: {
                        formatter: (value) => {
                            return this.$formatter(value, this.globalLanguage);
                        },
                    },
                },
            };
        },
    },
    data() {
        return {
            // month numbers with month names as value
            monthNumbers: {
                1: "jan",
                2: "feb",
                3: "mar",
                4: "apr",
                5: "may",
                6: "jun",
                7: "jul",
                8: "aug",
                9: "sep",
                10: "oct",
                11: "nov",
                12: "dec",
            },
            currentMonth: new Date().getMonth() + 1, // current month
            selectedYear: new Date().getFullYear(), // current year
            seriesExpansion: [76],
            chartOptionsExpansion: {
                chart: {
                    type: "radialBar",
                    offsetY: -20,
                    sparkline: {
                        enabled: true,
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
                labels: ["Expansion"],
            },
            seriesBusinessUnit: [44, 55, 4],
            chartOptionsBusinessUnit: {
                chart: {
                    width: 300,
                    type: "pie",
                },
                labels: ["ELO", "Individual"],
                responsive: [
                    {
                        breakpoint: 480,
                        options: {
                            chart: {
                                width: 200,
                            },
                            legend: {
                                position: "bottom",
                            },
                        },
                    },
                ],
            },
        };
    },
};
</script>

<style scoped>
:deep(tspan) {
    text-transform: capitalize;
}
</style>
