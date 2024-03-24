<template>
    <div>
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Lead Channel Statistics") }}</h3>
            </div>
            <div class="card-body">
                <div class="w-full">
                    <div style="height: 400px">
                        <apexchart
                            class="mt-5 pb-5"
                            type="bar"
                            height="100%"
                            :options="chartOptionsLeadChannels"
                            :series="seriesLeadChannels"
                        ></apexchart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueApexCharts from "vue3-apexcharts";
import mainMixin from "../Mixins/mainMixin";

export default {
    name: "LeadsChannelStatistics",
    mixins: [mainMixin],
    data() {
        return {
            leadChannelsStatistics: [],
        };
    },
    components: {
        apexchart: VueApexCharts,
    },
    methods: {
        /**
         * fetch the lead channel statistics
         */
        async fetchData() {
            // get the lead channel statistics
            try {
                const response = await this.$store.dispatch(
                    "saleDashboard/leadChannelsStatistics",
                    {
                        type: this.filterBy, // type of stats to be received
                        // depending on type the date sent is modifed
                        date: `${
                            this.filterBy === "month"
                                ? this.month.year
                                : this.year
                        }-${this.month.month + 1}-01`,
                    }
                );
                this.leadChannelsStatistics = response?.data?.data ?? [];
            } catch (e) {
                console.log(e);
            }
        },
    },
    computed: {
        // lead channel series for chart
        seriesLeadChannels() {
            return [
                {
                    data: this.leadChannelsStatistics.map((item) => item.total),
                },
            ];
        },
        // lead channel chart options
        chartOptionsLeadChannels() {
            return {
                chart: {
                    type: "bar",
                    height: 350,
                },
                dataLabels: {
                    enabled: true,
                    offsetX: 0,
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        horizontal: true,
                    },
                },
                yaxis: {
                    forceNiceScale: true,
                },
                xaxis: {
                    categories: this.leadChannelsStatistics.map(
                        (item) => item.name
                    ),
                },
            };
        },
    },
};
</script>

<style scoped></style>
