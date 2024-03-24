<template>
    <transition-group
        class="vuecal__cell"
        :class="cellClasses"
        :name="`slide-fade--${transitionDirection}`"
        tag="div"
        :appear="options.transitions"
        :style="cellStyles"
    >
        <div
            v-for="(resource, index) in resourcesWithEventsFilteredByViewDates"
            :key="
                'resource-' +
                (resource?.id ?? index + 'event-') +
                data.formattedDate
            "
            :style="`overflow: hidden; min-height: 40px; border-top: 1px solid lightgrey; width: 100%; opacity: ${
                !isCurrentMonth && isWeekOrDayView ? '0.5' : '1'
            }`"
        >
            <div
                v-if="vacationsByResource?.[resource.id]?.leaveType === 'half'"
               
            ></div>
            <div
                :style="`
                    min-height: 40px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    border-left: ${
                        resource.group ? 'none' : '1px solid lightgrey'
                    };
                    background-color: ${
                        resource.group
                            ? 'white'
                            : this.isWeekend
                            ? 'initial'
                            : getColorByVacationStatus(resource.id, false)
                    };
                `"
                class="vuecal__flex vuecal__cell-content"
                v-for="(split, i) in splitsCount
                    ? splits
                    : !resource.rowExpanded
                    ? 1
                    : 0"
                :key="
                    options.transitions ? `${view.id}-${data.content}-${i}` : i
                "
                :class="splitsCount && splitClasses(split)"
                :data-split="splitsCount ? split.id : false"
                column="column"
                tabindex="0"
                :aria-label="data.content"
                @focus="onCellFocus($event)"
                @keypress.enter="onCellkeyPressEnter($event)"
                @touchstart="
                    !isDisabled &&
                        onCellTouchStart($event, splitsCount ? split.id : null)
                "
                @mousedown="
                    !isDisabled &&
                        onCellMouseDown($event, splitsCount ? split.id : null)
                "
                @click="!isDisabled && onCellClick($event)"
                @dblclick="!isDisabled && onCellDblClick($event)"
                @contextmenu="
                    !isDisabled &&
                        options.cellContextmenu &&
                        onCellContextMenu($event)
                "
                @dragenter="
                    !isDisabled &&
                        editEvents.drag &&
                        dnd &&
                        dnd.cellDragEnter($event, $data, data.startDate)
                "
                @dragover="
                    !isDisabled &&
                        editEvents.drag &&
                        dnd &&
                        dnd.cellDragOver(
                            $event,
                            $data,
                            data.startDate,
                            splitsCount ? split.id : null
                        )
                "
                @dragleave="
                    !isDisabled &&
                        editEvents.drag &&
                        dnd &&
                        dnd.cellDragLeave($event, $data, data.startDate)
                "
                @drop="onDrop($event, resource?.id ?? resource.belongsTo)"
            >
                <div v-if="!resource.group && resource.id != 0">
                    <p v-if="isWeekOrDayView" class="p-0 m-0">
                        {{ currentDay }}
                    </p>
                    <p v-else class="p-0 m-0">
                        {{ data.startDate?.getDate() }} -
                        {{ data.endDate?.getDate() }}
                    </p>
                    <p
                        v-if="
                            isWeekOrDayView &&
                            showBalance &&
                            (workingHoursByResource[resource?.id] ?? 0) != 0
                        "
                        :class="`text-${
                            (workingHoursByResource[resource?.id] ?? 0) >= 0
                                ? 'red'
                                : 'green'
                        }-600 p-0 -mt-2`"
                    >
                        {{
                            ((workingHoursByResource[resource?.id] ?? 0) >= 0
                                ? ""
                                : "+") +
                            (resource?.id == 0
                                ? ""
                                : (
                                      -1 *
                                      (workingHoursByResource[resource?.id] ??
                                          0)
                                  ).toFixed(1)) +
                            "h"
                        }}
                    </p>
                    <p
                        :class="`text-${
                            (workingHoursByResourceMonthly[resource?.id] ??
                                0) >= 0
                                ? 'red'
                                : 'green'
                        }-600 p-0 -mt-2`"
                        v-if="
                            view.id === 'month' &&
                            showBalance &&
                            workingHoursByResourceMonthly[resource?.id]
                        "
                    >
                        {{
                            ((workingHoursByResourceMonthly[resource?.id] ??
                                0) >= 0
                                ? ""
                                : "+") +
                            (resource?.id == 0
                                ? ""
                                : (
                                      -1 *
                                      (workingHoursByResourceMonthly[
                                          resource?.id
                                      ] ?? 0)
                                  ).toFixed(1)) +
                            "h"
                        }}
                    </p>
                    <p
                        :class="`text-${
                            (workingHoursByResourceYearly[resource?.id] ?? 0) >=
                            0
                                ? 'red'
                                : 'green'
                        }-600 p-0 -mt-2`"
                        v-if="
                            view.id === 'year' &&
                            showBalance &&
                            workingHoursByResourceYearly[resource?.id]
                        "
                    >
                        {{
                            ((workingHoursByResourceYearly[resource?.id] ??
                                0) >= 0
                                ? ""
                                : "+") +
                            (resource?.id == 0
                                ? ""
                                : (
                                      -1 *
                                      (workingHoursByResourceYearly[
                                          resource?.id
                                      ] ?? 0)
                                  ).toFixed(1)) +
                            "h"
                        }}
                    </p>
                </div>
            </div>
        </div>
    </transition-group>
</template>

<script>
export default {
    emits: ["addEvent", "removeEvent", "addEventReverse", "removeEventReverse"],
    inject: ["vuecal", "utils", "modules", "view", "domEvents", "showBalance"],
    props: {
        // Vue-cal main component options (props).
        options: { type: Object, default: () => ({}) },
        editEvents: { type: Object, required: true },
        data: { type: Object, required: true },
        cellSplits: { type: Array, default: () => [] },
        minTimestamp: { type: [Number, null], default: null },
        maxTimestamp: { type: [Number, null], default: null },
        cellWidth: { type: [Number, Boolean], default: false },
        allDay: { type: Boolean, default: false },
        resources: Array,
        currentMonth: { type: String, default: "" },
        monthsData: { type: Array, default: [] },
    },
    data: () => ({
        vacationsByResource: {},
        workingHoursByResource: {},
        workingHoursByResourceMonthly: {},
        workingHoursByResourceYearly: {},
        weekDays: {
            0: "sun",
            1: "mon",
            2: "tue",
            3: "wed",
            4: "thu",
            5: "fri",
            6: "sat",
        },
        cellOverlaps: {},
        cellOverlapsStreak: 1, // Largest amount of simultaneous events in cell.
        // On mouse down, save the time at cursor so it can be reused on cell focus event
        // where there is no cursor coords.
        timeAtCursor: null,
        highlighted: false, // On event drag over.
        highlightedSplit: null,
    }),
    mounted() {
        this.$emitter.on("recomputeWorkHours", () => {
            this.recomputeWorkHours();
        });
    },
    methods: {
        getColorByVacationStatus(id, bypass) {
            if (
                this.vacationsByResource?.[id]?.leaveType === "half" &&
                !bypass
            ) {
                return "unset";
            } else if (!this.vacationsByResource?.[id]) {
                return "white";
            } else if (
                this.vacationsByResource?.[id]?.leaveType === "illness"
            ) {
                return "yellow";
            } else if (this.vacationsByResource[id]?.status === "approved") {
                return "lightgreen";
            } else if (this.vacationsByResource[id]?.status === "pending") {
                return "lightblue";
            } else if (this.vacationsByResource[id]?.status === "rejected") {
                return "red";
            } else {
                return "white";
            }
        },
        recomputeWorkHours(customResources) {
            let resources = customResources ? customResources : this.resources;
            resources.forEach((resource) => {
                if (this.view.id === "week") {
                    this.getTotalDayHours(resource);
                    this.getVacationsByResource(resource);
                } else if (this.view.id === "month") {
                    this.getTotalWeekHours(resource);
                } else if (this.view.id === "year") {
                    this.getTotalMonthHours(resource);
                }
            });
        },
        async onDrop(event, resourceId) {
            if (
                new Date().setHours(0, 0, 0, 0) >
                    this.data.startDate.setHours(0, 0, 0, 0) ||
                resourceId == 0
            )
                return;
            const resource = this.resources.find(
                (resource) =>
                    resource.id == event.dataTransfer.getData("resourceId")
            );
            const eventIndex = resource.events.findIndex(
                (ev) => ev.id == event.dataTransfer.getData("eventId")
            );
            const eventTemp = resource.events[eventIndex];
            if (event.dataTransfer.getData("resourceId") != resourceId) {
                resource.events.splice(eventIndex, 1);
                const resourceToBeAddedTo = this.resources.find(
                    (resource) => resource.id == resourceId
                );
                eventTemp.resourceId = resourceId;
                resourceToBeAddedTo.events.push(eventTemp);
                resourceToBeAddedTo.rowExpanded = true;
            }
            const prevStartDate = eventTemp.startDate;
            eventTemp.startDate = this.data.startDate;
            const diffTime = Math.abs(
                prevStartDate.setHours(0, 0, 0, 0) -
                    eventTemp.startDate.setHours(0, 0, 0, 0)
            );
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (
                prevStartDate.setHours(0, 0, 0, 0) >
                eventTemp.startDate.setHours(0, 0, 0, 0)
            )
                eventTemp.endDate = eventTemp.endDate.subtractDays(diffDays);
            else eventTemp.endDate = eventTemp.endDate.addDays(diffDays);
            let taskHoursTemp = {};
            let startDateTemp = eventTemp.startDate;
            for (let key in eventTemp.taskHours) {
                taskHoursTemp[startDateTemp.format("YYYY-MM-DD")] = {
                    hours: eventTemp.taskHours[key].hours,
                };
                startDateTemp = startDateTemp.addDays(1);
            }
            eventTemp.taskHours = { ...taskHoursTemp };
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
        },
        getVacationsByResource(resource) {
            let doesExist = false;
            let event = null;
            let limit = !resource.group ? resource.events.length : 0;
            for (
                let i = 0;
                i < limit;
                i++
            ) {
                event = resource.events[i];
                let startDate = new Date(event.startDate);
                let endDate = new Date(event.endDate);
                if (this.matchDates(this.data.startDate, startDate)) {
                    doesExist = true;
                    if (doesExist) {
                        this.vacationsByResource[resource.id] = event;
                    }
                    break;
                } else {
                    while (!this.matchDates(startDate, endDate) && (startDate < endDate)) {
                        if (this.matchDates(this.data.startDate, startDate)) {
                            doesExist = true;
                            if (doesExist) {
                                this.vacationsByResource[resource.id] = event;
                            }
                            break;
                        }
                        startDate = startDate.addDays(1);
                    }
                    if (this.matchDates(this.data.startDate, startDate)) {
                        {
                            doesExist = true;
                            if (doesExist) {
                                this.vacationsByResource[resource.id] = event;
                            }
                        }
                    }
                }
            }
        },
        getTotalDayHours(resource) {
            let doesExist = false;
            let timeTrackerData = null;
            for (let key in resource?.timeTrackerDetails ?? []) {
                timeTrackerData = resource.timeTrackerDetails[key];
                if (this.matchDates(this.data.startDate, new Date(key))) {
                    doesExist = true;
                    break;
                }
            }
            const workDay = resource?.employeeJobDetails?.workingDays?.find(
                (day) => {
                    return (
                        day.days?.[0] ===
                        this.weekDays[this.data.startDate.getDay()]
                    );
                }
            );

            let isHalfDay =
                resource?.events?.some((event) => {
                    return (
                        event.leaveType === "half" &&
                        this.matchDates(
                            new Date(event.startDate),
                            this.data.startDate
                        )
                    );
                }) ?? false;

            if (doesExist) {
                let totalTime = 0;
                timeTrackerData?.forEach((item) => {
                    totalTime += item.total_time;
                });
                this.workingHoursByResource[resource.id] =
                    (isHalfDay ? 6 : +(workDay?.numOfHours ?? 0)) - totalTime;
            }
        },
        getTotalWeekHours(resource) {
            let workedHours = 0;
            let startDate = this.data.startDate;
            let endDate = this.data.endDate;
            while (!this.matchDates(startDate, endDate)) {
                const dateString = `${startDate.getFullYear()}-${
                    (startDate.getMonth() < 10 ? "0" : "") +
                    (startDate.getMonth() + 1)
                }-${
                    (startDate.getDate() < 10 ? "0" : "") + startDate.getDate()
                }`;
                if (resource?.timeTrackerDetails?.[dateString]) {
                    let timeTrackerData =
                        resource.timeTrackerDetails[dateString];
                    const workDay =
                        resource?.employeeJobDetails?.workingDays?.find(
                            (day) => {
                                return (
                                    day.days?.[0] ===
                                    this.weekDays[startDate.getDay()]
                                );
                            }
                        );
                    let isHalfDay =
                        resource?.events?.some((event) => {
                            return (
                                event.leaveType === "half" &&
                                this.matchDates(
                                    new Date(event.startDate),
                                    startDate
                                )
                            );
                        }) ?? false;
                    let totalTime = 0;
                    timeTrackerData.forEach((item) => {
                        totalTime += item.total_time;
                    });
                    workedHours +=
                        (isHalfDay ? 6 : +(workDay?.numOfHours ?? 0)) -
                        totalTime;
                }
                startDate = startDate.addDays(1);
            }
            this.workingHoursByResourceMonthly[resource.id] = workedHours;
        },
        getTotalMonthHours(resource) {
            let workedHours = 0;
            let startDate = this.data.startDate;
            let endDate = this.data.endDate;
            while (!this.matchDates(startDate, endDate)) {
                const dateString = `${startDate.getFullYear()}-${
                    (startDate.getMonth() < 10 ? "0" : "") +
                    (startDate.getMonth() + 1)
                }-${
                    (startDate.getDate() < 10 ? "0" : "") + startDate.getDate()
                }`;
                if (resource?.timeTrackerDetails?.[dateString]) {
                    let timeTrackerData =
                        resource.timeTrackerDetails[dateString];
                    const workDay =
                        resource?.employeeJobDetails?.workingDays?.find(
                            (day) => {
                                return (
                                    day.days?.[0] ===
                                    this.weekDays[startDate.getDay()]
                                );
                            }
                        );
                    let isHalfDay =
                        resource?.events?.some((event) => {
                            return (
                                event.leaveType === "half" &&
                                this.matchDates(
                                    new Date(event.startDate),
                                    startDate
                                )
                            );
                        }) ?? false;
                    let totalTime = 0;
                    timeTrackerData.forEach((item) => {
                        totalTime += item.total_time;
                    });
                    workedHours +=
                        (isHalfDay ? 6 : +(workDay?.numOfHours ?? 0)) -
                        totalTime;
                }
                startDate = startDate.addDays(1);
            }
            this.workingHoursByResourceYearly[resource.id] = workedHours;
        },
        getSplitAtCursor({ target }) {
            const targetIsSplit =
                target.classList.contains("vuecal__cell-split");
            let split = targetIsSplit
                ? target
                : this.vuecal.findAncestor(target, "vuecal__cell-split");

            if (split) {
                split = split.attributes["data-split"].value;
                // Convert to a numeric value if split id is a number.
                if (parseInt(split).toString() === split.toString())
                    split = parseInt(split);
            }
            return split || null;
        },

        splitClasses(split) {
            return {
                "vuecal__cell-split": true,
                "vuecal__cell-split--highlighted":
                    this.highlightedSplit === split.id,
                [split.class]: !!split.class,
            };
        },

        checkCellOverlappingEvents() {
            // If splits, checkCellOverlappingEvents() is called from within computed splits.
            if (this.options.time && this.eventsCount && !this.splitsCount) {
                if (this.eventsCount === 1) {
                    this.cellOverlaps = [];
                    this.cellOverlapsStreak = 1;
                }
                // If only 1 event remains re-init the overlaps.
                else
                    [this.cellOverlaps, this.cellOverlapsStreak] =
                        this.utils.event.checkCellOverlappingEvents(
                            this.events,
                            this.options
                        );
            }
        },

        isDOMElementAnEvent(el) {
            return this.vuecal.isDOMElementAnEvent(el);
        },

        /**
         * Select a cell and go to narrower view on double click or single click according to vuecalProps option.
         */
        selectCell(DOMEvent, force = false) {
            // If splitting days, also return the clicked split on cell click when emitting event.
            const split = this.splitsCount
                ? this.getSplitAtCursor(DOMEvent)
                : null;

            this.utils.cell.selectCell(force, this.timeAtCursor, split);
            this.timeAtCursor = null;
        },

        onCellkeyPressEnter(DOMEvent) {
            if (!this.isSelected) this.onCellFocus(DOMEvent);

            // If splitting days, also return the clicked split on cell keypress when emitting event.
            const split = this.splitsCount
                ? this.getSplitAtCursor(DOMEvent)
                : null;

            this.utils.cell.keyPressEnterCell(this.timeAtCursor, split);
            this.timeAtCursor = null;
        },

        /**
         * On cell focus, from tab key or from mousedown, highlight the cell and emit
         * the cell-focus event with the date of the cell start when focusing from tab or
         * the date & time at cursor if click/touch.
         */
        onCellFocus(DOMEvent) {
            /** 
      if (!this.isSelected && !this.isDisabled) {
        this.isSelected = this.data.startDate; // Highlight the cell.

        // If splitting days, also return the clicked split on cell focus when emitting event.
        const split = this.splitsCount ? this.getSplitAtCursor(DOMEvent) : null;

        // Cell-focus event returns the cell start date (at midnight) if triggered from tab key,
        // or cursor coords time if clicked.
        const date = this.timeAtCursor || this.data.startDate;
        this.vuecal.$emit("cell-focus", split ? { date, split } : date);
      }
      **/
        },

        onCellMouseDown(DOMEvent, split = null, touch = false) {
            // Prevent a double mouse down on touch devices.
            if ("ontouchstart" in window && !touch) return false;

            if (!this.isSelected) this.onCellFocus(DOMEvent);

            const { clickHoldACell, focusAnEvent } = this.domEvents;
            // Reinit the click trigger on each mousedown.
            // In some cases we explicitly set this flag to prevent the click event to trigger,
            // and cancel event creation.
            this.domEvents.cancelClickEventCreation = false;
            // Also reinit this var on each mousedown.
            clickHoldACell.eventCreated = false;

            this.timeAtCursor = new Date(this.data.startDate);
            const {
                minutes,
                cursorCoords: { y },
            } = this.vuecal.minutesAtCursor(DOMEvent);
            this.timeAtCursor.setMinutes(minutes);

            const mouseDownOnEvent = this.isDOMElementAnEvent(DOMEvent.target);
            // Unfocus an event if any is focused and clicking on cell outside of an event.
            if (!mouseDownOnEvent && focusAnEvent._eid) {
                (
                    this.view.events.find(
                        (e) => e._eid === focusAnEvent._eid
                    ) || {}
                ).focused = false;
            }

            // Only if event creation is allowed and mousedown is on a cell (not on event).
            if (this.editEvents.create && !mouseDownOnEvent)
                this.setUpEventCreation(DOMEvent, y);
        },

        setUpEventCreation(DOMEvent, startCursorY) {
            // If dragToCreateEvent is true, start the event creation from dragging
            // only on week and day views (doesn't make sense on month view).
            if (
                this.options.dragToCreateEvent &&
                ["week", "day"].includes(this.view.id)
            ) {
                const { dragCreateAnEvent } = this.domEvents;
                dragCreateAnEvent.startCursorY = startCursorY;

                // If splitting days, store the clicked split to create an event in it from the global
                // mousemove handler in index.vue.
                dragCreateAnEvent.split = this.splitsCount
                    ? this.getSplitAtCursor(DOMEvent)
                    : null;

                // Save the time at cursor on initial mousedown.
                dragCreateAnEvent.start = this.timeAtCursor;

                // If snapToTime, set the start to the closest intervaled number.
                if (this.options.snapToTime) {
                    let timeMinutes =
                        this.timeAtCursor.getHours() * 60 +
                        this.timeAtCursor.getMinutes();
                    const plusHalfSnapTime =
                        timeMinutes + this.options.snapToTime / 2;
                    timeMinutes =
                        plusHalfSnapTime -
                        (plusHalfSnapTime % this.options.snapToTime);

                    dragCreateAnEvent.start.setHours(0, timeMinutes, 0, 0);
                }
            }

            // If the cellClickHold option is true and not mousedown on an event, click & hold to create an event.
            else if (
                this.options.cellClickHold &&
                ["month", "week", "day"].includes(this.view.id)
            ) {
                this.setUpCellHoldTimer(DOMEvent);
            }
        },

        // When click & holding a cell, and if allowed, set a timeout to create an event (can be cancelled).
        setUpCellHoldTimer(DOMEvent) {
            const { clickHoldACell } = this.domEvents;
            clickHoldACell.cellId = `${this.vuecal._.uid}_${this.data.formattedDate}`;
            // If splitting days, store the clicked split to create an event in it from the global
            // mousemove handler in index.vue.
            clickHoldACell.split = this.splitsCount
                ? this.getSplitAtCursor(DOMEvent)
                : null;

            clickHoldACell.timeoutId = setTimeout(() => {
                if (
                    clickHoldACell.cellId &&
                    !this.domEvents.cancelClickEventCreation
                ) {
                    const { _eid } = this.utils.event.createAnEvent(
                        this.timeAtCursor,
                        null,
                        clickHoldACell.split
                            ? { split: clickHoldACell.split }
                            : {}
                    );

                    clickHoldACell.eventCreated = _eid;
                }
            }, clickHoldACell.timeout);
        },

        onCellTouchStart(DOMEvent, split = null) {
            // If not mousedown on an event.
            this.onCellMouseDown(DOMEvent, split, true);
        },

        onCellClick(DOMEvent) {
            if (!this.isDOMElementAnEvent(DOMEvent.target))
                this.selectCell(DOMEvent);
        },

        onCellDblClick(DOMEvent) {
            const date = new Date(this.data.startDate);
            date.setMinutes(this.vuecal.minutesAtCursor(DOMEvent).minutes);

            // If splitting days, also return the clicked split on cell dblclick when emitting event.
            const split = this.splitsCount
                ? this.getSplitAtCursor(DOMEvent)
                : null;

            this.vuecal.$emit("cell-dblclick", split ? { date, split } : date);

            if (this.options.dblclickToNavigate)
                this.vuecal.switchToNarrowerView();
        },

        onCellContextMenu(DOMEvent) {
            DOMEvent.stopPropagation();
            DOMEvent.preventDefault();

            const date = new Date(this.data.startDate);
            const { cursorCoords, minutes } =
                this.vuecal.minutesAtCursor(DOMEvent);
            date.setMinutes(minutes);

            // If splitting days, also return the clicked split on cell contextmenu when emitting event.
            const split = this.splitsCount
                ? this.getSplitAtCursor(DOMEvent)
                : null;

            this.vuecal.$emit("cell-contextmenu", {
                date,
                ...cursorCoords,
                ...(split || {}),
                e: DOMEvent,
            });
        },
        matchDates(date1, date2) {
            return (
                date1.getDate() == date2.getDate() &&
                date1.getMonth() == date2.getMonth() &&
                date1.getFullYear() == date2.getFullYear()
            );
        },
    },
    computed: {
        isWeekend() {
            return [0, 6].includes(this.data.startDate.getDay());
        },
        isCurrentMonth() {
            return (
                this.currentMonth ===
                this.monthsData[this.data?.startDate?.getMonth()]
            );
        },
        currentDay() {
            return this.data.startDate.getDate();
        },
        resourcesWithEventsFilteredByViewDates() {
            let resources = [];
            if (this.isWeekOrDayView) {
                this.resources.forEach((resource) => {
                    let modifiedResource = { ...resource };
                    modifiedResource.events = [];
                    modifiedResource.rowExpanded = false;
                    resources.push({ ...modifiedResource });
                    if (resource.rowExpanded) {
                        for (let i = 0; i < resource.events.length; i++) {
                            resources.push({
                                ...resource.events[i],
                            });
                        }
                    }
                });
            } else {
                let modifiedResource = { ...this.data?.resource };
                modifiedResource.events = [];
                modifiedResource.rowExpanded = false;
                resources.push({ ...modifiedResource });
                if (this.data?.resource?.rowExpanded) {
                    for (
                        let i = 0;
                        i < (this.data?.resource?.events?.length ?? 0);
                        i++
                    ) {
                        resources.push({
                            ...this.data?.resource?.events[i],
                        });
                    }
                }
            }
            this.recomputeWorkHours(resources);
            return resources;
        },
        // Drag & drop module.
        dnd() {
            return this.modules.dnd;
        },
        nowInMinutes() {
            return this.utils.date.dateToMinutes(this.vuecal.now);
        },
        isBeforeMinDate() {
            return (
                this.minTimestamp !== null &&
                this.minTimestamp > this.data.endDate.getTime()
            );
        },
        isAfterMaxDate() {
            return (
                this.maxTimestamp &&
                this.maxTimestamp < this.data.startDate.getTime()
            );
        },
        // Is the current cell disabled or not.
        isDisabled() {
            const { disableDays } = this.options;
            const { isYearsOrYearView } = this.vuecal;
            if (
                disableDays.length &&
                disableDays.includes(this.data.formattedDate) &&
                !isYearsOrYearView
            )
                return true;
            return this.isBeforeMinDate || this.isAfterMaxDate;
        },
        // Is the current cell selected or not.
        isSelected: {
            get() {
                let selected = false;
                const { selectedDate } = this.view;

                if (this.view.id === "years") {
                    selected =
                        selectedDate.getFullYear() ===
                        this.data.startDate.getFullYear();
                } else if (this.view.id === "year") {
                    selected =
                        selectedDate.getFullYear() ===
                            this.data.startDate.getFullYear() &&
                        selectedDate.getMonth() ===
                            this.data.startDate.getMonth();
                } else
                    selected =
                        selectedDate.getTime() ===
                        this.data.startDate.getTime();

                return selected;
            },
            set(date) {
                this.view.selectedDate = date;
                this.vuecal.$emit(
                    "update:selected-date",
                    this.view.selectedDate
                );
            },
        },
        // Cache result for performance.
        isWeekOrDayView() {
            return ["week", "day"].includes(this.view.id);
        },
        transitionDirection() {
            return this.vuecal.transitionDirection;
        },
        specialHours() {
            // this.data.specialHours is always an array, but may be empty.
            return this.data.specialHours.map((block) => {
                let { from, to } = block;
                from = Math.max(from, this.options.timeFrom);
                to = Math.min(to, this.options.timeTo);
                return {
                    ...block,
                    height: (to - from) * this.timeScale,
                    top: (from - this.options.timeFrom) * this.timeScale,
                };
            });
        },
        events() {
            const { startDate: cellStart, endDate: cellEnd } = this.data;
            let events = [];

            // Calculate events on month/week/day views or years/year if eventsCountOnYearView.
            if (
                !(
                    ["years", "year"].includes(this.view.id) &&
                    !this.options.eventsCountOnYearView
                )
            ) {
                // Means that when vuecal.view.events changes all the cells will be looking up new value. :/
                // Also clone array to prevent modifying original.
                events = this.view.events.slice(0);

                if (this.view.id === "month") {
                    events.push(...this.view.outOfScopeEvents);
                }

                // Only keep events in cell time range.
                events = events.filter((e) =>
                    this.utils.event.eventInRange(e, cellStart, cellEnd)
                );

                if (this.options.showAllDayEvents && this.view.id !== "month")
                    events = events.filter((e) => !!e.allDay === this.allDay);

                // From events in view, filter the ones that are out of `time-from`-`time-to` range in this cell.
                if (this.options.time && this.isWeekOrDayView && !this.allDay) {
                    const { timeFrom, timeTo } = this.options;

                    events = events.filter((e) => {
                        const segment =
                            (e.daysCount > 1 &&
                                e.segments[this.data.formattedDate]) ||
                            {};
                        const singleDayInRange =
                            e.daysCount === 1 &&
                            e.startTimeMinutes < timeTo &&
                            e.endTimeMinutes > timeFrom;
                        const multipleDayInRange =
                            e.daysCount > 1 &&
                            segment.startTimeMinutes < timeTo &&
                            segment.endTimeMinutes > timeFrom;
                        const recurrMultDayInRange = false; // e.daysCount > 1 && e.repeat && recurringEventInRange(e, cellStart, cellEnd)
                        return (
                            e.allDay ||
                            singleDayInRange ||
                            multipleDayInRange ||
                            recurrMultDayInRange
                        );
                    });
                }

                // Position events with time in the timeline when there is a timeline and not in allDay slot.
                if (
                    this.options.time &&
                    this.isWeekOrDayView &&
                    !(this.options.showAllDayEvents && this.allDay)
                ) {
                    // Sort events in chronological order.
                    events.sort((a, b) => (a.start < b.start ? -1 : 1));
                }

                // If splits, checkCellOverlappingEvents() is called from within computed splits.
                if (!this.cellSplits.length)
                    this.$nextTick(this.checkCellOverlappingEvents);
            }

            return events;
        },
        eventsCount(events) {
            return this.events.length;
        },
        splits() {
            return this.cellSplits.map((item, i) => {
                const events = this.events.filter((e) => e.split === item.id);
                const [overlaps, streak] =
                    this.utils.event.checkCellOverlappingEvents(
                        events.filter((e) => !e.background && !e.allDay),
                        this.options
                    );
                return {
                    ...item,
                    overlaps,
                    overlapsStreak: streak,
                    events,
                };
            });
        },
        splitsCount() {
            return this.splits.length;
        },
        cellClasses() {
            return {
                "cursor-pointer":
                    this.view.id === "month" || this.view.id === "year",
                [this.data.class]: !!this.data.class,
                "vuecal__cell--current": this.data.current, // E.g. Current year in years view.
                "vuecal__cell--today": this.data.today,
                "vuecal__cell--out-of-scope": this.data.outOfScope,
                "vuecal__cell--before-min":
                    this.isDisabled && this.isBeforeMinDate,
                "vuecal__cell--after-max":
                    this.isDisabled && this.isAfterMaxDate,
                "vuecal__cell--disabled": this.isDisabled,
                "vuecal__cell--selected": this.isSelected,
                "vuecal__cell--highlighted": this.highlighted,
                "vuecal__cell--has-splits": this.splitsCount,
                "vuecal__cell--has-events": this.eventsCount,
            };
        },
        cellStyles() {
            let date = new Date(this.data.startDate);
                let day = date.toLocaleString('en-us', {weekday: 'long'});
               
            var showStyle='';
            if(  this.isWeekend && this.isWeekOrDayView  && (day==='Saturday' || day==='Sunday') ){
                showStyle="rgb(170 170 170 / 20%) !important";
           
            }else{
                showStyle='';
            }
           
            return {
                // cellWidth is only applied when hiding weekdays on month and week views.
                ...(this.cellWidth
                    ? {
                          //   display: "flex",
                          //   flexDirection: "row",
                          flexWrap: "wrap",
                          width: `${this.cellWidth}%`,
                          backgroundColor:
                          showStyle,
                          //   height:
                          //       this.isWeekend && this.isWeekOrDayView
                          //           ? "initial"
                          //           : `${this.showBalance ? "48" : "40"}px`,
                      }
                    : {
                          //   display: "flex",
                          //   flexDirection: "row",
                          flexWrap: "wrap",
                          backgroundColor:
                          showStyle,
                          //   height:
                          //       this.isWeekend && this.isWeekOrDayView
                          //           ? "initial"
                          //           : `${this.showBalance ? "48" : "40"}px`,
                      }),
            };
        },
        timelineVisible() {
            const { time, timeTo } = this.options;
            return (
                this.data.today &&
                this.isWeekOrDayView &&
                time &&
                !this.allDay &&
                this.nowInMinutes <= timeTo
            );
        },
        todaysTimePosition() {
            // Skip the Maths if not relevant.
            if (!this.data.today || !this.options.time) return;

            const minutesFromTop = this.nowInMinutes - this.options.timeFrom;
            return Math.round(minutesFromTop * this.timeScale);
        },
        timeScale() {
            return this.options.timeCellHeight / this.options.timeStep;
        },
    },
};
</script>

<style lang="scss">
.vuecal__cell {
    position: relative;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    transition: 0.15s ease-in-out background-color;

    // Cell modifiers.
    // -------------------------------------------------
    .vuecal__cells.year-view &,
    .vuecal__cells.week-view & {
        width: 8.333%;
    }

    .vuecal__cells.month-view & {
        width: 25%;
    }

    .vuecal--hide-weekends .vuecal__cells.month-view &,
    .vuecal--hide-weekends .vuecal__cells.week-view & {
        width: 20%;
    }

    // .vuecal__cells.years-view & {
    //     width: 20%;
    // }
    // .vuecal__cells.year-view & {
    //     width: 33.33%;
    // }
    .vuecal__cells.day-view & {
        flex: 1;
    }
    .vuecal--overflow-x.vuecal--day-view & {
        width: auto;
    }

    .vuecal--click-to-navigate &:not(&--disabled) {
        cursor: pointer;
    }
    .vuecal--view-with-time &,
    .vuecal--week-view.vuecal--no-time &:not(.vuecal__cell--has-splits),
    .vuecal--day-view.vuecal--no-time &:not(.vuecal__cell--has-splits) {
        display: block;
    }

    &.vuecal__cell--has-splits {
        flex-direction: row;
        display: flex;
    }

    &:before {
        content: "";
        position: absolute;
        z-index: 0;
        top: 0;
        left: 0;
        right: -1px;
        bottom: -1px;
        border: 1px solid rgba(196, 196, 196, 0.25);
    }
    .vuecal--overflow-x.vuecal--day-view &:before {
        bottom: 0;
    }

    &--today,
    &--current {
        background-color: rgba(240, 240, 255, 0.4);
        z-index: 1;
    }

    &--selected {
        background-color: rgba(235, 255, 245, 0.4);
        z-index: 2;

        .vuecal--day-view & {
            background: none;
        }
    }

    &--out-of-scope {
        color: rgba(0, 0, 0, 0.25);
    }
    &--disabled {
        color: rgba(0, 0, 0, 0.25);
        cursor: not-allowed;
    }

    // Cells/splits get highlighted when dragging an event over it.
    &--highlighted:not(.vuecal__cell--has-splits),
    &-split.vuecal__cell-split--highlighted {
        background-color: rgba(0, 0, 0, 0.04);
        // Drag over feedback must be fast. Then it can fade away with longer duration.
        transition-duration: 5ms;
    }
    // -------------------------------------------------

    &-content {
        position: relative;
        width: 100%;
        height: 100%;
        outline: none;

        .vuecal--years-view &,
        .vuecal--year-view &,
        .vuecal--month-view & {
            justify-content: center;
        }
    }

    .cell-time-labels {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
    }
    .cell-time-label {
        flex-grow: 1;
        font-size: 0.8em;
        opacity: 0.3;
        line-height: 1.7;
    }
    // .cell-time-label:hover {opacity: 0.7;}

    &-split {
        display: flex;
        flex-grow: 1;
        flex-direction: column;
        height: 100%;
        position: relative;
        transition: 0.15s ease-in-out background-color;
    }

    &-events {
        width: 100%;
    }

    &-events-count {
        position: absolute;
        left: 50%;
        top: 65%;
        transform: translateX(-50%);
        min-width: 12px;
        height: 12px;
        line-height: 12px;
        padding: 0 3px;
        background: #999;
        color: #fff;
        border-radius: 12px;
        font-size: 10px;
        box-sizing: border-box;
    }

    .vuecal__special-hours {
        position: absolute;
        left: 0;
        right: 0;
        box-sizing: border-box;
    }
}

.vuecal--overflow-x.vuecal--week-view .vuecal__cell,
.vuecal__cell-split {
    overflow: hidden;
}

.vuecal__no-event {
    padding-top: 1em;
    color: #aaa;
    justify-self: flex-start;
    margin-bottom: auto; // Vertical align top within flex column and align center.
}

.vuecal__all-day .vuecal__no-event {
    display: none;
}

.vuecal__now-line {
    position: absolute;
    left: 0;
    width: 100%;
    height: 0;
    color: red;
    border-top: 1px solid currentColor;
    opacity: 0.6;
    z-index: 1;

    &:before {
        content: "";
        position: absolute;
        top: -6px;
        left: 0;
        border: 5px solid transparent;
        border-left-color: currentColor;
    }
}
</style>
