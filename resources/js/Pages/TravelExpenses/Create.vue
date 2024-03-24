<template>
    <div>
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
                        <span class="font-bold">{{ $t("Travel No") }}</span
                        >: <span>{{ $t("N/A") }}</span>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/2">
                        <span class="font-bold">{{ $t("Company") }}</span
                        >: <span>{{ company?.companyName ?? $t("N/A") }}</span>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/2">
                        <span class="font-bold">{{ $t("Team") }}</span
                        >: <span>{{ teamName }}</span>
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/2">
                        <span class="font-bold">{{ $t("Department") }}</span
                        >: <span>{{ departmentName }}</span>
                    </div>
                    <div v-if="location" class="pb-8 pr-6 w-full lg:w-1/2">
                        <span class="font-bold">{{ $t("Location") }}</span
                        >: <span>{{ getLocationDetails(location) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <form novalidate @submit.prevent="store">
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Travel Expense Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
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
                                    v-if="isApiCalled"
                                    :required="true"
                                    v-model="form.departureCountry"
                                    :error="errors.departureCountry"
                                    class=""
                                    :label="$t('Departure Country')"
                                >
                                    <option
                                        v-for="(country, key) in countries"
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
                                    v-if="isApiCalled"
                                    :required="true"
                                    v-model="form.arrivalCountry"
                                    :error="errors.arrivalCountry"
                                    class=""
                                    :label="$t('Arrival Country')"
                                >
                                    <option
                                        v-for="(country, key) in countries"
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
                                    :required="true"
                                    :textLabel="$t('Request Type')"
                                    v-model="form.requestType"
                                    :options="requestTypes.data"
                                    :multiple="false"
                                    class=""
                                    label="name"
                                    trackBy="id"
                                    moduleName="requestTypes"
                                    @update:model-value="
                                        showCustomerProjectDropdown
                                    "
                                />
                            </div>
                        </div>
                        <div class="w-full" v-if="projects.length > 0">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    :textLabel="$t('Select Project')"
                                    v-model="form.project"
                                    :options="projects"
                                    :multiple="false"
                                    class=""
                                    label="name"
                                    trackBy="id"
                                    moduleName="projects"
                                />
                            </div>
                        </div>
                        <div class="w-full" v-if="customers.length > 0">
                            <div class="form-group">
                                <MultiSelectInput
                                    :required="true"
                                    :textLabel="$t('Select Customer')"
                                    v-model="form.customer"
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
                        <div class="grid items-center grid-cols-1 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <label class="input-label"
                                        ><span style="color: red">*</span
                                        >&nbsp;{{ $t("Date") }}:</label
                                    >
                                    <datepicker
                                        class="form-control"
                                        :clearable="false"
                                        :enable-time-picker="false"
                                        auto-apply
                                        :close-on-auto-apply="false"
                                        v-model="form.fromDate"
                                        :class="errors.fromDate ? 'error' : ''"
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
                                        :required="true"
                                        v-model="form.fromDepartureTime"
                                        :type="`time`"
                                        class=""
                                        :label="$t('Departure Time')"
                                        :error="errors.fromDepartureTime ?? ''"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.fromArrivalTime"
                                        :type="`time`"
                                        class=""
                                        :label="$t('Arrival Time')"
                                        :error="errors.fromArrivalTime ?? ''"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <number-input
                                        :readonly="true"
                                        :required="true"
                                        :roundNearQuarter="true"
                                        :forceLeftBound="true"
                                        :showPrefix="false"
                                        v-model="form.fromDiscrepancy"
                                        :min="0"
                                        class=""
                                        :label="$t('Travelling Time')"
                                        :errors="errors.fromDiscrepancy ?? ''"
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
                        <div class="grid items-center grid-cols-1 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <label class="input-label"
                                        ><span style="color: red">*</span
                                        >&nbsp;{{ $t("Date") }}:</label
                                    >
                                    <datepicker
                                        class="form-control"
                                        :clearable="false"
                                        :enable-time-picker="false"
                                        auto-apply
                                        :close-on-auto-apply="false"
                                        v-model="form.toDate"
                                        :class="errors.toDate ? 'error' : ''"
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
                                        :required="true"
                                        v-model="form.toDepartureTime"
                                        :type="`time`"
                                        class=""
                                        :label="$t('Departure Time')"
                                        :error="errors.toDepartureTime ?? ''"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.toArrivalTime"
                                        :type="`time`"
                                        class=""
                                        :label="$t('Arrival Time')"
                                        :error="errors.toArrivalTime ?? ''"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <number-input
                                        :readonly="true"
                                        :required="true"
                                        :roundNearQuarter="true"
                                        :forceLeftBound="true"
                                        :showPrefix="false"
                                        v-model="form.toDiscrepancy"
                                        :min="0"
                                        class=""
                                        :label="$t('Travelling Time')"
                                        :errors="errors.toDiscrepancy ?? ''"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="secondary-btn mr-3"
                    @click="store(true)"
                    >{{ $t("Create & Approve") }}</loading-button
                >
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="secondary-btn"
                    @click="store(false)"
                    >{{ $t("Create Travel Expense") }}</loading-button
                >
                
            </div>
        </form>
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
        SelectInput,
        TextAreaInput,
    },
    remember: "form",
    data() {
        return {
            form: {
                departureCity: "",
                departureCountry: "",
                arrivalCity: "",
                arrivalCountry: "",
                requestType: "",
                fromDate: "",
                fromDepartureTime: "",
                fromArrivalTime: "",
                fromDiscrepancy: "0",
                toDate: "",
                toDepartureTime: "",
                toArrivalTime: "",
                toDiscrepancy: "0",
                project: "",
                customer: "",
                sendForApproval: false,
                description: "",
            },
            countries: [],
            company: "",
            projects: [],
            customers: [],
            profileResponse: {},
            isApiCalled: false, // Assign the profile response dataset here
        };
    },
    async mounted() {
        this.$store.commit("showLoader", true);
        const countries = await this.$store.dispatch(
            "countries/getAllCountries"
        );
        this.countries = countries?.data?.data;
        const defaultCountry = this.countries?.find(
            (country) => country.isDefault === true
        );
        this.form.departureCountry = defaultCountry?.id;
        this.form.arrivalCountry = defaultCountry?.id;
        this.isApiCalled = true;
        this.$store.commit("errors", {}); //Reset all errors
        await this.$store.dispatch("travelExpenseRequestType/list", {
            perPage: 10,
            page: 1,
        });
        if (this.user.company_id != "" && this.user.company_id != null) {
            const response = await this.$store.dispatch(
                "companies/show",
                this.user.company_id
            );
            this.company = response?.data?.modelData ?? {};
        }
        // Fetch the profile response and assign it to the data property
        this.profileResponse = await this.$store.dispatch(
            "userProfile/showJobByUser",
            this.user.id
        );
        this.$store.commit("showLoader", false);
    },
    methods: {
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
         * Handles the store action
         */
        async store(sendForApproval) {
            this.validateTime();
            if (
                !this.errors.toDate &&
                !this.errors.fromArrivalTime &&
                !this.errors.toArrivalTime
            ) {
                try {
                    this.$store.commit("isLoading", true);
                    await this.$store.dispatch("travelExpense/create", {
                        ...this.form,
                        toDate: this.form.toDate.toISOString(),
                        fromDate: this.form.fromDate.toISOString(),
                        requestType: this.form?.requestType?.id,
                        projectId: this.form?.project?.id,
                        customerId: this.form?.customer?.id,
                        sendForApproval: sendForApproval, // Update the sendForApproval based on the argument passed
                    });
                    this.$emit("created", true); // redirect to listing page
                } catch (e) {}
            }
        },
        /**
         * Shows project and customer dropdown based on checkbox selection
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
