<template>
    <div class="flex items-center mt-4 mb-2">
        <button type="button" class="secondary-btn" @click="goToToday">
            {{ $t("Go To Today") }}
        </button>
    </div>
    <div
        ref="container"
        class="timeline-container"
        :style="timelineStore.cssVars"
    >
        <div
            ref="timelineEl"
            class="timeline teej-timeline"
            :class="{ 'is-dragging-event': isDraggingEvent }"
        >
            <div class="grid-bg"></div>

            <ResourceList />

            <MonthAndDayHeader />

            <WeekendIndicators />
            <!-- <ResourceTimeline
        v-for="resourceId in timelineStore.resourceTimelines"
        :key="resourceId.id"
        :resource-id="resourceId.id"
        :resource-name="resourceId.name"
        :project-number="resourceId.projectNumber"
        :company-name="resourceId.companyName"
      /> -->

            <EventTimeline
                v-for="event in timelineStore.visibleEventTimelines"
                :key="event.id"
                :data="event"
                @event-change="() => handleEventChange(event)"
            />
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import { useTextDirection } from "@vueuse/core";
import useTimelineStore from "./store";
import { provideTimeline } from "./composables/useTimeline";
import { provideMousePosition } from "./composables/useMousePosition";
import MonthAndDayHeader from "./components/MonthAndDayHeader.vue";
import ResourceList from "./components/ResourceList.vue";
import WeekendIndicators from "./components/WeekendIndicators.vue";
import ResourceTimeline from "./components/ResourceTimeline.vue";
import EventTimeline from "./components/EventTimeline.vue";

const props = defineProps({
    resources: {
        type: Array,
        required: true,
    },
    visibleResources: {
        type: Number,
        default: 10,
    },
    events: {
        type: Array,
        default: () => [],
    },
    columnWidth: {
        type: Number,
        default: 120,
    },
    resourceWidth: {
        type: Number,
        default: 200,
    },
    rowHeight: {
        type: Number,
        default: 50,
    },
    headerHeight: {
        type: Number,
        default: 80,
    },
});

const textDir = useTextDirection();
const timelineStore = useTimelineStore();
const timelineEl = ref(null);

timelineStore.$reset();

function setConfig() {
    timelineStore.setConfig({
        columnWidth: props.columnWidth,
        resourceWidth: props.resourceWidth,
        rowHeight: props.rowHeight,
        headerHeight: props.headerHeight,
        textDir: textDir.value,
    });
}

timelineStore.addResources(props.resources);
timelineStore.addEvents(props.events);

watch(
    [
        () => props.columnWidth,
        () => props.resourceWidth,
        () => props.rowHeight,
        () => props.headerHeight,
    ],
    setConfig,
    { immediate: true }
);

const emit = defineEmits([
    "event-change",
    "date-change",
    "visible-date-change",
]);

const { container, goToToday } = provideTimeline();

const { isDraggingEvent } = provideMousePosition({ container });

watch(
    () => [timelineStore.startDate, timelineStore.endDate],
    ([newStart, newEnd]) => {
        emit("date-change", {
            startDate: newStart,
            endDate: newEnd,
        });
    }
);

watch(
    () => [timelineStore.visibleStartDate, timelineStore.visibleEndDate],
    ([visibleStart, visibleEnd]) => {
        emit("visible-date-change", {
            startDate: visibleStart,
            endDate: visibleEnd,
        });
    }
);

onMounted(() => {
    goToToday();
});

function handleEventChange(event) {
    emit("event-change", event);
}
</script>

<style scoped>
.info {
    font-family: sans-serif;
    text-align: center;
}

.grid-bg {
    position: absolute;
    top: var(--header-height);
    inset-inline-start: var(--resource-width);
    background-size: var(--column-width);
    background-image: linear-gradient(
        to right,
        rgba(0, 0, 0, 0.05) 1px,
        transparent 1px
    );
    width: 100%;
    pointer-events: none;
}

.timeline-container {
    width: 100%;
    overflow: auto;
    border: 1px solid #ccc;
    height: calc(100vh - 100px) !important;
}

.timeline {
    display: flex;
    position: relative;
    font-family: sans-serif;
    width: var(--timeline-width);
    /* height: var(--timeline-height); */
    height: 100%;
}

.is-dragging-event {
    cursor: grabbing;
}
</style>

<style>
.teej-timeline *,
.teej-timeline *:before,
.teej-timeline *:after {
    box-sizing: border-box;
}
</style>
