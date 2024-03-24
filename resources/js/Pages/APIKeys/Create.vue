<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="">
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
                                    v-model="form.company_id"
                                    :key="form.company_id"
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
                                    v-model="form.tenant_id"
                                    :key="form.tenant_id"
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
                    @click="store"
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="secondary-btn mr-3"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
                <button
                    @click="toggleModal = true"
                    v-if="clientID && clientSecret"
                    class="secondary-btn"
                >
                    {{ $t("Show API Key") }}
                </button>
            </div>
        </form>
    </div>
    <KeysModal
        :clientID="clientID"
        :clientSecret="clientSecret"
        @toggleModal="toggleModal = $event"
        :toggleModal="toggleModal"
    />
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import KeysModal from "./Components/KeysModal.vue";
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
        KeysModal,
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
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            toggleModal: false,
            form: {
                name: "",
                company_id: "",
                tenant_id: "",
                roles: [],
                status: 0,
                type: "",
            },
            clientSecret: "",
            clientID: "",
        };
    },
    async mounted() {
        // fetch the companies list if empty. Needed for company and tenant dropdown
        try {
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
            if (!this.roles?.length) await this.$store.dispatch("roles/list");
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        /**
         * hits the apiKeys create API
         */
        async store() {
            try {
                this.$store.commit("isLoading", true);
                const response = await this.$store.dispatch("apiKeys/create", {
                    ...this.form,
                    roles: this.form.roles.map((role) => role.id),
                    company_id: this.form.company_id?.id ?? "",
                    tenant_id: this.form.tenant_id?.id ?? "",
                });
                this.clientID = response?.data?.client_id ?? "";
                this.clientSecret = response?.data?.secret ?? "";
                this.toggleModal = true;
                await this.$store.dispatch("apiKeys/list");
            } catch (e) {}
        },
    },
};
</script>
