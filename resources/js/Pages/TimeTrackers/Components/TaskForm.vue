<template>
    <template v-if="actionType === 'add'">
        <div class="grid items-center grid-cols-3 gap-6">
            <div class="w-full">
                <div class="form-group">
                    <MultiSelectInput
                        :required="true"
                        :options="projectOptions"
                        class=""
                        :multiple="false"
                        :text-label="$t('Projects')"
                        label="name"
                        v-if="$can('project.list')"
                        trackBy="id"
                        moduleName="projects"
                        :query="{
                            status: ['new', 'in-work', 'in-review', 'blocked'],
                        }"
                        @update:modelValue="filterTaskForm($event, 'project')"
                        v-model="project"
                        :key="project.id"
                        :error="$t(modalErrors.projectId)"
                    />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <MultiSelectInput
                        :isDisabled="!project"
                        :options="milestoneOptions"
                        class=""
                        :multiple="false"
                        v-if="$can('milestone.list')"
                        :text-label="$t('Milestones')"
                        label="name"
                        trackBy="id"
                        moduleName="milestones"
                        v-model="milestone"
                        :key="milestone.id"
                    />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <MultiSelectInput
                        v-if="$can('project.list')"
                        :isDisabled="!project"
                        :required="true"
                        :options="taskOptions"
                        class=""
                        :multiple="false"
                        :text-label="$t('Task Number')"
                        label="name"
                        trackBy="id"
                        moduleName="tasks"
                        v-model="task"
                        :key="task?.id"
                        :error="$t(modalErrors.taskId)"
                    >
                        <template #singleLabel="{ props }">
                            <p>
                                {{ props.option.name }} #{{ props.option.id }}
                            </p>
                        </template>
                        <template #option="{ props }">
                            <p>
                                {{ props.option.name }} #{{ props.option.id }}
                            </p>
                        </template>
                    </MultiSelectInput>
                </div>
            </div>
        </div>

        <!-- Display Task additional info -->
        <div
            v-if="task"
            class="w-full bg-white rounded-md shadow mb-8 p-4 mr-6"
        >
            <p>
                <span class="font-bold">{{ $t("Estimated Time") }}:&nbsp;</span>
                {{ taskAdditionalInfo.estimatedTime }}
                <br />
                <span class="font-bold">{{ $t("Spent Time") }}:&nbsp;</span
                >{{ taskAdditionalInfo.spentTime }}
                <span v-if="isSpentTimeError" class="text-red-600"
                    >({{
                        $t(
                            "our spend time is higher than your estimated time. If thats not a mistake, click on SAVE again"
                        )
                    }})</span
                >
                <br />
                <span class="font-bold"
                    >{{ $t("Planned Finished Date") }}:&nbsp;</span
                >{{ taskAdditionalInfo.plannedFinishedDate }}
                <br />
                <span class="font-bold"
                    >{{ $t("Expected Finished Date") }}:&nbsp;</span
                >{{ taskAdditionalInfo.expectedFinishedDate }}
            </p>
        </div>
    </template>

    <template v-else>
        <div class="w-full bg-white rounded-md shadow mb-8 p-4 mr-6">
            <p>
                <span class="font-bold">{{ $t("Project") }}:&nbsp;</span>
                {{
                    this.timeTrackerCompanyEditData.additionalInfo.project.name
                }}
                <br />
                <span class="font-bold">{{ $t("Milestone") }}:&nbsp;</span
                >{{
                    this.timeTrackerCompanyEditData.additionalInfo.milestone
                        .name ?? "N/A"
                }}
                <br />
                <span class="font-bold">{{ $t("Task Number") }}:&nbsp;</span
                >{{ this.timeTrackerCompanyEditData.moduleId.name }}
                <br />
                <span class="font-bold">{{ $t("Customer") }}:&nbsp;</span
                >{{ this.timeTrackerCompanyEditData.company.companyName }}
            </p>
        </div>
    </template>

    <div class="grid items-center grid-cols-2 gap-6 mt-5">
        <div class="w-full">
            <div class="form-group">
                <!-- Time spent on the given task, if edit and not 'new entry' accounted time needs to be disabled -->
                <number-input
                    :roundNearQuarter="true"
                    :forceLeftBound="true"
                    :showPrefix="false"
                    v-model="accountedTime"
                    :min="0"
                    class=""
                    :required="true"
                    :label="$t('Accounted Time')"
                    @update:modelValue="updateAccountedTime"
                    :error="$t(modalErrors.accountedTime)"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <!-- Which "company" does the current record belong to -->
                <MultiSelectInput
                    v-if="actionType === 'add' && $can('company.list')"
                    :required="true"
                    class=""
                    :textLabel="$t('Customer')"
                    v-model="company"
                    :options="companyOptions"
                    :multiple="false"
                    label="companyName"
                    trackBy="id"
                    moduleName="companies"
                    :key="company?.id"
                    :error="$t(modalErrors.company)"
                    @asyncSearch="companyOptions = companies?.data"
                />
            </div>
        </div>
        <div class="w-full col-span-2">
            <div class="form-group">
                <!-- Description of the given type -->
                <text-area-input
                    :required="true"
                    v-model="description"
                    :rows="3"
                    :error="$t(modalErrors.description)"
                    class=""
                    :label="$t('Description')"
                />
            </div>
        </div>
        <div class="w-full col-span-2">
            <div class="form-group">
                <text-area-input
                    :required="false"
                    v-model="internalNote"
                    :rows="3"
                    class=""
                    :label="$t('Internal Note')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="checkbox-group">
                <!-- Is the time tracking for free? -->
                <input
                    id="goodwill"
                    v-model="isGoodwill"
                    :type="`checkbox`"
                    class="checkbox-input"
                />
                <label class="checkbox-label" :for="'goodwill'">
                    {{ $t("Is Goodwill") }}?
                </label>
            </div>
        </div>
    </div>
</template>

<script>
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import { mapGetters } from "vuex";

export default {
    props: [
        "modalErrors",
        "actionType",
        "filterCompany",
        "timeTrackerCompanyEditData",
    ],
    components: {
        MultiSelectInput,
        NumberInput,
        TextAreaInput,
    },
    computed: {
        ...mapGetters("projects", ["projects"]),
        ...mapGetters("companies", ["companies"]),
        ...mapGetters("milestones", ["milestones"]),
        ...mapGetters("tasks", ["tasks"]),
    },
    watch: {
        project(item) {
            if (this.actionType == "edit") {
                return;
            }
            //Select the company associated with the current project
            this.company = this.companyOptions.find((company) => {
                return company.id === item.companyId;
            });
        },
        task(item) {
            if (this.actionType == "edit" || !item) {
                return;
            }
            //Define additional info data
            this.taskAdditionalInfo.estimatedTime = item.estimatedTime
                ? parseFloat(item.estimatedTime).toFixed(2)
                : "0.00";
            this.taskAdditionalInfo.spentTime = item.spentTime
                ? parseFloat(item.spentTime).toFixed(2)
                : "0.00";
            this.taskAdditionalInfo.plannedFinishedDate =
                item.plannedFinishedDate;
            this.taskAdditionalInfo.expectedFinishedDate =
                item.expectedFinishedDate;

            //Define accounted time
            let spentTime = item?.spentTime ?? 0;
            let estimatedTime = item?.estimatedTime ?? 0;
            let time = (
                parseFloat(estimatedTime) - parseFloat(spentTime)
            ).toFixed(2);
            this.accountedTime = time > 0 ? time : "0.00";

            //Get task number
            //Define description data from task
            let plainText = "Project ID: " + this.project.projectNumber + "\n";
            plainText += "Project Name: " + this.project.name + "\n";
            plainText += "Milestone Name: " + this.milestone.name + "\n";
            plainText += "Task Description:" + "\n";
            if (typeof item.description === "string") {
                const obj = JSON.parse(item.description);
                plainText +=
                    obj.ops?.[0]?.insert?.trim() || item.description.trim();
            } else {
                plainText += item.description?.ops?.[0]?.insert?.trim() || "";
            }
            this.description = plainText;
            //Auto-select milestone (if defined)
            if (item.milestoneId !== "" && this.milestoneOptions !== "") {
                this.milestone = this.milestoneOptions.find((milestone) => {
                    return milestone.id === item.milestoneId;
                });
            }
        },
    },
    data() {
        return {
            project: "",
            projectOptions: [],
            milestone: "",
            milestoneOptions: [],
            task: "",
            taskOptions: [],
            company: "",
            companyOptions: [],
            accountedTime: "0.00",
            description: "",
            internalNote: "",
            isGoodwill: false,
            taskAdditionalInfo: {
                estimatedTime: "",
                spentTime: "",
                plannedFinishedDate: "",
                expectedFinishedDate: "",
            },
            isSpentTimeError: false,
        };
    },
    mounted() {
        this.getProjects();
        this.getCompanies();

        if (this.actionType === "edit") {
            //Define form data
            this.description = this.timeTrackerCompanyEditData.description;
            this.internalNote = this.timeTrackerCompanyEditData.internalNote;
            this.accountedTime = this.timeTrackerCompanyEditData.time;
            this.isGoodwill =
                this.timeTrackerCompanyEditData.isGoodwill == 1 ? true : false;
            this.project =
                this.timeTrackerCompanyEditData.additionalInfo.project;
            this.milestone =
                this.timeTrackerCompanyEditData.additionalInfo.milestone;
            this.task = this.timeTrackerCompanyEditData.moduleId;
            this.company = this.timeTrackerCompanyEditData.company;
        }
    },
    methods: {
        async getProjects() {
            let payload = {
                perPage: 25,
                page: 1,
                status: ["new", "in-work", "in-review", "blocked"],
            };
            if (this.filterCompany.id !== "all") {
                payload.companyId = this.filterCompany.id;
            }
            await this.$store.dispatch("projects/list", payload);
            this.projectOptions = this.projects?.data;
        },
        async getCompanies() {
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
            if (this.filterCompany.id !== "all") {
                this.companyOptions = this.companies?.data.filter(
                    (item) => item.id === this.filterCompany.id
                );
                this.company = this.filterCompany;
            } else {
                this.companyOptions = this.companies?.data;
            }
        },
        async getMilestones(id) {
            await this.$store.dispatch("milestones/milestonesByProject", id);
            this.milestoneOptions = this.milestones?.data;
        },
        async getTasks(id) {
            await this.$store.dispatch("tasks/list", {
                projectId: id,
                perPage: 25,
                page: 1,
            });

            this.taskOptions = this.tasks?.data;
        },
        async filterTaskForm(event, type) {
            if (type === "project") {
                //Get milestones by project
                this.getMilestones(event.id);
                //Get tasks by project
                this.getTasks(event.id);
            }
        },
        async updateAccountedTime() {
            if (this.accountedTime != "" && this.accountedTime != "0.00") {
                this.isSpentTimeError =
                    parseFloat(this.accountedTime) +
                        parseFloat(this.taskAdditionalInfo.spentTime) >
                    this.taskAdditionalInfo.estimatedTime;
            }
        },
    },
};
</script>
