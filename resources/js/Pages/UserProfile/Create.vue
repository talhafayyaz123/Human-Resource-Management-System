<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="card mb-3" v-if="profileForm?.firstName">
            <div class="grid grid-cols-[1fr,1fr,1fr,1fr] gap-2 mb-3">
                <input
                    @change="handleFileChange"
                    type="file"
                    ref="fileInput"
                    style="display: none"
                />
                <div class="flex justify-center items-center p-2">
                    <div
                        @click="this.$refs.fileInput.click()"
                        :style="{ backgroundImage: 'url(' + userImage + ')' }"
                        style="
                            cursor: pointer;
                            background-position: center center;
                            background-repeat: no-repeat;
                            background-size: cover;
                        "
                        class="avatar-container"
                    >
                        <!-- <img
                            class="image"
                            src="images/default-avatar.png"
                            alt="avatar"
                        /> -->
                        <font-awesome-icon
                            icon="fas fa-camera image-selector"
                        />
                    </div>
                </div>
                <div class="m-5">
                    <p v-if="jobForm?.personalNumber" class="font-bold">
                        PN: {{ jobForm?.personalNumber }}
                    </p>
                    <p
                        v-if="profileForm?.firstName && profileForm?.lastName"
                        class="text-gray-500"
                    >
                        {{ profileForm?.firstName }}&nbsp;{{
                            profileForm?.lastName
                        }}
                    </p>
                    <p v-if="jobForm?.jobTitle" class="text-gray-500 mt-2">
                        {{ jobForm?.jobTitle }}
                    </p>
                    <p v-if="profileForm?.dateOfBirth" class="text-gray-500">
                        {{ jobForm?.dateOfBirth }}
                    </p>
                </div>
                <div
                    class="bg-gray-200 text-gray-500 rounded m-5 px-5 grid grid-rows-[1fr,1fr,1fr] justify-start"
                >
                    <span class="grid grid-cols-[1fr,5fr] gap-2 items-center">
                        <font-awesome-icon
                            icon="fas fa-building-columns self-center"
                        />
                        <p v-if="jobForm?.teams?.length" class="mb-0">
                            {{ departmentNames(jobForm?.teams ?? []) }}
                        </p>
                    </span>
                    <span class="grid grid-cols-[1fr,5fr] gap-2 items-center">
                        <font-awesome-icon
                            icon="fas fa-users-rectangle self-center"
                        />
                        <p v-if="jobForm?.teams?.length" class="mb-0">
                            {{ teamNames(jobForm?.teams ?? []) }}
                        </p>
                    </span>
                    <span class="grid grid-cols-[1fr,5fr] gap-2 items-center">
                        <font-awesome-icon icon="fas fa-map-pin self-center" />
                        <p
                            v-if="
                                (jobForm?.locationId?.zip_code ??
                                    jobForm?.locationId?.zipCode) ||
                                jobForm?.locationId?.city
                            "
                            class="mb-0"
                        >
                            {{
                                jobForm?.locationId?.zip_code ??
                                jobForm?.locationId?.zipCode
                            }}
                            {{ jobForm?.locationId?.city }}
                        </p>
                    </span>
                </div>
                <div
                    class="bg-gray-200 text-gray-500 rounded m-5 flex px-5 flex-col justify-around"
                >
                    <span class="grid grid-cols-[1fr,5fr] gap-2 items-center">
                        <font-awesome-icon icon="fas fa-envelope self-center" />
                        <p v-if="profileForm?.email" class="mb-0">
                            {{ profileForm?.email }}
                        </p> </span
                    ><span class="grid grid-cols-[1fr,5fr] gap-2 items-center">
                        <font-awesome-icon icon="fas fa-phone self-center" />
                        <p v-if="profileForm?.publicPhone" class="mb-0">
                            {{ profileForm?.publicPhone }}
                        </p> </span
                    ><span class="grid grid-cols-[1fr,5fr] gap-2 items-center">
                        <font-awesome-icon icon="fas fa-mobile self-center" />
                        <p v-if="profileForm?.city" class="mb-0">
                            {{ profileForm?.city }}
                        </p>
                    </span>
                </div>
            </div>
        </div>
        <div class="tab-header">
            <ul class="">
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="activeTab = 'profile'"
                        :class="
                            activeTab === 'profile'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Profile") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="userId && (activeTab = 'job')"
                        :class="
                            activeTab === 'job'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (userId ? '' : 'disabled')
                        "
                    >
                        {{ $t("Job") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="userId && (activeTab = 'compensation')"
                        :class="
                            activeTab === 'compensation'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (userId ? '' : 'disabled')
                        "
                    >
                        {{ $t("Compensation and Benefits") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="userId && (activeTab = 'assets')"
                        :class="
                            activeTab === 'assets'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (userId ? '' : 'disabled')
                        "
                    >
                        {{ $t("Assets") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="userId && (activeTab = 'document')"
                        :class="
                            activeTab === 'document'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (userId ? '' : 'disabled')
                        "
                    >
                        {{ $t("Document and Contracts") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="userId && (activeTab = 'calendar')"
                        :class="
                            activeTab === 'calendar'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (userId ? '' : 'disabled')
                        "
                    >
                        {{ $t("Calendar") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        @click="userId && (activeTab = 'skill')"
                        :class="
                            activeTab === 'skill'
                                ? activeClasses
                                : inactiveClasses +
                                  ' ' +
                                  (userId ? '' : 'disabled')
                        "
                    >
                        {{ $t("Skills") }}
                    </a>
                </li>
            </ul>
        </div>
        <div class="flex justify-end mt-5 mb-3">
            <div class="form-group w-1/3">
                <MultiSelectInput
                    v-if="show"
                    class=""
                    @update:model-value="userAdded"
                    v-model="profileForm.userId"
                    :options="users"
                    :multiple="false"
                    :textLabel="$t('Attach User')"
                    label="first_name"
                    trackBy="id"
                    :showLabels="false"
                    moduleName="auth"
                    :search-param-name="'search_string'"
                    :error="errors.userId"
                >
                    <template #beforeList>
                        <div
                            class="grid p-2 gap-2"
                            style="grid-template-columns: 25% 25% 50%"
                        >
                            <p class="font-bold">{{ $t("First Name") }}</p>
                            <p class="font-bold">{{ $t("Last Name") }}</p>
                            <p class="font-bold">{{ $t("Email") }}</p>
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
                            style="grid-template-columns: 25% 25% 50%"
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
            </div>
        </div>
        <div id="myTabContent mt-3">
            <!-- Profile tab -->
            <div
                v-if="activeTab === 'profile'"
                id="profile"
                role="tabpanel"
                aria-labelledby="profile-tab"
            >
                <form novalidate @submit.prevent="saveProfile">
                    <Profile :data="profileForm" :errors="errors" />
                </form>
            </div>

            <!-- Job tab -->
            <div
                v-else-if="activeTab === 'job'"
                id="job"
                role="tabpanel"
                aria-labelledby="job-tab"
            >
                <form novalidate @submit.prevent="saveJob">
                    <Job :data="jobForm" :errors="errors" />
                </form>
            </div>

            <!-- Compensation tab -->
            <div
                v-else-if="activeTab === 'compensation'"
                id="compensation"
                role="tabpanel"
                aria-labelledby="compensation-tab"
            >
                <form novalidate @submit.prevent="saveCompensation">
                    <Compensation
                        :data="compensationForm"
                        :errors="errors"
                        @next="activeTab = 'assets'"
                    />
                </form>
            </div>

            <!--Assets tab -->
            <div
                v-else-if="activeTab === 'assets'"
                id="assets"
                role="tabpanel"
                aria-labelledby="assets-tab"
            >
                <Assets
                    :userId="profileForm?.userId?.id"
                    @next="activeTab = 'document'"
                />
            </div>
            <!-- Document tab -->
            <div
                v-else-if="activeTab === 'document'"
                id="document"
                role="tabpanel"
                aria-labelledby="document-tab"
            >
                <DocumentAndContracts @next="activeTab = 'calendar'" />
            </div>

            <!-- Calendar tab -->
            <div
                v-else-if="activeTab === 'calendar'"
                id="calendar"
                role="tabpanel"
                aria-labelledby="calendar-tab"
            >
                <div
                    class="w-full bg-white rounded-md shadow margin-bottom-3rem"
                >
                    <div class="flex justify-between p-2">
                        <h6
                            class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800"
                        >
                            {{ $t("Calendar Details") }}
                        </h6>
                    </div>
                </div>
            </div>

            <!-- Skill tab -->
            <div
                v-else-if="activeTab === 'skill'"
                id="skill"
                role="tabpanel"
                aria-labelledby="skill-tab"
            >
                <div
                    class="w-full bg-white rounded-md shadow margin-bottom-3rem"
                >
                    <div class="flex justify-between p-2">
                        <h6
                            class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800"
                        >
                            {{ $t("Skill Details") }}
                        </h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

import Profile from "./Profile.vue";
import Job from "./Job.vue";
import Compensation from "./Compensation.vue";
import Calendar from "./Calendar.vue";
import DocumentAndContracts from "./DocumentAndContracts.vue";

import { computed } from "vue";
import Assets from "./Assets.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    provide() {
        return {
            shouldDisable: computed(() => this.shouldDisable),
            userId: computed(() => this.userId),
            existingUser: computed(() => this.existingUser),
            isLoaded: true,
            monthlyWorkingHours: computed(() => this.monthlyWorkingHours),
        };
    },
    watch: {
        "jobForm.probationPeriodMonths": "probationEndDateMethod",
        "jobForm.joinDate": "probationEndDateMethod",
    },
    async mounted() {
        try {
            await this.$store.dispatch("auth/list", {
                limit_start: 0,
                limit_count: 25,
                type: "employee",
            });
            await this.$store.dispatch("roles/list");
            await this.$store.dispatch("userJobs/list");
            await this.$store.dispatch("userDepartments/list", {
                limit_start: 0,
                limit_count: 25,
            });
            await this.$store.dispatch("userTeams/list", {
                limit_start: 0,
                limit_count: 25,
            });
            await this.$store.dispatch("userDepartments/list", {
                limit_start: 0,
                limit_count: 25,
            });
            await this.$store.dispatch("userLocations/list", {
                limit_start: 0,
                limit_count: 25,
            });
        } catch (e) {
            console.log(e);
        }
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", ["users", "user"]),
        existingUser() {
            try {
                return Object.keys(this.profileForm.userId).length > 0;
            } catch (e) {
                return false;
            }
        },
        monthlyWorkingHours() {
            let weeklyHours = 0;
            let monthlyHours = 0;
            this.jobForm?.workHours?.forEach((workDay) => {
                weeklyHours += workDay?.numOfHours ?? 0;
            });
            monthlyHours = weeklyHours * 4;
            return monthlyHours;
        },
    },
    components: {
        MultiSelectInput,
        LoadingButton,
        TextInput,
        Profile,
        Job,
        Compensation,
        Calendar,
        DocumentAndContracts,
        Assets,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Management"),
                    to: "/user-profiles",
                },
                {
                    text: this.$t("User Profiles"),
                    to: "/user-profiles",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            show: true,
            shouldDisable: false,
            userImage: null,
            userId: null,
            activeClasses: "active",
            inactiveClasses: "inactive",
            activeTab: "profile",
            profileForm: {
                title: "",
                firstName: "",
                lastName: "",
                birthName: "",
                dateOfBirth: "",
                countryOfBirth: "",
                placeOfBirth: "",
                gender: "",
                nationality: "",
                highestSchoolDegree: "",
                highestEducationLevel: "",
                street: "",
                address: "",
                secondaryAddress: "",
                zipCode: "",
                city: "",
                state: "",
                country: "",
                phone: "",
                mobile: "",
                publicPhone: "",
                email: "",
                childrenData: [],
                financeDepartmentNumber: "",
                financeCategory: "",
                religion: "",
                religionOfPartner: "",
                freeAmountChildren: "",
                freeAmountYearly: "",
                freeAmountMonthly: "",
                taxLiability: "",
                accountOwner: "",
                iban: "",
                bic: "",
                bankName: "",
                socialSecurityNumber: "",
                healthInsurance: "",
                previousHealthInsurance: "",
                groupPeople: "",
                contributionGroup: "",
                accidentInsurance: "",
                tariffPoints: "",
                percentageAssociation: "",
                userId: "",
                password: "",
                confirmPassword: "",
                privateEmail: "",
                maritalStatus: "",
                roles: [],
            },
            jobForm: {
                jobTitle: "",
                jobDescription: "",
                personalNumber: "",
                jobNumber: {},
                contractType: "",
                supervisorId: "",
                joinDate: "",
                exitDate: "",
                accountingDate: "",
                isMainJob: true,
                costCenter: "",
                isEmployeeLeasing: false,
                locationId: "",
                totalAnnualLeaves: "",
                remainingLeaves: "",
                remainingLeavesYear: new Date().getFullYear(),
                teams: [],
                departments: [],
                userId: "",
                workHours: [{ day: "", numOfHours: "" }],
                targetValueYear: 0,
                targetValueMonth: 0,
                probationPeriodMonths: "",
                probationEndDate: "",
                additionalLeaves: 0,
            },
            compensationForm: {
                compensationType: "",
                grossMonthlySalary: "",
                grossHourlySalary: "",
                contractNumber: "",
                insuranceCompany: "",
                amountMonthly: "",
                userId: "",
                bonus: [
                    {
                        targetType: "",
                        targetValue: "",
                        level: "one",
                        month: "",
                        halfYear: "",
                        year: "",
                        bonusFaktor: "",
                        goal: "",
                        percentNumber: "",
                    },
                    {
                        targetType: "",
                        targetValue: "",
                        level: "two",
                        month: "",
                        halfYear: "",
                        year: "",
                        bonusFaktor: "",
                        goal: "",
                        percentNumber: "",
                    },
                    {
                        targetType: "",
                        targetValue: "",
                        level: "three",
                        month: "",
                        halfYear: "",
                        year: "",
                        bonusFaktor: "",
                        goal: "",
                        percentNumber: "",
                    },
                    {
                        targetType: "",
                        targetValue: "",
                        level: "four",
                        month: "",
                        halfYear: "",
                        year: "",
                        bonusFaktor: "",
                        goal: "",
                        percentNumber: "",
                    },
                    {
                        targetType: "",
                        targetValue: "",
                        level: "five",
                        month: "",
                        halfYear: "",
                        year: "",
                        bonusFaktor: "",
                        goal: "",
                        percentNumber: "",
                    },
                ],
            },
        };
    },
    methods: {
        departmentNames(teams) {
            try {
                let departmentNames = teams.map(
                    (team) => team?.department?.name ?? ""
                );
                departmentNames = Array.from(new Set(departmentNames));
                return departmentNames.join(", ");
            } catch (e) {
                return "";
            }
        },
        teamNames(teams) {
            try {
                let teamNames = teams.map((team) => team?.name ?? "");
                teamNames = Array.from(new Set(teamNames));
                return teamNames.join(", ");
            } catch (e) {
                return "";
            }
        },
        probationEndDateMethod() {
            const startDate = new Date(this.jobForm.joinDate);
            const periodMonths = parseInt(this.jobForm.probationPeriodMonths);
            if (!isNaN(startDate) && !isNaN(periodMonths)) {
                const endDate = new Date(startDate);
                endDate.setMonth(startDate.getMonth() + periodMonths);
                this.jobForm.probationEndDate = endDate
                    .toISOString()
                    .substr(0, 10);
            } else {
                this.jobForm.probationEndDate = null;
            }
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        userAdded(user) {
            if (user) {
                this.profileForm.firstName = user.first_name;
                this.profileForm.lastName = user.last_name;
                this.profileForm.email = user.email;
                this.profileForm.mobile = user.mobile;
                this.profileForm.city = user.city;
                this.profileForm.street = user.street;
                this.profileForm.zipCode = user.zip;
                this.profileForm.password = "";
                this.profileForm.confirmPassword = "";
                this.userId = user.id;
            } else {
                this.profileForm.firstName = "";
                this.profileForm.lastName = "";
                this.profileForm.email = "";
                this.profileForm.mobile = "";
                this.profileForm.city = "";
                this.profileForm.street = "";
                this.profileForm.zipCode = "";
                this.profileForm.password = "";
                this.profileForm.confirmPassword = "";
            }
            if (this.userId) {
                this.$store
                    .dispatch("auth/show", { id: this.userId })
                    .then((res) => {
                        if (res.data) {
                            this.userImage = res?.data?.profile_image;
                        }
                    });
            }
        },
        async handleFileChange(event) {
            const file = event.target.files[0];
            this.userImage = URL.createObjectURL(file);
            if (this.userId) {
                this.$store
                    .dispatch("auth/show", {
                        id: this.userId,
                    })
                    .then(async (res) => {
                        let base64 = await this.setProfilePicture(
                            file,
                            res?.data
                        );
                        this.userImage = base64;
                        if (this.user.id === this.userId) {
                            this.$store
                                .dispatch("auth/show", { id: this.user.id })
                                .then((res) => {
                                    this.$store.commit(
                                        "auth/set_user",
                                        res?.data ?? {}
                                    );
                                });
                        }
                    })
                    .catch((err) => console.log({ err }));
            }
        },
        async saveProfile() {
            this.shouldDisable = true;
            if (!this.existingUser && !this.profileForm.userId) {
                if (
                    !this.profileForm.password.length ||
                    !this.profileForm.confirmPassword.length ||
                    this.profileForm.password !==
                        this.profileForm.confirmPassword
                ) {
                    if (!this.profileForm.password.length)
                        this.$store.commit("errors", {
                            password: "Password is a required field",
                        });
                    else if (!this.profileForm.confirmPassword.length)
                        this.$store.commit("errors", {
                            password: "Password is a required field",
                        });
                    this.$store.commit("flashMessage", {
                        show: true,
                        message:
                            !this.profileForm.password.length ||
                            !this.profileForm.confirmPassword.length
                                ? "Password is a required field"
                                : "Password and confirm password must match",
                        type: "error",
                    });
                    this.shouldDisable = false;
                    return;
                }
                const payload = {
                    first_name: this.profileForm.firstName,
                    last_name: this.profileForm.lastName,
                    mail: this.profileForm.email,
                    city: this.profileForm.city,
                    street: this.profileForm.street,
                    street_number: "",
                    zip: this.profileForm.zipCode,
                    mobile: this.profileForm.mobile,
                    password: this.profileForm.password,
                    confirmPassword: this.profileForm.confirmPassword,
                    roles: this.profileForm.roles.map((role) => role.id),
                    types: ["employee"],
                    profile_image: this.userImage,
                };
                try {
                    this.$store.commit("isLoading", true);
                    const response = await this.$store.dispatch("auth/create", {
                        ...payload,
                    });
                    this.show = false;
                    this.profileForm.userId = response?.data ?? {};
                } catch (e) {
                    this.shouldDisable = false;
                    return;
                } finally {
                    await this.$nextTick();
                    this.show = true;
                }
            }
            try {
                this.$store.commit("isLoading", true);
                const response = await this.$store.dispatch(
                    "userProfile/saveProfile",
                    {
                        ...this.profileForm,
                        userId: this.profileForm?.userId?.id,
                        highestSchoolDegree:
                            this.profileForm?.highestSchoolDegree?.id,
                        highestEducationLevel:
                            this.profileForm?.highestEducationLevel?.id,
                    }
                );
                this.userId = response?.data?.userId ?? null;
                this.profileForm.id = this.userId;
                if (this.userId) {
                    this.activeTab = "job";
                }
                await this.$store.dispatch("userProfile/list");
            } catch (e) {
                this.shouldDisable = false;
                console.log(e);
            }
            this.shouldDisable = false;
        },
        async saveJob() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("userProfile/saveJob", {
                    ...this.jobForm,
                    jobNumber: this.jobForm?.jobNumber?.id ?? null,
                    supervisorId: this.jobForm?.supervisorId?.id ?? null,
                    remainingLeaves: this.jobForm.remainingLeaves
                        ? this.jobForm.remainingLeaves
                        : this.jobForm.totalAnnualLeaves,
                    remainingLeavesYear:
                        this.jobForm.remainingLeavesYear ??
                        new Date().getFullYear(),
                    teams:
                        typeof this.jobForm?.teams === "string"
                            ? []
                            : this.jobForm?.teams?.map((item) => item.id) ?? [],
                    userId: this.userId,
                });
                this.activeTab = "compensation";
                await this.$store.dispatch("userProfile/list");
            } catch (e) {}
        },
        async saveCompensation() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("userProfile/saveCompensation", {
                    ...this.compensationForm,
                    userId: this.userId,
                });
                this.activeTab = "assets";
                await this.$store.dispatch("userProfile/list");
            } catch (e) {}
        },
    },
};
</script>

<style scoped>
.table-layout-fixed {
    table-layout: fixed;
}

.disabled {
    color: lightgray;
    cursor: not-allowed;
}
.avatar-container {
    min-height: 150px;
    max-height: 150px;
    min-width: 150px;
    max-width: 150px;
    background-color: lightgray;
    border-radius: 50%;
    position: relative;
}
.image {
    border-radius: 50%;
}
.image-selector {
    cursor: pointer;
    color: white;
    font-size: 1rem;
    position: absolute;
    right: 7%;
    bottom: 10%;
    background-color: #2957a4;
    padding: 5px;
    border-radius: 50%;
}
</style>
