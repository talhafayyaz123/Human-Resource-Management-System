<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Fill User Details") }}</h3>
            </div>
            <div class="card-body">
                <form>
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.firstName"
                                    :error="errors.firstName"
                                    :required="true"
                                    :label="$t('First Name')"
                                    :floatingLabel="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.lastName"
                                    :error="errors.lastName"
                                    :required="true"
                                    :label="$t('Last Name')"
                                    :floatingLabel="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.email"
                                    :error="errors.email"
                                    :required="true"
                                    type="email"
                                    :label="$t('Email')"
                                    :floatingLabel="true"
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
                                    :floatingLabel="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.street"
                                    :error="errors.street"
                                    type="text"
                                    :label="$t('Street')"
                                    :floatingLabel="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.streetNumber"
                                    :error="errors.streetNumber"
                                    type="text"
                                    :label="$t('Street Number')"
                                    :floatingLabel="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.zipCode"
                                    :error="errors.zipCode"
                                    type="text"
                                    :label="$t('ZIP')"
                                    :floatingLabel="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.mobile"
                                    :error="errors.mobile"
                                    type="text"
                                    :label="$t('Mobile')"
                                    :floatingLabel="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.publicPhone"
                                    :error="errors.publicPhone"
                                    :label="$t('Phone')"
                                    :floatingLabel="true"
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
                                    :options="modifiedRoles"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :error="errors.types"
                                    v-model="form.types"
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
                            <div class="form-group">
                                <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <CustomIcon name="lckIcon" />
                                            </span>
                                        </div>
                                <text-input
                                    v-model="form.password"
                                    :key="form.inputType"
                                    :required="true"
                                    :error="errors.password"
                                    :type="inputType"
                                    :label="$t('Password')"
                                    :floatingLabel="true"
                                    :show-password="showPassword"
                                    @child-event="handleChildEvent"
                                />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :key="form.inputType"
                                    v-model="form.confirmPassword"
                                    :required="true"
                                    :error="errors.confirmPassword"
                                    :type="inputType"
                                    :label="$t('Confirm Password')"
                                    :floatingLabel="true"
                                    :show-password="showPassword"
                                    @child-event="handleChildEvent"
                                />
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
                        <div class="w-full flex justify-around">
                            <div class="form-group">
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
                            </div>
                            <div class="form-group">
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
                </form>
            </div>
        </div>

        <div v-if="form?.types?.includes('employee')">
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Personal Data") }}</h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="flex flex-wrap">
                            <date-input
                                v-model="form.dateOfBirth"
                                :required="true"
                                :error="errors.dateOfBirth"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Date of birth')"
                            />
                            <text-input
                                v-model="form.countryOfBirth"
                                :required="true"
                                :error="errors.countryOfBirth"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Country Of Birth')"
                            />

                            <text-input
                                v-model="form.placeOfBirth"
                                :required="true"
                                :error="errors.placeOfBirth"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Place Of Birth')"
                            />

                            <select-input
                                v-model="form.gender"
                                :required="true"
                                :error="errors.gender"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Gender')"
                            >
                                <option value="male">
                                    {{ $t("Male") }}
                                </option>
                                <option value="female">
                                    {{ $t("Female") }}
                                </option>
                                <option value="other">
                                    {{ $t("Other") }}
                                </option>
                            </select-input>

                            <text-input
                                :required="true"
                                v-model="form.nationality"
                                :error="errors.nationality"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Nationality')"
                            />

                            <select-input
                                :required="true"
                                :key="form.maritalStatus"
                                v-model="form.maritalStatus"
                                :error="errors.maritalStatus"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Marital Status')"
                            >
                                <option value="married">
                                    {{ $t("Married") }}
                                </option>
                                <option value="single">
                                    {{ $t("Single") }}
                                </option>
                                <option value="divorced">
                                    {{ $t("Divorced") }}
                                </option>
                                <option value="widowed">
                                    {{ $t("Widowed") }}
                                </option>
                            </select-input>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Private Address") }}</h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="flex flex-wrap">
                            <text-input
                                :required="true"
                                v-model="form.address"
                                :error="errors.address"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Address')"
                            />
                            <text-input
                                :required="true"
                                v-model="form.state"
                                :error="errors.state"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('State')"
                            />

                            <text-input
                                :required="true"
                                v-model="form.country"
                                :error="errors.country"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Country')"
                            />
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Job Data") }}</h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="flex flex-wrap">
                            <text-input
                                :required="true"
                                v-model="jobForm.jobTitle"
                                :error="errors.jobTitle"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Job Title')"
                            />

                            <text-input
                                :required="true"
                                v-model="jobForm.personalNumber"
                                :error="errors.personalNumber"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Personal Number')"
                            />
                            <MultiSelectInput
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :showLabels="false"
                                :required="true"
                                v-model="jobForm.supervisorId"
                                :options="users"
                                :multiple="false"
                                textLabel="Mentor"
                                label="first_name"
                                trackBy="id"
                                moduleName="auth"
                                :search-param-name="'search_string'"
                                :error="errors.supervisorId"
                            >
                                <template #beforeList>
                                    <div
                                        class="grid p-2 gap-2"
                                        style="
                                            grid-template-columns: 25% 25% 50%;
                                        "
                                    >
                                        <p class="font-bold">First Name</p>
                                        <p class="font-bold">Last Name</p>
                                        <p class="font-bold">Email</p>
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
                                        <p
                                            style="width: 10ch"
                                            class="overflow-text-users-table"
                                        >
                                            {{ props.option.last_name }}
                                        </p>
                                        <p class="overflow-text-users-table">
                                            {{ props.option.email }}
                                        </p>
                                    </div>
                                </template>
                            </MultiSelectInput>
                            <select-input
                                :required="true"
                                v-model="jobForm.contractType"
                                :error="errors.contractType"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Contract Type')"
                            >
                                <option value="fulltime">
                                    {{ $t("Full Time") }}
                                </option>
                                <option value="parttime">
                                    {{ $t("Part Time") }}
                                </option>
                            </select-input>
                            <date-input
                                :required="true"
                                v-model="jobForm.joinDate"
                                :error="errors.joinDate"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Join Date')"
                            />
                            <date-input
                                :required="true"
                                v-model="jobForm.accountingDate"
                                :error="errors.accountingDate"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Start Accounting Date')"
                            />

                            <select-input
                                :required="true"
                                v-model="jobForm.isMainJob"
                                :error="errors.isMainJob"
                                class="pb-8 pr-6 lg:w-1/3"
                                :label="$t('Is Main Job')"
                            >
                                <option :value="true">{{ $t("Yes") }}</option>
                                <option :value="false">{{ $t("No") }}</option>
                            </select-input>

                            <text-input
                                v-model="jobForm.costCenter"
                                :required="true"
                                type="number"
                                :error="errors.costCenter"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Cost Center')"
                            />

                            <select-input
                                :required="true"
                                v-model="jobForm.isEmployeeLeasing"
                                :error="errors.isEmployeeLeasing"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Is Employee Leasing')"
                            >
                                <option :value="true">{{ $t("True") }}</option>
                                <option :value="false">
                                    {{ $t("False") }}
                                </option>
                            </select-input>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Assigned Workspace") }}</h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="flex flex-wrap">
                            <MultiSelectInput
                                class="pb-8 pr-6 w-1/3"
                                :required="true"
                                :show-labels="false"
                                v-model="jobForm.locationId"
                                :options="locations.data"
                                :multiple="false"
                                :textLabel="$t('Location')"
                                label="address"
                                trackBy="id"
                                moduleName="locations"
                                :showLoadMoreText="true"
                                :error="errors.locationId"
                            >
                                <template #beforeList>
                                    <div
                                        class="grid p-2 gap-2"
                                        style="
                                            grid-template-columns: 16.66% 16.66% 16.66% 16.66% 16.66% 16.66%;
                                        "
                                    >
                                        <p class="font-bold">
                                            {{ $t("Name") }}
                                        </p>
                                        <p class="font-bold">
                                            {{ $t("Street") }}
                                        </p>
                                        <p class="font-bold">
                                            {{ $t("City") }}
                                        </p>
                                        <p class="font-bold">
                                            {{ $t("ZIP") }}
                                        </p>
                                        <p class="font-bold">
                                            {{ $t("State") }}
                                        </p>
                                        <p class="font-bold">
                                            {{ $t("Country") }}
                                        </p>
                                    </div>
                                    <hr />
                                </template>
                                <template #singleLabel="{ props }">
                                    {{ props.option.name }}
                                </template>
                                <template #option="{ props }">
                                    <div
                                        class="grid gap-2"
                                        style="
                                            grid-template-columns: 16.66% 16.66% 16.66% 16.66% 16.66% 16.66%;
                                        "
                                    >
                                        <p
                                            class="overflow-text-users-table location-length"
                                        >
                                            {{ props.option.name }}
                                        </p>
                                        <p
                                            class="overflow-text-users-table location-length"
                                        >
                                            {{ props.option.street }}
                                        </p>
                                        <p
                                            class="overflow-text-users-table location-length"
                                        >
                                            {{ props.option.city }}
                                        </p>
                                        <p
                                            class="overflow-text-users-table location-length"
                                        >
                                            {{ props.option.zipCode }}
                                        </p>
                                        <p
                                            class="overflow-text-users-table location-length"
                                        >
                                            {{ props.option.state }}
                                        </p>
                                        <p
                                            class="overflow-text-users-table location-length"
                                        >
                                            {{ props.option.country }}
                                        </p>
                                    </div>
                                </template>
                            </MultiSelectInput>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Holidays") }}</h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="flex flex-wrap">
                            <text-input
                                :required="true"
                                type="number"
                                v-model="jobForm.totalAnnualLeaves"
                                :error="errors.totalAnnualLeaves"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Total Annual Leaves')"
                            />

                            <text-input
                                :required="true"
                                type="number"
                                v-model="jobForm.remainingLeaves"
                                :error="errors.remainingLeaves"
                                class="pb-8 pr-6 w-full lg:w-1/3"
                                :label="$t('Remaining Leaves')"
                            />
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Working Hours") }}</h3>
                </div>
                <div class="card-body">
                    <form>
                        <div class="flex flex-wrap">
                            <div class="pb-8 pr-6 w-full">
                                <div
                                    v-for="(work, index) in jobForm.workHours"
                                    :key="index"
                                    class="flex pb-8"
                                    :class="{
                                        'pb-0':
                                            index ===
                                                jobForm.workHours.length - 1 &&
                                            errorsExist,
                                    }"
                                >
                                    <MultiSelectInput
                                        :required="true"
                                        v-model="work.days"
                                        class="pr-6 w-full lg:w-1/2"
                                        :textLabel="$t('Working Days')"
                                        :multiple="true"
                                        :options="[
                                            'mon',
                                            'tue',
                                            'wed',
                                            'thu',
                                            'fri',
                                            'sat',
                                            'sun',
                                        ]"
                                    >
                                        <template #option="{ props }">
                                            <p class="capitalize">
                                                {{ props.option }}
                                            </p>
                                        </template>
                                    </MultiSelectInput>

                                    <text-input
                                        type="number"
                                        v-model="work.numOfHours"
                                        :error="errors.numOfHours"
                                        class="pr-6 w-full lg:w-1/2"
                                        :label="$t('Daily Hours')"
                                    />
                                    <button
                                        role="button"
                                        type="button"
                                        @click="
                                            jobForm.workHours.splice(index, 1)
                                        "
                                        style="color: red"
                                        class="pt-7"
                                    >
                                        <font-awesome-icon
                                            ref="icon"
                                            icon="fa-regular fa-trash-can"
                                        ></font-awesome-icon>
                                    </button>
                                </div>
                                <div v-if="errorsExist" class="form-error mb-2">
                                    {{
                                        $t(
                                            "Duplication exists in the days. Please correct it"
                                        )
                                    }}
                                </div>
                                <div class="mb-4">
                                    <button
                                        role="button"
                                        type="button"
                                        @click="
                                            jobForm.workHours.push({
                                                days: '',
                                                numOfHours: '',
                                            })
                                        "
                                        class="px-3 py-1 text-white bg-blue-700 rounded hover:bg-blue-800 flex items-center space-x-1"
                                    >
                                        <svg
                                            class="w-4 h-4 text-white stroke-current stroke-2"
                                            viewBox="0 0 24 24"
                                        >
                                            <path d="M12 4v16m-8-8h16" />
                                        </svg>
                                        <span>{{ $t("Add") }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <router-link to="/employees" class="primary-btn mr-3">
                <span class="mr-1">
                    <CustomIcon name="cancelIcon" />
                </span>
                <span>{{ $t("Cancel") }}</span>
            </router-link>
            <loading-button
                @click="store"
                :loading="isLoading"
                class="secondary-btn"
            >
                <span class="mr-1">
                    <CustomIcon name="saveIcon" />
                </span>
                {{ $t("Save and Proceed") }}
            </loading-button>
        </div>
    </div>
</template>

<script>
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import DateInput from "@/Components/DateInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("userLocations", ["locations"]),
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("roles", ["roles"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("auth", ["user", "users"]),
        modifiedRoles() {
            if (this.user?.roles?.includes("admin")) {
                return this.roles;
            } else {
                return this.roles.filter((role) => role !== "admin");
            }
        },
        errorsExist() {
            for (let key in this.errors) {
                if (key.startsWith("workHoursArray.")) {
                    return true;
                }
            }
            return false;
        },
    },
    components: {
        DateInput,
        SelectInput,
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
                    to: "/employees",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            userId: null,
            form: {
                firstName: "",
                lastName: "",
                birthName: "",
                dateOfBirth: "",
                countryOfBirth: "",
                placeOfBirth: "",
                gender: "",
                nationality: "",
                maritalStatus: "",
                street: "",
                streetNumber: "",
                address: "",
                zipCode: "",
                city: "",
                state: "",
                country: "",
                phone: "",
                publicPhone: "",
                mobile: "",
                email: "",
                userId: "",
                password: "",
                confirmPassword: "",
                roles: [],
                types: [],
                userId: "",
                company_id: "",
                tenant_id: "",
                canBeDeleted: false,
                canBeEdited: false,
            },
            jobForm: {
                jobTitle: "",
                personalNumber: "",
                supervisorId: "",
                contractType: "",
                joinDate: "",
                accountingDate: "",
                isMainJob: true,
                costCenter: "",
                isEmployeeLeasing: false,
                locationId: "",
                totalAnnualLeaves: "",
                remainingLeaves: "",
                userId: "",
                workHours: [{ day: "", numOfHours: "" }],
                workHoursArray: [],
                remainingLeavesYear: new Date().getFullYear(),
                additionalLeaves: 0,
            },
            showPassword: false,
            inputType: "password"
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            try {
                await this.$store.dispatch("roles/list");
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "employee",
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
                this.$store.dispatch("userLocations/list", {
                    perPage: 10,
                    page: 1,
                });
            } catch (e) {}
        },
        addNewType(tag) {
            this.form.types.push(tag);
        },
        async store() {
            const errors = {};
            let containsError = false;
            if (!this.form.firstName) {
                errors["firstName"] = "First Name is a required field";
                containsError = true;
            }
            if (!this.form.lastName) {
                errors["lastName"] = "Last Name is a required field";
                containsError = true;
            }
            if (!this.form.email) {
                errors["email"] = "Email is a required field";
                containsError = true;
            }
            if (!this.form.password) {
                errors["password"] = "Password is a required field";
                containsError = true;
            }
            if (!this.form.company_id) {
                errors["company_id"] = "Company is a required field";
                containsError = true;
            }
            if (
                this.form.password.length &&
                this.form.password !== this.form.confirmPassword
            ) {
                errors["confirmPassword"] = "Passwords do not match";
                containsError = true;
            }
            if (this.form.types.includes("employee")) {
                if (!this.form.dateOfBirth) {
                    errors["dateOfBirth"] = "Date of birth is a required field";
                    containsError = true;
                }
                if (!this.form.countryOfBirth) {
                    errors["countryOfBirth"] =
                        "Country of birth is a required field";
                    containsError = true;
                }
                if (!this.form.placeOfBirth) {
                    errors["placeOfBirth"] =
                        "Place of birth is a required field";
                    containsError = true;
                }
                if (!this.form.gender) {
                    errors["gender"] = "Gender is a required field";
                    containsError = true;
                }
                if (!this.form.nationality) {
                    errors["nationality"] = "Nationality is a required field";
                    containsError = true;
                }
                if (!this.form.maritalStatus) {
                    errors["maritalStatus"] =
                        "Marital Status is a required field";
                    containsError = true;
                }
                if (!this.form.address) {
                    errors["address"] = "Address is a required field";
                    containsError = true;
                }
                if (!this.form.state) {
                    errors["state"] = "State is a required field";
                    containsError = true;
                }
                if (!this.form.country) {
                    errors["country"] = "Country is a required field";
                    containsError = true;
                }
                if (!this.form.street) {
                    errors["street"] = "Street is a required field";
                    containsError = true;
                }
                if (!this.jobForm.jobTitle) {
                    errors["jobTitle"] = "Job Title is a required field";
                    containsError = true;
                }
                if (!this.jobForm.supervisorId) {
                    errors["supervisorId"] = "Supervisor is a required field";
                    containsError = true;
                }
                if (!this.jobForm.personalNumber) {
                    errors["personalNumber"] =
                        "Personal Number is a required field";
                    containsError = true;
                }
                if (!this.jobForm.contractType) {
                    errors["contractType"] =
                        "Contract Type is a required field";
                    containsError = true;
                }
                if (!this.jobForm.joinDate) {
                    errors["joinDate"] = "Join Date is a required field";
                    containsError = true;
                }
                if (!this.jobForm.accountingDate) {
                    errors["accountingDate"] =
                        "Accounting Date is a required field";
                    containsError = true;
                }
                if (!this.jobForm.costCenter) {
                    errors["costCenter"] = "Cost Center is a required field";
                    containsError = true;
                }
                if (!this.jobForm.locationId) {
                    errors["locationId"] = "Location is a required field";
                    containsError = true;
                }
                if (!this.jobForm.totalAnnualLeaves) {
                    errors["totalAnnualLeaves"] =
                        "Total Annual Leaves is a required field";
                    containsError = true;
                }
                if (!this.jobForm.remainingLeaves) {
                    errors["remainingLeaves"] =
                        "Remaining Leaves is a required field";
                    containsError = true;
                }
                if (!this.jobForm.workHours) {
                    errors["workHours"] = "Work Hours is a required field";
                    containsError = true;
                }
            }
            if (containsError) {
                this.$store.commit("errors", errors);
                return;
            }
            try {
                this.$store.commit("isLoading", true);

                const response = await this.$store.dispatch("auth/create", {
                    first_name: this.form.firstName,
                    last_name: this.form.lastName,
                    mail: this.form.email,
                    city: this.form.city,
                    street: this.form.street,
                    street_number: this.form.streetNumber,
                    zip: this.form.zipCode,
                    mobile: this.form.mobile,
                    password: this.form.password,
                    confirmPassword: this.form.confirmPassword,
                    roles: this.form.roles.map((role) => role.id),
                    types: this.form.types,
                    company_id: this.form.company_id?.id ?? "",
                    tenant_id: this.form.tenant_id?.id ?? "",
                    can_be_deleted: this.form.canBeDeleted ? 1 : 0,
                    can_be_edited: this.form.canBeEdited ? 1 : 0,
                });
                if (this.form.types.includes("employee")) {
                    let output = [];
                    this.jobForm.workHours.forEach((obj) => {
                        const { days, numOfHours } = obj;
                        days.forEach((day) => {
                            output.push({ day: day, numOfHours: numOfHours });
                        });
                    });

                    this.jobForm.workHoursArray = output;
                    this.form.userId = response?.data ?? {};
                    const responseProfile = await this.$store.dispatch(
                        "userProfile/saveProfile",
                        {
                            ...this.form,
                            userId: this.form?.userId?.id,
                        }
                    );
                    this.userId = responseProfile?.data?.userId ?? null;
                    this.form.id = this.userId;
                    await this.$store.dispatch("userProfile/saveJob", {
                        ...this.jobForm,
                        supervisorId: this.jobForm?.supervisorId?.id,
                        userId: this.userId,
                    });
                    await this.$store.dispatch("auth/list", {
                        limit_start: 0,
                        limit_count: 25,
                        type: "employee",
                    });
                }
                this.$router.push("/employees");
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
