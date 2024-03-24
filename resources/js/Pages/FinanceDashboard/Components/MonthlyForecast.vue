<template>
    <div>
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Service Forecast") }}</h3>
            </div>
            <div class="card-body">
                <span>
                    {{ $t("Average Money per Hour (weighted, without 0€/h)") }}:
                    {{ averageMoneyPerHourWeightedWithOut0 }}€
                </span>
                <br />
                <span>
                    {{ $t("Average Money per Hour (weighted)") }}:
                    {{ averageMoneyPerHourWeighted }}€
                </span>
                <br />
                <span>
                    {{ $t("Average Money per Hour (raw)") }}:
                    {{ averageMoneyPerHourRaw }}€
                </span>
                <div class="grid items-center grid-cols-2 gap-6 mt-6">
                    <div class="w-full">
                        <div class="form-group">
                            <label class="input-label"
                                ><span class="text-red-500">*&nbsp;</span
                                >{{ $t("Month") }}:</label
                            >
                            <datepicker
                                class="form-control"
                                :clearable="false"
                                :locale="globalLanguage"
                                :enable-time-picker="false"
                                auto-apply
                                :close-on-auto-apply="false"
                                v-model="date"
                                month-picker
                                @update:model-value="getMonthlyForecast"
                            />
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div style="height: 400px">
                        <apexchart
                            :key="globalLanguage"
                            class="mt-5 pb-5"
                            type="line"
                            height="100%"
                            :options="chartOptionsServiceForecast"
                            :series="seriesServiceForecast"
                        ></apexchart>
                    </div>
                </div>
                <div class="grid items-center grid-cols-2 gap-6 mt-6">
                    <div class="w-full">
                        <div class="form-group">
                            <SelectInput
                                v-model="sortBy"
                                :label="$t('Sort By')"
                            >
                                <option value="">{{ $t("None") }}</option>
                                <option value="desc">
                                    {{ $t("Highest To Lowest") }}
                                </option>
                                <option value="asc">
                                    {{ $t("Lowest To Highest") }}
                                </option>
                            </SelectInput>
                        </div>
                    </div>
                </div>
                <div class="mt-5 grid gap-3 grid-cols-[50%_50%]">
                    <CompanyHours
                        :statistics="sortedCompanyHourlyRateMonthly"
                        label="Money Per Hour"
                    />
                    <CompanyHours
                        :statistics="sortedCompanyHourlyRateOverall"
                        label="Overall This Month"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import CompanyHours from "./CompanyHours.vue";
import SelectInput from "@/Components/SelectInput.vue";

export default {
    data() {
        return {
            sortBy: "",
            companyHourlyRateMonthly: [],
            companyHourlyRateOverall: [],
            // the currently selected date
            date: {
                month: new Date().getMonth(),
                year: new Date().getFullYear(),
            },
            // contains the monthly forecast data
            timeTrackerMonthlyForecast: {},
            averageMoneyPerHour: 0,
        };
    },
    components: {
        apexchart: VueApexCharts,
        CompanyHours,
        SelectInput,
    },
    mounted() {
        // fetch the month forecast on mount
        this.getMonthlyForecast();
    },
    methods: {
        /**
         * hits the monthly forecast API and sets the timeTrackerMonthlyForecast
         */
        async getMonthlyForecast() {
            try {
                const response = await this.$store.dispatch(
                    "timeTrackers/timeTrackerMonthlyForecast",
                    {
                        date: `${this.date.year}-${this.date.month + 1}-01`,
                    }
                );
                this.timeTrackerMonthlyForecast = response?.data?.data ?? {};
                this.averageMoneyPerHourWeighted =
                    response?.data?.averageMoneyPerHourWeighted ?? 0;
                this.averageMoneyPerHourWeightedWithOut0 =
                    response?.data?.averageMoneyPerHourWeightedWithOut0 ?? 0;
                this.averageMoneyPerHourRaw =
                    response?.data?.averageMoneyPerHourRaw ?? 0;
                const companyHourlyRate = Object.values(
                    response?.data?.companyHourlyRate ?? {}
                );
                this.companyHourlyRateMonthly = companyHourlyRate.map(
                    (item) => {
                        return {
                            total: +item.hourly,
                            name: item.name,
                        };
                    }
                );
                this.companyHourlyRateOverall = companyHourlyRate.map(
                    (item) => {
                        return {
                            total: +item.overall,
                            name: item.name,
                        };
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },
    },
    computed: {
        sortedCompanyHourlyRateMonthly() {
            return !this.sortBy
                ? this.companyHourlyRateMonthly
                : [...this.companyHourlyRateMonthly].sort((a, b) => {
                      if (this.sortBy === "desc") return b.total - a.total;
                      else if (this.sortBy === "asc") return a.total - b.total;
                  });
        },
        sortedCompanyHourlyRateOverall() {
            return !this.sortBy
                ? this.companyHourlyRateOverall
                : [...this.companyHourlyRateOverall].sort((a, b) => {
                      if (this.sortBy === "desc") return b.total - a.total;
                      else if (this.sortBy === "asc") return a.total - b.total;
                  });
        },
        seriesServiceForecast() {
            let totalHoursWithoutForecast = []; // keeps the series for totalTime where 'isForecast' is set to false
            let totalHoursForecast = []; // keeps the series for totalTime where 'isForecast' is set to true
            let hourlyRateWithoutForecast = []; // keeps the series for totalHourlyRateTime where 'isForecast' is set to false
            let hourlyRateForecast = []; // keeps the series for totalHourlyRateTime where 'isForecast' is set to true
            // keeps track of the 'isForecast' of the last entry in the loop, initially it's value is set to the first entry
            let isForecastPrevious =
                Object.values(this.timeTrackerMonthlyForecast)[0]?.isForecast ??
                false;
            // loop over the timeTrackerMonthlyForecast data and insert the data in totalHoursWithoutForecast, totalHoursForecast
            Object.values(this.timeTrackerMonthlyForecast).forEach((item) => {
                // if the isForecast of the current and the last entry do not match then add the value to both totalHoursWithoutForecast and totalHoursForecast
                // we do this because we want both the line charts to appear as if they were one and want them to appear connected
                if (item.isForecast != isForecastPrevious) {
                    totalHoursWithoutForecast.push(item.totalTime);
                    totalHoursForecast.push(item.totalTime);
                    hourlyRateWithoutForecast.push(item.totalHourlyRateTime);
                    hourlyRateForecast.push(item.totalHourlyRateTime);
                } else {
                    // push the non forecast values to totalHoursWithoutForecast and remanining values can be null
                    totalHoursWithoutForecast.push(
                        !item.isForecast ? item.totalTime : null
                    );
                    // push the non forecast values to hourlyRateWithoutForecast and remanining values can be null
                    hourlyRateWithoutForecast.push(
                        !item.isForecast ? item.totalHourlyRateTime : null
                    );
                    // push the forecast values to totalHoursForecast and remanining values can be null
                    totalHoursForecast.push(
                        item.isForecast ? item.totalTime : null
                    );
                    // push the forecast values to totalHourlyRateTime and remanining values can be null
                    hourlyRateForecast.push(
                        item.isForecast ? item.totalHourlyRateTime : null
                    );
                }
                // update the isForecastPrevious to the isForecast value of current item
                isForecastPrevious = item.isForecast;
            });
            return [
                {
                    name: this.$t("Total Money"),
                    type: "area",
                    data: hourlyRateWithoutForecast,
                },
                {
                    name: this.$t("Total Money (Forecast)"),
                    type: "area",
                    data: hourlyRateForecast,
                },
                {
                    name: this.$t("Total Hours"),
                    type: "line",
                    data: totalHoursWithoutForecast, //set 'totalTime' without forecast
                },
                {
                    name: this.$t("Total Hours (Forecast)"),
                    type: "line",
                    data: totalHoursForecast, //set 'totalTime' with forecast
                },
            ];
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
        chartOptionsServiceForecast() {
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
                colors: ["#1297fb", "#1297fb", "#00e396", "#00e396"],
                labels: Object.keys(this.timeTrackerMonthlyForecast).map(
                    (date) => this.$dateFormatter(date, this.globalLanguage)
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
                yaxis: [
                    {
                        forceNiceScale: true,
                        title: {
                            text: "€ Euro",
                        },
                        min: Math.min(
                            ...Object.values(
                                this.timeTrackerMonthlyForecast
                            ).map((item) => item.totalHourlyRateTime)
                        ),
                        max: Math.max(
                            ...Object.values(
                                this.timeTrackerMonthlyForecast
                            ).map((item) => item.totalHourlyRateTime)
                        ),
                        labels: {
                            formatter: (value) => {
                                return this.$formatter(
                                    value,
                                    this.globalLanguage
                                );
                            },
                        },
                    },
                    {
                        forceNiceScale: true,
                        show: false,
                        title: {
                            text: "€ Euro",
                        },
                        min: Math.min(
                            ...Object.values(
                                this.timeTrackerMonthlyForecast
                            ).map((item) => item.totalHourlyRateTime)
                        ),
                        max: Math.max(
                            ...Object.values(
                                this.timeTrackerMonthlyForecast
                            ).map((item) => item.totalHourlyRateTime)
                        ),
                        labels: {
                            formatter: (value) => {
                                return this.$formatter(
                                    value,
                                    this.globalLanguage
                                );
                            },
                        },
                    },
                    {
                        forceNiceScale: true,
                        opposite: true,
                        title: {
                            text: this.$t("Hours"),
                        },
                        min: Math.min(
                            ...Object.values(
                                this.timeTrackerMonthlyForecast
                            ).map((item) => item.totalTime)
                        ),
                        max: Math.max(
                            ...Object.values(
                                this.timeTrackerMonthlyForecast
                            ).map((item) => item.totalTime)
                        ),
                    },
                    {
                        forceNiceScale: true,
                        show: false,
                        title: {
                            text: this.$t("Hours"),
                        },
                        min: Math.min(
                            ...Object.values(
                                this.timeTrackerMonthlyForecast
                            ).map((item) => item.totalTime)
                        ),
                        max: Math.max(
                            ...Object.values(
                                this.timeTrackerMonthlyForecast
                            ).map((item) => item.totalTime)
                        ),
                    },
                ],
                stroke: {
                    width: [5, 5, 5, 5],
                    colors: ["#1297fb", "#1297fb", "#00e396", "#00e396"],
                    curve: "straight",
                    dashArray: [0, 8, 0, 8],
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: [
                        {
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
                        {
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
                        {
                            formatter: (y) => {
                                if (typeof y !== "undefined" && y !== null) {
                                    return (
                                        y.toFixed(0) + " " + this.$t("hours")
                                    );
                                }
                                return y;
                            },
                        },
                        {
                            formatter: (y) => {
                                if (typeof y !== "undefined" && y !== null) {
                                    return (
                                        y.toFixed(0) + " " + this.$t("hours")
                                    );
                                }
                                return y;
                            },
                        },
                    ],
                },
            };
        },
    },
};
</script>

<style scoped></style>
