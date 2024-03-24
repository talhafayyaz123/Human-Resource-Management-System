<template>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">
                {{ $t("Offers") }}/{{ $t("Incoming Orders") }}
            </h3>
        </div>
        <div class="card-body">
            <div class="w-full">
                <div style="height: 400px">
                    <apexchart
                        :key="globalLanguage"
                        class="mt-5 pb-5"
                        type="line"
                        height="100%"
                        :options="chartOptionsOffers"
                        :series="seriesOffers"
                    ></apexchart>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import mainMixin from "../Mixins/mainMixin";

export default {
    name: "OffersStatistics",
    mixins: [mainMixin],
    components: {
        apexchart: VueApexCharts,
    },
    data() {
        return {
            offersStatistics: {},
        };
    },
    methods: {
        /**
         * fetch the offers statistics
         */
        async fetchData() {
            // get the offers statistics
            try {
                const response = await this.$store.dispatch(
                    "saleDashboard/offersStatistics",
                    {
                        type: this.filterBy, // type of stats to be received
                        // depending on type the date sent is modifed
                        date: `${
                            this.filterBy === "month"
                                ? this.month.year
                                : this.year
                        }-${this.month.month + 1}-01`,
                        companyId: this.companyId,
                    }
                );
                this.offersStatistics = response?.data?.data ?? {};
                // when offer statistics is fetched, then use emit the offer statistics so that they can be used in stacked chart
                this.$emitter.emit("offer-statistics", this.offersStatistics);
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
        toolbarOptions() {
            if (this.globalLanguage === "de") {
                return {
                    name: "de",
                    options: {
                        toolbar: {
                            exportToSVG: "SVG speichern",
                            exportToPNG: "PNG speichern",
                            exportToCSV: "CSV speichern",
                            menu: "Menü",
                            selection: "Auswahl",
                            selectionZoom: "Auswahl vergrößern",
                            zoomIn: "Vergrößern",
                            zoomOut: "Verkleinern",
                            pan: "Verschieben",
                            reset: "Zoom zurücksetzen",
                        },
                    },
                };
            } else {
                return {
                    name: "en",
                    options: {
                        toolbar: {
                            exportToSVG: "Download SVG",
                            exportToPNG: "Download PNG",
                            exportToCSV: "Download CSV",
                            menu: "Menu",
                            selection: "Selection",
                            selectionZoom: "Selection Zoom",
                            zoomIn: "Zoom In",
                            zoomOut: "Zoom Out",
                            pan: "Panning",
                            reset: "Reset Zoom",
                        },
                    },
                };
            }
        },
        // offers series for chart
        seriesOffers() {
            return [
                {
                    name: this.$t("Offers"),
                    type: "area",
                    data: Object.values(this.offersStatistics).map((item) =>
                        item.nettoTotalOffers.toFixed(0)
                    ),
                },
                {
                    name: this.$t("Orders"),
                    type: "line",
                    data: Object.values(this.offersStatistics).map((item) =>
                        item.nettoTotalOrders.toFixed(0)
                    ),
                },
            ];
        },
        // offers chart options
        chartOptionsOffers() {
            return {
                chart: {
                    height: 350,
                    type: "line",
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset:
                                true |
                                '<img src="/static/icons/reset.png" width="20">',
                        },
                    },
                    locales: [this.toolbarOptions],
                    defaultLocale: this.globalLanguage,
                },
                fill: {
                    type: "solid",
                },
                colors: ["#1297fb", "#00e396"],
                labels: Object.keys(this.offersStatistics).map((date) =>
                    this.filterBy === "monthly"
                        ? this.$dateFormatter(date, this.globalLanguage)
                        : this.monthlyData[date]
                ), //map the date
                markers: {
                    size: 0,
                },
                xaxis: {
                    labels: {
                        show: true,
                        rotate: 0,
                    },
                },
                yaxis: {
                    forceNiceScale: true,
                    title: {
                        text: "€ Euro",
                    },
                    labels: {
                        formatter: (value) => {
                            return this.$formatter(value, this.globalLanguage);
                        },
                    },
                },
                stroke: {
                    width: [5, 5],
                    colors: ["#1297fb", "#00e396"],
                    curve: "straight",
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: (y) => {
                            if (typeof y !== "undefined" && y !== null) {
                                return this.$formatter(
                                    y.toFixed(0),
                                    this.globalLanguage
                                );
                            }
                            return y;
                        },
                    },
                },
            };
        },
    },
};
</script>

<style scoped></style>
