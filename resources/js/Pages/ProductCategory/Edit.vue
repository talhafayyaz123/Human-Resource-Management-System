<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <trashed-message
            v-if="data?.deleted_at"
            class="mb-6"
            @restore="restore"
        >
            {{ $t("This product category has been deleted") }}.
        </trashed-message>

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update Product Category Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.name"
                                    :error="errors.name"
                                    :required="true"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-if="isApiCalled"
                                    v-model="form.defaultUnit"
                                    :required="true"
                                    :error="errors.defaultUnit"
                                    :label="$t('Default Unit')"
                                >
                                    <option
                                        v-for="unit in units.data"
                                        :key="'unit-' + unit.id"
                                        :value="unit.id"
                                    >
                                        {{ unit.name }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.productType"
                                    :key="form.productType"
                                    :error="errors.productType"
                                    :label="$t('Product Type')"
                                >
                                    <option value="">{{ $t("All") }}</option>
                                    <option value="software">
                                        {{ $t("Software") }}
                                    </option>
                                    <option value="service">{{ $t("Service") }}</option>
                                    <option value="pauschal">
                                        {{ $t("Pauschal") }}
                                    </option>
                                    <option value="ams">{{ $t("AMS") }}</option>
                                    <option value="hosting">{{ $t("Hosting") }}</option>
                                    <option value="cloud-software">
                                        {{ $t("Cloud Software") }}
                                    </option>
                                    <option value="traveling">
                                        {{ $t("Traveling") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group flex justify-around">
                                <div
                                    class="flex items-center"
                                >
                                    <label for="isDefaultOnOffers">{{
                                        $t("Is Default On Offers")
                                    }}</label>
                                    <input
                                        class="ml-5 mt-1"
                                        id="isDefaultOnOffers"
                                        type="checkbox"
                                        v-model="form.isDefaultOnOffers"
                                        :error="errors.isDefaultOnOffers"
                                    />
                                </div>
                                <div
                                    class="flex items-center"
                                >
                                    <label for="serviceContingent">{{
                                        $t("Service Contingent")
                                    }}</label>
                                    <input
                                        class="ml-5 mt-1"
                                        id="serviceContingent"
                                        type="checkbox"
                                        v-model="form.serviceContingent"
                                        :error="errors.serviceContingent"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/product-categories" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <button
                    v-if="!data.deleted_at"
                    class="secondary-btn mr-3"
                    tabindex="-1"
                    type="button"
                    @click="destroy"
                >
                    {{ $t("Delete product category") }}
                </button>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.edit`)"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="updateIcon" />
                    </span>
                    {{ $t("Update") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import TrashedMessage from "../../Components/TrashedMessage.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    mounted() {
        this.refresh();
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("units", ["units"]),
    },
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
        TrashedMessage,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Product Category",
                    to: "/product-categories",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            data: {},
            isApiCalled: false,
            form: {
                name: "",
                defaultUnit: "",
                isDefaultOnOffers: false,
                productType: "",
                serviceContingent: false,
            },
        };
    },
    methods: {
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "productCategory/show",
                    this.$route.params.id
                );
                this.data = response?.data?.data ?? {};
                this.form = {
                    name: this.data.name,
                    defaultUnit: this.data.defaultUnit,
                    isDefaultOnOffers: this.data.isDefaultOnOffers == 1,
                    productType: this.data.productType ?? "",
                    serviceContingent: this.data.serviceContingent == 1,
                };
                await this.$store.dispatch("units/list");
                this.isApiCalled = true;
            } catch (e) {}
            finally{
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("productCategory/update", {
                    id: this.$route.params.id,
                    data: { ...this.form },
                });
                await this.$store.dispatch("productCategory/list");
                this.$router.push("/product-categories");
            } catch (e) {}
        },
        async destroy() {
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    try {
                        await this.$store.dispatch(
                            "productCategory/destroy",
                            this.$route.params.id
                        );
                        await this.$store.dispatch("productCategory/list");
                        this.$router.push("/product-categories");
                    } catch (e) {}
                }
            });
        },
        restore() {
            if (
                confirm(
                    "Are you sure you want to restore this product category?"
                )
            ) {
            }
        },
    },
};
</script>
