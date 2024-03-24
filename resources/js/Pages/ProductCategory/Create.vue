<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Product Category Details") }}
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
                                    v-model="form.defaultUnit"
                                    :error="errors.defaultUnit"
                                    :required="true"
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
                                <div class="flex items-center">
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
                                <div class="flex items-center">
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
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("units", ["units"]),
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
        PageHeader,
    },
    remember: "form",
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
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            form: {
                name: "",
                defaultUnit: "",
                isDefaultOnOffers: false,
                productType: "",
                serviceContingent: false,
            },
        };
    },
    async mounted() {
        try {
            await this.$store.dispatch("units/list");
        } catch (e) {}
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("productCategory/create", {
                    ...this.form,
                });
                await this.$store.dispatch("productCategory/list");
                this.$router.push("/product-categories");
            } catch (e) {}
        },
    },
};
</script>
