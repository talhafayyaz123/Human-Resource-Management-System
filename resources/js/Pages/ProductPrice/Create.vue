<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Price List Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.name"
                                    :error="errors.name"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.status"
                                    :error="errors.status"
                                    :label="$t('Status')"
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :error="errors.productSoftwareId"
                                    v-model="form.productSoftware"
                                    :options="softwares.data"
                                    :multiple="false"
                                    :textLabel="$t('Software')"
                                    label="name"
                                    :trackBy="'id'"
                                    :moduleName="'softwares'"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="flex justify-between">
                                    <label class="ml-3" for="isDefaultOnSoftware">{{ $t("Is Default") }}</label>
                                    <input
                                        class="ml-1"
                                        id="isDefaultOnSoftware"
                                        type="checkbox"
                                        v-model="form.isDefault"
                                        :error="errors.isDefault"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.partnerId"
                                    :textLabel="$t('Partner')"
                                    :key="form.partnerId"
                                    :options="partners?.data ?? []"
                                    :multiple="false"
                                    label="companyName"
                                    trackBy="id"
                                    moduleName="partner"
                                    :query="{ customerType: 'partner' }"
                                    :countStore="'partnerCount'"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.type"
                                    :error="errors.type"
                                    :label="$t('Type')"
                                >
                                    <option value="docshero_customer_price_list">{{ $t('DOCSHERO Customer Price List')}}</option>
                                    <option value="docshero_partner_price_list">{{ $t('DOCSHERO Partner Price List')}}</option>
                                    <option value="partner_price_list">{{ $t('Partner Price List')}}</option>
                                </select-input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/product-prices" class="primary-btn mr-3">
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
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("softwares", ["softwares"]),
        ...mapGetters("companies", ["partners"]),
    },
    async mounted() {
        await this.$store.dispatch("softwares/list");
        await this.$store.dispatch("companies/list", {
            perPage: 25,
            page: 1,
            customerType: "partner",
        });
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        SelectInput,
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
                    text: "Price List",
                    to: "/product-prices",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            form: {
                name: "",
                status: "",
                productSoftware: "",
                isDefault: false,
                productSoftwareId: "",
                partnerId: "",
                type: "docshero_customer_price_list"
            },
        };
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                if (this.form.productSoftware) {
                    this.form.productSoftwareId = this.form.productSoftware.id;
                }
                await this.$store.dispatch("productprice/create", {
                    ...this.form,
                });
                this.$router.push("/product-prices");
            } catch (e) {}
        },
    },
};
</script>
