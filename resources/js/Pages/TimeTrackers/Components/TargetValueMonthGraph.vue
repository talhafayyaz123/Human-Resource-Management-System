<template>
    <label class="font-bold" for="">{{ $t("Target Value Month") }}:</label>
    <apexchart
        class="mt-5"
        type="radialBar"
        width="350"
        height="250"
        :options="chartOptionsUtilization"
        :series="seriesTargetValue"
    ></apexchart>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        targetValueMonth: {
            type: Number,
            default: 0,
        },
    },
    computed: {
        seriesTargetValue() {
            const targetValue = this.targetValueMonth;
            return [
                isFinite(targetValue) && !isNaN(targetValue)
                    ? (+targetValue).toFixed(2)
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
                                fontSize: "25.5px",
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

<style scoped></style>
