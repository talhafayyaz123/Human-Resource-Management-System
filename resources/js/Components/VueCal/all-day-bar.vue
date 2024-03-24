<template>
  <div
    class="vuecal__flex vuecal__all-day"
    :style="cellOrSplitMinWidth && { height }"
  >
    <div
      class="vuecal__all-day-text"
      v-if="!cellOrSplitMinWidth"
      style="width: 3em"
    >
      <span><span v-if="required" style="color: red;">*</span>&nbsp;{{ label }}</span>
    </div>
    <div
      class="vuecal__flex vuecal__cells"
      :class="`${view.id}-view`"
      grow="grow"
      :style="cellOrSplitMinWidth ? `min-width: ${cellOrSplitMinWidth}px` : ''"
    >
      <vuecal-cell
        v-for="(cell, i) in cells"
        :key="i"
        :options="options"
        :edit-events="editEvents"
        :data="cell"
        :all-day="true"
        :cell-width="options.hideWeekdays.length && (vuecal.isWeekView || vuecal.isMonthView) && vuecal.cellWidth"
        :min-timestamp="options.minTimestamp"
        :max-timestamp="options.maxTimestamp"
        :cell-splits="daySplits"
      >
        <template #event="{ event, view }">
          <slot name="event" :view="view" :event="event"></slot>
        </template>
      </vuecal-cell>
    </div>
  </div>
</template>

<script>
import Cell from "./cell.vue";

export default {
  inject: ["vuecal", "view", "editEvents"],
  components: { "vuecal-cell": Cell },
  props: {
    // Vue-cal main component options (props).
    required: { type: Boolean, required: false },
    options: { type: Object, required: true },
    cells: { type: Array, required: true },
    label: { type: String, required: true },
    daySplits: { type: Array, default: () => [] },
    shortEvents: { type: Boolean, default: true },
    height: { type: String, default: "" },
    cellOrSplitMinWidth: { type: Number, default: null },
  },

  computed: {
    hasCellOrSplitWidth() {
      return !!(
        this.options.minCellWidth ||
        (this.daySplits.length && this.options.minSplitWidth)
      );
    },
  },
};
</script>
