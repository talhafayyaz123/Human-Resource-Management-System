<template>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Offers") }}</h3>
        </div>
        <div class="card-body">
            <div class="w-full">
                <div style="height: 400px">
                    <Chart
                        v-if="shouldShowOffers"
                        :series="offersData"
                        :dates="offersDates"
                        :filterBy="filterBy"
                    />
                </div>
            </div>
            <OfferTable
                :listing="offersRecords"
                :type="'offer'"
                @changePage="getOfferData($event)"
            />
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Incoming Orders") }}</h3>
        </div>
        <div class="card-body">
            <div class="w-full">
                <div style="height: 400px">
                    <Chart
                        v-if="shouldShowOrders"
                        :series="ordersData"
                        :dates="ordersDates"
                        :filterBy="filterBy"
                    />
                </div>
            </div>
            <OfferTable
                :listing="ordersRecords"
                :type="'order'"
                @changePage="getOrderData($event)"
            />
        </div>
    </div>

</template>

<script>
import Chart from "./Chart.vue";
import OfferTable from "./OfferTable.vue";
import mainMixin from "../../Mixins/mainMixin";

export default {
    name: "StackedProportions",
    mixins: [mainMixin],
    data() {
        return {
            // toggles offers chart
            shouldShowOffers: false,
            // toggles orders chart
            shouldShowOrders: false,
            // offers data
            offersData: {
                license: [],
                maintenance: [],
                service: [],
                application: [],
                hosting: [],
                cloud: [],
            },
            // holds offers listing data
            offersRecords: {
                data: [],
                links: [],
            },
            // holds orders listing data
            ordersRecords: {
                data: [],
                links: [],
            },
            // orders data
            ordersData: {
                license: [],
                maintenance: [],
                service: [],
                application: [],
                hosting: [],
                cloud: [],
            },
            // offer dates used in x-axis
            offersDates: [],
            // order dates used in x-axis
            ordersDates: [],
        };
    },
    components: {
        Chart,
        OfferTable,
    },
    methods: {
        /**
         * get offers listing
         * @param {params} query params
         */
        async getOfferData(params = {}) {
            try {
                let response = await this.$store.dispatch(
                    "saleDashboard/offersData",
                    {
                        type: this.filterBy, // type of stats to be received
                        // depending on type the date sent is modifed
                        date: `${
                            this.filterBy === "month"
                                ? this.month.year
                                : this.year
                        }-${this.month.month + 1}-01`,
                        companyId: this.companyId,
                        offerType: "offer",
                        ...params,
                    }
                );
                this.offersRecords = response?.data ?? {
                    data: [],
                    links: [],
                };
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * get orders listing
         * @param {params} query params
         */
        async getOrderData(params = {}) {
            try {
                let response = await this.$store.dispatch(
                    "saleDashboard/offersData",
                    {
                        type: this.filterBy, // type of stats to be received
                        // depending on type the date sent is modifed
                        date: `${
                            this.filterBy === "month"
                                ? this.month.year
                                : this.year
                        }-${this.month.month + 1}-01`,
                        companyId: this.companyId,
                        offerType: "order",
                        ...params,
                    }
                );
                this.ordersRecords = response?.data ?? {
                    data: [],
                    links: [],
                };
            } catch (e) {
                console.log(e);
            }
        },
    },
    mounted() {
        // get offers and orders listing
        this.getOfferData();
        this.getOrderData();
        // when the offer statistics are fetched then perform this mapping
        this.$emitter.on("offer-statistics", (data) => {
            // toggle off the offers chart
            this.shouldShowOffers = false;
            // toggle off the orders chart
            this.shouldShowOrders = false;
            // reset the offers data
            this.offersData = {
                license: [],
                maintenance: [],
                service: [],
                application: [],
                hosting: [],
                cloud: [],
            };
            // reset the orders data
            this.ordersData = {
                license: [],
                maintenance: [],
                service: [],
                application: [],
                hosting: [],
                cloud: [],
            };
            // set the x-axis dates
            this.offersDates = this.ordersDates = Object.keys(data);
            // loop over the values from offer statistics
            Object.values(data).forEach((item) => {
                // set the offersData after adding a check
                let offersData =
                    item.nettoTotalOffersDetails instanceof Array
                        ? {}
                        : item.nettoTotalOffersDetails;
                // set the ordersData after adding a check
                let ordersData =
                    item.nettoTotalOrdersDetails instanceof Array
                        ? {}
                        : item.nettoTotalOrdersDetails;
                // set the chart data based on category of products
                this.offersData.license.push(
                    (offersData.license ?? 0).toFixed(2)
                );
                this.ordersData.license.push(
                    (ordersData.license ?? 0).toFixed(2)
                );
                this.offersData.maintenance.push(
                    (offersData.maintenance ?? 0).toFixed(2)
                );
                this.ordersData.maintenance.push(
                    (ordersData.maintenance ?? 0).toFixed(2)
                );
                this.offersData.service.push(
                    (offersData.service ?? 0).toFixed(2)
                );
                this.ordersData.service.push(
                    (ordersData.service ?? 0).toFixed(2)
                );
                this.offersData.application.push(
                    (offersData.application ?? 0).toFixed(2)
                );
                this.ordersData.application.push(
                    (ordersData.application ?? 0).toFixed(2)
                );
                this.offersData.hosting.push(
                    (offersData.hosting ?? 0).toFixed(2)
                );
                this.ordersData.hosting.push(
                    (ordersData.hosting ?? 0).toFixed(2)
                );
                this.offersData.cloud.push((offersData.cloud ?? 0).toFixed(2));
                this.ordersData.cloud.push((ordersData.cloud ?? 0).toFixed(2));
            });
            // timeout is added to improve performance, since rendering two charts at the same time is CPU intensive
            setTimeout(() => {
                // toggle on the offers chart
                this.shouldShowOffers = true;
            }, 100);
            setTimeout(() => {
                // toggle on the orders chart
                this.shouldShowOrders = true;
            }, 500);
        });
    },
};
</script>

<style scoped></style>
