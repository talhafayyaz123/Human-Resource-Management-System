<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="flex">
            <div style="height: auto !important" class="card lg:w-1/2 mr-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Article Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap">
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Article No") }}:</label
                            >
                            <p>{{ assetArticle?.articleNumber }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Asset Name") }}:</label
                            >
                            <p>{{ assetArticle.assetName }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Model") }}:</label
                            >
                            <p>{{ assetArticle.model }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Serial No") }}:</label
                            >
                            <p>{{ assetArticle.serialNo }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card lg:w-1/2 ml-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Asset Barcode") }}</h3>
                </div>

                <div class="card-body">
                    <div class="flex flex-wrap">
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Asset Name") }}:</label
                            >
                            <p>{{ assetArticle.assetName }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Article No") }}:</label
                            >
                            <p>{{ assetArticle?.articleNumber }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Model") }}:</label
                            >
                            <p>{{ assetArticle.model }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Serial No") }}:</label
                            >
                            <p>{{ assetArticle.serialNo }}</p>
                        </div>
                        <vue3-barcode
                            :value="assetArticle.serialNo"
                            :height="50"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="flex">
            <div class="card mr-3 lg:w-1/2 mt-4">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Inventory") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap">
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Delivery Date") }}:</label
                            >
                            <p>{{ assetArticle.deliveryDate }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Storage Location") }}:</label
                            >
                            <p>{{ assetArticle.storageLocation }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Inventory Status") }}:</label
                            >
                            <p>{{ assetArticle.inventoryStatus }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 ml-3"></div>
        </div>
        <div class="flex">
            <div class="card mr-3 lg:w-1/2 mt-4">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Assignment") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap">
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Employee List") }}:</label
                            >
                            <p>{{ assetArticle.assetEmployeeList }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Employee Name") }}:</label
                            >
                            <p>{{ assetArticle.employee }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 ml-3"></div>
        </div>
    </div>
</template>

<script>
import PageHeader from "@/Components/Layouts/Page-header.vue";
import Vue3Barcode from "vue3-barcode";

export default {
    components: {
        PageHeader,
        Vue3Barcode,
    },
    data() {
        return {
            assetArticle: {},
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Asset Management"),
                    to: "/assets",
                },
                {
                    text: this.$t("Asset Articles"),
                    to: "/assets",
                },
                {
                    text: this.$t("Show"),
                    active: true,
                },
            ],
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "assetArticle/show",
                this.$route.params.id
            );
            this.assetArticle = response?.data?.data ?? {};
        } catch (e) {
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
};
</script>

<style></style>
