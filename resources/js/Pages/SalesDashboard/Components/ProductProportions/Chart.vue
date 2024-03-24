<template>
    <apexchart
        class="mt-5 pb-5"
        type="pie"
        height="400"
        :options="chartOptionsProductProportions"
        :series="seriesProductProportions"
    ></apexchart>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
    name: "ProductProportionsChart",
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        // the series data for chart
        series: {
            type: Array,
            default: () => [],
        },
    },
    computed: {
        // series for chart
        seriesProductProportions() {
            const series = this.series.map((stat) => +stat.total);
            return series.length ? series : [1];
        },
        // chart options for product proportion chart
        chartOptionsProductProportions() {
            const labels = this.series.map((stat) => stat.type);
            return {
                chart: {
                    height: "100%",
                    type: "pie",
                },
                dataLabels: {
                    enabled: true,
                    formatter: (val) => {
                        return [val.toFixed(2) + "%"];
                    },
                },
                labels: labels.length
                    ? labels.map((label) => this.$t(label))
                    : [this.$t("No Data")],
                tooltip: {
                    y: {
                        formatter: (val) => {
                            return labels.length
                                ? this.$t(val)
                                : this.$t("Empty");
                        },
                    },
                },
                legend: {
                    width: 200,
                    position: "bottom",
                },
                xaxis: {
                    categories: labels,
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            offset: -20,
                        },
                    },
                },
            };
        },
    },
};
</script>

<style scoped></style>
