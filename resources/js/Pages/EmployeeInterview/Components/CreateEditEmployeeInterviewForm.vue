<template>
    <div>
        <div class="tab-header">
            <ul class="">
                <li
                    v-if="
                        isEdit &&
                        (user.id === parentForm.creatorId ||
                            this.user.roles.includes('admin'))
                    "
                    class="nav-item"
                >
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'teamLead'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `teamLead`"
                    >
                        {{ $t("Team Lead") }}
                    </a>
                </li>

                <li
                    v-if="
                        isEdit &&
                        (user.id === parentForm.employeeId?.id ||
                            this.user.roles.includes('admin'))
                    "
                    class="nav-item"
                >
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'employee'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `employee`"
                    >
                        {{ $t("Employee") }}
                    </a>
                </li>
                <li
                    v-if="
                        isEdit &&
                        (user.id === parentForm.employeeId?.id ||
                            user.id === parentForm.creatorId ||
                            this.user.roles.includes('admin'))
                    "
                    class="nav-item"
                >
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'final'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `final`"
                    >
                        {{ $t("Final") }}
                    </a>
                </li>
            </ul>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    {{
                        $t(
                            `Fill ${
                                activeTab === `teamLead`
                                    ? "Team Lead"
                                    : activeTab === `employee`
                                    ? `Employee`
                                    : "Final"
                            }  Interview Details`
                        )
                    }}
                </h3>
            </div>
            <div class="card-body">
                <div class="flex flex-wrap">
                    <div class="form-group pb-8 pr-6 w-full lg:w-1/2">
                        <MultiSelectInput
                            @open="fetchUsers"
                            :required="true"
                            v-model="parentForm.employeeId"
                            :error="errors.employeeId"
                            :key="parentForm.employeeId"
                            :options="users"
                            :customLabel="customLabel"
                            :multiple="false"
                            :textLabel="$t('Employee')"
                            label="first_name"
                            trackBy="id"
                            :moduleName="'auth'"
                        >
                            <template #beforeList>
                                <div
                                    class="grid p-2 gap-2"
                                    style="grid-template-columns: 50% 50%"
                                >
                                    <p class="font-bold">
                                        {{ $t("First Name") }}
                                    </p>
                                    <p class="font-bold">
                                        {{ $t("Last Name") }}
                                    </p>
                                </div>
                                <hr />
                            </template>
                            <template #singleLabel="{ props }">
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 50% 50%"
                                >
                                    <p class="overflow-text-users-table">
                                        {{ props.option.first_name }}
                                    </p>
                                    <p class="overflow-text-users-table">
                                        {{ props.option.last_name }}
                                    </p>
                                </div>
                            </template>
                            <template #option="{ props }">
                                <div
                                    class="grid"
                                    style="grid-template-columns: 50% 50%"
                                >
                                    <p class="overflow-text-users-table">
                                        {{ props.option.first_name }}
                                    </p>
                                    <p class="overflow-text-users-table">
                                        {{ props.option.last_name }}
                                    </p>
                                </div>
                            </template>
                        </MultiSelectInput>
                    </div>
                    <div class="form-group pb-8 pr-6 w-full lg:w-1/2">
                        <text-input
                            :required="true"
                            v-model="parentForm.creator"
                            :isReadonly="true"
                            class="pb-8 lg:pb-0"
                            :label="$t('Creator')"
                        />
                    </div>
                    <div class="form-group pb-8 pr-6 w-full lg:w-1/2">
                        <MultiSelectInput
                            v-model="parentForm.interviewReason"
                            :options="interviewReasonOptions"
                            :key="parentForm.interviewReason"
                            :multiple="false"
                            :textLabel="$t('Interview Reason')"
                            label="label"
                            :trackBy="'value'"
                            :moduleName="'interviewReason'"
                            :taggable="true"
                        />
                    </div>
                    <div class="pb-8 pr-6 w-full lg:w-1/2">
                        <div class="form-group">
                            <label class="form-label input-label"
                                >{{ $t("Planned Date") }}:</label
                            >
                            <datepicker
                                class="form-control"
                                :clearable="false"
                                :enable-time-picker="false"
                                auto-apply
                                :close-on-auto-apply="false"
                                v-model="parentForm.plannedDate"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card my-6">
            <div class="card-body" v-if="recoId">
                <team-lead
                    :recordId="recoId"
                    :parentForm="parentForm"
                    :childForm="childForm"
                    :activeTab="activeTab"
                    v-if="activeTab === `teamLead` && childForm"
                />
                <employee
                    :recordId="recoId"
                    :parentForm="parentForm"
                    :childForm="childForm"
                    :activeTab="activeTab"
                    v-else-if="activeTab === `employee` && childForm"
                />
                <final
                    :recordId="recoId"
                    :parentForm="parentForm"
                    :childForm="childForm"
                    :activeTab="activeTab"
                    v-else-if="activeTab === `final` && childForm"
                />
            </div>
            <div class="card-body" v-else>
                <team-lead
                    :recordId="recoId"
                    :parentForm="parentForm"
                    :activeTab="activeTab"
                    v-if="activeTab === `teamLead`"
                />
                <employee
                    :recordId="recoId"
                    :parentForm="parentForm"
                    :activeTab="activeTab"
                    v-if="activeTab === `employee`"
                />
                <final
                    :recordId="recoId"
                    :parentForm="parentForm"
                    :activeTab="activeTab"
                    v-if="activeTab === `final`"
                />
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="flex flex-wrap">
                    <div class="form-group pb-8 pr-6 w-full lg:w-1/2">
                        <text-area-input
                            v-model="parentForm.trainingPersonalDevelopment"
                            class="pb-8"
                            :label="$t('Training and personal Development')"
                        />
                    </div>

                    <div class="flex items-center justify-end w-full mt-4">
                        <router-link
                            to="/employee-interview"
                            class="primary-btn me-3"
                        >
                            <span class="mr-1">
                                <CustomIcon name="cancelIcon" />
                            </span>
                            <span>{{ $t("Cancel") }}</span>
                        </router-link>
                        <loading-button
                            v-if="$can(`${$route.meta.permission}.create`)"
                            :loading="isLoading"
                            @click="saveButtonHandler"
                            class="secondary-btn"
                        >
                            <span class="mr-1">
                                <CustomIcon name="saveIcon" />
                            </span>
                            {{ $t("Save and Proceed") }}
                        </loading-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import Icon from "@/Components/Icon.vue";

import TeamLead from "./TeamLead.vue";
import Employee from "./Employee.vue";
import Final from "./Final.vue";

export default {
    props: {
        recoId: {
            type: Number,
            required: false,
        },
        isEdit: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("suppliers", ["suppliers"]),
        ...mapGetters("auth", { user: "user", users: "users" }),
    },
    components: {
        TextInput,
        TextAreaInput,
        MultiSelectInput,
        PageHeader,
        Icon,
        TeamLead,
        Employee,
        Final,
    },
    async mounted() {
        if (this.$route.params.id) {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "employeeInterview/show",
                    this.$route.params.id
                );
                const {
                    employeeId,
                    creator,
                    creatorId,
                    interviewReason,
                    plannedDate,
                    trainingPersonalDevelopment,
                } = response?.data?.data;

                this.childForm = { ...response?.data?.data };

                this.parentForm.employeeId = employeeId;
                this.parentForm.creator = creator;
                this.parentForm.creatorId = creatorId;
                this.parentForm.plannedDate = plannedDate;
                this.parentForm.trainingPersonalDevelopment =
                    trainingPersonalDevelopment;
                if (interviewReason === "yearlyInterview") {
                    this.parentForm.interviewReason = {
                        value: "yearlyInterview",
                        label: "Yearly Interview",
                    };
                } else if (interviewReason === "probationTimeEnd") {
                    this.parentForm.interviewReason = {
                        value: "probationTimeEnd",
                        label: "Probation Time End",
                    };
                }

                this.parentForm.employeeId =
                    this.users?.find((data) => {
                        return data.id === this.parentForm.employeeId;
                    }) ?? [];
                document.title =
                    "Edit Employee " + this.parentForm?.employeeId?.first_name;

                if (this.isEdit && !this.user.roles.includes("admin")) {
                    if (this.parentForm.employeeId?.id === this.user.id) {
                        this.activeTab = "employee";
                    }
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        } else {
            this.parentForm.creator =
                this.user?.first_name + " " + this.user?.last_name;
            this.parentForm.creatorId = this.user?.id;
        }
    },
    data() {
        return {
            parentForm: {
                employeeId: "",
                creator: "",
                creatorId: "",
                interviewReason: "",
                plannedDate: "",
                trainingPersonalDevelopment: "",
            },
            interviewReasonOptions: [
                {
                    value: "yearlyInterview",
                    label: "Yearly Interview",
                },
                {
                    value: "probationTimeEnd",
                    label: "Probation Time End",
                },
            ],
            activeTab: "teamLead",
            activeClasses: "active",
            inactiveClasses: "inactive",
        };
    },
    methods: {
        saveButtonHandler() {
            this.$store.dispatch("employeeInterview/toggleSaveBotton", true);
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async fetchUsers() {
            try {
                if (!this.users?.length) {
                    await this.$store.dispatch("auth/list");
                }
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
