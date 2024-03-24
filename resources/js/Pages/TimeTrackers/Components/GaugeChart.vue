<template>
    <div style="margin-left: 2.4rem;" class="absolute" id="gaugeArea"></div>
</template>

<script>
import * as GaugeChart from "https://unpkg.com/gauge-chart@next/dist/bundle.mjs";

export default {
    data() {
        return {
            enums: {
                one: "BS0",
                two: "BS1",
                three: "BS2",
                four: "BS3",
                five: "BS4",
            },
        };
    },
    props: {
        targetValueAbsoluteMonth: {
            type: Array,
            default: () => [],
        },
        targetValueMonth: {
            type: Number,
            default: 0,
        },
    },
    computed: {
        maxValue() {
            return Math.max(
                ...this.targetValueAbsoluteMonth.map(
                    (item) => +item.absoluteValue
                )
            );
        },
    },
    watch: {
        targetValueAbsoluteMonth() {
            this.renderChart();
        },
    },
    methods: {
        renderChart() {
            if (this.targetValueAbsoluteMonth.length) {
                // Element inside which you want to see the chart
                let element = document.querySelector("#gaugeArea");

                try {
                    element.removeChild(element.firstElementChild);
                } catch (e) {}

                let delimiters = [
                    ...this.targetValueAbsoluteMonth.map(
                        (item) => (+item.absoluteValue * 100) / this.maxValue
                    ),
                ].sort((a, b) => a - b);

                if (delimiters?.[delimiters.length - 1] == 100) {
                    delimiters[delimiters.length - 1] = 99;
                }
                let centralValue =
                    ((+this.targetValueMonth / this.maxValue) *
                        100 *
                        this.maxValue) /
                    100;
                // Properties of the gauge
                let gaugeOptions = {
                    centralLabel:
                        (isNaN(centralValue) || typeof centralValue !== "number"
                            ? 0
                            : centralValue
                        ).toFixed(2) + "%",
                    arcDelimiters: delimiters,
                    arcOverEffect: true,
                    hasNeedle: false,
                    rangeLabel: ["0%", this.maxValue + "%"],
                    arcLabels: this.targetValueAbsoluteMonth.map(
                        (item) => this.enums?.[item.level] ?? ""
                    ),
                };

                // Drawing and updating the chart
                GaugeChart.gaugeChart(element, 265, gaugeOptions);
            }
        },
    },
    mounted() {
        this.renderChart();
    },
};
</script>