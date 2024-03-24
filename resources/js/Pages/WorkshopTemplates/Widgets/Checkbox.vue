<template>
    <!-- 
        the component below renders the remove widget button in the Toolbar component
     -->
    <RemoveWidgetButton :type="widget.type" :id="id" @removeWidget="$emit('removeWidget', true)" />
    <!-- 
        inlineCSS is an object containing different style properties.
        styleClasses an array of predefined tailwind classes 
     -->
    <div
        @click.stop="selectWidget"
        :class="[
            'flex',
            'm-1 self-start',
            selectedWidget?.id == id && selectedWidgetType == widget.type
                ? 'border-2 border-blue-500'
                : '',
            ...styleClasses,
        ]"
        :style="{ ...inlineCSS }"
    >
        <input
            v-model="widget.configuration.value"
            :name="widget.configuration.name"
            class="mr-1"
            type="checkbox"
        />
        <label>{{ widget.configuration.label }}</label>
    </div>
</template>

<script>
import mainMixin from "../Mixins/mainMixin";
import widgetMixin from "../Mixins/widgetMixin";

export default {
    name: "Checkbox",
    mixins: [mainMixin, widgetMixin],
};
</script>

<style scoped></style>
