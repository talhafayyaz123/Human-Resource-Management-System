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
                ? 'widget-toolbar'
                : '',
            ...styleClasses,
        ]"
    >
        <QuillTextEditor
            v-if="!showPreview"
            class="pb-8 pr-6"
            :content="widget.configuration.value"
            @delta="widget.configuration.value = $event"
        />
        <span v-else v-html="htmlFromText(widget.configuration.value)"></span>
    </div>
</template>

<script>
import mainMixin from "../Mixins/mainMixin";
import widgetMixin from "../Mixins/widgetMixin";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import Quill from "quill";

export default {
    name: "Wysiwyg",
    mixins: [mainMixin, widgetMixin],
    components: {
        QuillTextEditor,
    },
    methods: {
        /**
         * Converts quill delta into html string, which is used with v-html to display quill editor text
         * @param {text} quill delta to be converted to html
         * @returns {string} html string
         */
        htmlFromText(text) {
            try {
                let article = document.createElement("article");
                let quill = new Quill(article, { readOny: true });
                quill.setContents(JSON.parse(text));
                return quill.root.innerHTML;
            } catch (err) {
                return "";
            }
        },
    },
};
</script>

<style scoped>
.widget-toolbar {
    border-top: 2px solid rgba(41, 87, 164, 0.8);
    border-left: 2px solid rgba(41, 87, 164, 0.8);
    border-bottom: 2px solid rgba(41, 87, 164, 0.8);
    border-right: 25px solid rgba(41, 87, 164, 0.8);
}
</style>
