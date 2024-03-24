<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Document Service Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <TextInput
                                    :required="true"
                                    v-model="name"
                                    :error="errors.name"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="companyId"
                                    :key="companyId"
                                    :required="true"
                                    :options="companies.data"
                                    :multiple="false"
                                    :textLabel="$t('Company')"
                                    label="companyName"
                                    :error="errors.companyId"
                                    trackBy="id"
                                    moduleName="companies"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="tenant_id"
                                    :key="tenant_id"
                                    :options="partners?.data ?? []"
                                    :multiple="false"
                                    :textLabel="$t('Tenant')"
                                    label="companyName"
                                    trackBy="id"
                                    moduleName="companies"
                                    :query="{ customerType: 'partner' }"
                                    :countStore="'partnerCount'"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/event-name" class="primary-btn mr-3">
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
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies", "partners"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
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
                    text: "Event Name",
                    to: "/event-name",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            name: "",
            companyId: "",
            tenant_id: "",
            form: new FormData(),
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const obj = {
                id: this.$route.params.id,
            };
            const eventName = await this.$store.dispatch("eventName/show", obj);
            this.name = eventName?.data[0]?.name ?? "";
            // this.companyId = eventName?.data[0]?.company_id ?? "";
            // this.tenant_id = eventName?.data[0]?.tenant_id ?? "";
            if (!this.companies?.data?.length)
                await this.$store.dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                });
            if (!this.partners?.data?.length)
                await this.$store.dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                    customerType: "partner",
                });
            let company =
                this.companies?.data?.find(
                    (company) => company.id === this.$route.query.companyId
                ) ?? "";

            let response = null;
            if (!company && this.$route.query.companyId) {
                response = await this.$store.dispatch(
                    "companies/show",
                    this.$route.query.companyId
                );
                company = response?.data?.modelData ?? "";
            }
            this.companyId = company;
            let tenant =
                this.partners?.data?.find(
                    (company) => company.id === this.$route.query.tenantId
                ) ?? "";
            if (!tenant && this.$route.query.tenantId) {
                response = await this.$store.dispatch(
                    "companies/show",
                    this.$route.query.tenantId
                );
                tenant = response?.data?.modelData ?? "";
            }
            this.tenant_id = tenant;
        } catch (e) {
            console.log(e);
        }
        finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        async update() {
            try {
                if (!this.name) {
                    this.$store.commit("errors", {
                        name: "Name is a required field",
                    });
                    return;
                }
                if (!this.companyId?.id) {
                    this.$store.commit("errors", {
                        companyId: "Company is a required field",
                    });
                    return;
                }
                this.form = {
                    set_company_id: this.companyId?.id,
                    set_tenant_id: this.tenant_id?.id ?? "",
                    name: this.name,
                    id: this.$route.params.id,
                };
                console.log(this.form);
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("eventName/update", this.form);
                await this.$store.dispatch("eventName/list");
                this.$router.push("/event-name");
            } catch (e) {}
        },
    },
};
</script>
