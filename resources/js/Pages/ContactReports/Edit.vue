<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.subject"
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
                                v-if="form.text"
                                :required="true"
                                :content="form.text"
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
                                    v-if="form.type"
                                    :error="errors.type"
                                    v-model="form.type"
                                    :label="$t('Type')"
                                >
                                    <!-- :error="form.errors.type" -->
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
                                    :required="true"
                                    :textLabel="
                                        form.type === 'lead'
                                            ? $t('Lead')
                                            : $t('Customer')
                                    "
                                    v-model="form.companyId"
                                    :key="form.companyId"
                                    :options="
                                        form.type === 'lead'
                                            ? leads.data
                                            : companies.data
                                    "
                                    :multiple="false"
                                    label="companyName"
                                    trackBy="id"
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
                                    v-model="form.companyEmployees"
                                    :key="form.companyEmployees"
                                    :error="errors.companyEmployees"
                                    :options="employees"
                                    :multiple="true"
                                    :custom-label="customLabel"
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
                                    :showLabels="false"
                                    v-model="form.assignee"
                                    :key="form.assignee"
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
                                <!-- :error="form.errors.companyEmployees" -->
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    :showLabels="false"
                                    v-model="form.employees"
                                    :key="form.employees"
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
                                <MultiSelectInput
                                    v-if="isApiCalled"
                                    :required="true"
                                    :textLabel="`Contact Report Source`"
                                    v-model="form.contactSources"
                                    :options="contactSources?.data"
                                    :multiple="true"
                                    label="name"
                                    trackBy="id"
                                    moduleName="sources"
                                    :error="errors.contactSources"
                                />
                                <!-- :error="form.errors.employees" -->
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    v-model="form.createdByEmployee"
                                    :key="form.createdByEmployee"
                                    :showLabels="false"
                                    :options="users"
                                    :multiple="false"
                                    :textLabel="$t('Created By')"
                                    :customLabel="customLabel"
                                    label="first_name"
                                    trackBy="id"
                                    moduleName="auth"
                                    :search-param-name="'search_string'"
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
                                        {{ props.option.first_name }}
                                        {{ props.option.last_name }}
                                    </template>
                                    <template #option="{ props }">
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
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-if="form.priority"
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
                                    v-if="form.categoryId"
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
                                    v-if="form.contactType"
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
                                    v-if="form.initiatedBy"
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
            <div class="flex items-center justify-end mt-4">
                <router-link :to="`/contact-reports?page=${returnPage}`" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    @click.prevent="generate('pdf')"
                    :loading="isLoading"
                    class="secondary-btn mr-3"
                    >{{ $t("Generate PDF") }}</loading-button
                >
                <loading-button
                    @click.prevent="generate('docx')"
                    :loading="isLoading"
                    class="secondary-btn mr-3"
                    >{{ $t("Generate Docx") }}</loading-button
                >
                <loading-button
                    @click.prevent="update"
                    v-if="$can(`${$route.meta.permission}.edit`)"
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
        ...mapGetters("contactSource", ["contactSources"]),
    },
    async mounted() {
        this.$store.commit("showLoader", true);
        await this.$store
            .dispatch("reportCategories/list", {
                perPage: 0,
                page: 1,
            })
            .then((res) => {
                this.reportCategory = res?.data?.data ?? [];
            });
        if (!this.contactSources.data.length) {
            await this.$store.dispatch("contactSource/list", {
                page: this.page,
                search: this.form.search,
            });
        }
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });
        this.refresh();
    },
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        QuillTextEditor,
        MultiSelectInput,
        PageHeader,
    },
    watch: {
        "form.companyId"(val) {
            this.fetchEmployees();
        },
        "form.type"() {
            this.shouldShow = false;
            this.form.companyId = "";
            this.form.companyEmployees = "";
            this.$nextTick(() => {
                this.shouldShow = true;
            });
        },
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
                    to: "/contact-reports?page="+this.$route.query.page,
                },
                {
                    text: this.$t("Edit"),
                    active: true,
                },
            ],
            showCompanyEmployees: false,
            shouldShow: true,
            show: false,
            returnPage:'',
            reportCategory: [],
            modelData: {},
            form: {
                subject: "",
                text: "",
                type: "",
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
                contactSources: "",
            },
            isApiCalled: false,
            people: [],
        };
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async refresh() {
            if(this.$route.query.page){
             this.returnPage=this.$route.query.page; 
            }
            
            try {
                const response = await this.$store.dispatch(
                    "contactReports/show",
                    this.$route.params.id
                );
                this.modelData = response?.data?.modelData ?? {};
                document.title =
                    "Edit Contact Report " + this.modelData?.subject;
                await this.$store.dispatch("companies/list", {
                    perPage: 25,
                    page: 1,
                    selectedIds: [this.modelData.companyId],
                });
                await this.$store.dispatch("companies/list", {
                    perPage: 25,
                    page: 1,
                    customerType: "lead",
                    selectedIds: [this.modelData.companyId],
                });
                this.form = {
                    subject: this.modelData.subject,
                    text: this.modelData.text,
                    type: this.modelData.type,
                    priority: this.modelData.priority,
                    categoryId: this.modelData.categoryId,
                    contactType: this.modelData.contactType,
                    initiatedBy: this.modelData.initiatedBy,
                    resubmitOn: "",
                    companyId: "",
                    createdByEmployee:
                        this.users?.find(
                            (user) =>
                                user.id == this.modelData.createdByEmployee
                        ) ?? "",
                    companyEmployees: [],
                    employees:
                        this.users?.filter((user) =>
                            this.modelData?.employees?.some(
                                (employee) => employee == user.id
                            )
                        ) ?? [],
                    assignee:
                        this.users?.find(
                            (user) => user.id == this.modelData.assignee
                        ) ?? "",
                    contactSources: this.modelData.contactSources,
                };
                if (this.modelData.resubmitOn) {
                    this.form.resubmitOn = new Date(this.modelData.resubmitOn);
                }
                this.$nextTick(() => {
                    this.form.companyId =
                        this.modelData.type === "lead"
                            ? this.leads?.data?.find(
                                  (company) =>
                                      company.id == this.modelData.companyId
                              ) ?? ""
                            : this.companies?.data?.find(
                                  (company) =>
                                      company.id == this.modelData.companyId
                              ) ?? "";
                    this.show = true; //necessary because the multiselect dropdown does not work when the page loads, so we have to add a little delay with nextTick
                });
                this.isApiCalled = true;
            } catch (e) {
                console.log(e);
            }
            finally {
                this.$store.commit("showLoader", false);
            }
        },
        async fetchEmployees() {
            try {
                await this.$store.dispatch("companyEmployees/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "customer",
                    company_id: this.form.companyId?.id,
                });
                this.form.companyEmployees =
                    this.employees?.filter((employee) =>
                        this.modelData.companyEmployees.some(
                            (companyEmployee) => companyEmployee == employee.id
                        )
                    ) ?? [];
                this.showCompanyEmployees = true;
            } catch (e) {}
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                
                await this.$store.dispatch("contactReports/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        companyId: this.form.companyId?.id,
                        createdByEmployee: this.form.createdByEmployee?.id,
                        companyEmployees: this.form.companyEmployees.map(
                            (employee) => employee.id
                        ),
                        employees: this.form.employees.map(
                            (employee) => employee.id
                        ),
                        assignee: this.form.assignee?.id ?? null,
                    },
                });
                await this.$store.dispatch("contactReports/list");
              
                this.$router.push("/contact-reports?page="+this.returnPage);
            } catch (e) {
            }
        },
        generate(documentType) {
            this.generateProcessTemplate(documentType);
        },
        async generateProcessTemplate(documentType) {
            const templateId = await this.$store.dispatch(
                "globalSettings/getTemplateByName",
                "contactReportTemplateId"
            );

            if (templateId != "") {
                const payload = {
                    ...this.form,
                    id: templateId,
                    output: documentType,
                    userFirstName: this.form.createdByEmployee?.first_name,
                    userLastName: this.form.createdByEmployee?.last_name,
                    userPhone: this.form.createdByEmployee?.phone,
                    reportCategory: this.reportCategory.find(
                        (report) => report.id === this.form.categoryId
                    ),
                    updatedTime: Date.now(),
                };

                this.$store.commit("isLoading", true);
                await this.$store.dispatch("documentService/processTemplate", {
                    data: payload,
                    filename:
                        "contact-report-" +
                        this.$route.params.id +
                        `.${documentType}`,
                    documentType: documentType,
                });
            }
        },
    },
};
</script>
