<template>
    <div class="test-view">
        <vue-cal
            @resourcesChanged="resourcesChanged"
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
            :disable-views="['years', 'day']"
        ></vue-cal>
    </div>
</template>

<script>
import VueCal from "@/Components/VueCal/index.vue";
const now = new Date();

export default {
    components: { VueCal },
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
        this.employees.forEach((employee) => {
            let totalEstimatedTime = 0
            let totalSpentTime = 0
            const tasks = this.tasks
                .filter((task) => task.employeeId == employee.id)
                .map((task) => {
                    totalEstimatedTime += +task.estimatedTime ?? 0
                    totalSpentTime += +task.spentTime ?? 0
                    return {
                        id: task.id,
                        taskId: task.taskId,
                        name: task.name,
                        startDate: new Date(
                            new Date(task.plannedStartDate).setHours(0, 0, 0, 0)
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
                        resourceId: task.employeeId,
                        employeeId: task.employeeId,
                        milestoneId: task.milestoneId,
                        estimatedTime: task.estimatedTime ?? 0,
                        spentTime: task.spentTime ?? 0,
                        remainingTime: (+task.estimatedTime ?? 0) - (+task.spentTime ?? 0),
                        actualStartDate: task.actualStartDate,
                        earliestStartDate: task.earliestStartDate,
                        actualFinishedDate: task.actualFinishedDate,
                        expectedFinishedDate: task.expectedFinishedDate,
                        plannedFinishedDate: task.plannedFinishedDate,
                        priority: task.priority,
                        status: task.status,
                        description: task.description,
                        taskHours: task.taskHours,
                        relationships: task.relationships,
                    };
                });
            this.resources.push({
                id: employee.id,
                label: employee.first_name + " " + employee.last_name,
                value: employee.id,
                estimatedTime: totalEstimatedTime,
                spentTime: totalSpentTime,
                remainingTime: +totalEstimatedTime - +totalSpentTime,
                rowExpanded: false,
                events: tasks,
            });
        });
        if (this.resources.length < 10) {
            const resourcesLength = this.resources.length;
            for (let i = 0; i < 10 - resourcesLength; i++) {
                this.resources.push({
                    id: 0,
                    label: "",
                    value: "0",
                    estimatedTime: "0",
                    spentTime: "0",
                    remainingTime: "0",
                    rowExpanded: false,
                    events: [],
                });
            }
        }
    },
    data: () => ({
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
        updateEvent(event) {
            this.$nextTick(async () => {
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
                } catch (e) {}
            });
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
            this.$nextTick(() => {
                this.updateEvent(
                    this.resources[resourceIndex].events[eventIndex]
                );
            });
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
            this.$nextTick(() => {
                this.updateEvent(
                    this.resources[resourceIndex].events[eventIndex]
                );
            });
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
            this.$nextTick(() => {
                this.updateEvent(
                    this.resources[resourceIndex].events[eventIndex]
                );
            });
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
            this.$nextTick(() => {
                this.updateEvent(
                    this.resources[resourceIndex].events[eventIndex]
                );
            });
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
