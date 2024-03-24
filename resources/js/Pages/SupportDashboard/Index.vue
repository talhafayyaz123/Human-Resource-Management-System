<template>
    <PageHeader :items="breadcrumbItems"/>
    <div class="w-full margin-bottom-3rem main-div gap-1 mt-3">
        <div style="display: grid; gap: 20; grid-template-rows: 420px 290px">
            <OpenTickets
                @pageChanged="fetchData($event)"
                :openTicketsData="openTicketsData"
            />
            <OpenTicketsGrouped 
                 @pageChanged="fetchData($event)"
                :openTicketsGroupedData="openTicketsGroupedData"
            />
        </div>
        <div style="display: grid;gap: 20; grid-template-rows: 290px 290px">
            <CasesStats :casesStatsData="casesStatsData" />
            <!-- <CasesGraph :casesGraphData="casesGraphData" /> -->
            
            <Ams
                @pageChangedAms="fetchAmsData($event)"
                :amsData="amsData"
            />
        </div>
    </div>
</template>

<script>
import OpenTickets from "./Components/OpenTickets.vue";
import Ams from "./Components/Ams.vue";
import OpenTicketsGrouped from "./Components/OpenTicketsGrouped.vue";
import CasesStats from "./Components/CasesStats.vue";
import CasesGraph from "./Components/CasesGraph.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        OpenTickets,
        OpenTicketsGrouped,
        CasesStats,
        CasesGraph,
        Ams,
        PageHeader
    },
    methods: {
        async fetchData(queryParams = {}) {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "supportDashboard/supportDashboard",
                    { ...queryParams, groupBy: "companyId" }
                );
                this.casesGraphData = response?.data?.statusTicketCount ?? {};
                this.casesStatsData = {
                    incidentTicketsCount:
                        response?.data?.incidentTicketsCount ?? 0,
                    lastUpdatedRecords: response?.data?.lastUpdatedRecords ?? 0,
                    newTicketsTotal: response?.data?.newTicketsTotal ?? 0,
                    openAndNewTicketsCount:
                        response?.data?.openAndNewTicketsCount ?? 0,
                    assigneeGroupTicketCount:
                        response?.data?.assigneeGroupTicketCount ?? [],
                };
                this.openTicketsData = response?.data?.openAndNewTickets ?? {};
                this.openTicketsGroupedData =
                    response?.data?.openAndNewTicketsGrouped ?? {};
            } catch (e) {
                console.log(e);
            }
            finally {
                this.$store.commit("showLoader", false);
            }
        },
        async fetchAmsData(queryParams = {}) {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "supportDashboard/supportDashboardAms",
                    { ...queryParams }
                );
                this.amsData = response?.data ?? {};
    
            } catch (e) {
                console.log(e);
            }
            finally {
                this.$store.commit("showLoader", false);
            }
        },
    },
    mounted() {
        this.fetchData();
        this.fetchAmsData();
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Support"),
                    to: "/support-dashboard",
                },
                {
                    text: this.$t("Dashboard"),
                    active:true
                },
            ],
            casesStatsData: {},
            casesGraphData: {},
            openTicketsData: {
                data: [],
                links: [],
            },
            openTicketsGroupedData: {},
            amsData:{},
        };
    },
};
</script>

<style scoped>
.main-div {
    display: grid;
    grid-template-columns: 1fr 1fr;
}
</style>
