<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill Role Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.title"
                                    :error="errors.title"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.permissions"
                                    :key="form.permissions"
                                    :options="permissions"
                                    moduleName="permissions"
                                    :search-param-name="'search_string'"
                                    :multiple="true"
                                    :textLabel="$t('Permissions')"
                                    label="title"
                                    trackBy="id"
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
                                    v-model="form.company_id"
                                    :key="form.company_id"
                                    :options="companies.data"
                                    :multiple="false"
                                    textLabel="Company"
                                    label="companyName"
                                    :error="errors.company_id"
                                    trackBy="id"
                                    moduleName="companies"
                                />
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
                                    <option :value="1">{{ $t("Owner") }}</option>
                                    <option :value="2">{{ $t("Partner") }}</option>
                                    <option :value="3">{{ $t("Customer") }}</option>
                                </SelectInput>
                            </div>
                        </div>
                    </div>
                    <div class="grid items-center grid-cols-2 gap-6 my-5">
                        <div class="w-full">
                            <div class="form-group">
                                <div
                                    class="flex justify-between"
                                >
                                    <label for="can_register">
                                        {{ $t("Can Register") }}
                                    </label>
                                    <input
                                        id="can_register"
                                        v-model="form.can_register"
                                        type="checkbox"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div
                                    class="flex justify-between"
                                >
                                    <label for="active"> {{ $t("Active") }} </label>
                                    <input
                                        id="active"
                                        v-model="form.active"
                                        type="checkbox"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div
                                    class="flex justify-between"
                                >
                                    <label for="is_all_tenants">
                                        {{ $t("Available For All Tenants") }}
                                    </label>
                                    <input
                                        id="is_all_tenants"
                                        v-model="form.is_all_tenants"
                                        type="checkbox"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div
                                    class="flex justify-between"
                                >
                                    <label for="active">
                                        {{ $t("Available For All Companies") }}
                                    </label>
                                    <input
                                        id="is_all_companies"
                                        v-model="form.is_all_companies"
                                        type="checkbox"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="flex justify-between">
                                    <label class="mr-5" for="can-be-deleted">{{
                                        $t("Can be deleted")
                                    }}</label>
                                    <input
                                        id="can-be-deleted"
                                        type="checkbox"
                                        v-model="form.canBeDeleted"
                                        :checked="form.canBeDeleted"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="flex justify-between">
                                    <label class="mr-5" for="can-be-edited">{{
                                        $t("Can be edited")
                                    }}</label>
                                    <input
                                        id="can-be-edited"
                                        type="checkbox"
                                        v-model="form.canBeEdited"
                                        :checked="form.canBeEdited"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/roles" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
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
import LoadingButton from "../../Components/LoadingButton.vue";
import SelectInput from "../../Components/SelectInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("permissions", ["permissions"]),
        ...mapGetters("companies", ["companies", "partners"]),
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
                    text: "Roles",
                    to: "/roles",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                title: "",
                permissions: [],
                tenant_id: "",
                company_id: "",
                can_register: 0,
                active: 0,
                is_all_tenants: false,
                is_all_companies: false,
                canBeDeleted: false,
                canBeEdited: false,
                type: "",
            },
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            if (!this.permissions?.length)
                await this.$store.dispatch("permissions/list", {
                    limit_start: 0,
                    limit_count: 100,
                });
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
            let response = await this.$store.dispatch("roles/show", {
                id: this.$route.params.id,
            });
            let tenant =
                this.partners?.data?.find(
                    (company) => company.id == response?.data?.tenant_id
                ) ?? "";
            // if the tenant does not exist in the companies listing then use the show API to fetch the tenant separately
            if (!tenant && this.form.tenant_id) {
                response = await this.$store.dispatch(
                    "companies/show",
                    this.form.tenant_id
                );
                tenant = response?.data?.modelData ?? "";
            }
            let company =
                this.companies?.data?.find(
                    (company) => company.id == response?.data?.company_id
                ) ?? "";
            // if the tenant does not exist in the companies listing then use the show API to fetch the tenant separately
            if (!company && this.form.company_id) {
                response = await this.$store.dispatch(
                    "companies/show",
                    this.form.company_id
                );
                company = response?.data?.modelData ?? "";
            }
            this.form = {
                title: response?.data?.title,
                permissions: (response?.data?.permissions ?? [])
                    .map((permissionId) => {
                        return {
                            ...(this.permissions.find(
                                (permission) => permission.id == permissionId
                            ) ?? {}),
                        };
                    })
                    .filter((permission) => permission.id),
                tenant_id: tenant,
                company_id: company,
                can_register: response?.data?.can_register ? true : false,
                active: response?.data?.active ? true : false,
                is_all_tenants: response?.data?.is_all_tenants ? true : false,
                is_all_companies: response?.data?.is_all_companies
                    ? true
                    : false,
                canBeDeleted: response?.data?.can_be_deleted == 1,
                canBeEdited: response?.data?.can_be_edited == 1,
                type: response?.data?.type ?? "",
            };
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
                this.$store.commit("isLoading", true);
                const payload = {
                    id: this.$route.params.id,
                    ...this.form,
                    permissions: this.form.permissions.map(
                        (permission) => permission.id
                    ),
                    can_register: this.form.can_register === true ? 1 : 0,
                    active: this.form.active === true ? 1 : 0,
                    set_tenant_id: this.form.tenant_id?.id ?? "",
                    set_company_id: this.form.company_id?.id ?? "",
                    is_all_tenants: this.form.is_all_tenants === true ? 1 : 0,
                    is_all_companies:
                        this.form.is_all_companies === true ? 1 : 0,
                    can_be_deleted: this.form.canBeDeleted ? 1 : 0,
                    can_be_edited: this.form.canBeEdited ? 1 : 0,
                };
                delete payload["tenant_id"];
                delete payload["company_id"];
                await this.$store.dispatch("roles/update", payload);
                await this.$store.dispatch("roles/list");
                this.$router.push("/roles");
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
