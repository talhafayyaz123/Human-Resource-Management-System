<template>
    <PageHeader :items="breadcrumbItems" />
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Time Tracker Statistics") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-4 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <DateInput
                            v-model="timeTrackerFilters.startDate"
                            class=""
                            :label="$t('Start Date')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <DateInput
                            v-model="timeTrackerFilters.endDate"
                            class=""
                            :label="$t('End Date')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <SelectInput
                            v-model="timeTrackerFilters.type"
                            :label="$t('Type')"
                            class=""
                        >
                            <option value="">{{ $t("All") }}</option>
                            <option :value="String.raw`App\Models\Task`">
                                {{ $t("Task") }}
                            </option>
                            <option
                                :value="String.raw`App\Models\TicketComment`"
                            >
                                {{ $t("Ticket") }}
                            </option>
                            <option
                                :value="
                                    String.raw`App\Models\ApplicationManagementService`
                                "
                            >
                                {{ $t("AMS") }}
                            </option>
                            <option value="newEntry">
                                {{ $t("New Entry") }}
                            </option>
                        </SelectInput>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <SelectInput
                            v-model="timeTrackerFilters.status"
                            :label="$t('Status')"
                            class=""
                        >
                            <option value="">{{ $t("All") }}</option>
                            <option value="new">{{ $t("New") }}</option>
                            <option value="pr">
                                {{ $t("Performance Record") }}
                            </option>
                            <option value="billed">{{ $t("Billed") }}</option>
                        </SelectInput>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <SelectInput
                            v-model="timeTrackerFilters.invoiceStatus"
                            :label="$t('Invoice Status')"
                            class=""
                        >
                            <option value="">{{ $t("All") }}</option>
                            <option value="draft">{{ $t("Draft") }}</option>
                            <option value="approved">
                                {{ $t("Approved") }}
                            </option>
                            <option value="sent">{{ $t("Sent") }}</option>
                            <option value="paid">{{ $t("Paid") }}</option>
                            <option value="warning level 1">
                                {{ $t("Warning Level 1") }}
                            </option>
                            <option value="warning level 2">
                                {{ $t("Warning Level 2") }}
                            </option>
                            <option value="warning level 3">
                                {{ $t("Warning Level 3") }}
                            </option>
                        </SelectInput>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            class=""
                            :required="true"
                            :showLabels="false"
                            v-model="timeTrackerFilters.person"
                            :options="users"
                            :multiple="true"
                            :textLabel="$t('Person')"
                            label="first_name"
                            trackBy="id"
                            moduleName="auth"
                            :search-param-name="'search_string'"
                            :customLabel="customLabel"
                        >
                            <template #beforeList>
                                <div
                                    class="grid p-2 gap-2"
                                    style="grid-template-columns: 25% 25% 50%"
                                >
                                    <p class="font-bold">
                                        {{ $t("First Name") }}
                                    </p>
                                    <p class="font-bold">
                                        {{ $t("Last Name") }}
                                    </p>
                                    <p class="font-bold">{{ $t("Email") }}</p>
                                </div>
                                <hr />
                            </template>
                            <template #singleLabel="{ props }">
                                <p>
                                    {{ props.option.first_name }}
                                    {{ props.option.last_name }}
                                </p>
                            </template>
                            <template #option="{ props }">
                                <div
                                    class="grid"
                                    style="grid-template-columns: 25% 25% 50%"
                                >
                                    <p class="overflow-text-users-table">
                                        {{ props.option.first_name }}
                                    </p>
                                    <p class="overflow-text-users-table">
                                        {{ props.option.last_name }}
                                    </p>
                                    <p class="overflow-text-users-table">
                                        {{ props.option.email }}
                                    </p>
                                </div>
                            </template>
                        </MultiSelectInput>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            class=""
                            :textLabel="$t('Company')"
                            v-model="timeTrackerFilters.companyId"
                            :options="companies.data"
                            :multiple="false"
                            label="companyName"
                            trackBy="id"
                            moduleName="companies"
                        ></MultiSelectInput>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Time Tracker Statistics") }}</h3>
        </div>
        <div class="card-body">
            <div class="flex flex-wrap">
                <div class="w-full lg:w-1/3">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <label class="mr-2" for="pie">{{
                                    $t("Pie")
                                }}</label>
                                <input
                                    id="pie"
                                    name="chart-type"
                                    type="radio"
                                    v-model="timeTrackerChartType"
                                    value="pie"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <label class="mr-2" for="gross">{{
                                    $t("Bar")
                                }}</label>
                                <input
                                    id="bar"
                                    name="chart-type"
                                    type="radio"
                                    v-model="timeTrackerChartType"
                                    value="bar"
                                />
                            </div>
                        </div>
                    </div>

                    <div style="height: auto">
                        <apexchart
                            :key="timeTrackerChartType"
                            class="mt-5 pb-5"
                            :type="timeTrackerChartType"
                            height="600"
                            :options="chartOptionsTimeTracker"
                            :series="seriesTimeTracker"
                            @dataPointSelection="dataPointSelectedTimeTracker"
                        ></apexchart>
                    </div>
                    <div
                        style="border-top: 2px solid lightgray"
                        class="flex justify-center items-center flex-col py-8"
                    >
                        <h1 class="font-medium text-5xl">
                            {{
                                $formatter(
                                    timeTrackerTotalTemp,
                                    globalLanguage,
                                    "EUR",
                                    true, // not a currency
                                    2, // minimum fraction digits
                                    2 // maximum fraction digits
                                )
                            }}
                            {{ $t("hours") }}
                        </h1>
                        <p class="mt-2">{{ $t("Total Time") }}</p>
                    </div>
                    <div
                        style="border-top: 2px solid lightgray"
                        class="flex justify-center items-center flex-col py-8"
                    >
                        <h1 class="font-medium text-5xl">
                            {{
                                $formatter(
                                    timeTrackerKulanzTime,
                                    globalLanguage,
                                    "EUR",
                                    true, // not a currency
                                    2, // minimum fraction digits
                                    2 // maximum fraction digits
                                )
                            }}
                            {{ $t("hours") }}
                        </h1>
                        <p class="mt-2">{{ $t("Kulanz Time") }}</p>
                    </div>
                </div>
                <div class="w-full lg:w-2/3">
                    <!-- time tracker listing -->
                    <TimeTrackerTable
                        @orderTimeTrackerTable="dashboardTimeTrackerSorting"
                        @changePage="fetchTimeTrackerStatistics"
                        :timeTrackerListing="timeTrackerListing"
                    />
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Invoice Statistics") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-4 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <SelectInput
                            v-model="invoiceFilters.month"
                            :label="$t('Month')"
                            class=""
                        >
                            <option value="">{{ $t("All") }}</option>
                            <option value="1">{{ $t("January") }}</option>
                            <option value="2">{{ $t("February") }}</option>
                            <option value="3">{{ $t("March") }}</option>
                            <option value="4">{{ $t("April") }}</option>
                            <option value="5">{{ $t("May") }}</option>
                            <option value="6">{{ $t("June") }}</option>
                            <option value="7">{{ $t("July") }}</option>
                            <option value="8">{{ $t("August") }}</option>
                            <option value="9">{{ $t("September") }}</option>
                            <option value="10">{{ $t("October") }}</option>
                            <option value="11">{{ $t("November") }}</option>
                            <option value="12">{{ $t("December") }}</option>
                        </SelectInput>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <TextInput
                            onkeydown="return false;"
                            type="number"
                            min="1900"
                            :max="new Date().getFullYear() + 1"
                            v-model="invoiceFilters.year"
                            class=""
                            :label="$t('Year')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            class=""
                            :textLabel="$t('Company')"
                            v-model="invoiceFilters.company"
                            :options="companies.data"
                            :multiple="false"
                            label="companyName"
                            trackBy="id"
                            moduleName="companies"
                        ></MultiSelectInput>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <SelectInput
                            v-model="invoiceFilters.invoiceStatus"
                            :label="$t('Invoice Status')"
                            class=""
                        >
                            <option value="">{{ $t("All") }}</option>
                            <option value="draft">{{ $t("Draft") }}</option>
                            <option value="approved">
                                {{ $t("Approved") }}
                            </option>
                            <option value="sent">{{ $t("Sent") }}</option>
                            <option value="paid">{{ $t("Paid") }}</option>
                            <option value="warning level 1">
                                {{ $t("Warning level 1") }}
                            </option>
                            <option value="warning level 2">
                                {{ $t("Warning level 2") }}
                            </option>
                            <option value="warning level 3">
                                {{ $t("Warning level 3") }}
                            </option>
                        </SelectInput>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            :label="$t('Invoice Category')"
                            class=""
                            v-model="invoiceFilters.category"
                        >
                            <option value="">
                                {{ $t("All") }}
                            </option>
                            <option value="license">
                                {{ $t("Software License") }}
                            </option>
                            <option value="maintenance">
                                {{ $t("Software Maintenance") }}
                            </option>
                            <option value="service">{{ $t("Service") }}</option>
                            <option value="ams">
                                {{ $t("Application Management Service") }}
                            </option>
                            <option value="hosting">{{ $t("Hosting") }}</option>
                            <option value="cloud-software">
                                {{ $t("Cloud") }}
                            </option>
                        </select-input>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap py-8 px-8">
                <div class="pr-6">
                    <label class="mr-2" for="net">{{ $t("Net Total") }}</label>
                    <input
                        id="net"
                        name="chart-total-type"
                        type="radio"
                        v-model="chartTotalType"
                        value="net"
                    />
                </div>
                <div class="pr-6">
                    <label class="mr-2" for="gross">{{
                        $t("Gross Total")
                    }}</label>
                    <input
                        id="gross"
                        name="chart-total-type"
                        type="radio"
                        v-model="chartTotalType"
                        value="gross"
                    />
                </div>
            </div>
            <div class="flex flex-wrap mt-8">
                <div class="w-full lg:w-1/3">
                    <div style="height: auto">
                        <apexchart
                            class="mt-5 pb-5"
                            type="pie"
                            height="600"
                            :options="chartOptionsInvoice"
                            :series="seriesInvoice"
                            @dataPointSelection="dataPointSelectedInvoice"
                        ></apexchart>
                    </div>
                    <div
                        style="border-top: 2px solid lightgray"
                        class="flex justify-center items-center flex-col py-8"
                    >
                        <h1 class="font-medium text-5xl">
                            {{
                                $formatter(
                                    chartTotalType === "net"
                                        ? invoiceNetTotalTemp
                                        : invoiceGrossTotalTemp,
                                    globalLanguage,
                                    "EUR",
                                    false, // not a currency
                                    2, // minimum fraction digits
                                    2 // maximum fraction digits
                                )
                            }}
                        </h1>
                        <p class="mt-2">{{ $t("Total Amount") }}</p>
                    </div>
                </div>
                <div class="w-full lg:w-2/3">
                    <!-- invoice listing -->
                    <InvoiceTable
                        @changePage="fetchInvoiceStatistics"
                        :invoiceListing="invoiceListing"
                        @orderInvoicesTable="orderInvoicesTable"
                    />
                </div>
            </div>
        </div>
    </div>

    <MonthlyForecast />
</template>

<script>
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import TextInput from "../../Components/TextInput.vue";
import DateInput from "../../Components/DateInput.vue";
import { mapGetters } from "vuex";
import VueApexCharts from "vue3-apexcharts";
import TimeTrackerTable from "./Components/TimeTrackerTable.vue";
import InvoiceTable from "./Components/InvoiceTable.vue";
import MonthlyForecast from "./Components/MonthlyForecast.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Finance"),
                    to: "/finance-dashboard",
                },
                {
                    text: this.$t("Finance Dashboard"),
                    active: true,
                },
            ],
            timeTrackerCompanyId: null, // sent as companyId filter in time tracker listing statistics
            invoiceCompanyId: null, // sent as companyId filter in invoice listing statistics
            timeTrackerListing: {
                data: [],
                links: [],
            },
            invoiceListing: {
                data: [],
                links: [],
            },
            form: {},
            invoiceForm: {},
            timeTrackerChartType: "pie",
            chartTotalType: "net",
            invoiceNetTotal: 0,
            invoiceNetTotalTemp: 0,
            invoiceGrossTotal: 0,
            invoiceGrossTotalTemp: 0,
            invoiceStatistics: [],
            timeTrackerTotal: 0,
            timeTrackerTotalTemp: 0,
            timeTrackerKulanzTime: 0,
            timeTrackerStatistics: [],
            // filters for the time tracker statistics
            timeTrackerFilters: {
                // the default startDate is the start of the current year
                startDate: new Date(
                    `${new Date().getFullYear()}-${
                        new Date().getMonth() + 1 < 10 ? "0" : ""
                    }${new Date().getMonth() + 1}-01`
                )
                    .toISOString()
                    .slice(0, 10),
                // the default endDate is the end of the current year
                endDate: new Date(
                    `${new Date().getFullYear()}-${
                        new Date().getMonth() + 1 < 10 ? "0" : ""
                    }${new Date().getMonth() + 1}-${this.getDays(
                        new Date().getFullYear(),
                        new Date().getMonth() + 1
                    )}`
                )
                    .toISOString()
                    .slice(0, 10),
                type: "",
                status: "",
                invoiceStatus: "",
                companyId: "",
            },
            invoiceFilters: {
                month: new Date().getMonth() + 1,
                year: new Date().getFullYear(),
                company: "",
                invoiceStatus: "",
                category: "",
                person: "",
            },
        };
    },
    mounted() {
        this.fetchTimeTrackerStatistics();
        this.fetchInvoiceStatistics();
        if (!this.companies?.data?.length) {
            this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
        }
        this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });
    },
    watch: {
        /**
         * reset the timeTrackerTotalTemp to original value from the API response evertime the type changes
         * we do this because the user may have selected a datapoint on the chart and as a result of that
         * the temp value may be incorrect when the chart type changes
         */
        timeTrackerChartType() {
            this.timeTrackerTotalTemp = this.timeTrackerTotal;
        },
        /**
         * reset the invoiceNetTotalTemp/invoiceGrossTotalTemp to original value from the API response evertime the totalType changes
         * we do this because the user may have selected a datapoint on the chart and as a result of that
         * the temp value may be incorrect when the chart total type changes
         */
        chartTotalType() {
            this.invoiceNetTotalTemp = this.invoiceNetTotal;
            this.invoiceGrossTotalTemp = this.invoiceGrossTotal;
        },
        timeTrackerFilters: {
            handler: function () {
                this.fetchTimeTrackerStatistics();
            },
            deep: true,
        },
        invoiceFilters: {
            handler: function () {
                this.fetchInvoiceStatistics();
            },
            deep: true,
        },
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        dashboardTimeTrackerSorting(data) {
            this.form = data;
            this.fetchTimeTrackerStatistics();
        },
        orderInvoicesTable(data) {
            this.invoiceForm = data;
            this.fetchInvoiceStatistics();
        },
        /**
         * sets the 'timeTrackerTotalTemp' which represents the 'total time' underneath the chart
         * we can get the value of the selected data point from the 'selectedDataPoints' array by grabbing the 'time'
         * from the 'timeTrackerStatistics' array based on the index received from 'selectedDataPoints'
         * @param {event} received native click event
         * @param {chart} all the chart options, configurations
         * @param {selectedDataPoints} an array containing the indexes of all the selected data points on a chart. Format will be [[index]]
         * where 'index' is the index of the selected datapoint in the series (timeTrackerStatistics)
         */
        dataPointSelectedInvoice(event, chart, { selectedDataPoints }) {
            // set the invoiceCompanyId to the companyId from the selected data point
            this.invoiceCompanyId =
                this.invoiceStatistics[selectedDataPoints?.[0]?.[0]]?.companyId;
            // filter the statistics based on the companyId of selected data point
            this.fetchInvoiceStatistics();
            const invoiceTotal =
                this.invoiceStatistics[selectedDataPoints?.[0]?.[0]]?.[
                    this.chartTotalType === "net" ? "netTotal" : "grossTotal"
                ] ?? 0;
            if (this.chartTotalType === "net")
                this.invoiceNetTotalTemp =
                    selectedDataPoints?.[0].length > 0
                        ? invoiceTotal
                        : this.invoiceNetTotal;
            else
                this.invoiceGrossTotalTemp =
                    selectedDataPoints?.[0].length > 0
                        ? invoiceTotal
                        : this.invoiceGrossTotal;
        },
        /**
         * sets the 'timeTrackerTotalTemp' which represents the 'total time' underneath the chart
         * we can get the value of the selected data point from the 'selectedDataPoints' array by grabbing the 'time'
         * from the 'timeTrackerStatistics' array based on the index received from 'selectedDataPoints'
         * @param {event} received native click event
         * @param {chart} all the chart options, configurations
         * @param {selectedDataPoints} an array containing the indexes of all the selected data points on a chart. Format will be [[index]]
         * where 'index' is the index of the selected datapoint in the series (timeTrackerStatistics)
         */
        dataPointSelectedTimeTracker(event, chart, { selectedDataPoints }) {
            // set the timeTrackerCompanyId to the companyId from the selected data point
            this.timeTrackerCompanyId =
                this.timeTrackerStatistics[
                    selectedDataPoints?.[0]?.[0]
                ]?.companyId;
            // filter the statistics based on the companyId of selected data point
            this.fetchTimeTrackerStatistics();
            const totalTime =
                this.timeTrackerStatistics[selectedDataPoints?.[0]?.[0]]
                    ?.time ?? 0;
            this.timeTrackerTotalTemp =
                selectedDataPoints?.[0].length > 0
                    ? totalTime
                    : this.timeTrackerTotal;
        },
        /**
         * gives back the number of days a month has based on year and month number
         * @param {year} the year for which we wanna calculate the month days for
         * @param {month} the month for which we wanna calculate the days for
         */
        getDays(year, month) {
            return new Date(year, month, 0).getDate();
        },
        /**
         * triggers the timeTrackers timeTrackerStatistics API
         * @param {page} the current page for the timeTrackerListing pagination
         */
        async fetchTimeTrackerStatistics(page = 1) {
            try {
                const response = await this.$store.dispatch(
                    "timeTrackers/timeTrackerStatistics",
                    {
                        ...this.timeTrackerFilters,
                        ...this.form,
                        page: page,
                    }
                );
                this.timeTrackerStatistics = response?.data?.statistics ?? [];
                // set the timeTrackerListing from API response
                this.timeTrackerListing = response?.data
                    ?.timeTrackerListing ?? {
                    data: [],
                    links: [],
                };
                this.timeTrackerKulanzTime = response?.data?.goodwillTime ?? 0; // set the goodwill time
                // if a data point is selected ('timeTrackerCompanyId' is set) then this means that we have already filtered the timeTrackerTotal and
                // do not need to set the timeTrackerTotal from API response
                if (!this.timeTrackerCompanyId) {
                    /**
                     * timeTrackerTotal value contains the original total that we get from the API response
                     * timeTrackerTotalTemp value contains the original total as well, however the timeTrackerTotalTemp value
                     * will change when the user selects a datapoint on the chart by clicking
                     */
                    this.timeTrackerTotal = this.timeTrackerTotalTemp =
                        response?.data?.total ?? 0;
                }
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * triggers the invoices invoiceStatistics API
         * @param {page} the current page for the invoiceListing pagination
         */
        async fetchInvoiceStatistics(page = 1) {
            try {
                const response = await this.$store.dispatch(
                    "invoices/invoiceStatistics",
                    {
                        ...this.invoiceFilters,
                        ...this.invoiceForm,
                        page: page,
                        companyId: this.invoiceCompanyId,
                    }
                );
                this.invoiceStatistics = response?.data?.statistics ?? [];
                // set the invoiceListing from API response
                this.invoiceListing = response?.data?.invoiceListing ?? {
                    data: [],
                    links: [],
                };
                // if a data point is selected ('invoiceCompanyId' is set) then this means that we have already filtered the invoiceNetTotal, invoiceGrossTotal and
                // do not need to set the invoiceNetTotal, invoiceGrossTotal from API response
                if (!this.invoiceCompanyId) {
                    /**
                     * invoiceNetTotal value contains the original total that we get from the API response
                     * invoiceNetTotalTemp value contains the original total as well, however the invoiceNetTotalTemp value
                     * will change when the user selects a datapoint on the chart by clicking, same for grossTotal as well
                     */
                    this.invoiceNetTotal = this.invoiceNetTotalTemp =
                        response?.data?.totalNetTotal ?? 0;
                    this.invoiceGrossTotal = this.invoiceGrossTotalTemp =
                        response?.data?.totalGrossTotal ?? 0;
                }
            } catch (e) {
                console.log(e);
            }
        },
    },
    computed: {
        ...mapGetters("companies", ["companies"]),
        ...mapGetters("auth", {
            users: "users",
        }),
        seriesTimeTracker() {
            const series = this.timeTrackerStatistics.map((stat) => stat.time);
            return this.timeTrackerChartType === "pie"
                ? series.length
                    ? series
                    : [1]
                : [
                      {
                          data: series,
                      },
                  ];
        },
        chartOptionsTimeTracker() {
            const labels = this.timeTrackerStatistics.map(
                (stat) => stat.companyName
            );
            return {
                chart: {
                    height: "100%",
                    type: this.timeTrackerChartType,
                },
                dataLabels: {
                    enabled: true,
                    formatter: (val, { seriesIndex }) => {
                        return [
                            val.toFixed(2) + "%",
                            (this.timeTrackerStatistics[seriesIndex]?.time ??
                                0) +
                                " " +
                                this.$t("h"),
                        ];
                    },
                },
                labels: labels.length ? labels : [this.$t("No Data")],
                tooltip: {
                    y: {
                        formatter: (val) => {
                            return labels.length ? val : this.$t("Empty");
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
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                    },
                    pie: {
                        dataLabels: {
                            offset: -20,
                        },
                    },
                },
            };
        },
        seriesInvoice() {
            const series = this.invoiceStatistics.map((stat) => {
                return this.chartTotalType === "net"
                    ? stat.netTotal
                    : stat.grossTotal;
            });
            return series.length ? series : [1];
        },
        chartOptionsInvoice() {
            const labels = this.invoiceStatistics.map((stat) => {
                if (this.chartTotalType === "net") {
                    return `${stat.companyName} - (${this.$formatter(
                        stat.netTotal,
                        this.globalLanguage,
                        "EUR",
                        false,
                        2,
                        2
                    )})`;
                } else if (this.chartTotalType === "gross") {
                    return `${stat.companyName} - (${this.$formatter(
                        stat.grossTotal,
                        this.globalLanguage,
                        "EUR",
                        false,
                        2,
                        2
                    )})`;
                } else {
                    return stat.companyName; // Default behavior, no concatenation
                }
            });
            return {
                chart: {
                    height: "100%",
                    type: "pie",
                },
                legend: {
                    width: 200,
                    position: "bottom",
                },
                dataLabels: {
                    enabled: true,
                    formatter: (val, { seriesIndex }) => {
                        return [
                            val.toFixed(2) + "%",
                            this.$formatter(
                                this.invoiceStatistics[seriesIndex]?.[
                                    this.chartTotalType === "net"
                                        ? "netTotal"
                                        : "grossTotal"
                                ] ?? 0,
                                this.globalLanguage,
                                "EUR",
                                false,
                                2,
                                2
                            ),
                        ];
                    },
                },
                tooltip: {
                    y: {
                        formatter: (val) => {
                            return labels.length
                                ? this.$formatter(
                                      val,
                                      this.globalLanguage,
                                      "EUR",
                                      false,
                                      2,
                                      2
                                  )
                                : this.$t("Empty");
                        },
                    },
                },
                labels: labels.length ? labels : [this.$t("No Data")],
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
    components: {
        MultiSelectInput,
        SelectInput,
        DateInput,
        TextInput,
        apexchart: VueApexCharts,
        TimeTrackerTable,
        InvoiceTable,
        MonthlyForecast,
        PageHeader,
    },
};
</script>

<style scoped>
.apexcharts-legend {
    width: 300px !important;
}
</style>
