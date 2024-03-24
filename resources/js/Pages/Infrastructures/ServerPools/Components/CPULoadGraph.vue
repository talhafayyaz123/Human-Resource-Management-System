<template>
    <div class="mt-5">
        <div class="w-full">
            <div class="w-full bg-white rounded-md shadow margin-bottom-3rem">
                <div class="flex flex-wrap">
                    <div class="w-full">
                        <div style="min-height: 400px">
                            <apexchart
                                class="mt-5"
                                type="line"
                                height="100%"
                                :options="chartOptions"
                                :series="series"
                            ></apexchart>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
    name: "CompanyHours",
    props: {
        label: {
            type: String,
            default: "",
        },
        statistics: {
            type: Array,
            default: [],
        },
        time: {
            type: Number,
            default: 0,
        },
    },
    components: {
        apexchart: VueApexCharts,
    },
    computed: {
        // lead channel series for chart
        series() {
            return [
                {
                    name: "CPU Usage",
                    data: this.statistics
                        .sort(function (a, b) {
                            return a.timestamp - b.timestamp;
                        })
                        .map((stat) => stat?.data?.cpu?.percent ?? ""),
                },
            ];
        },
        // lead channel chart options
        chartOptions() {
            return {
                chart: {
                    height: 350,
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
                grid: {
                    row: {
                        colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
                        opacity: 0.5,
                    },
                },
                xaxis: {
                    categories: this.statistics
                        .sort(function (a, b) {
                            return a.timestamp - b.timestamp;
                        })
                        .map((stat) => {
                            let dateTime = this.$dateFormatter(
                                this.formatDateLite(
                                    new Date(stat?.timestamp * 1000),
                                    true
                                ),
                                this.globalLanguage
                            );
                            const indexOfDelimiter = dateTime.indexOf(" ");
                            const date = dateTime.substring(
                                0,
                                indexOfDelimiter
                            );
                            const time = dateTime.substring(
                                indexOfDelimiter,
                                dateTime.length
                            );
                            return [date, time];
                        }),
                    tickAmount:
                        this.time == 0
                            ? undefined
                            : this.time / (this.time / 10),
                },
                yaxis: {
                    forceNiceScale: true,
                    labels: {
                        formatter: (value) => {
                            return value.toFixed(0) + "%";
                        },
                    },
                },
            };
        },
    },
};
</script>

<style scoped></style>
