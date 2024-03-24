<template>
    <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

    <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ $t("Configuration") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-3 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            :required="true"
                            v-model="form.receiverType"
                            :key="form.receiverType"
                            :error="errors.receiverType"
                            :label="$t('Receiver Type')"
                        >
                            <option value="customer">{{ $t("Customer") }}</option>
                            <option value="lead">{{ $t("Lead") }}</option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-if="shouldShow"
                            v-model="form.company"
                            :key="form.company"
                            :required="true"
                            @update:model-value="
                                fetchProjectsByCustomer();
                                fetchCompanyLeadEmployees();
                            "
                            :textLabel="$t('Receiver')"
                            :placeholder="$t('Select Receiver')"
                            :options="
                                form.receiverType === 'lead' ? leads.data : companies.data
                            "
                            :multiple="false"
                            label="companyName"
                            trackBy="id"
                            moduleName="companies"
                            :error="errors.receiverId"
                            :query="{ customerType: form.receiverType }"
                            :countStore="
                                form.receiverType === 'lead' ? 'leadCount' : 'count'
                            "
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :model-value="
                                (authUserProfile?.firstName ?? '') +
                                ' ' +
                                (authUserProfile?.lastName ?? '')
                            "
                            :isReadonly="true"
                            :error="errors.createdBy"
                            :label="$t('Created By')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-model="form.projectId"
                            :textLabel="$t('Project')"
                            :options="projects?.data ?? []"
                            :multiple="false"
                            label="projectNumber"
                            trackBy="id"
                            moduleName="projects"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-area-input
                            :required="true"
                            v-model="form.text"
                            :error="errors.text"
                            :label="$t('Information for offer')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :customLabel="customLabel"
                            v-model="form.contactPerson"
                            :key="form.contactPerson"
                            :textLabel="$t('Contact Person')"
                            :options="companyEmployees"
                            :multiple="false"
                            trackBy="id"
                            moduleName="companyEmployees"
                            :error="errors.contactPerson"
                        />
                        <template v-if="form.company?.id">
                            <p>{{ $t("or") }}</p>
                            <p
                                @click="openCreateContactPersonModal"
                                class="underline text-blue-800 cursor-pointer"
                            >
                                {{ $t("Create Contact Person") }}
                            </p>
                        </template>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <select-input
                            :required="true"
                            v-model="form.requestStatus"
                            :error="errors.requestStatus"
                            :key="form.requestStatus"
                            :label="$t('Status')"
                        >
                            <option value="draft">Draft</option>
                            <option value="open">Open</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                            <option value="approved">Approved</option>
                        </select-input>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <p>{{ $t("Components") }}:</p>
                        <div class="mt-2">
                            <div class="flex">
                                <input
                                    @input="
                                        resetProductsUponUncheck(
                                            $event,
                                            'softwareLicenses'
                                        );
                                        softwareMaintenanceToggle =
                                            !softwareMaintenanceToggle;
                                    "
                                    v-model="softwareLicencesToggle"
                                    type="checkbox"
                                />
                                <label class="ml-1" for="">{{
                                    $t("Software Licences")
                                }}</label>
                            </div>
                            <div class="flex">
                                <input
                                    @input="
                                        resetProductsUponUncheck(
                                            $event,
                                            'softwareMaintenance'
                                        );
                                        softwareLicencesToggle =
                                            !softwareLicencesToggle;
                                    "
                                    v-model="softwareMaintenanceToggle"
                                    type="checkbox"
                                />
                                <label class="ml-1" for="">{{
                                    $t("Software Maintenance")
                                }}</label>
                            </div>
                            <div class="flex">
                                <input
                                    @input="
                                        resetProductsUponUncheck($event, 'services')
                                    "
                                    v-model="servicesToggle"
                                    type="checkbox"
                                />
                                <label class="ml-1" for="">{{ $t("Services") }}</label>
                            </div>
                            <div class="flex">
                                <input
                                    @input="
                                        resetProductsUponUncheck($event, 'application')
                                    "
                                    v-model="applicationManagementServiceToggle"
                                    type="checkbox"
                                />
                                <label class="ml-1" for="">{{
                                    $t("Application Management Service")
                                }}</label>
                            </div>
                            <div class="flex">
                                <input
                                    @input="resetProductsUponUncheck($event, 'hosting')"
                                    v-model="hostingToggle"
                                    type="checkbox"
                                />
                                <label class="ml-1" for="">{{ $t("Hosting") }}</label>
                            </div>
                            <div class="flex">
                                <input
                                    @input="
                                        resetProductsUponUncheck(
                                            $event,
                                            'cloud-software'
                                        )
                                    "
                                    v-model="cloudToggle"
                                    type="checkbox"
                                />
                                <label class="ml-1" for="">{{ $t("Cloud") }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br />

    <template v-if="form.company?.companyName">
        <div class="card my-5">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Company Details") }}</h3>
            </div>
            <div class="card-body">
                <span>
                    {{ form.company?.companyName }}
                    {{ form.company?.address }}
                    <br />
                    {{ form.company?.code }}&nbsp;{{ form.company?.city }}
                    <br />
                    {{ form.company?.state }}
                    <br />
                    {{ form.company?.country }}
                </span>
            </div>
        </div>
    </template>

    <div class="card my-5">
        <div class="card-header">
            <h3 class="card-title flex justify-between">
                <h1 class="secondary-color text-2xl">
                    {{ $t(`Draft Offer Request`) }}
                </h1>
                <h6>
                    {{ $dateFormatter(formatDateLite(new Date()), globalLanguage) }}
                </h6>
            </h3>
        </div>
        <div class="card-body">
            <div
                class="grid gap-2"
                style="grid-template-columns: repeat(auto-fit, 500px)"
            >
                <div class="mb-8" style="color: #2957a4">
                    <div
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{ $t("Customer Nr.") }}</b>
                        <p>{{ form?.company?.companyNumber ?? "-" }}</p>
                    </div>
                    <div
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{ $t(`Offer Request Nr.`) }}</b>
                        <p>
                            {{ $t(`Draft Offer Request`) }}
                        </p>
                    </div>
                </div>
                <div class="mb-8" style="color: #2957a4">
                    <div
                        v-if="user"
                        class="grid gap-2"
                        style="grid-template-columns: repeat(auto-fit, 200px)"
                    >
                        <b>{{ $t("Created By") }}:</b>
                        <p>{{ user?.first_name }} {{ user?.last_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="grid-template-rows: repeat(auto-fill, auto)" class="grid gap-3">
        <div v-if="softwareLicencesToggle">
            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="software-license-accordion"
                        :checked="true"
                    />
                    <label
                        style="background-color: #2957a4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 text-white"
                        for="software-license-accordion"
                    >
                        <p class="self-center">{{ $t("Software licences") }}</p>
                    </label>
                    <div class="tab-content border p-2 overflow-auto">
                        <div class="overflow-x-auto">
                            <TextAreaInput
                                :required="true"
                                :label="$t('Description')"
                                v-model="softwareLicences.description"
                            />
                            <div class="mt-5">
                                <label
                                    ><span class="text-red-500">*&nbsp;</span
                                    >{{ $t("License Report") }}:</label
                                >
                                <file-inputs
                                    class="mt-3"
                                    @file-changed="addFiles"
                                    :productFiles="
                                        softwareLicences.licenseReport
                                    "
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="softwareMaintenanceToggle">
            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="software-maintenance-accordion"
                        :checked="true"
                    />
                    <label
                        style="background-color: #2957a4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 text-white"
                        for="software-maintenance-accordion"
                    >
                        <p class="self-center">
                            {{ $t("Software Maintenance") }}
                        </p>
                    </label>
                    <div class="tab-content border p-2 overflow-auto">
                        <div class="overflow-x-auto">
                            <TextAreaInput
                                :required="true"
                                :label="$t('Description')"
                                v-model="softwareMaintenance.description"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="servicesToggle">
            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="services-accordion"
                        :checked="true"
                    />
                    <label
                        style="background-color: #2957a4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 text-white"
                        for="services-accordion"
                    >
                        <p class="self-center">{{ $t("Services") }}</p>
                    </label>
                    <div class="tab-content border p-2 overflow-auto">
                        <div class="overflow-x-auto">
                            <NumberInput
                                class="w-1/3 mb-5"
                                :required="true"
                                :showPrefix="false"
                                :label="$t('Estimated Work (h)')"
                                v-model="services.estimatedWork"
                            />
                            <TextAreaInput
                                :required="true"
                                :label="$t('Description')"
                                v-model="services.description"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="applicationManagementServiceToggle">
            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="ams-accordion"
                        :checked="true"
                    />
                    <label
                        style="background-color: #2957a4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 text-white"
                        for="ams-accordion"
                    >
                        <p class="self-center">
                            {{ $t("Application Management Service") }}
                        </p>
                    </label>
                    <div class="tab-content border p-2 overflow-auto">
                        <div class="overflow-x-auto">
                            <TextAreaInput
                                :required="true"
                                :label="$t('Description')"
                                v-model="ams.description"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="hostingToggle">
            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="hosting-accordion"
                        :checked="true"
                    />
                    <label
                        style="background-color: #2957a4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 text-white"
                        for="hosting-accordion"
                    >
                        <p class="self-center">
                            {{ $t("Hosting") }}
                        </p>
                    </label>
                    <div class="tab-content border p-2 overflow-auto">
                        <div class="overflow-x-auto">
                            <TextAreaInput
                                :required="true"
                                :label="$t('Description')"
                                v-model="hosting.description"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="cloudToggle">
            <div class="tabs">
                <div class="relative tab">
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        id="cloud-accordion"
                        :checked="true"
                    />
                    <label
                        style="background-color: #2957a4"
                        class="rounded tab-label styles-configurator-tab-label flex justify-between p-2 text-white"
                        for="cloud-accordion"
                    >
                        <p class="self-center">
                            {{ $t("Cloud") }}
                        </p>
                    </label>
                    <div class="tab-content border p-2 overflow-auto">
                        <div class="overflow-x-auto">
                            <TextAreaInput
                                :required="true"
                                :label="$t('Description')"
                                v-model="cloud.description"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <router-link to="/offer-requests" class="primary-btn mr-3">
                <span class="mr-1">
                    <CustomIcon name="cancelIcon" />
                </span>
                <span>{{ $t("Cancel") }}</span>
            </router-link>
            <loading-button :loading="isLoading" @click="create('create')" class="secondary-btn">
                <span class="mr-1">
                    <CustomIcon name="saveIcon" />
                </span>
                {{ $t("Save and Proceed") }}
            </loading-button>
        </div>
    </div>
    <create-contact-person-modal
        v-if="toggleEmployeeModal"
        @userAdded="userAdded"
        :toggleEmployeeModal="toggleEmployeeModal"
        @toggleEmployeeModal="toggleEmployeeModal = $event"
        :company="form.company"
    />
</template>

<script>
import SelectInput from "../../Components/SelectInput.vue";
import TextInput from "../../Components/TextInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import CreateContactPersonModal from "./Components/CreateContactPersonModal.vue";
import FileInputs from "../../Components/FileInputs.vue";
import { mapGetters } from "vuex";
import LoadingButton from "../../Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    data() {
        return {
            softwareLicencesToggle: false,
            softwareMaintenanceToggle: false,
            applicationManagementServiceToggle: false,
            hostingCloudToggle: false,
            servicesToggle: false,
            toggleEmployeeModal: false,
            hostingToggle: false,
            cloudToggle: false,
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Sales",
                    to: "/offer-requests",
                },
                {
                    text: "Offer Requests",
                    to: "/offer-requests",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            modelData: {},
            productTypeEnums: {
                software: "software",
                service: "service",
                ams: "ams",
                hosting: "hosting",
                "cloud-software": "cloudSoftware",
            },
            productType: "software",
            shouldShow: true,
            show: true,
            form: {
                company: null,
                receiverType: "customer",
                productsGroupedBy: "nothing",
                text: "",
                projectId: "",
                status: "open",
                requestStatus: 'draft',
                createdBy: "",
                contactPerson: "",
            },
            softwareLicences: {
                description: "",
                licenseReport: {
                    files: [],
                },
                estimatedWork: 0,
            },
            softwareMaintenance: {
                description: "",
                estimatedWork: 0,
            },
            services: {
                description: "",
                estimatedWork: 0,
            },
            ams: {
                description: "",
                estimatedWork: 0,
            },
            hosting: {
                description: "",
                estimatedWork: 0,
            },
            cloud: {
                description: "",
                estimatedWork: 0,
            },
        };
    },
    async mounted() {
        try {
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
            this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                customerType: "lead",
            });
            this.fetchAuthUsers();
        } catch (e) {}
    },
    computed: {
        ...mapGetters("companyEmployees", {
            companyEmployees: "users",
        }),
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", ["user", "users"]),
        ...mapGetters("userProfile", ["authUserProfile"]),
        ...mapGetters("projects", ["projects"]),
        ...mapGetters(["errors"]),
        ...mapGetters("companies", ["companies", "leads"]),
        dropdownStyles() {
            return {
                "--elem-hover-bg-color": "#312E81",
                "--elem-selected-bg-color": "#312E81",
            };
        },
    },
    watch: {
        "form.receiverType"() {
            this.shouldShow = false;
            this.form.company = "";
            this.$nextTick(() => {
                this.shouldShow = true;
            });
        },
    },
    unmounted() {
        this.$store.commit("projects/projects", {
            data: [],
            links: [],
        });
    },
    methods: {
        resetProductsUponUncheck() {},
        // fetch auth user listing if not already fetched
        fetchAuthUsers() {
            if (!this.users.length) {
                this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                });
            }
        },
        /**
         * triggered when new user is added in CreateContactPersonModal
         * auto selects contactPerson
         * refreshes company employees listing and auth listing
         * @param {addedUser} newly added user object
         */
        async userAdded(addedUser) {
            try {
                await this.fetchCompanyLeadEmployees();
                this.form.contactPerson = addedUser ?? "";
                this.fetchAuthUsers();
            } catch (e) {
                console.log(e);
            }
        },
        openCreateContactPersonModal() {
            this.toggleEmployeeModal = true;
        },
        /**
         * triggered when a company is selected
         * fetches the company employees related to the selected company
         */
        fetchCompanyLeadEmployees() {
            return new Promise(async (resolve, reject) => {
                try {
                    this.form.contactPerson = ""; // reset the contact person when the customer changes
                    await this.$store.dispatch("companyEmployees/list", {
                        limit_start: 0,
                        limit_count: 25,
                        type: this.form.receiverType,
                        company_id: this.form.company?.id,
                    });
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        /**
         * fetch projects based on customerId
         */
        fetchProjectsByCustomer() {
            return new Promise(async (resolve, reject) => {
                try {
                    this.form.project = null;
                    await this.$store.dispatch("projects/list", {
                        companyId: this.form.company?.id,
                    });
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        /**
         * formats the option label in multiselect input
         * @param {props} the options received from the multiselect input
         * @returns the formated label
         */
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async create() {
            const payload = new FormData();

            payload.append("receiverId", this.form.company?.id ?? "");
            payload.append("receiverType", this.form.receiverType);
            payload.append("groupedBy", this.form.productsGroupedBy);
            payload.append("text", this.form.text);
            payload.append("projectId", this.form.projectId?.id ?? "");
            payload.append("status", this.form.status);
            payload.append("requestStatus", this.form.requestStatus);
            payload.append("createdBy", this.authUserProfile?.id ?? "");
            payload.append("contactPerson", this.form.contactPerson?.id ?? "");

            if (this.softwareLicencesToggle) {
                payload.append("components[0][type]", "license");
                for (const key in this.softwareLicences) {
                    if (this.softwareLicences.hasOwnProperty(key)) {
                        if (key === "licenseReport") {
                            payload.append(
                                `components[0][${key}]`,
                                this.softwareLicences[key]?.["files"]?.[0] ?? ""
                            );
                        } else {
                            payload.append(
                                `components[0][${key}]`,
                                this.softwareLicences[key]
                            );
                        }
                    }
                }
            }

            if (this.softwareMaintenanceToggle) {
                payload.append("components[1][type]", "maintenance");
                for (const key in this.softwareMaintenance) {
                    if (this.softwareMaintenance.hasOwnProperty(key)) {
                        payload.append(
                            `components[1][${key}]`,
                            this.softwareMaintenance[key]
                        );
                    }
                }
            }

            if (this.servicesToggle) {
                payload.append("components[2][type]", "service");
                for (const key in this.services) {
                    if (this.services.hasOwnProperty(key)) {
                        payload.append(
                            `components[2][${key}]`,
                            this.services[key]
                        );
                    }
                }
            }

            if (this.applicationManagementServiceToggle) {
                payload.append("components[3][type]", "application");
                for (const key in this.ams) {
                    if (this.ams.hasOwnProperty(key)) {
                        payload.append(`components[3][${key}]`, this.ams[key]);
                    }
                }
            }

            if (this.hostingToggle) {
                payload.append("components[4][type]", "hosting");
                for (const key in this.hosting) {
                    if (this.hosting.hasOwnProperty(key)) {
                        payload.append(
                            `components[4][${key}]`,
                            this.hosting[key]
                        );
                    }
                }
            }

            if (this.cloudToggle) {
                payload.append("components[5][type]", "cloud");
                for (const key in this.cloud) {
                    if (this.cloud.hasOwnProperty(key)) {
                        payload.append(
                            `components[5][${key}]`,
                            this.cloud[key]
                        );
                    }
                }
            }

            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(`offerRequests/create`, payload);
                this.$store.dispatch(`offerRequests/list`);
                this.$router.push(`/offer-requests`);
            } catch (e) {}
        },
    },
    components: {
        FileInputs,
        SelectInput,
        MultiSelectInput,
        TextAreaInput,
        CreateContactPersonModal,
        LoadingButton,
        TextInput,
        NumberInput,
        PageHeader,
    },
};
</script>

<style lang="scss" scoped>
li {
    list-style: none;
}
.tabs {
    overflow: hidden;
}

.tab {
    width: 100%;
    color: black;
    overflow: hidden;
    &-label {
        display: flex;
        justify-content: space-between;
        cursor: pointer;
    }
    &-content {
        display: none;
        max-height: 0;
        color: black;
        transition: all 0.35s;
    }
    &-close {
        display: flex;
        justify-content: flex-end;
        font-size: 0.75em;
        cursor: pointer;
        &:hover {
        }
    }
}
.styles-configurator-tab-label::after {
    margin-top: 0px !important;
}

// :checked
input:checked {
    ~ .tab-content {
        display: block;
        max-height: 100vh;
    }
}

.inner-accordion-label::after {
    transform: rotate(90deg);
    transform-origin: center;
}

input[class="tab-checkbox"] {
    position: absolute;
    opacity: 0;
    z-index: -1;
}

.inner-accordion {
    display: none;
}
</style>
