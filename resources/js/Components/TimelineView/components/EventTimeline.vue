<template>
    <div
        ref="target"
        tabindex="-1"
        class="event event-card"
        :style="positionStyles"
        :class="{
            'is-dragging': isDragging,
        }"
    >
        <div class="icon">
            <CustomIcon name="projIcon" />
        </div>
        <div class="content">
            <div class="content-left" @mousedown.prevent="handleStartDrag">
                <h3>{{ props.data?.name }}</h3>
                <p>{{ props.data?.companyName }}</p>
                <p>{{ props.data?.projectNumber }}</p>
                <div class="flex items-center gap-2 relative">
                    <div class="plane-hours">
                        <span>{{ $t("Planned Hours: ") }}</span>
                        <p>{{ props.data?.estimatedTime }} h</p>
                    </div>
                    <div class="plane-hours used">
                        <span>{{ $t("Used: ") }}</span>
                        <p>100 h</p>
                    </div>
                    <div class="plane-hours open">
                        <span>{{ $t("Open: ") }}</span>
                        <p>100 h</p>
                    </div>
                </div>
            </div>
            <div class="content-right">
                <div
                    class="flex items-center justify-between w-full gap-3 relative"
                >
                    <div class="tag">New</div>
                    <div
                        class="flag-icon cursor-pointer"
                        @click="showPlanningDiv"
                    >
                        <CustomIcon name="flagIcon" />
                    </div>
                    <div class="info-icon">
                        <CustomIcon name="infoEvIcon" />
                    </div>
                    <div class="action-icon">
                        <dropdown placement="bottom-end">
                            <template #default>
                                <div
                                    class="group flex items-center cursor-pointer select-none"
                                >
                                    <CustomIcon name="actionbarIcon" />
                                </div>
                            </template>
                            <template #dropdown>
                                <div class="dropdown-menu">
                                    <ul class="dropdown-list">
                                        <li class="dropdown-item">
                                            <div class="icon mr-2">
                                                <CustomIcon name="editIcon" />
                                            </div>
                                            <span>{{ $t("Edit") }}</span>
                                        </li>
                                        <li class="dropdown-item delete">
                                            <div class="icon mr-2">
                                                <CustomIcon name="DeleteIcon" />
                                            </div>
                                            <span>{{ $t("Delete") }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                    <div
                        class=""
                        id="planningDetail"
                        v-show="isPlanningDetailVisible"
                    >
                        <EventProjectDetails :data="props.data"/>
                    </div>
                </div>
                <div class="user">
                    <img src="@/assets/images/avatar-1.png" alt="" />
                </div>
                <div class="users">
                    <ul>
                        <li>
                            <img src="@/assets/images/avatar-1.png" alt="" />
                        </li>
                        <li>
                            <img src="@/assets/images/avatar-1.png" alt="" />
                        </li>
                        <li>
                            <img src="@/assets/images/avatar-1.png" alt="" />
                        </li>
                        <li>
                            <img src="@/assets/images/avatar-1.png" alt="" />
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { useCurrentMousePosition } from "../composables/useMousePosition";
import useTimelineStore from "../store";
import { DATE_FORMAT } from "../constants";
import EventProjectDetails from "./EventProjectDetails.vue";
import Dropdown from "../../Dropdown.vue";
const emit = defineEmits(["event-change"]);

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
});

const target = ref(null);
const isDragging = ref(false);
const isPlanningDetailVisible = ref(false);
const { hoveredDate, isDraggingEvent } = useCurrentMousePosition();
const store = useTimelineStore();
const dragOffset = ref(0);
const draggingPos = computed(() => {
    if (!isDragging.value) return null;

    return {
        x: store.datePositions[hoveredDate.value.toFormat(DATE_FORMAT)],
    };
});
const position = computed(() => {
    if (!store.eventPositions[props.data.id]) return "";

    const pos = store.eventPositions[props.data.id];
    const x = draggingPos.value
        ? draggingPos.value.x - dragOffset.value
        : pos.left;
    const y = pos.top;

    return { x, y, w: pos.width };
});
const positionStyles = computed(() => {
    if (!position.value) return "";

    const { w, x, y } = position.value;
    return `--transform: translate(${x}px, ${y}px); --width: ${w}px`;
});
const showPlanningDiv = () => {
    isPlanningDetailVisible.value = !isPlanningDetailVisible.value;
};

const hidePlanningDiv = () => {
    isPlanningDetailVisible.value = false;
};
function handleStartDrag(e) {
    const colOffsetPos =
        parseInt(e.offsetX / store.columnWidth, 10) * store.columnWidth;
    dragOffset.value = colOffsetPos;
    isDragging.value = true;
    isDraggingEvent.value = props.data.id;

    // adding event listener here instead of on the element
    // because if you move the mouse off the event
    // and then let go, nothing happens as the event can't be triggered
    document.addEventListener("mouseup", handleStopDrag, { once: true });
}

function handleStopDrag() {
    const colOffsetPos = draggingPos.value.x - dragOffset.value;

    const date = Object.keys(store.datePositions).find(
        (key) => store.datePositions[key] === colOffsetPos
    );

    store.updateEventDate(props.data.id, date);
    emit("event-change", store.events[props.data.id]);

    isDragging.value = false;
    isDraggingEvent.value = null;
}
</script>

<style scoped>
.event {
    position: absolute;
    padding: 12px;
    /* height: var(--row-height); */
    transform: var(--transform);
    width: var(--width);
    cursor: grab;
    contain-intrinsic-size: var(--width) var(--row-height);
}

.event.is-dragging {
    cursor: grabbing;
    transition: transform ease 0.1s;
}
</style>
