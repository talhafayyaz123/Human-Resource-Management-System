<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill User Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.first_name"
                                    :required="true"
                                    :error="errors.name"
                                    :label="$t('First Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.last_name"
                                    :required="true"
                                    :error="errors.name"
                                    :label="$t('Last Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.email"
                                    :label="$t('Email')"
                                    :readOnly="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.city"
                                    :error="errors.city"
                                    type="text"
                                    :label="$t('City')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.street"
                                    :error="errors.street"
                                    type="text"
                                    :label="$t('Street')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.street_number"
                                    :error="errors.street_number"
                                    type="text"
                                    :label="$t('Street Number')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.zip"
                                    :error="errors.zip"
                                    type="text"
                                    :label="$t('ZIP')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.mobile"
                                    :error="errors.mobile"
                                    type="text"
                                    :label="$t('Phone')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :textLabel="'Roles'"
                                    :key="form.roles"
                                    @open="getRolesListing"
                                    v-model="form.roles"
                                    :options="roles"
                                    moduleName="roles"
                                    :search-param-name="'search_string'"
                                    label="title"
                                    :trackBy="'id'"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <CustomIcon name="lckIcon" />
                                        </span>
                                    </div>
                                    <text-input
                                        :key="form.inputType"
                                        v-model="form.password"
                                        :error="errors.password"
                                        :type="inputType"
                                        :label="$t('Password')"
                                        :show-password="showPassword"
                                        @child-event="handleChildEvent"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="input-group">
                                    <text-input
                                        :key="form.inputType"
                                        v-model="form.confirmPassword"
                                        :error="errors.confirmPassword"
                                        :type="inputType"
                                        :label="$t('Confirm Password')"
                                        :show-password="showPassword"
                                        @child-event="handleChildEvent"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
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
                                    :error="errors.types"
                                    v-model="form.types"
                                    :key="form.types"
                                    :options="[
                                        'customer_employee',
                                        'employee',
                                        'partner',
                                    ]"
                                    :multiple="true"
                                    :textLabel="$t('Types')"
                                    :tagPlaceholder="$t('Add a type')"
                                    @tagAdded="addNewType"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group flex justify-around">
                                <div class="">
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
                                <div class="">
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
                <router-link
                    :to="`/employees?page=${this.$route.query.page}`"
                    class="primary-btn mr-3"
                >
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
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("roles", ["roles"]),
        ...mapGetters("companies", ["companies", "partners"]),
    },
    components: {
        MultiSelectInput,
        LoadingButton,
        TextInput,
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
                    text: "Users",
                    to: "/employees?page=" + this.$route.query.page,
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            shouldShow: false,
            showPassword: false,
            inputType: "password",
            form: {
                first_name: "",
                last_name: "",
                mail: "",
                city: "",
                street: "",
                street_number: "",
                zip: "",
                mobile: "",
                roles: [],
                password: "",
                confirmPassword: "",
                company_id: "",
                tenant_id: "",
                canBeDeleted: false,
                canBeEdited: false,
            },
        };
    },
    async mounted() {
        this.shouldShow = false;
        try {
            this.$store.commit("showLoader", true);
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
        } catch (e) {
            console.log(e);
        }
        this.$store
            .dispatch("auth/show", {
                id: this.$route.params.id,
            })
            .then(async (res) => {
                this.form = { ...(res?.data ?? {}) };
                this.form.mail = this.form.email;
                this.form.types = Object.keys(this.form.types).filter(
                    (type) => this.form.types[type] == 1
                );
                this.form.canBeDeleted = this.form.can_be_deleted == 1;
                this.form.canBeEdited = this.form.can_be_edited == 1;
                let company =
                    this.companies?.data?.find(
                        (company) => company.id === this.form.company_id
                    ) ?? "";
                let response = null;
                // if the company does not exist in the companies listing then use the show API to fetch the company separately
                if (!company && this.form.company_id) {
                    response = await this.$store.dispatch(
                        "companies/show",
                        this.form.company_id
                    );
                    company = response?.data?.modelData ?? "";
                }
                this.form.company_id = company;
                let tenant =
                    this.partners?.data?.find(
                        (company) => company.id === this.form.tenant_id
                    ) ?? "";
                // if the tenant does not exist in the companies listing then use the show API to fetch the tenant separately
                if (!tenant && this.form.tenant_id) {
                    response = await this.$store.dispatch(
                        "companies/show",
                        this.form.tenant_id
                    );
                    tenant = response?.data?.modelData ?? "";
                }
                this.form.tenant_id = tenant;
            })
            .catch((e) => {
                console.log(e);
            })
            .finally(async () => {
                this.shouldShow = true;
                if (!!this.form.roles?.length) {
                    try {
                        const response = await this.$store.dispatch(
                            "roles/show",
                            {
                                id: this.form.roles,
                            }
                        );
                        this.form.roles = response?.data ?? [];
                        if (
                            !(this.form.roles instanceof Array) &&
                            typeof this.form.roles === "object"
                        ) {
                            this.form.roles = [this.form.roles];
                        }
                    } catch (e) {
                        console.log(e);
                    }
                }
                this.$store.commit("showLoader", false);
            });
    },
    methods: {
        /**
         * fetch roles listing on multiselect open if not already fetched
         */
        async getRolesListing() {
            try {
                if (!this.roles?.length) {
                    await this.$store.dispatch("roles/list");
                }
            } catch (e) {
                console.log(e);
            }
        },
        async update() {
            try {
                if (
                    this.form.password?.length ||
                    this.form.confirmPassword?.length
                ) {
                    if (this.form.password !== this.form.confirmPassword) {
                        this.$store.commit("errors", {
                            confirmPassword:
                                "Password and confirm password must match",
                        });
                        return;
                    }
                }
                if (!this.form.password?.length) {
                    delete this.form["password"];
                    delete this.form["confirmPassword"];
                }
                if (!this.form.company_id) {
                    this.$store.commit("errors", {
                        company_id: "Company is a required field",
                    });
                    return;
                }
                this.$store.commit("isLoading", true);
                let payload = {
                    id: this.$route.params.id,
                    ...this.form,
                    types: this.form.types,
                    roles: this.form.roles.map((role) => role.id),
                    set_company_id: this.form.company_id?.id ?? "",
                    set_tenant_id: this.form.tenant_id?.id ?? "",
                    can_be_deleted: this.form.canBeDeleted ? 1 : 0,
                    can_be_edited: this.form.canBeEdited ? 1 : 0,
                };
                delete payload["company_id"];
                delete payload["tenant_id"];
                await this.$store.dispatch("auth/update", { ...payload });
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "employee",
                });
                this.$router.push("/employees?page=" + this.$route.query.page);
            } catch (e) {
                console.log(e);
            }
        },
        handleChildEvent() {
            this.showPassword = !this.showPassword;
            this.inputType = this.showPassword ? "text" : "password";
        },
    },
};
</script>
