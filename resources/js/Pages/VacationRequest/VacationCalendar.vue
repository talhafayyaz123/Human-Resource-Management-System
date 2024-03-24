<template>
    <div class="tab-header">
        <ul>
            <li class="nav-item">
                <a
                    class="nav-link"
                    @click="
                        activeTab = 'daily';
                        activeView = 'week';
                    "
                    :class="
                        activeTab === 'daily' ? activeClasses : inactiveClasses
                    "
                >
                    {{ $t("Daily Calendar") }}
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    @click="
                        activeTab = 'monthly';
                        activeView = 'month';
                    "
                    :class="
                        activeTab === 'monthly'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("Monthly Calendar") }}
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    @click="
                        activeTab = 'yearly';
                        activeView = 'year';
                    "
                    :class="
                        activeTab === 'yearly' ? activeClasses : inactiveClasses
                    "
                >
                    {{ $t("Annual Calendar") }}
                </a>
            </li>
        </ul>
    </div>
    <div class="mt-5">
        <div class="grid items-center grid-cols-3 gap-6">
            <div class="w-full">
                <div class="form-group">
                    <text-input
                        v-model="form.search"
                        :label="$t('Search Term')"
                    />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <MultiSelectInput
                        v-model="form.status"
                        :options="statuses"
                        :multiple="false"
                        :textLabel="$t('Status')"
                        label="label"
                        :trackBy="'id'"
                        :error="errors.status"
                    />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <MultiSelectInput
                        :textLabel="$t('Type Of Absence')"
                        v-model="form.leaveType"
                        :options="typesOfAbsence"
                        :multiple="false"
                        label="label"
                        :trackBy="'id'"
                        :error="errors.leaveType"
                    />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <MultiSelectInput
                        :show-labels="false"
                        v-model="form.locationId"
                        :options="locations.data"
                        :multiple="false"
                        :textLabel="$t('Location')"
                        label="street"
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
                                <p class="font-bold">{{ $t("Name") }}</p>
                                <p class="font-bold">{{ $t("Street") }}</p>
                                <p class="font-bold">{{ $t("City") }}</p>
                                <p class="font-bold">{{ $t("ZIP") }}</p>
                                <p class="font-bold">{{ $t("State") }}</p>
                                <p class="font-bold">{{ $t("Country") }}</p>
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
            </div>
            <div class="w-full">
                <div class="form-group">
                    <MultiSelectInput
                        @update:model-value="fetchTeamsByDepartment"
                        v-model="form.departmentId"
                        :options="departments.data"
                        :multiple="false"
                        :textLabel="$t('Department')"
                        label="name"
                        trackBy="id"
                        moduleName="departments"
                        :error="errors.departmentId"
                    />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <MultiSelectInput
                        v-if="cleared"
                        v-model="form.teamId"
                        :options="teams"
                        :multiple="false"
                        :textLabel="$t('Team')"
                        label="name"
                        trackBy="id"
                        moduleName="teams"
                        :error="errors.teamId"
                    />
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <div class="flex">
                        <p class="self-center">{{ $t("Sort By:") }}</p>
                        <div class="ml-3 self-center">
                            <input
                                v-model="form.sortBy"
                                value="employees"
                                name="sort"
                                id="employees"
                                type="radio"
                            />
                            <label class="ml-1" for="employees">{{
                                $t("Employees")
                            }}</label>
                        </div>
                        <div class="ml-3 self-center">
                            <input
                                v-model="form.sortBy"
                                value="departments"
                                name="sort"
                                id="departments"
                                type="radio"
                            />
                            <label class="ml-1" for="departments">{{
                                $t("Departments")
                            }}</label>
                        </div>
                        <div class="ml-3 self-center">
                            <input
                                v-model="form.sortBy"
                                value="teams"
                                name="sort"
                                id="teams"
                                type="radio"
                            />
                            <label class="ml-1" for="teams">{{
                                $t("Teams")
                            }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="form-group">
                    <div class="flex">
                        <input
                            v-model="form.showBalance"
                            id="show-daily-balance"
                            type="checkbox"
                        />
                        <label
                            class="ml-2 self-center"
                            for="show-daily-balance"
                            >{{ $t("Show Daily Balance") }}</label
                        >
                    </div>
                </div>
            </div>
            <div class="w-full">
                <div class="flex justify-end">
                    <button @click="search" class="secondary-btn">
                        {{ $t("Search") }}
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <label class="">{{ $t("Group By:") }}</label>
            <div class="flex items-center mt-2">
                <div class="ml-3 self-center">
                    <input
                        v-model="form.groupBy"
                        value="employees"
                        name="order"
                        id="employees"
                        type="radio"
                    />
                    <label class="ml-1" for="employees">{{
                        $t("Employees")
                    }}</label>
                </div>
                <div class="ml-3 self-center">
                    <input
                        v-model="form.groupBy"
                        value="departments"
                        name="order"
                        id="departments"
                        type="radio"
                    />
                    <label class="ml-1" for="departments">{{
                        $t("Departments")
                    }}</label>
                </div>
                <div class="ml-3 self-center">
                    <input
                        v-model="form.groupBy"
                        value="teams"
                        name="order"
                        id="teams"
                        type="radio"
                    />
                    <label class="ml-1" for="teams">{{ $t("Teams") }}</label>
                </div>
            </div>
        </div>
    </div>
    <div v-if="show" class="mt-3 relative vacation-calendar">
        <vacation-calendar
            @currentYear="currentYear = $event"
            @currentMonth="currentMonth = $event"
            @viewChanged="search"
            @weekViewBoundary="weekViewBoundaryChanged"
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
            :active-view="activeView"
            :disable-views="['years', 'day']"
            :showBalance="form.showBalance"
            :loading="loading"
        ></vacation-calendar>
        <div
            v-if="userPerPage > perPage"
            @click="loadMore()"
            class="flex justify-center mt-3"
        >
            <button class="px-3 py-2 rounded bg-gray-200 cursor-pointer">
                {{ $t("Load more") }}
            </button>
        </div>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import VacationCalendar from "../../Components/VacationCalendar/index.vue";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin";
import { mapGetters } from "vuex";

const now = new Date();

export default {
    mixins: [userProfilePicturesMixin],
    props: {
        tasks: {
            default: () => [],
        },
        milestones: {
            default: () => [],
        },
    },
    computed: {
        ...mapGetters("userDepartments", ["departments"]),
        ...mapGetters("userLocations", ["locations"]),
        ...mapGetters("vacationRequest", ["vacationCalendar", "userPerPage"]),
        ...mapGetters(["errors"]),
        ...mapGetters("auth", {
            employees: "users",
            userProfilePictures: "userProfilePictures",
        }),
    },
    components: {
        TextInput,
        MultiSelectInput,
        VacationCalendar,
    },
    watch: {
        "form.groupBy"() {
            this.createGroups();
        },
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            this.$emitter.on("search", () => {
                this.search();
            });
            setTimeout(async () => {
                this.show = true;
                await this.$nextTick();
                this.search();
            }, 1000);

            if (!this.departments?.data?.length)
                await this.$store.dispatch("userDepartments/list", {
                    limit_start: 0,
                    limit_count: 25,
                });
            if (!this.locations?.data?.length)
                await this.$store.dispatch("userLocations/list", {
                    limit_start: 0,
                    limit_count: 25,
                });
        } catch (e) {
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
    data() {
        return {
            perPage: 25,
            loading: false,
            monthsData: [
                "january",
                "february",
                "march",
                "april",
                "may",
                "june",
                "july",
                "august",
                "september",
                "october",
                "november",
                "december",
            ],
            currentMonth: "",
            currentYear: "",
            weekViewBoundary: {},
            teams: [],
            statuses: [
                {
                    id: 1,
                    value: "approved",
                    label: "Approved",
                },
                {
                    id: 2,
                    value: "pending",
                    label: "Pending",
                },
                {
                    id: 3,
                    value: "rejected",
                    label: "Rejected",
                },
            ],
            typesOfAbsence: [
                {
                    id: 1,
                    value: "whole",
                    label: "Whole",
                },
                { id: 2, value: "half", label: "Half" },
            ],
            cleared: true,
            activeView: "week",
            activeTab: "daily",
            activeClasses:
                "active",
            inactiveClasses:
                "inactive",
            show: false,
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
            vacationTypes: ["Requested", "Approved"],
            form: {
                search: "",
                status: null,
                departmentId: null,
                locationId: null,
                teamId: null,
                showBalance: true,
                leaveType: null,
                sortBy: "employees",
                groupBy: "employees",
            },
        };
    },
    methods: {
        createGroups() {
            if (
                this.form.groupBy === "departments" ||
                this.form.groupBy === "teams"
            ) {
                let resources = {};
                (this.vacationCalendar?.data ?? []).forEach((item, index) => {
                    let groups = [];
                    if (this.form.groupBy === "departments") {
                        groups = item.departments;
                    } else if (this.form.groupBy === "teams") {
                        groups = item.teams;
                    }
                    groups.forEach((departmentOrTeam) => {
                        if (!resources[departmentOrTeam.id]) {
                            resources[departmentOrTeam.id] = {
                                id: departmentOrTeam.id,
                                label: departmentOrTeam.name,
                                group: true,
                                events: [
                                    {
                                        id: index + 1,
                                        profilePic: item?.profilePictureUrl,
                                        label:
                                            item.firstName +
                                            " " +
                                            item.lastName,
                                        value: "0",
                                        estimatedTime: "0",
                                        spentTime: "0",
                                        neededTime: "0",
                                        rowExpanded: false,
                                        employeeJobDetails:
                                            item?.employeeJobDetails ?? null,
                                        timeTrackerDetails:
                                            item?.timeTrackerDetails ?? null,
                                        weeklyWorkedDetails:
                                            item?.weeklyWorkedDetails ?? null,
                                        events: item?.vacationDetails ?? [],
                                    },
                                ],
                            };
                        } else {
                            resources[departmentOrTeam.id].events = [
                                ...resources[departmentOrTeam.id].events,
                                {
                                    id: index + 1,
                                    profilePic: item?.profilePictureUrl,
                                    label: item.firstName + " " + item.lastName,
                                    userId: item.userId,
                                    value: "0",
                                    estimatedTime: "0",
                                    spentTime: "0",
                                    neededTime: "0",
                                    rowExpanded: false,
                                    employeeJobDetails:
                                        item?.employeeJobDetails ?? null,
                                    timeTrackerDetails:
                                        item?.timeTrackerDetails ?? null,
                                    weeklyWorkedDetails:
                                        item?.weeklyWorkedDetails ?? null,
                                    events: item?.vacationDetails ?? [],
                                },
                            ];
                        }
                    });
                });
                this.resources = [...Object.values(resources)];
            } else {
                let resources = [];
                (this.vacationCalendar?.data ?? []).forEach((item, index) => {
                    resources.push({
                        id: index + 1,
                        profilePic: item?.profilePictureUrl,
                        group: false,
                        label: item.firstName + " " + item.lastName,
                        value: "0",
                        estimatedTime: "0",
                        spentTime: "0",
                        neededTime: "0",
                        rowExpanded: false,
                        employeeJobDetails: item?.employeeJobDetails ?? null,
                        timeTrackerDetails: item?.timeTrackerDetails ?? null,
                        weeklyWorkedDetails: item?.weeklyWorkedDetails ?? null,
                        userId: item?.userId ?? "",
                        events: item?.vacationDetails ?? [],
                    });
                });
                this.resources = [...resources];
            }
        },
        loadMore() {
            this.$store.commit("vacationCalendarLoader", true);
            this.perPage += 10;
            this.search();
        },
        weekViewBoundaryChanged(event) {
            this.weekViewBoundary = event;
        },
        async fetchTeamsByDepartment() {
            try {
                await this.$nextTick();
                if (this.form?.departmentId?.id) {
                    const response = await this.$store.dispatch(
                        "userTeams/getTeamsByDepartment",
                        this.form?.departmentId?.id
                    );
                    this.teams = response?.data?.data ?? [];
                    this.resetDataForDependents();
                }
            } catch (e) {
                console.log(e);
            }
        },
        async resetDataForDependents() {
            this.cleared = false;
            this.form.teamId = null;
            await this.$nextTick();
            this.cleared = true;
        },
        async search() {
            let resources = JSON.parse(JSON.stringify(this.resources));
            this.$store.commit("vacationCalendarLoader", true);
            await this.$nextTick();
            resources.splice(0, resources.length);
            const payload = {
                calendarType: this.activeTab,
                calendarStartDate: this.weekViewBoundary?.startDate,
                calendarEndDate: this.weekViewBoundary?.endDate,
                calendarDay: `${this.currentYear}-${
                    this.monthsData.indexOf(this.currentMonth) + 1
                }-01`,
            };
            try {
                await this.$store.dispatch(
                    "vacationRequest/listUserVacationDataByDates",
                    {
                        payload: { ...payload },
                        queryParams: {
                            ...this.form,
                            status: this?.form?.status?.value ?? "",
                            departmentId: this?.form?.departmentId?.id ?? "",
                            locationId: this?.form?.locationId?.id ?? "",
                            teamId: this?.form?.teamId?.id ?? "",
                            leaveType: this?.form?.leaveType?.value ?? "",
                            sortBy: this.form.sortBy,
                            groupBy: this.form.groupBy,
                            userPerPage: this.perPage,
                        },
                    }
                );
                this.createGroups();
            } catch (e) {
                console.log(e);
            } finally {
                this.$nextTick(() => {
                    this.$emitter.emit("recomputeWorkHours", true);
                });
                this.$store.commit("vacationCalendarLoader", false);
                this.getUserProfilePictures(
                    this.vacationCalendar?.data ?? [],
                    "userId"
                );
            }
        },
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
