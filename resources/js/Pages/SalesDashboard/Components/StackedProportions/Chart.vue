<template>
    <apexchart
        class="mt-5 pb-5"
        type="bar"
        height="400"
        :options="chartOptionsProductProportions"
        :series="seriesProductProportions"
        @mounted="mounted"
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
            type: Object,
            default: () => ({}),
        },
        // dates or x-axis values for chart
        dates: {
            type: Array,
            default: () => [],
        },
        // current view of th chart
        filterBy: {
            type: String,
            default: "yearly",
        },
    },
    methods: {
        // triggered when chart is mounted
        mounted() {
            try {
                // get all the rect elements
                const rects = document.querySelectorAll(
                    ".apexcharts-data-labels rect"
                );
                // add 5 to the y property of the rect
                rects.forEach((rect) => {
                    rect.setAttribute("y", +rect.getAttribute("y") + 5);
                });
                // get all the text elements
                const texts = document.querySelectorAll(
                    ".apexcharts-data-labels text"
                );
                // add 5 to the y property of the text
                texts.forEach((text) => {
                    text.setAttribute("y", +text.getAttribute("y") + 5);
                });
            } catch (e) {
                console.log(e);
            }
        },
    },
    computed: {
        monthlyData() {
            return {
                1: this.$t("January"),
                2: this.$t("February"),
                3: this.$t("March"),
                4: this.$t("April"),
                5: this.$t("May"),
                6: this.$t("June"),
                7: this.$t("July"),
                8: this.$t("August"),
                9: this.$t("September"),
                10: this.$t("October"),
                11: this.$t("November"),
                12: this.$t("December"),
            };
        },
        // series for chart
        seriesProductProportions() {
            return [
                {
                    name: this.$t("License"),
                    data: this.series.license,
                },
                {
                    name: this.$t("Maintenance"),
                    data: this.series.maintenance,
                },
                {
                    name: this.$t("Service"),
                    data: this.series.service,
                },
                {
                    name: this.$t("Application"),
                    data: this.series.application,
                },
                {
                    name: "Hosting",
                    data: this.series.hosting,
                },
                {
                    name: "Cloud",
                    data: this.series.cloud,
                },
            ];
        },
        // chart options for product proportion chart
        chartOptionsProductProportions() {
            return {
                chart: {
                    type: "bar",
                    height: "100%",
                    stacked: true,
                    toolbar: {
                        show: true,
                    },
                    zoom: {
                        enabled: true,
                    },
                },
                responsive: [
                    {
                        breakpoint: 480,
                        options: {
                            legend: {
                                position: "bottom",
                                offsetX: -10,
                                offsetY: 0,
                            },
                        },
                    },
                ],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        borderRadius: 10,
                        columnWidth: "100%",
                        distrubuted: true,
                        dataLabels: {
                            position: "top",
                            total: {
                                enabled: true,
                                style: {
                                    fontSize: "8px",
                                    color: "white",
                                },
                            },
                        },
                    },
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: "8px",
                        marginTop: "8px",
                    },
                    background: {
                        enabled: true,
                        foreColor: "black",
                        padding: 4,
                        borderRadius: 2,
                        borderWidth: 1,
                        borderColor: "#fff",
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
                    formatter: (val, opt) => {
                        return this.$formatter(
                            typeof val === "number" ? val.toFixed(2) : val,
                            this.globalLanguage
                        );
                    },
                    offsetX: 0,
                },
                xaxis: {
                    categories: this.dates.map((date) =>
                        this.filterBy === "monthly"
                            ? this.$dateFormatter(date, this.globalLanguage)
                            : this.monthlyData[date]
                    ), //map the date,
                },
                yaxis: {
                    forceNiceScale: true,
                    labels: {
                        formatter: (value) => {
                            return this.$formatter(
                                value,
                                this.globalLanguage,
                                "EUR",
                                false,
                                2,
                                2
                            );
                        },
                    },
                },
                tooltip: {
                    y: {
                        formatter: (y) => {
                            if (typeof y !== "undefined" && y !== null) {
                                return this.$formatter(
                                    y.toFixed(0),
                                    this.globalLanguage,
                                    "EUR",
                                    false,
                                    2,
                                    2
                                );
                            }
                            return y;
                        },
                    },
                    x: {
                        show: true,
                        formatter: (x, { dataPointIndex, w }) => {
                            return (
                                x +
                                ": " +
                                this.$formatter(
                                    w.globals.stackedSeriesTotals[
                                        dataPointIndex
                                    ],
                                    this.globalLanguage,
                                    "EUR",
                                    false,
                                    2,
                                    2
                                )
                            );
                        },
                    },
                },
                legend: {
                    position: "bottom",
                    offsetY: 0,
                },
                fill: {
                    opacity: 1,
                },
            };
        },
    },
};
</script>

<style scoped></style>
