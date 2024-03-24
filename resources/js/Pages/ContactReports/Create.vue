<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Contact Report Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="w-full my-5">
                        <div class="form-group">
                            <text-input
                                v-model="form.subject"
                                :required="true"
                                :error="errors.subject"
                                :label="$t('Subject')"
                            />
                            <!-- :error="form.errors.subject" -->
                        </div>
                    </div>
                    <div class="w-full my-5">
                        <div class="form-group">
                            <label class="form-label"
                                >{{ $t("Resubmit On") }}:</label
                            >
                            <datepicker
                                :clearable="false"
                                :enable-time-picker="false"
                                auto-apply
                                :close-on-auto-apply="false"
                                v-model="form.resubmitOn"
                                :class="errors.resubmitOn ? 'error' : ''"
                            />
                            <div v-if="errors?.resubmitOn" class="form-error">
                                {{ errors.resubmitOn }}
                            </div>
                        </div>
                    </div>
                    <div class="w-full my-5">
                        <div class="form-group">
                            <QuillTextEditor
                                :key="globalLanguage"
                                :content="form.text"
                                :required="true"
                                :error="errors.text"
                                @delta="form.text = $event"
                                :label="$t('Text')"
                            />
                            <!-- :error="form.errors.text" -->
                        </div>
                    </div>
                    <div class="grid items-center grid-cols-3 gap-6 my-5">
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    :error="errors.type"
                                    v-model="form.type"
                                    :isReadOnly="$route.query.lead"
                                    :key="form.type"
                                    :label="$t('Type')"
                                >
                                    <option value="customer">
                                        {{ $t("Customer") }}
                                    </option>
                                    <option value="lead">{{ $t("Lead") }}</option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-if="show"
                                    :required="true"
                                    :textLabel="
                                        form.type === 'lead'
                                            ? $t('Lead')
                                            : $t('Customer')
                                    "
                                    :isDisabled="$route.query.lead"
                                    v-model="form.companyId"
                                    :options="
                                        form.type === 'lead'
                                            ? leads.data
                                            : companies.data
                                    "
                                    :multiple="false"
                                    label="companyName"
                                    trackBy="id"
                                    :key="form.companyId"
                                    moduleName="companies"
                                    :query="{ customerType: form.type }"
                                    :countStore="
                                        form.type === 'lead' ? 'leadCount' : 'count'
                                    "
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    v-if="form.companyId"
                                    v-model="form.companyEmployees"
                                    :error="errors.companyEmployees"
                                    :options="employees"
                                    :customLabel="customLabel"
                                    :multiple="true"
                                    :textLabel="$t('Talked to People')"
                                    label="first_name"
                                    trackBy="id"
                                    :moduleName="'companyEmployees'"
                                >
                                    <template #beforeList>
                                        <div
                                            class="grid p-2 gap-2"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p class="font-bold">
                                                {{ $t("First Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Last Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Email") }}
                                            </p>
                                        </div>
                                        <hr />
                                    </template>
                                    <template #singleLabel="{ props }">
                                        <div
                                            class="grid gap-2"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p class="overflow-text-users-table">
                                                {{ props.option.first_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.last_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.email }}
                                            </p>
                                        </div>
                                    </template>
                                    <template #option="{ props }">
                                        <div
                                            class="grid"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p class="overflow-text-users-table">
                                                {{ props.option.first_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.last_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.email }}
                                            </p>
                                        </div>
                                    </template>
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <!-- multi select for assignee -->

                                <MultiSelectInput
                                    :required="true"
                                    v-if="users && isApiCalled"
                                    :showLabels="false"
                                    v-model="form.assignee"
                                    :options="users"
                                    :multiple="false"
                                    :textLabel="$t('Assignee')"
                                    label="first_name"
                                    trackBy="id"
                                    moduleName="auth"
                                    :search-param-name="'search_string'"
                                    :customLabel="customLabel"
                                    :error="errors.assignee"
                                >
                                    <template #beforeList>
                                        <div
                                            class="grid p-2 gap-2"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p class="font-bold">
                                                {{ $t("First Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Last Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Email") }}
                                            </p>
                                        </div>
                                        <hr />
                                    </template>
                                    <template #singleLabel="{ props }">
                                        <p>
                                            {{ props.option.first_name }}
                                            {{ props.option.last_name }}
                                        </p>
                                    </template>
                                    <template #option="{ props }">
                                        <div
                                            class="grid"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p class="overflow-text-users-table">
                                                {{ props.option.first_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.last_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.email }}
                                            </p>
                                        </div>
                                    </template>
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <!-- :error="form.errors.companyEmployees" -->
                                <MultiSelectInput
                                    :required="true"
                                    v-if="users"
                                    :showLabels="false"
                                    v-model="form.employees"
                                    :options="users"
                                    :multiple="true"
                                    :textLabel="$t('Employees')"
                                    label="first_name"
                                    trackBy="id"
                                    moduleName="auth"
                                    :search-param-name="'search_string'"
                                    :customLabel="customLabel"
                                    :error="errors.employees"
                                >
                                    <template #beforeList>
                                        <div
                                            class="grid p-2 gap-2"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p class="font-bold">
                                                {{ $t("First Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Last Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Email") }}
                                            </p>
                                        </div>
                                        <hr />
                                    </template>
                                    <template #singleLabel="{ props }">
                                        <div
                                            class="grid gap-2"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p class="overflow-text-users-table">
                                                {{ props.option.first_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.last_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.email }}
                                            </p>
                                        </div>
                                    </template>
                                    <template #option="{ props }">
                                        <div
                                            class="grid"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p class="overflow-text-users-table">
                                                {{ props.option.first_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.last_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.email }}
                                            </p>
                                        </div>
                                    </template>
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <!-- multi select for contact report sources -->
                                <MultiSelectInput
                                    v-if="isApiCalled"
                                    :isDisabled="isFoundLead"
                                    :textLabel="`Contact Report Source`"
                                    v-model="form.contactSources"
                                    :options="contactSources?.data"
                                    :multiple="true"
                                    :required="true"
                                    label="name"
                                    trackBy="id"
                                    moduleName="sources"
                                    :error="errors.contactSources"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <!-- :error="form.errors.employees" -->
                                <MultiSelectInput
                                    :required="true"
                                    v-if="isApiCalled"
                                    v-model="form.createdByEmployee"
                                    :showLabels="false"
                                    :options="users"
                                    :multiple="false"
                                    :textLabel="$t('Created By')"
                                    label="first_name"
                                    trackBy="id"
                                    moduleName="auth"
                                    :search-param-name="'search_string'"
                                    :customLabel="customLabel"
                                    :error="errors.createdByEmployee"
                                >
                                    <template #beforeList>
                                        <div
                                            class="grid p-2 gap-2"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p class="font-bold">
                                                {{ $t("First Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Last Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Email") }}
                                            </p>
                                        </div>
                                        <hr />
                                    </template>
                                    <template #singleLabel="{ props }">
                                        <p>
                                            {{ props.option.first_name }}
                                            {{ props.option.last_name }}
                                        </p>
                                    </template>
                                    <template #option="{ props }">
                                        <div
                                            class="grid"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p class="overflow-text-users-table">
                                                {{ props.option.first_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.last_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.email }}
                                            </p>
                                        </div>
                                    </template>
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    :error="errors.priority"
                                    v-model="form.priority"
                                    :label="$t('Priority')"
                                >
                                    <!-- :error="form.errors.priority" -->
                                    <option value="low">{{ $t("Low") }}</option>
                                    <option value="medium">
                                        {{ $t("Medium") }}
                                    </option>
                                    <option value="high">{{ $t("High") }}</option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.categoryId"
                                    :error="errors.categoryId"
                                    :label="$t('Category')"
                                >
                                    <!-- :error="form.errors.categoryId" -->
                                    <option
                                        v-for="category in reportCategory"
                                        :key="'lead-' + category.id"
                                        :value="category.id"
                                    >
                                        {{ category.name }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.contactType"
                                    :label="$t('Contact Type')"
                                    :error="errors.contactType"
                                >
                                    <!-- :error="form.errors.contactType" -->
                                    <option value="mail">{{ $t("Mail") }}</option>
                                    <option value="phone">{{ $t("Phone") }}</option>
                                    <option value="facebook">
                                        {{ $t("Facebook") }}
                                    </option>
                                    <option value="visit">{{ $t("Visit") }}</option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.initiatedBy"
                                    :error="errors.initiatedBy"
                                    :label="$t('Initiated By')"
                                >
                                    <!-- :error="form.errors.initiatedBy" -->
                                    <option value="customer">
                                        {{ $t("Customer") }}
                                    </option>
                                    <option value="docshero">
                                        {{ $t("Docs Hero") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Contact Report Files") }}
                    </h3>
                </div>
                <div class="card-body">
                    <file-inputs
                        @file-changed="addFiles"
                        :productFiles="form"
                    />
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/contact-reports" class="primary-btn mr-3">
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
import QuillTextEditor from "../../Components/QuillTextEditor.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import FileInputs from "../../Components/FileInputs.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", {
            users: "users",
        }),
        ...mapGetters("companies", {
            companies: "companies",
            leads: "leads",
        }),
        ...mapGetters("companyEmployees", {
            employees: "users",
            count: "count",
        }),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("contactSource", ["contactSources"]),
    },
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        QuillTextEditor,
        MultiSelectInput,
        FileInputs,
        PageHeader,
    },
    watch: {
        "form.companyId"(val) {
            this.fetchEmployees();
        },
        "form.type"() {
            this.show = false;
            this.form.companyId = "";
            this.form.companyEmployees = "";
            this.$nextTick(() => {
                this.show = true;
            });
        },
    },
    async mounted() {
        try {
            await this.$store
                .dispatch("reportCategories/list", {
                    perPage: 0,
                    page: 1,
                })
                .then((res) => {
                    this.reportCategory = res?.data?.data ?? [];
                });
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                customerType: "lead",
            });
            await this.$store.dispatch("auth/list", {
                limit_start: 0,
                limit_count: 25,
                type: "employee",
            });

            /**
             * if the lead is present in the query params then find the lead based on the lead from the query params and make the type
             * and customer read only
             */
            if (this.$route.query.lead) {
                this.form.type = "lead";
                await this.$nextTick();
                let foundLead = this.leads?.data?.find(
                    (lead) => lead.id == this.$route.query.lead
                );
                // if the lead does not exist in the lead list then fetch separately using id
                if (!foundLead) {
                    const response = await this.$store.dispatch(
                        "companies/show",
                        this.$route.query.lead
                    );
                    foundLead = response?.data?.modelData ?? "";
                }
                this.isFoundLead = true;
                this.form.companyId = foundLead;
                this.form.contactSources = foundLead?.contactReportSources;
            }

            if (!this.contactSources.data.length) {
                await this.$store.dispatch("contactSource/list", {
                    page: this.page,
                    search: this.form.search,
                });
            }
            this.form.createdByEmployee = this.users.find(
                (user) => user.id == this.user.id
            );
            this.form.assignee = this.users.find(
                (user) => user.id == this.user.id
            );
            this.isApiCalled = true;
        } catch (e) {}
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Sales",
                    to: "/contact-reports",
                },
                {
                    text: "Contact Reports",
                    to: "/contact-reports",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            show: true,
            reportCategory: [],
            isApiCalled: false,
            userId: "",
            form: {
                contactSources: "",
                subject: "",
                text: "",
                type: "customer",
                priority: "",
                categoryId: "",
                contactType: "",
                initiatedBy: "",
                companyId: "",
                createdByEmployee: "",
                companyEmployees: [],
                employees: [],
                resubmitOn: "",
                assignee: "",
                files: [],
            },
            isFoundLead: false,
            people: [],
        };
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async fetchEmployees() {
            try {
                await this.$store.dispatch("companyEmployees/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "customer",
                    company_id: this.form.companyId?.id,
                });
            } catch (e) {}
        },
        addFiles(data) {
            this.form.files = data;
        },
        async store() {
            try {
                const formData = new FormData();

                //append the form data properties
                for (const key in this.form) {
                    if (this.form.hasOwnProperty(key)) {
                        let value = this.form[key];
                        // Format date value
                        if (key === "resubmitOn" && value instanceof Date) {
                            value = value.toISOString();
                        }
                        if (Array.isArray(value)) {
                            // Convert the array to a string or JSON representation
                            const arrayAsString = JSON.stringify(value);

                            // Append the converted value to formData
                            formData.append(key, arrayAsString);
                        } else {
                            // Append other properties to formData
                            formData.append(key, value);
                        }
                    }
                }
                // Append uploaded files to the FormData object
                this.form.files.forEach((file) => {
                    formData.append("uploadedFiles[]", file);
                });
                formData.append("companyId", this.form.companyId?.id);
                formData.append(
                    "createdByEmployee",
                    this.form.createdByEmployee?.id
                );
                if (Array.isArray(this.form.companyEmployees)) {
                    formData.append(
                        "companyEmployees",
                        this.form.companyEmployees.map(
                            (employee) => employee.id
                        )
                    );
                }
                formData.append(
                    "employees",
                    this.form.employees.map((employee) => employee.id)
                );
                formData.append("assignee", this.form.assignee?.id ?? null);
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("contactReports/create", formData);
                await this.$store.dispatch("contactReports/list");
                // if the lead is present in query params then redirect to the lead edit page and set active tab as contact-reports in leads edit page
                if (this.$route.query.lead) {
                    this.$router.push(
                        `/leads/${this.$route.query.lead}/edit?activeTab=contact-reports`
                    );
                    return;
                }
                this.$router.push("/contact-reports");
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
