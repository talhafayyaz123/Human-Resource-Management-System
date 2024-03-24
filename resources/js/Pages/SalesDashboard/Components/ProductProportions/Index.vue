<template>
    <div class="card mt-3">
        <div class="grid items-center grid-cols-2 gap-6">
            <div class="">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Product Proportions Offers") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="w-full">
                        <div style="height: 400px">
                            <Chart
                                :key="1"
                                :series="productProportionsOffers"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Product Proportions Orders") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="w-full">
                        <div style="height: 400px">
                            <Chart
                                :key="2"
                                :series="productProportionsOrders"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from "./Chart.vue";
import mainMixin from "../../Mixins/mainMixin";

export default {
    name: "ProductProportions",
    mixins: [mainMixin],
    data() {
        return {
            productProportionsOffers: [], // product proportion statistics for offers
            productProportionsOrders: [], // product proportion statistics for orders
        };
    },
    components: {
        Chart,
    },
    methods: {
        /**
         * fetch the product proportions stats
         */
        async fetchData() {
            // get statistics for product proportions
            try {
                const response = await this.$store.dispatch(
                    "saleDashboard/productProportionsStatistics",
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
                this.productProportionsOffers = response?.data?.offers ?? [];
                this.productProportionsOrders = response?.data?.orders ?? [];
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

<style scoped></style>
