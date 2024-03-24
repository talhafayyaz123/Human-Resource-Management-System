<template>
    <div class="grid items-center grid-cols-2 gap-6">
        <div class="w-full">
            <div class="form-group">
                <text-input
                    v-model="jobForm.jobTitle"
                    :required="true"
                    :error="errors.jobTitle"
                    class=""
                    :label="$t('Job Title')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <text-input
                    v-model="jobForm.jobDescription"
                    :error="errors.jobDescription"
                    class=""
                    :label="$t('Job Description')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <text-input
                    :required="true"
                    v-model="jobForm.personalNumber"
                    :error="errors.personalNumber"
                    class=""
                    :label="$t('Personal Number')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    :key="jobForm.jobNumber"
                    class=""
                    :options="jobs.data"
                    :multiple="false"
                    v-model="jobForm.jobNumber"
                    label="jobTitle"
                    :textLabel="$t('Jobs')"
                    trackBy="id"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    v-if="isLoaded"
                    class=""
                    v-model="jobForm.teams"
                    :options="teams.data"
                    :multiple="true"
                    textLabel="Team"
                    label="name"
                    trackBy="id"
                    moduleName="teams"
                    :error="errors.teams"
                />
            </div>
        </div>
        <div class="w-full" v-if="teamLeads.length">
            <div class="form-group">
                <MultiSelectInput
                    :isDisabled="true"
                    
                    class=""
                    :multiple="true"
                    label="name"
                    textLabel="Team Leads"
                    trackBy="id"
                    :options="teamLeads"
                    v-model="selectedTeamLeads"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    :key="jobForm.departments"
                    class=""
                    :options="departments.data"
                    v-model="jobForm.departments"
                    :multiple="true"
                    label="name"
                    textLabel="Departments"
                    trackBy="id"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <select-input
                    v-if="isLoaded"
                    :required="true"
                    v-model="jobForm.contractType"
                    :error="errors.contractType"
                    class=""
                    :label="$t('Contract Type')"
                >
                    <option value="fulltime">{{ $t("Full Time") }}</option>
                    <option value="parttime">{{ $t("Part Time") }}</option>
                </select-input>
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    v-if="isLoaded"
                    class=""
                    :showLabels="false"
                    v-model="jobForm.supervisorId"
                    :options="users"
                    :multiple="false"
                    :textLabel="$t('Mentor')"
                    label="first_name"
                    trackBy="id"
                    moduleName="auth"
                    :search-param-name="'search_string'"
                    :error="errors.supervisorId"
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
        <div class="w-full">
            <div class="form-group">
                <date-input
                    v-model="jobForm.joinDate"
                    :required="true"
                    :error="errors.joinDate"
                    class=""
                    :label="$t('Join Date')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <text-input
                    :type="`number`"
                    v-model="jobForm.probationPeriodMonths"
                    :error="errors.probationPeriodMonths"
                    class=""
                    :label="$t('Probation period months')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <date-input
                    v-model="jobForm.probationEndDate"
                    :error="errors.probationEndDate"
                    class=""
                    :label="$t('Probation end date')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <date-input
                    v-model="jobForm.exitDate"
                    :error="errors.exitDate"
                    class=""
                    :label="$t('Exit Date')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <select-input
                    :required="true"
                    v-model="jobForm.isMainJob"
                    :error="errors.isMainJob"
                    class=""
                    :label="$t('Is Main Job')"
                >
                    <option :value="true">{{ $t("Yes") }}</option>
                    <option :value="false">{{ $t("No") }}</option>
                </select-input>
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <date-input
                    :required="true"
                    v-model="jobForm.accountingDate"
                    :error="errors.accountingDate"
                    class=""
                    :label="$t('Start Accounting Date')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <text-input
                    v-model="jobForm.costCenter"
                    type="number"
                    :error="errors.costCenter"
                    class=""
                    :label="$t('Cost Center')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <select-input
                    :required="true"
                    v-model="jobForm.isEmployeeLeasing"
                    :error="errors.isEmployeeLeasing"
                    class=""
                    :label="$t('Is Employee Leasing')"
                >
                    <option :value="true">{{ $t("True") }}</option>
                    <option :value="false">{{ $t("False") }}</option>
                </select-input>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import DateInput from "@/Components/DateInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    inject: ["isLoaded"],
    computed: {
        ...mapGetters("auth", ["users"]),
        ...mapGetters("userTeams", ["teams"]),
        ...mapGetters("userDepartments", ["departments"]),
        ...mapGetters("userJobs", ["jobs"]),
        teamLeads() {
            let teamLeads =
                (
                    (typeof this.jobForm?.teams === "string"
                        ? []
                        : this.jobForm?.teams) ?? []
                )?.map((team) => {
                    return {
                        id: team?.teamLead?.id,
                        name: `${team?.teamLead?.last_name ?? ""}, ${
                            team?.teamLead?.first_name ?? ""
                        }`,
                        value: team?.teamLead?.id,
                    };
                }) ?? [];
            let uniqueTeamLeads = Array.from(
                new Set(teamLeads.map((lead) => lead.id))
            )
                .map((leadId) => {
                    let teamLead =
                        teamLeads?.find((lead) => lead.id == leadId) ?? {};
                    return {
                        ...teamLead,
                    };
                })
                .filter((lead) => lead.id);
            this.selectedTeamLeads = uniqueTeamLeads;
            return uniqueTeamLeads;
        },
        /*     departments() {
            let departments =
                (
                    (typeof this.jobForm?.teams === "string"
                        ? []
                        : this.jobForm?.teams) ?? []
                )?.map((team) => {
                    return {
                        id: team?.department?.id,
                        name: team?.department?.name ?? "",
                        value: team?.department?.id,
                    };
                }) ?? [];
            let uniqueDepartments = Array.from(
                new Set(departments.map((department) => department.id))
            )
                .map((departmentId) => {
                    let department =
                        departments?.find(
                            (department) => department.id == departmentId
                        ) ?? {};
                    return {
                        ...department,
                    };
                })
                .filter((department) => department.id);
            this.selectedDepartments = uniqueDepartments;
            return uniqueDepartments;
        },*/
    },
    components: {
        MultiSelectInput,
        TextInput,
        DateInput,
        SelectInput,
    },
    props: ["jobForm", "errors"],
    data() {
        return {
            selectedTeamLeads: [],
            selectedDepartments: [],
        };
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
    },
    watch: {
        "jobForm.teams": {
            handler(newVal, oldVal) {
                let removedItems;
                let departments =
                    (
                        (typeof this.jobForm?.teams === "string"
                            ? []
                            : this.jobForm?.teams) ?? []
                    )?.map((team) => {
                        return {
                            id: team?.department?.id,
                            name: team?.department?.name ?? "",
                            value: team?.department?.id,
                        };
                    }) ?? [];
                let uniqueDepartments = Array.from(
                    new Set(departments.map((department) => department.id))
                )
                    .map((departmentId) => {
                        let department =
                            departments?.find(
                                (department) => department.id == departmentId
                            ) ?? {};
                        return {
                            ...department,
                        };
                    })
                    .filter((department) => department.id);
                this.selectedDepartments = uniqueDepartments;

                //this group of code removed value departments field also, when we removed in teams field
                if (!!oldVal) {
                    removedItems = oldVal.filter(
                        (item) =>
                            !newVal.some((newItem) => newItem.id === item.id)
                    );

                    if (removedItems.length > 0) {
                        this.jobForm.departments =
                            this.jobForm.departments.filter((department) =>
                                removedItems.some(
                                    (removedItem) =>
                                        removedItem.department.id !==
                                        department.id
                                )
                            );
                    }
                }

                //end removed code

                /* In this code we check if the departments array already exist department then nothing happen and
                if the teams.department.id matched with departments.id id the object push*/
                if (!!this.selectedDepartments) {
                    this.selectedDepartments.forEach((department) => {
                        if (
                            !this.jobForm.departments.some(
                                (d) => d.id === department.id
                            )
                        ) {
                            this.jobForm.departments.push(department);
                        }
                    });
                }
            },
            deep: true, // Enable deep watching if jobForm.teams is an object or array
        },
    },
};
</script>
