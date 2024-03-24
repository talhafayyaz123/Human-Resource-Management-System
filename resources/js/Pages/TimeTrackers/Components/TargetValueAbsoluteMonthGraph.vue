<template>
    <label class="font-bold" for=""
        >{{ $t("Target Value Absolute Month") }}:</label
    >
    <div
        v-if="targetValueAbsoluteMonth?.length"
        class="relative"
        style="min-height: 170px"
    >
        <apexchart
            class="mt-5 absolute"
            type="donut"
            width="385"
            :options="chartOptionsUtilization"
            :series="seriesTargetValue"
        ></apexchart>
        <p style="top: 80%; left: 42%" class="absolute text-xl">
            {{
                typeof totalMonthPercentage === "number"
                    ? totalMonthPercentage.toFixed(2)
                    : 0
            }}%
        </p>
        <p style="top: 95%; left: 42%" class="absolute text-xl">
            {{
                typeof totalMonthValue === "number"
                    ? totalMonthValue.toFixed(2)
                    : 0
            }}h
        </p>
    </div>
    <div v-else>
        <p>{{ $t("No Data") }}</p>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";

export default {
    data() {
        return {
            levelEnums: {
                one: 1,
                two: 2,
                three: 3,
                four: 4,
                five: 5,
            },
        };
    },
    components: {
        apexchart: VueApexCharts,
    },
    props: {
        targetValueAbsoluteMonth: {
            type: Array,
            default: [],
        },
    },
    computed: {
        totalMonthPercentage() {
            return this.targetValueAbsoluteMonth.reduce(
                (acc, curr) => (acc += +curr.absoluteValue),
                0
            );
        },
        totalMonthValue() {
            return this.targetValueAbsoluteMonth.reduce(
                (acc, curr) => (acc += +curr.month),
                0
            );
        },
        seriesTargetValue() {
            let series = this.targetValueAbsoluteMonth.map((item) => {
                return +item.absoluteValue;
            });
            return series;
        },
        // chart options for total utilization average pie semi radial chart
        chartOptionsUtilization() {
            return {
                chart: {
                    width: 400,
                    type: "donut",
                },
                grid: {
                    show: false,
                    padding: {
                        left: 0,
                        right: 0,
                        botton: 0,
                    },
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: "70%",
                        },
                        customScale: 0.9,
                        startAngle: -90,
                        endAngle: 90,
                        dataLabels: {
                            offsetY: -2,
                        },
                    },
                },

                dataLabels: {
                    enabled: true,
                    formatter: (val, opts) => {
                        const item =
                            this.targetValueAbsoluteMonth[
                                opts?.seriesIndex ?? 0
                            ];
                        return (
                            "BS" +
                            this.levelEnums[item.level] +
                            ": " +
                            item?.absoluteValue +
                            "%"
                        );
                    },
                },
                legend: {
                    show: false,
                },
            };
        },
    },
};
</script>

<style scoped></style>
