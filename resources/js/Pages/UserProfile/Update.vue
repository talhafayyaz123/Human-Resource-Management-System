<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div v-if="profileForm?.firstName" class="rounded card shadow">
            <div
                v-if="profileForm?.firstName"
                class="grid grid-cols-[1fr,1fr,1fr,1fr] gap-2"
            >
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
                        <p v-if="profileForm?.mobile" class="mb-0">
                            {{ profileForm?.mobile }}
                        </p>
                    </span>
                </div>
            </div>
            <div
                class="flex justify-between mb-4 border-b border-gray-200 dark:border-gray-700 mt-5"
            >
                <div class="flex flex-wrap mx-auto">
                    <a
                        @click="activeTab = 'profile'"
                        :class="
                            activeTab === 'profile'
                                ? activeClasses
                                : inactiveClasses
                        "
                    >
                        {{ $t("Profile") }}
                    </a>
                    <a
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
                    <a
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
                    <a
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
                    <a
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
                    <a
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
                    <a
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
                </div>
            </div>
        </div>
        <div id="myTabContent">
            <!-- Profile tab -->
            <div
                v-if="activeTab === 'profile'"
                class="p-4"
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
                class="p-4"
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
                class="p-4"
                id="compensation"
                role="tabpanel"
                aria-labelledby="compensation-tab"
            >
                <form novalidate @submit.prevent="saveCompensation">
                    <Compensation :data="compensationForm" :errors="errors" />
                </form>
            </div>
            <div
                v-else-if="activeTab === 'assets'"
                class="p-4"
                id="assets"
                role="tabpanel"
                aria-labelledby="assets-tab"
            >
                <Assets
                    :userId="profileForm?.userId"
                    @next="activeTab = 'document'"
                />
            </div>

            <!-- Document tab -->
            <div
                v-else-if="activeTab === 'document'"
                class="p-4"
                id="document"
                role="tabpanel"
                aria-labelledby="document-tab"
            >
                <DocumentAndContracts @next="activeTab = 'calendar'" />
            </div>

            <!-- Calendar tab -->
            <div
                v-else-if="activeTab === 'calendar'"
                class="p-4"
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
                class="p-4"
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
import { mapGetters, mapActions } from "vuex";

import Profile from "./Profile.vue";
import Job from "./Job.vue";
import Compensation from "./Compensation.vue";
import Calendar from "./Calendar.vue";
import DocumentAndContracts from "./DocumentAndContracts.vue";

import { computed } from "vue";
import Assets from "./Assets.vue";

import profilePictureMixin from "@/Mixins/profilePictureMixin";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    mixins: [profilePictureMixin],
    provide() {
        return {
            userId: computed(() => this.userId),
            existingUser: true,
            isLoaded: computed(() => this.isLoaded),
            monthlyWorkingHours: computed(() => this.monthlyWorkingHours),
            requesterId: computed(() => this.profileForm.userId),
        };
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", ["users", "user"]),
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
        DocumentAndContracts,
        LoadingButton,
        TextInput,
        Profile,
        Job,
        Compensation,
        Calendar,
        Assets,
        PageHeader,
    },
    watch: {
        "jobForm.probationPeriodMonths": "probationEndDateMethod",
        "jobForm.joinDate": "probationEndDateMethod",
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
                    to: `/user-profiles?page=${this.$route.query.page}`,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            userForm: {},
            userImage: null,
            isLoaded: false,
            userId: null,
            activeClasses:
                "cursor-pointer inline-flex items-center justify-center w-1/2 py-3 font-medium leading-none tracking-wider text-indigo-500 bg-gray-100 border-b-2 border-indigo-500 rounded-t sm:px-6 sm:w-auto sm:justify-start title-font",
            inactiveClasses:
                "cursor-pointer inline-flex items-center justify-center w-1/2 py-3 font-medium leading-none tracking-wider border-b-2 border-gray-200 sm:px-6 sm:w-auto sm:justify-start title-font hover:text-gray-900",
            activeTab: "profile",
            profileForm: {
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
                publicPhone: "",
                mobile: "",
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
                privateEmail: "",
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
                maritalStatus: "",
            },
            jobForm: {
                jobTitle: "",
                jobDescription: "",
                personalNumber: "",
                jobNumber: {},
                departments: [],
                teams: [],
                contractType: "",
                supervisorId: "",
                joinDate: "",
                exitDate: "",
                accountingDate: "",
                isMainJob: true,
                isOtherJob: false,
                costCenter: "",
                isEmployeeLeasing: false,
                locationId: "",
                workingDays: "",
                weeklyHours: "",
                totalAnnualLeaves: 0,
                remainingLeaves: 0,
                additionalLeaves: 0,
                previousYearRemainingLeaves: 0,
                remainingLeavesYear: new Date().getFullYear(),
                userId: "",
                targetValueYear: 0,
                targetValueMonth: 0,
                probationPeriodMonths: "",
                probationEndDate: "",
            },
            compensationForm: {
                compensationType: "",
                grossMonthlySalary: "",
                grossHourlySalary: "",
                contractNumber: "",
                insuranceCompany: "",
                amountMonthly: "",
                userId: "",
                bonus: [],
            },
        };
    },
    watch: {
        // whenever setActiveTab changes, this function will run
        activeTab(newValue, oldValue) {
            this.setActiveTab(newValue);
        },
    },
    mounted() {
        this.refresh();
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
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "userProfile/showProfile",
                    this.$route.params.id
                );
                document.title =
                    "Update User Profile " +
                    response?.data?.data?.personalNumber;
                if (this.$route.query.activeTab) {
                    this.activeTab = this.$route.query.activeTab;
                }

                this.profileForm = {
                    ...(response?.data?.data ?? this.profileForm),
                };
                this.$store
                    .dispatch("auth/show", {
                        id: this.profileForm.userId,
                    })
                    .then((res) => {
                        this.userForm = { ...(res?.data ?? {}) };
                        this.userImage = res?.data?.profile_image;
                        this.userForm.mail = this.userForm.email;
                    });
                this.userId = this.profileForm?.id ?? null;
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "employee",
                });
                await this.$store.dispatch("userJobs/list");
                await this.$store.dispatch("userDepartments/list", {
                    limit_start: 0,
                    limit_count: 25,
                });
                await this.$store.dispatch("userTeams/list", {
                    limit_start: 0,
                    limit_count: 25,
                });
                await this.$store.dispatch("userLocations/list", {
                    limit_start: 0,
                    limit_count: 25,
                });
                this.$store
                    .dispatch("userProfile/showJob", this.userId)
                    .then((res) => {
                        this.jobForm = {
                            ...(res?.data?.data ?? this.jobForm),
                            remainingLeavesYear: res?.data?.data
                                ?.remainingLeavesYear
                                ? res?.data?.data?.remainingLeavesYear
                                : this.jobForm.remainingLeavesYear,
                        };
                        this.setJobFormId(this.jobForm?.id ?? "");

                        this.jobForm.isMainJob =
                            this.jobForm.isMainJob == 1 ? true : false;
                        this.jobForm.isOtherJob =
                            this.jobForm.isOtherJob == 1 ? true : false;
                        this.jobForm.isEmployeeLeasing =
                            this.jobForm.isEmployeeLeasing == 1 ? true : false;
                        this.jobForm.supervisorId =
                            this.users.find(
                                (user) => user.id == this.jobForm?.supervisorId
                            ) ?? "";
                        this.jobForm.department = this.isLoaded = true;
                    });
                this.$store
                    .dispatch("userProfile/showCompensation", this.userId)
                    .then((res) => {
                        this.compensationForm = {
                            ...(res?.data?.data ?? this.compensationForm),
                        };
                        this.setCompensationFormId(
                            this.compensationForm?.id ?? ""
                        );
                        console.log(this.compensationForm);
                    });
            } catch (e) {
                this.isLoaded = true;
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        async handleFileChange(event) {
            const file = event.target.files[0];
            this.userImage = URL.createObjectURL(file);
            this.$store
                .dispatch("auth/show", {
                    id: this.userForm?.id,
                })
                .then(async (res) => {
                    let base64 = await this.setProfilePicture(file, res?.data);
                    this.userImage = base64;
                    if (this.user.id === this.userForm?.id) {
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
        },
        async saveProfile() {
            try {
                const payload = {
                    ...this.userForm,
                    first_name: this.profileForm.firstName,
                    last_name: this.profileForm.lastName,
                    mail: this.profileForm.email,
                    city: this.profileForm.city,
                    street: this.profileForm.street,
                    zip: this.profileForm.zipCode,
                    mobile: this.profileForm.mobile,
                    types: Object.keys(this.userForm.types).filter(
                        (type) => this.userForm.types[type] == 1
                    ),
                    set_company_id: this.userForm.company_id,
                    set_tenant_id: this.userForm.tenant_id,
                };
                delete payload["company_id"];
                delete payload["tenant_id"];
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("auth/update", {
                    id: this.profileForm.userId,
                    ...payload,
                });
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("userProfile/saveProfile", {
                    ...this.profileForm,
                    highestSchoolDegree:
                        this.profileForm?.highestSchoolDegree?.id,
                    highestEducationLevel:
                        this.profileForm?.highestEducationLevel?.id,
                });
                this.activeTab = "job";
                await this.$store.dispatch("userProfile/list");
            } catch (e) {
                console.log(e);
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
        async saveJob() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("userProfile/saveJob", {
                    ...this.jobForm,
                    jobNumber: this.jobForm?.jobNumber?.id ?? null,
                    supervisorId: this.jobForm?.supervisorId?.id ?? null,
                    teams:
                        typeof this.jobForm?.teams === "string"
                            ? []
                            : this.jobForm?.teams?.map((item) => item.id) ?? [],
                    remainingLeaves: this.jobForm.remainingLeaves
                        ? this.jobForm.remainingLeaves
                        : this.jobForm.totalAnnualLeaves,
                    remainingLeavesYear:
                        this.jobForm.remainingLeavesYear ??
                        new Date().getFullYear(),
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
        ...mapActions("userProfile", [
            "setActiveTab",
            "setJobFormId",
            "setCompensationFormId",
        ]),
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
