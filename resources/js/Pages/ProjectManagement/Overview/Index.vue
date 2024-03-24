<template>
    <div class="test-view">
        <GanttChart
            @resourcesChanged="resourcesChanged"
            class="ml2 mr1 vuecal--blue-theme"
            :events="events"
            @addEvent="addEvent"
            @addEventReverse="addEventReverse"
            @removeEvent="removeEvent"
            @removeEventReverse="removeEventReverse"
            editable-events="editable-events"
            cell-contextmenu="cell-contextmenu"
            today-button="today-button"
            :time-from="7 * 60"
            :time-to="20 * 60"
            :special-hours="{}"
            v-model:selectedDate="selectedDate"
            @cell-contextmenu="log"
            :resources="resources"
            active-view="week"
            :disable-views="['years', 'year', 'month', 'day']"
            :employees="employees"
        ></GanttChart>
    </div>
</template>

<script>
import GanttChart from "@/Components/Gantt/index.vue";

const now = new Date();

export default {
    components: { GanttChart },
    props: {
        tasks: {
            default: () => [],
        },
        milestones: {
            default: () => [],
        },
        employees: {
            default: () => [],
        },
    },
    mounted() {
        this.refresh();
    },
    data: () => ({
        milestoneMinMaxDateTracker: {},
        resources: [],
        selectedDate: now,
        view: "week",
        events: [
            {
                start: new Date(new Date(now).setHours(1, 0, 0)),
                end: new Date(new Date(now).setHours(4, 0, 0)),
                allDay: true,
                title: "Event 1",
                split: 2,
                resourceId: 1,
            },
        ],
        daySplits: [
            { label: "Tom", color: "green" },
            { label: "Kate", color: "pink" },
        ],
    }),

    methods: {
        refresh() {
            this.milestones.forEach((milestone) => {
                let minStartDate = "";
                let maxEndDate = "";
                let minStartDateTask = "";
                let maxEndDateTask = "";
                let counter = 0;
                const tasks = this.tasks
                    .filter((task) => task.milestoneId == milestone.id)
                    .map((task) => {
                        if (counter == 0) {
                            minStartDate = new Date(task.plannedStartDate);
                            maxEndDate = new Date(task.plannedFinishedDate);
                            minStartDateTask = task.id;
                            maxEndDateTask = task.id;
                            counter++;
                        } else {
                            if (
                                new Date(task.plannedStartDate).setHours(
                                    0,
                                    0,
                                    0,
                                    0
                                ) < minStartDate.setHours(0, 0, 0, 0)
                            ) {
                                minStartDate = new Date(task.plannedStartDate);
                                minStartDateTask = task.id;
                            }
                            if (
                                new Date(task.plannedFinishedDate).setHours(
                                    0,
                                    0,
                                    0,
                                    0
                                ) > maxEndDate.setHours(0, 0, 0, 0)
                            ) {
                                maxEndDate = new Date(task.plannedFinishedDate);
                                maxEndDateTask = task.id;
                            }
                        }
                        this.milestoneMinMaxDateTracker[milestone.id] = {
                            minStartDate: minStartDate,
                            minStartDateTask: minStartDateTask,
                            maxEndDate: maxEndDate,
                            maxEndDateTask: maxEndDateTask,
                        };
                        return {
                            id: task.id,
                            taskId: task.taskId,
                            name: task.name,
                            startDate: new Date(
                                new Date(task.plannedStartDate).setHours(
                                    0,
                                    0,
                                    0,
                                    0
                                )
                            ),
                            endDate: new Date(
                                new Date(task.plannedFinishedDate).setHours(
                                    0,
                                    0,
                                    0,
                                    0
                                )
                            ),
                            allDay: true,
                            title: "6",
                            split: 2,
                            resourceId: task.milestoneId,
                            milestoneId: task.milestoneId,
                            employeeId: task.employeeId,
                            estimatedTime: task.estimatedTime,
                            spentTime: "1",
                            neededTime: task.neededTime,
                            actualStartDate: task.actualStartDate,
                            earliestStartDate: task.earliestStartDate,
                            actualFinishedDate: task.actualFinishedDate,
                            expectedFinishedDate: task.expectedFinishedDate,
                            plannedFinishedDate: task.plannedFinishedDate,
                            assignee: task.employeeId,
                            priority: task.priority,
                            status: task.status,
                            description: task.description,
                            relationships: task.relationships,
                            taskHours: (() => {
                                const taskHours = {};
                                let startDate = new Date(
                                    new Date(task.plannedStartDate)
                                );
                                const endDate = new Date(
                                    new Date(task.plannedFinishedDate)
                                );
                                while (
                                    startDate.setHours(0, 0, 0, 0) !=
                                        endDate.setHours(0, 0, 0, 0) &&
                                    endDate.setHours(0, 0, 0, 0) >=
                                        startDate.setHours(0, 0, 0, 0)
                                ) {
                                    taskHours[startDate.format("YYYY-MM-DD")] =
                                        {
                                            hours: 0,
                                        };
                                    startDate = startDate.addDays(1);
                                }
                                taskHours[startDate.format("YYYY-MM-DD")] = {
                                    hours: 0,
                                };
                                return taskHours;
                            })(),
                        };
                    });
                this.resources.push({
                    id: milestone.id,
                    name: milestone.name,
                    plannedStartDate: milestone.plannedStartDate,
                    plannedFinishedDate: milestone.plannedFinishedDate,
                    value: milestone.id,
                    estimatedTime: "0",
                    spentTime: "0",
                    neededTime: "0",
                    rowExpanded: true,
                    events: tasks,
                });
            });
            if (this.resources.length < 10) {
                const resourcesLength = this.resources.length;
                for (let i = 0; i < 10 - resourcesLength; i++) {
                    this.resources.push({
                        id: "0",
                        name: "",
                        plannedStartDate: "",
                        plannedFinishedDate: "",
                        value: "",
                        estimatedTime: "0",
                        spentTime: "0",
                        neededTime: "0",
                        rowExpanded: true,
                        events: [],
                    });
                }
            }
        },
        async updateEvent(event) {
            const eventTemp = { ...event };
            eventTemp.employeeId = eventTemp.resourceId;
            eventTemp.plannedStartDate =
                eventTemp.startDate.format("YYYY-MM-DD");
            eventTemp.plannedFinishedDate =
                eventTemp.endDate.format("YYYY-MM-DD");
            try {
                await this.$store.dispatch("tasks/update", {
                    id: eventTemp.id,
                    data: { ...eventTemp },
                });
                await this.$store.dispatch("tasks/list");
                await this.$store.dispatch(
                    "projectManagement/projectData",
                    this.$route.query.id
                );
            } catch (e) {}
            if (
                event.id ==
                this.milestoneMinMaxDateTracker[event.milestoneId]
                    .maxEndDateTask
            ) {
                const milestone = this.milestones.find(
                    (milestone) => milestone.id == event.milestoneId
                );
                milestone.plannedFinishedDate = eventTemp.plannedFinishedDate;
                const milestoneIndex = this.resources.findIndex(
                    (resource) => resource.id == milestone.id
                );
                this.resources[milestoneIndex].plannedFinishedDate =
                    milestone.plannedFinishedDate;
                try {
                    await this.$store.dispatch("milestones/update", {
                        id: event.milestoneId,
                        data: { ...milestone },
                    });
                    await this.$store.dispatch("milestones/list");
                    await this.$store.dispatch(
                        "projectManagement/projectData",
                        this.$route.query.id
                    );
                } catch (e) {}
            } else if (
                event.id ==
                this.milestoneMinMaxDateTracker[event.milestoneId]
                    .minStartDateTask
            ) {
                const milestone = this.milestones.find(
                    (milestone) => milestone.id == event.milestoneId
                );
                milestone.plannedStartDate = eventTemp.plannedStartDate;
                const milestoneIndex = this.resources.findIndex(
                    (resource) => resource.id == milestone.id
                );
                this.resources[milestoneIndex].plannedStartDate =
                    milestone.plannedStartDate;
                try {
                    await this.$store.dispatch("milestones/update", {
                        id: event.milestoneId,
                        data: { ...milestone },
                    });
                    await this.$store.dispatch(
                        "projectManagement/projectData",
                        this.$route.query.id
                    );
                    await this.$store.dispatch("milestones/list");
                    await this.$store.dispatch(
                        "projectManagement/projectData",
                        this.$route.query.id
                    );
                } catch (e) {}
            }
            // Inertia.visit(
            //     "/project-management?id=" +
            //         (new URLSearchParams(window.location.search).get("id") + '&tab=overview' ??
            //             ""),
            //     { only: ["tasks", "employees", "milestones"] }
            // );
        },
        addEventReverse(e) {
            const newEventDate = e.event.startDate.subtractDays(1);
            const resourceIndex = this.resources.findIndex(
                (r) => r.id == e.event.resourceId
            );
            const eventIndex = this.resources[resourceIndex].events.findIndex(
                (event) => event.id == e.event.id
            );
            this.resources[resourceIndex].events[eventIndex].startDate =
                newEventDate;
            this.resources[resourceIndex].events[eventIndex].taskHours[
                newEventDate.format("YYYY-MM-DD")
            ] = { hours: 0 };
            this.updateEvent(this.resources[resourceIndex].events[eventIndex]);
        },
        addEvent(e) {
            const newEventDate = e.event.endDate.addDays(1);
            const resourceIndex = this.resources.findIndex(
                (r) => r.id == e.event.resourceId
            );
            const eventIndex = this.resources[resourceIndex].events.findIndex(
                (event) => event.id == e.event.id
            );
            this.resources[resourceIndex].events[eventIndex].endDate =
                newEventDate;
            this.resources[resourceIndex].events[eventIndex].taskHours[
                newEventDate.format("YYYY-MM-DD")
            ] = { hours: 0 };
            this.updateEvent(this.resources[resourceIndex].events[eventIndex]);
            let event = this.resources[resourceIndex].events[eventIndex];
        },
        removeEvent(e) {
            const newEventDate = e.event.endDate.subtractDays(1);
            const resourceIndex = this.resources.findIndex(
                (r) => r.id == e.event.resourceId
            );
            const eventIndex = this.resources[resourceIndex].events.findIndex(
                (event) => event.id == e.event.id
            );
            this.resources[resourceIndex].events[eventIndex].endDate =
                newEventDate;
            delete this.resources[resourceIndex].events[eventIndex].taskHours[
                e.event.endDate.addDays(1).format("YYYY-MM-DD")
            ];
            this.updateEvent(this.resources[resourceIndex].events[eventIndex]);
        },
        removeEventReverse(e) {
            const newEventDate = e.event.startDate.addDays(1);
            const resourceIndex = this.resources.findIndex(
                (r) => r.id == e.event.resourceId
            );
            const eventIndex = this.resources[resourceIndex].events.findIndex(
                (event) => event.id == e.event.id
            );
            this.resources[resourceIndex].events[eventIndex].startDate =
                newEventDate;
            delete this.resources[resourceIndex].events[eventIndex].taskHours[
                e.event.startDate.subtractDays(1).format("YYYY-MM-DD")
            ];
            this.updateEvent(this.resources[resourceIndex].events[eventIndex]);
        },
        resourcesChanged(resources) {
            this.resources = [...resources];
        },
        log(...params) {
            // eslint-disable-next-line
            console.log(...params);
        },
    },
};
</script>

<style lang="scss">
.test-view {
    height: 100vh;
    overflow: auto;
    display: flex;
    flex-direction: column;

    .vuecal {
        height: 400px;
    }
    .vuecal__event {
        background-color: rgba(160, 220, 255, 0.5);
        border: 1px solid rgba(0, 100, 150, 0.15);
    }
}

// Global.
.w-app {
    margin: 0;
    padding: 0;
}
.top-bar,
footer {
    display: none !important;
}

.vuecal__special-hours {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 4px;

    em {
        font-size: 0.9em;
        color: #999;
    }
}
.doctor-1 {
    background-color: hsl(127, 100%, 97%);
    color: hsl(127, 50%, 67%);
}
.doctor-2 {
    background-color: hsl(217, 100%, 97%);
    color: hsl(217, 80%, 67%);
}
.doctor-3 {
    background-color: hsl(287, 100%, 97%);
    color: hsl(287, 80%, 67%);
}
.closed {
    background: hsl(27, 100%, 97%)
        repeating-linear-gradient(
            -45deg,
            hsla(27, 100%, 67%, 0.25),
            hsla(27, 100%, 67%, 0.25) 5px,
            rgba(255, 255, 255, 0) 5px,
            rgba(255, 255, 255, 0) 15px
        );
    color: hsl(27, 90%, 63%);
}
</style>
