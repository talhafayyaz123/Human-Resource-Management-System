<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="tab-header">
            <ul class="">
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'request-details'"
                        :class="
                            activeTab === 'request-details'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Request Details") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'bill'"
                        :class="
                            activeTab === 'bill'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Bills") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'transportation'"
                        :class="
                            activeTab === 'transportation'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Private Transportation") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'day'"
                        :class="
                            activeTab === 'day'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Day Specifications") }}
                    </a>
                </li>
            </ul>
        </div>
        <div id="myTabContent">
            <div
                v-show="activeTab === 'request-details'"
                class=""
                id="request-details"
                role="tabpanel"
                aria-labelledby="request-details-tab"
            >
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $t("User Details") }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-wrap">
                            <div class="pb-8 pr-6 w-full lg:w-1/2">
                                <span class="font-bold">{{
                                    $t("Personal Number")
                                }}</span
                                >:
                                <span>{{ personalNumber ?? $t("N/A") }}</span>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/2">
                                <span class="font-bold">{{ $t("Name") }}</span
                                >:
                                <span>{{
                                    user.first_name + " " + user.last_name
                                }}</span>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/2">
                                <span class="font-bold">{{ $t("Email") }}</span
                                >: <span>{{ user.email }}</span>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/2">
                                <span class="font-bold">{{
                                    $t("Travel No")
                                }}</span
                                >: <span>{{ $t("N/A") }}</span>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/2">
                                <span class="font-bold">{{
                                    $t("Company")
                                }}</span
                                >:
                                <span>{{
                                    company?.companyName ?? $t("N/A")
                                }}</span>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/2">
                                <span class="font-bold">{{ $t("Team") }}</span
                                >: <span>{{ teamName }}</span>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/2">
                                <span class="font-bold">{{
                                    $t("Department")
                                }}</span
                                >: <span>{{ departmentName }}</span>
                            </div>
                            <div
                                v-if="location"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                            >
                                <span class="font-bold">{{
                                    $t("Location")
                                }}</span
                                >:
                                <span>{{ getLocationDetails(location) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <form novalidate @submit.prevent="update">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $t("Update Travel Expense Details") }}
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="grid items-center grid-cols-2 gap-6">
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :isReadonly="
                                                form.sendForApproval == true
                                            "
                                            :required="true"
                                            v-model="form.departureCity"
                                            :error="errors?.departureCity"
                                            class=""
                                            :label="$t('Departure City')"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            :required="true"
                                            v-if="form.departureCountry"
                                            :isReadonly="
                                                form.sendForApproval == true
                                            "
                                            v-model="form.departureCountry"
                                            :error="errors.departureCountry"
                                            class=""
                                            :label="$t('Departure Country')"
                                        >
                                            <option
                                                v-for="(
                                                    country, key
                                                ) in countries"
                                                :value="country.id"
                                                :key="key"
                                            >
                                                {{ country.name }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <text-input
                                            :isReadonly="
                                                form.sendForApproval == true
                                            "
                                            :required="true"
                                            v-model="form.arrivalCity"
                                            :error="errors?.arrivalCity"
                                            class=""
                                            :label="$t('Arrival City')"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <select-input
                                            :required="true"
                                            v-if="form.arrivalCountry"
                                            :isReadonly="
                                                form.sendForApproval == true
                                            "
                                            v-model="form.arrivalCountry"
                                            :error="errors.arrivalCountry"
                                            class=""
                                            :label="$t('Arrival Country')"
                                        >
                                            <option
                                                v-for="(
                                                    country, key
                                                ) in countries"
                                                :value="country.id"
                                                :key="key"
                                            >
                                                {{ country.name }}
                                            </option>
                                        </select-input>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            :isDisabled="
                                                form.sendForApproval == true
                                            "
                                            :required="true"
                                            :textLabel="$t('Request Type')"
                                            v-model="form.requestType"
                                            :options="requestTypes.data"
                                            :multiple="false"
                                            class=""
                                            label="name"
                                            trackBy="id"
                                            moduleName="requestTypes"
                                            :key="form.requestType.id"
                                            @update:model-value="
                                                showCustomerProjectDropdown
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            :isDisabled="
                                                form.sendForApproval == true
                                            "
                                            v-if="projects.length > 0"
                                            :required="true"
                                            :textLabel="$t('Select Project')"
                                            v-model="form.project"
                                            :options="projects"
                                            :multiple="false"
                                            class=""
                                            label="name"
                                            trackBy="id"
                                            moduleName="projects"
                                            :key="
                                                form.project?.id ??
                                                'project-key-1'
                                            "
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            v-if="customers.length > 0"
                                            :required="true"
                                            :textLabel="$t('Select Customer')"
                                            v-model="form.customer"
                                            :key="form.customer"
                                            :options="customers"
                                            :multiple="false"
                                            class=""
                                            label="companyName"
                                            trackBy="id"
                                            moduleName="customers"
                                        />
                                    </div>
                                </div>
                                <div class="w-full col-span-2">
                                    <div class="form-group">
                                        <TextAreaInput
                                            :required="true"
                                            :label="$t('Description')"
                                            v-model="form.description"
                                            class=""
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start card mt-3">
                        <div class="w-1/2">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{ $t("From Date and Time information") }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div
                                    class="grid items-center grid-cols-1 gap-6"
                                >
                                    <div class="w-full">
                                        <div class="form-group">
                                            <label class="input-label"
                                                ><span style="color: red"
                                                    >*</span
                                                >&nbsp;{{ $t("Date") }}:</label
                                            >
                                            <datepicker
                                                class="form-control"
                                                :disabled="
                                                    form.sendForApproval == true
                                                "
                                                :clearable="false"
                                                :enable-time-picker="false"
                                                auto-apply
                                                :close-on-auto-apply="false"
                                                v-model="form.fromDate"
                                                :class="
                                                    errors.fromDate
                                                        ? 'error'
                                                        : ''
                                                "
                                            />
                                            <div
                                                v-if="errors?.fromDate"
                                                class="form-error"
                                            >
                                                {{ errors.fromDate }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <text-input
                                                :isReadonly="
                                                    form.sendForApproval == true
                                                "
                                                :required="true"
                                                v-model="form.fromDepartureTime"
                                                :type="`time`"
                                                class=""
                                                :label="$t('Departure Time')"
                                                :error="
                                                    errors.fromDepartureTime ??
                                                    ''
                                                "
                                            />
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <text-input
                                                :isReadonly="
                                                    form.sendForApproval == true
                                                "
                                                :required="true"
                                                v-model="form.fromArrivalTime"
                                                :type="`time`"
                                                class=""
                                                :label="$t('Arrival Time')"
                                                :error="
                                                    errors.fromArrivalTime ?? ''
                                                "
                                            />
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <number-input
                                                :isReadonly="true"
                                                :required="true"
                                                :roundNearQuarter="true"
                                                :forceLeftBound="true"
                                                :showPrefix="false"
                                                v-model="form.fromDiscrepancy"
                                                :min="0"
                                                class=""
                                                :label="$t('Travelling Time')"
                                                :errors="
                                                    errors.fromDiscrepancy ?? ''
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-1/2">
                            <div class="card-header">
                                <h3 class="card-title">
                                    {{ $t("To Date and Time information") }}
                                </h3>
                            </div>
                            <div class="card-body">
                                <div
                                    class="grid items-center grid-cols-1 gap-6"
                                >
                                    <div class="w-full">
                                        <div class="form-group">
                                            <label class="input-label"
                                                ><span style="color: red"
                                                    >*</span
                                                >&nbsp;{{ $t("Date") }}:</label
                                            >
                                            <datepicker
                                                class="form-control"
                                                :disabled="
                                                    form.sendForApproval == true
                                                "
                                                :clearable="false"
                                                :enable-time-picker="false"
                                                auto-apply
                                                :close-on-auto-apply="false"
                                                v-model="form.toDate"
                                                :class="
                                                    errors.toDate ? 'error' : ''
                                                "
                                            />
                                            <div
                                                v-if="errors?.toDate"
                                                class="form-error"
                                            >
                                                {{ errors.toDate }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <text-input
                                                :isReadonly="
                                                    form.sendForApproval == true
                                                "
                                                :required="true"
                                                v-model="form.toDepartureTime"
                                                :type="`time`"
                                                class=""
                                                :label="$t('Departure Time')"
                                                :error="
                                                    errors.toDepartureTime ?? ''
                                                "
                                            />
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <text-input
                                                :isReadonly="
                                                    form.sendForApproval == true
                                                "
                                                :required="true"
                                                v-model="form.toArrivalTime"
                                                :type="`time`"
                                                class=""
                                                :label="$t('Arrival Time')"
                                                :error="
                                                    errors.toArrivalTime ?? ''
                                                "
                                            />
                                        </div>
                                    </div>
                                    <div class="w-full">
                                        <div class="form-group">
                                            <number-input
                                                :isReadonly="true"
                                                :required="true"
                                                :roundNearQuarter="true"
                                                :forceLeftBound="true"
                                                :showPrefix="false"
                                                v-model="form.toDiscrepancy"
                                                :min="0"
                                                class=""
                                                :label="$t('Travelling Time')"
                                                :errors="
                                                    errors.toDiscrepancy ?? ''
                                                "
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="form.sendForApproval == false"
                        class="mt-4 flex justify-end"
                    >
                        <loading-button
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            :loading="isLoading"
                            class="secondary-btn mr-3"
                            @click="update(true)"
                            >{{ $t("Update & Approve") }}</loading-button
                        >
                        <loading-button
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            :loading="isLoading"
                            class="secondary-btn"
                            @click="update(false)"
                            >{{ $t("Update Travel Expense") }}</loading-button
                        >
                    </div>
                </form>
            </div>
            <div
                v-show="activeTab === 'bill'"
                class=""
                id="bill"
                role="tabpanel"
                aria-labelledby="bill-tab"
            >
                <Bill />
            </div>
            <div
                v-show="activeTab === 'transportation'"
                id="transportation"
                role="tabpanel"
                aria-labelledby="transportation-tab"
            >
                <Transportation
                    :transportationData="transportationData"
                    :errors="errors"
                    :departureCity="form.departureCity"
                    :arrivalCity="form.arrivalCity"
                />
            </div>
            <div
                v-show="activeTab === 'day'"
                id="day"
                role="tabpanel"
                aria-labelledby="day-tab"
            >
                <DaySpecification
                    :daySpecificationData="daySpecificationData"
                    :errors="errors"
                    :arrival-country="form.arrivalCountry"
                />
            </div>
        </div>
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import SelectInput from "../../Components/SelectInput.vue";
import timeDifferenceMixin from "@/Mixins/timeDifferenceMixin";
import Bill from "./ExpenseReport/Bill.vue";
import Transportation from "./ExpenseReport/Transportation.vue";
import DaySpecification from "./ExpenseReport/DaySpecification.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    mixins: [timeDifferenceMixin],
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("travelExpenseRequestType", ["requestTypes"]),
        teamName() {
            const teams = this.profileResponse?.data?.data?.teams || [];
            const uniqueTeamNames = [
                ...new Set(teams.map((team) => team.name)),
            ];
            return uniqueTeamNames.join(", ");
        },
        departmentName() {
            const teams = this.profileResponse?.data?.data?.teams || [];
            const uniqueDepartmentNames = [
                ...new Set(
                    teams
                        .filter((team) => team.department !== null) // Filter out teams with null department
                        .map((team) => team.department.name)
                ),
            ];
            return uniqueDepartmentNames.join(", ");
        },
        personalNumber() {
            const personalNumber =
                this.profileResponse?.data?.data?.personalNumber || "";
            return personalNumber;
        },
        location() {
            return this.profileResponse?.data?.data?.location || null;
        },
        timeDifferenceFrom() {
            return this.calculateTimeDifference(
                this.form.fromDepartureTime,
                this.form.fromArrivalTime
            );
        },
        timeDifferenceTo() {
            return this.calculateTimeDifference(
                this.form.toDepartureTime,
                this.form.toArrivalTime
            );
        },
    },
    watch: {
        timeDifferenceFrom(newVal) {
            // Update form.time when the timeDifferenceFrom computed property changes
            this.form.fromDiscrepancy = newVal;
        },
        timeDifferenceTo(newVal) {
            // Update form.time when the timeDifferenceTo computed property changes
            this.form.toDiscrepancy = newVal;
        },
    },
    components: {
        LoadingButton,
        TextInput,
        NumberInput,
        MultiSelectInput,
        TextAreaInput,
        SelectInput,
        Bill,
        Transportation,
        DaySpecification,
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
                    text: "Travel Expenses",
                    to: "/travel-expenses",
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            activeClasses: "active",
            inactiveClasses: "inactive",
            activeTab: "request-details",
            isLoaded: false,
            modelData: {},
            form: {
                departureCity: "",
                departureCountry: "",
                arrivalCity: "",
                arrivalCountry: "",
                fromDate: "",
                fromDepartureTime: "",
                fromArrivalTime: "",
                fromDiscrepancy: "0",
                toDate: "",
                toDepartureTime: "",
                toArrivalTime: "",
                toDiscrepancy: "0",
                description: "",
                requestType: "",
                project: "",
                customer: "",
                sendForApproval: "",
            },
            countries: [],
            company: "",
            projects: [],
            customers: [],
            profileResponse: {}, // Assign the profile response dataset here
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            try {
                const countries = await this.$store.dispatch(
                    "countries/getAllCountries"
                );
                this.countries = countries?.data?.data;
                if (
                    this.user.company_id != "" &&
                    this.user.company_id != null
                ) {
                    const response = await this.$store.dispatch(
                        "companies/show",
                        this.user.company_id
                    );
                    this.company = response?.data?.modelData ?? {};
                }
            } catch (e) {
                console.log(e);
            }
            try {
                await this.$store.dispatch("travelExpenseRequestType/list", {
                    perPage: 10,
                    page: 1,
                });

                const response = await this.$store.dispatch(
                    "travelExpense/show",
                    this.$route.params.id
                );
                this.modelData = response?.data?.data ?? {};
                this.form = {
                    departureCity: this.modelData.departureCity,
                    departureCountry: this.modelData.departureCountry,
                    arrivalCity: this.modelData.arrivalCity,
                    arrivalCountry: this.modelData.arrivalCountry,
                    fromDate: new Date(this.modelData.fromDate),
                    fromDepartureTime: this.modelData.fromDepartureTime,
                    fromArrivalTime: this.modelData.fromArrivalTime,
                    fromDiscrepancy: this.modelData.fromDiscrepancy,
                    toDate: new Date(this.modelData.toDate),
                    toDepartureTime: this.modelData.toDepartureTime,
                    toArrivalTime: this.modelData.toArrivalTime,
                    toDiscrepancy: this.modelData.toDiscrepancy,
                    sendForApproval: this.modelData.sendForApproval,
                    description: this.modelData.description,
                    requestType:
                        this.requestTypes?.data?.find(
                            (type) => type.id == this.modelData.requestType
                        ) ?? "",
                };
                await this.showCustomerProjectDropdown(this.form.requestType);
                if (this.projects.length > 0) {
                    this.form.project =
                        this.projects?.find(
                            (project) => project.id == this.modelData.projectId
                        ) ?? "";
                }
                if (this.customers.length > 0) {
                    this.form.customer =
                        this.customers?.find(
                            (customer) =>
                                customer.id == this.modelData.customerId
                        ) ?? "";
                }
                // Fetch the profile response and assign it to the data property
                this.profileResponse = await this.$store.dispatch(
                    "userProfile/showJobByUser",
                    this.user.id
                );
            } catch (e) {
            } finally {
                this.isLoaded = true;
            }
        },
        async update(sendForApproval) {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("travelExpense/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        toDate: this.form.toDate.toISOString(),
                        fromDate: this.form.fromDate.toISOString(),
                        requestType: this.form?.requestType?.id,
                        projectId: this.form?.project?.id,
                        customerId: this.form?.customer?.id,
                        sendForApproval: sendForApproval,
                    },
                });
            } catch (e) {}
        },
        async destroy() {
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    try {
                        await this.$store.dispatch(
                            "travelExpense/destroy",
                            this.$route.params.id
                        );
                        await this.$store.dispatch("travelExpense/list");
                        this.$router.push("/travel-expenses");
                    } catch (e) {}
                }
            });
        },
        /**
         * Validates the time inputs
         */
        validateTime() {
            const fromDate = new Date(this.form.fromDate);
            const toDate = new Date(this.form.toDate);

            if (fromDate > toDate) {
                this.errors.toDate =
                    "To Date must be greater than or equal to From Date";
            } else {
                this.errors.toDate = "";
            }

            if (this.form.fromDepartureTime && this.form.fromArrivalTime) {
                const fromDepartureTime = this.parseTimeString(
                    this.form.fromDepartureTime
                );
                const fromArrivalTime = this.parseTimeString(
                    this.form.fromArrivalTime
                );

                if (fromArrivalTime.getTime() <= fromDepartureTime.getTime()) {
                    this.errors.fromArrivalTime =
                        "Arrival time must be greater than the departure time";
                } else {
                    this.errors.fromArrivalTime = "";
                }
            }

            if (this.form.toDepartureTime && this.form.toArrivalTime) {
                const toDepartureTime = this.parseTimeString(
                    this.form.toDepartureTime
                );
                const toArrivalTime = this.parseTimeString(
                    this.form.toArrivalTime
                );

                if (toArrivalTime.getTime() <= toDepartureTime.getTime()) {
                    this.errors.toArrivalTime =
                        "Arrival time must be greater than the departure time";
                } else {
                    this.errors.toArrivalTime = "";
                }
            }
        },
        /**
         * Parses a time string and returns a Date object
         * @param {string} timeString - The time string to parse
         * @returns {Date} The parsed Date object
         */
        parseTimeString(timeString) {
            let date = new Date();
            date.setHours(Number(timeString.split(":")[0]));
            date.setMinutes(Number(timeString.split(":")[1]));
            return date;
        },
        /**
         * Shows project and customer dropdown based on approval levels
         * @param {Object} item - The item object
         */
        async showCustomerProjectDropdown(item) {
            if (item.projectSpecific) {
                //Get user projects
                const responseProject = await this.$store.dispatch(
                    "projects/list",
                    {
                        page: 1,
                        perPage: 25,
                    }
                );
                this.projects = responseProject?.data?.data ?? [];
            } else {
                this.projects = [];
            }

            if (item.customerSpecific) {
                //Get companies
                const responseCustomer = await this.$store.dispatch(
                    "companies/list",
                    {
                        page: 1,
                        perPage: 25,
                    }
                );
                this.customers = responseCustomer?.data?.data ?? [];
            } else {
                this.customers = [];
            }
        },
        /**
         * Retrieves and formats location details
         * @param {Object} location - The location object
         * @returns {string} The formatted location details
         */
        getLocationDetails(location) {
            const address = location.address || "";
            const street = location.street || "";
            const city = location.city || "";
            const state = location.state || "";
            const country = location.country || "";

            const locationDetails = [address, street, city, state, country]
                .filter((value) => value) // Filter out empty values
                .join(", ");

            return locationDetails;
        },
    },
};
</script>
