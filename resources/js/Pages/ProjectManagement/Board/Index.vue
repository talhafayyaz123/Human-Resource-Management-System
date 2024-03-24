<template>
    <div>
        <div class="flex flex-wrap pt-8">
            <select-input
                v-model="status"
                label="Status"
                class="pb-8 pr-6 lg:w-1/3"
            >
                <option value="">{{ $t("All") }}</option>
                <option value="new">{{ $t("New") }}</option>
                <option value="done">{{ $t("Done") }}</option>
                <option value="in-work">{{ $t("In Work") }}</option>
                <option value="in-review">{{ $t("In Review") }}</option>
                <option value="blocked">{{ $t("Blocked") }}</option>
            </select-input>
            <select-input
                v-model="milestone"
                label="Milestone"
                class="pb-8 pr-6 lg:w-1/3"
            >
                <option value="">{{ $t("All") }}</option>
                <option
                    v-for="milestone in milestones"
                    :key="'milestone-' + milestone.id"
                    :value="milestone.id"
                >
                    {{ milestone.name }}
                </option>
            </select-input>
            <MultiSelectInput
                class="pb-8 pr-6 lg:w-1/3"
                :showLabels="false"
                v-model="employee"
                :options="users"
                :multiple="false"
                textLabel="Assignee"
                label="first_name"
                trackBy="id"
                moduleName="auth"
                :query="{
                    limit_start: 0,
                    limit_count: 25,
                    type: 'employee',
                }"
                :search-param-name="'search_string'"
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
        <div
            style="
                display: grid;
                grid-template-columns: 25% 25% 25% 25% 25%;
                gap: 1rem;
            "
        >
            <div
                @drop="onDrop($event, key)"
                @dragover="onDragover($event)"
                v-for="(status, key) in tasksByStatus"
                :key="key"
            >
                <div class="flex">
                    <div
                        :style="`background-color: ${colorsByStatus[key]}`"
                        class="round-circle self-center"
                    ></div>
                    <p class="ml-2 mb-2">{{ enums[key] }}</p>
                </div>
                <div
                    style="
                        min-height: 500px;
                        border: 1px solid lightgrey;
                        flex-direction: column;
                        gap: 1rem;
                    "
                    class="bg-white rounded-md shadow-lg overflow-x-auto p-3 flex"
                >
                    <div
                        draggable="true"
                        v-for="task in tasksByStatus[key]"
                        :key="task.id"
                        @dragstart="onDragstart($event, task.id)"
                        class="rounded-md p-2 shadow-md cursor-pointer"
                        style="border: 1px solid lightgrey"
                    >
                        <p style="color: #2957a4" class="mb-4">
                            {{ task.name }}
                        </p>
                        <p class="mb-4">
                            {{ getMilestoneName(task.milestoneId) }}
                        </p>
                        <p class="mb-4">
                            {{ formatDate(task.plannedStartDate) }} -
                            {{ formatDate(task.plannedFinishedDate) }}
                        </p>
                        <p class="mb-4">
                            <span style="color: green">Priority:</span
                            >&nbsp;&nbsp;{{ enums[task.priority] }}
                        </p>
                        <div class="flex">
                            <font-awesome-icon
                                class="avatar"
                                icon="fa-solid fa-user"
                            />
                            <p class="self-center ml-2">
                                {{ getEmployeeName(task.employeeId) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    props: {
        tasks: {
            default: () => [],
        },
        milestones: {
            default: () => [],
        },
    },
    computed: {
        ...mapGetters("auth", ["users"]),
        tasksByStatus() {
            const tasksByStatus = this.status
                ? JSON.parse(`{ "${this.status}" : [] }`)
                : {
                      new: [],
                      "in-work": [],
                      "in-review": [],
                      done: [],
                      blocked: [],
                  };
            for (let key in tasksByStatus) {
                const tasks = this.tasks.filter((task) => {
                    return (
                        task.status === key &&
                        (this.employee
                            ? task.employeeId == this.employee?.id
                            : true) &&
                        (this.milestone
                            ? task.milestoneId == this.milestone
                            : true)
                    );
                });
                tasksByStatus[key] = tasks;
            }
            return tasksByStatus;
        },
    },
    data() {
        return {
            status: "",
            milestone: "",
            employee: "",
            enums: {
                new: "New",
                done: "Done",
                "in-work": "In Work",
                "in-review": "In Review",
                blocked: "Blocked",
                low: "Low",
                medium: "Medium",
                high: "High",
            },
            colorsByStatus: {
                new: "lightgreen",
                done: "green",
                "in-work": "orange",
                "in-review": "yellow",
                blocked: "red",
            },
        };
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        onDragstart(event, taskId) {
            event.dataTransfer.setData("taskId", taskId);
        },
        async onDrop(event, status) {
            event.preventDefault();
            const taskId = event.dataTransfer.getData("taskId");
            const task = this.tasks.find((task) => task.id == taskId) ?? {};
            task.status = status;
            try {
                await this.$store.dispatch("tasks/update", {
                    id: taskId,
                    data: { ...task },
                });
                await this.$store.dispatch("tasks/list");
            } catch (e) {}
        },
        onDragover(event) {
            event.preventDefault();
        },
        formatDate(date) {
            return new Date(date).toDateString();
        },
        getMilestoneName(id) {
            return (
                this.milestones.find((milestone) => milestone.id == id)?.name ??
                ""
            );
        },
        getEmployeeName(id) {
            return this.users.find((employee) => employee.id == id)?.name ?? "";
        },
    },
    components: {
        MultiSelectInput,
        SelectInput,
    },
};
</script>

<style scoped>
.round-circle {
    border-radius: 50%;
    height: 10px;
    width: 10px;
}

.avatar {
    font-size: 1.2rem;
    background: lightgray;
    padding: 0.5rem;
    border-radius: 50%;
}
</style>
