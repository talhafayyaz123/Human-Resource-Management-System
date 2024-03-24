<template>
    <div>
        <div class="w-full">
            <h3
                class="card-title"
            >
                {{ $t(label) }}
            </h3>
            <div class="w-full bg-white rounded-md shadow margin-bottom-3rem">
                <div class="flex flex-wrap pb-8">
                    <div class="w-full">
                        <div style="min-height: 800px">
                            <apexchart
                                class="mt-5 pb-5"
                                type="bar"
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
    },
    components: {
        apexchart: VueApexCharts,
    },
    computed: {
        // lead channel series for chart
        series() {
            return [
                {
                    data: this.statistics.map((item) => item.total),
                },
            ];
        },
        // lead channel chart options
        chartOptions() {
            return {
                chart: {
                    type: "bar",
                },
                dataLabels: {
                    enabled: true,
                    offsetX: 0,
                    background: {
                        enabled: true,
                        foreColor: "black",
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: "black",
                        opacity: 0.9,
                        dropShadow: {
                            enabled: false,
                            top: 1,
                            left: 1,
                            blur: 1,
                            color: "#000",
                            opacity: 0.45,
                        },
                    },
                    formatter: (val) => {
                        return this.$formatter(
                            val,
                            this.globalLanguage,
                            "EUR",
                            false,
                            2,
                            2
                        );
                    },
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                    },
                },
                tooltip: {
                    y: {
                        formatter: (val) => {
                            return this.$formatter(
                                val,
                                this.globalLanguage,
                                "EUR",
                                false,
                                2,
                                2
                            );
                        },
                    },
                },
                yaxis: {
                    forceNiceScale: true,
                },
                xaxis: {
                    categories: this.statistics.map((item) => item.name),
                },
            };
        },
    },
};
</script>

<style scoped></style>
