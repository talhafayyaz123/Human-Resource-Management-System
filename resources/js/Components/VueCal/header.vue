<template>
<div class="vuecal__header">
  <div class="vuecal__flex vuecal__menu" v-if="!options.hideViewSelector" role="tablist" aria-label="Calendar views navigation">
    <template v-for="(v, id) in viewProps.views" :key="id">
      <button class="vuecal__view-btn" v-if="v.enabled" type="button" :class="{ 'vuecal__view-btn--active': view.id === id, 'vuecal__view-btn--highlighted': highlightedControl === id }" @dragenter="editEvents.drag && dnd && dnd.viewSelectorDragEnter($event, id, $data)" @dragleave="editEvents.drag && dnd && dnd.viewSelectorDragLeave($event, id, $data)" @click="switchView(id, null, true)" :aria-label="`${v.label} view`">{{ v.label }}</button>
    </template>
  </div>
  <div class="vuecal__title-bar" v-if="!options.hideTitleBar">
    <button class="vuecal__arrow vuecal__arrow--prev" type="button" :class="{ 'vuecal__arrow--highlighted': highlightedControl === 'previous' }" @click="previous" @dragenter="editEvents.drag && dnd && dnd.viewSelectorDragEnter($event, 'previous', $data)" @dragleave="editEvents.drag && dnd && dnd.viewSelectorDragLeave($event, 'previous', $data)" :aria-label="`Previous ${view.id}`">
      <slot name="arrow-prev"></slot>
    </button>
    <div class="vuecal__flex vuecal__title" grow="grow">
      <transition :name="options.transitions ? `slide-fade--${transitionDirection}` : ''">
        <component :type="!!broaderView && 'button'" :is="broaderView ? 'button' : 'span'" :key="`${view.id}${view.startDate.toString()}`" @click="!!broaderView && switchToBroaderView()" :aria-label="!!broaderView && `Go to ${broaderView} view`">
          <slot name="title"></slot>
        </component>
      </transition>
    </div>
    <button class="vuecal__today-btn" v-if="options.todayButton" type="button" :class="{ 'vuecal__today-btn--highlighted': highlightedControl === 'today' }" @click="goToToday" @dragenter="editEvents.drag && dnd && dnd.viewSelectorDragEnter($event, 'today', $data)" @dragleave="editEvents.drag && dnd && dnd.viewSelectorDragLeave($event, 'today', $data)" aria-label="Today">
      <slot name="today-button"></slot>
    </button>
    <button class="vuecal__arrow vuecal__arrow--next" type="button" :class="{ 'vuecal__arrow--highlighted': highlightedControl === 'next' }" @click="next" @dragenter="editEvents.drag && dnd && dnd.viewSelectorDragEnter($event, 'next', $data)" @dragleave="editEvents.drag && dnd && dnd.viewSelectorDragLeave($event, 'next', $data)" :aria-label="`Next ${view.id}`">
      <slot name="arrow-next"></slot>
    </button>
  </div>
  <weekdays-headings v-if="viewProps.weekDaysInHeader" :week-days="weekDays" :transition-direction="transitionDirection" :switch-to-narrower-view="switchToNarrowerView">
    <template #weekday-heading="{ heading, view }" v-if="$slots['weekday-heading']">
      <slot name="weekday-heading" :heading="heading" :view="view"></slot>
    </template>
    <template #split-label="{ split }" v-if="$slots['split-label']">
      <slot name="split-label" :split="split" :view="view"></slot>
    </template>
  </weekdays-headings>
  <transition :name="`slide-fade--${transitionDirection}`">
    <div class="vuecal__flex vuecal__split-days-headers" v-if="showDaySplits">
      <div class="day-split-header" v-for="(split, i) in daySplits" :key="i" :class="split.class || false">
        <slot name="split-label" :split="split" :view="view.id">{{ split.label }}</slot>
      </div>
    </div>
  </transition>
</div>
</template>

<script>
import WeekdaysHeadings from './weekdays-headings.vue'

export default {
  inject: ['vuecal', 'previous', 'next', 'switchView', 'updateSelectedDate', 'modules', 'view'],
  components: { WeekdaysHeadings },
  props: {
    // Vuecal main component options (props).
    options: { type: Object, default: () => ({}) },
    editEvents: { type: Object, required: true },
    hasSplits: { type: [Boolean, Number], default: false },
    daySplits: { type: Array, default: () => [] },
    viewProps: { type: Object, default: () => ({}) },
    weekDays: { type: Array, default: () => [] },
    switchToNarrowerView: { type: Function, default: () => {} }
  },

  data: () => ({
    highlightedControl: null
  }),

  methods: {
    goToToday () {
      // Last midnight.
      this.updateSelectedDate(new Date(new Date().setHours(0, 0, 0, 0)))
    },

    switchToBroaderView () {
      this.transitionDirection = 'left'

      if (this.broaderView) this.switchView(this.broaderView)
    }
  },

  computed: {
    transitionDirection: {
      get () {
        return this.vuecal.transitionDirection
      },
      set (direction) {
        this.vuecal.transitionDirection = direction
      }
    },
    broaderView () {
      const { enabledViews } = this.vuecal
      return enabledViews[enabledViews.indexOf(this.view.id) - 1]
    },
    showDaySplits () {
      return this.view.id === 'day' && this.hasSplits && this.options.stickySplitLabels && !this.options.minSplitWidth
    },
    // Drag & drop module.
    dnd () {
      return this.modules.dnd
    }
  }
}
</script>

<style lang="scss">
.vuecal {
  &__header button {
    outline: none;
    font-family: inherit;
  }

  &__menu {
    padding: 0;
    margin: 0;
    list-style-type: none;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.02);
  }

  &__view-btn {
    background: none;
    border: none;
    padding: 0.3em 1em;
    height: 2.2em;
    font-size: 1.3em;
    border-bottom: 0 solid currentColor;
    cursor: pointer;
    color: inherit;
    box-sizing: border-box;
    transition: 0.2s;

    &--active {
      border-bottom-width: 2px;
      background: rgba(255, 255, 255, 0.15);
    }
  }

  &__title-bar {
    background-color: rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    text-align: center;
    justify-content: space-between;
    font-size: 1.4em;
    line-height: 1.3;
    min-height: 2em;

    .vuecal--xsmall & {font-size: 1.3em;}
  }

  &__title {
    position: relative;
    justify-content: center;

    button {
      cursor: pointer;
      background: none;
      border: none;
    }
    button.slide-fade--left-leave-active,
    button.slide-fade--right-leave-active {width: 100%;}
  }

  &__today-btn {
    position: relative;
    align-items: center;
    display: flex;
    font-size: 0.8em;
    background: none;
    border: none;

    span.default {
      font-size: 0.8em;
      padding: 3px 6px;
      text-transform: uppercase;
      cursor: pointer;
    }
  }

  &__arrow {
    cursor: pointer;
    position: relative;
    z-index: 1;
    background: none;
    border: none;
    // Non-breakable spaces are added around the default angle icon to make it larger.
    white-space: nowrap;

    &--prev {margin-left: 0.6em;}
    &--next {margin-right: 0.6em;}

    i.angle {
      display: inline-block;
      border: solid currentColor;
      border-width: 0 2px 2px 0;
      padding: 0.25em;
      transform: rotate(-45deg);
    }

    &--prev i.angle {border-width: 2px 0 0 2px;}
  }

  // Pulse header buttons when dragging over with an event.
  &__arrow--highlighted,
  &__today-btn--highlighted,
  &__view-btn--highlighted {
    position: relative;
    background-color: rgba(0, 0, 0, 0.04);

    // Prevent event bubbling on hover and move.
    * {pointer-events: none;}

    &:before, &:after {
      content: '';
      background-color: inherit;
      animation: sonar 0.8s infinite ease-out;
      position: absolute;
      top: 50%;
      left: 50%;
      pointer-events: none;
    }

    &:before {
      width: 3em;
      height: 3em;
      border-radius: 3em;
      margin-top: -1.5em;
      margin-left: -1.5em;
    }

    &:after {
      animation-duration: 1.5s;
      animation-delay: 0.1s;
      width: 2.6em;
      height: 2.6em;
      border-radius: 2.6em;
      margin-top: -1.3em;
      margin-left: -1.3em;
    }
  }
}

// Animations.
//==================================//
@keyframes sonar {
  0%, 20% {opacity: 1;}
  100% {
    transform: scale(2.5);
    opacity: 0;
  }
}

// Media queries.
//==================================//
@media screen and (max-width: 450px) {
  .vuecal__title {font-size: 0.9em;}
  .vuecal__view-btn {padding-left: 0.6em;padding-right: 0.6em;}
}

@media screen and (max-width: 350px) {
  .vuecal__view-btn {font-size: 1.1em;}
}
</style>
