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
        :style="{ ...inlineCSS }"
        :class="[
            'm-1 self-start',
            selectedWidget?.id == id && selectedWidgetType === widget.type
                ? 'border-2 border-blue-500'
                : '',
            'flex flex-col self-start',
            ...styleClasses,
        ]"
    >
        <label>{{ widget.configuration.label }}</label>
        <select
            class="form-input lg:w-1/3 md:w-full"
            :name="widget.configuration.name"
            v-model="widget.configuration.value"
        >
            <option
                v-for="(option, index) in widget.configuration.options"
                :key="'option-' + index"
                :value="option.value"
            >
                {{ option.name }}
            </option>
        </select>
    </div>
</template>

<script>
import mainMixin from "../Mixins/mainMixin";
import widgetMixin from "../Mixins/widgetMixin";

export default {
    name: "Select",
    mixins: [mainMixin, widgetMixin],
};
</script>

<style scoped></style>
