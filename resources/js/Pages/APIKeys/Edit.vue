<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill API Key Details") }}</h3>
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
                                <MultiSelectInput
                                    v-model="form.company"
                                    :key="form.company"
                                    :required="true"
                                    :options="companies.data"
                                    :multiple="false"
                                    textLabel="Company"
                                    label="companyName"
                                    trackBy="id"
                                    :error="errors.companyId"
                                    moduleName="companies"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.tenant"
                                    :key="form.tenant"
                                    :options="partners?.data ?? []"
                                    :multiple="false"
                                    textLabel="Tenant"
                                    label="companyName"
                                    :error="errors.tenant_id"
                                    trackBy="id"
                                    moduleName="companies"
                                    :query="{ customerType: 'partner' }"
                                    :countStore="'partnerCount'"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :textLabel="'Roles'"
                                    :trackBy="'id'"
                                    :label="'title'"
                                    v-model="form.roles"
                                    moduleName="roles"
                                    :key="form.roles"
                                    :search-param-name="'search_string'"
                                    :options="roles"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <SelectInput
                                    :label="'Status'"
                                    v-model="form.status"
                                    :key="form.status"
                                >
                                    <option value="1">
                                        {{ $t("Active") }}
                                    </option>
                                    <option value="0">
                                        {{ $t("Inactive") }}
                                    </option>
                                </SelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <SelectInput
                                    v-model="form.type"
                                    :key="form.type"
                                    :label="$t('Type')"
                                    :required="false"
                                    :error="errors.type"
                                >
                                    <option :value="1">
                                        {{ $t("Owner") }}
                                    </option>
                                    <option :value="2">
                                        {{ $t("Partner") }}
                                    </option>
                                    <option :value="3">
                                        {{ $t("Customer") }}
                                    </option>
                                </SelectInput>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/api-keys" class="primary-btn mr-3">
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
                    {{ $t("Update") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("roles", ["roles"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        SelectInput,
        PageHeader,
    },
    props: {},
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("API Keys"),
                    to: "/api-keys",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                name: "",
                company: "",
                tenant: "",
                roles: [],
                status: 0,
                type: "",
            },
        };
    },
    async mounted() {
        // fetch the companies list if empty. Needed for company and tenant dropdown
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "apiKeys/show",
                this.$route.params.id
            );
            if (!this.roles?.length) await this.$store.dispatch("roles/list");
            if (!this.companies?.data?.length) {
                await this.$store.dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                });
            }
            if (!this.partners?.data?.length) {
                await this.$store.dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                    customerType: "partner",
                });
            }
            this.form = {
                id: response?.data[0]?.id ?? "",
                name: response?.data[0]?.name ?? "",
                company:
                    this.companies?.data?.find((company) => {
                        return company.id === response?.data[0]?.company_id;
                    }) ?? "",
                tenant:
                    this.partners?.data?.find((partner) => {
                        return partner.id === response?.data[0]?.tenant_id;
                    }) ?? "",
                roles: response?.data[0]?.roles ?? [],
                status: response?.data[0]?.status ?? 0,
                type: response?.data[0]?.type ?? "",
            };
            this.form.roles = this.form.roles
                .map((roleId) => {
                    return {
                        ...(this.roles.find((role) => role.id == roleId) ?? {}),
                    };
                })
                .filter((role) => role.id);
            console.log(this.form);
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        /**
         * hits the apiKeys create API
         */
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("apiKeys/update", {
                    id: this.form.id,
                    name: this.form.name ?? "",
                    status: this.form.status ?? 0,
                    roles: this.form.roles.map((role) => role.id),
                    set_company_id: this.form.company?.id ?? "",
                    set_tenant_id: this.form.tenant?.id ?? "",
                    type: this.form.type,
                });
                await this.$store.dispatch("apiKeys/list");
                this.$router.push("/api-keys");
            } catch (e) {}
        },
    },
};
</script>
