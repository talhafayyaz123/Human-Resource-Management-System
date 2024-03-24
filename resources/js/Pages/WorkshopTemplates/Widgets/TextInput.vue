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
            'form-group',
            'flex flex-col self-start',
            selectedWidget?.id == id && selectedWidgetType === widget.type
                ? 'border-2 border-blue-500'
                : '',
            ...styleClasses,
        ]"
    >
        <label class="input-label">{{ widget.configuration.label }}</label>
        <input
            class="form-control  w-full"
            :placeholder="widget.configuration.placeholder"
            :name="widget.configuration.name"
            v-model="widget.configuration.value"
            type="text"
        />
    </div>
</template>

<script>
import mainMixin from "../Mixins/mainMixin";
import widgetMixin from "../Mixins/widgetMixin";

export default {
    name: "TextInput",
    emits: ["removeWidget"],
    mixins: [mainMixin, widgetMixin],
};
</script>

<style scoped></style>
