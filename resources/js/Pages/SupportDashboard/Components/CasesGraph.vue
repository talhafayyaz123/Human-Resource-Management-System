<template>
    <div class="mt-2  mx-2">
        <p class="text-center">{{ $t("Cases active or closed < IW") }}</p>
        <apexchart
            class="mt-5"
            type="bar"
            height="260"
            :options="chartOptions"
            :series="series"
        ></apexchart>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
    props: {
        casesGraphData: {
            type: Object,
            required: true,
            default: {},
        },
    },
    components: {
        apexchart: VueApexCharts,
    },
    computed: {
        chartOptions() {
            return {
                chart: {
                    type: "bar",
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "55%",
                        endingShape: "rounded",
                    },
                },
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ["transparent"],
                },
                xaxis: {
                    categories: Object.keys(this.casesGraphData),
                },
                fill: {
                    opacity: 1,
                },
            };
        },
        series() {
            return [
                {
                    data: Object.values(this.casesGraphData),
                },
            ];
        },
    },
};
</script>

<style scoped></style>
