<template>
    <div class="toolbar card p-5">
        <!-- 
            if the showPreview state is set to true then prevent widget icon drag
         -->
        <div
            v-for="widget in widgets"
            :key="'widget-' + widget.id"
            @dragstart="onDragstart($event, widget.type)"
            @dragend="onDragend"
            :draggable="!showPreview"
            class="secondary-btn"
        >
            <font-awesome-icon
                class="cursor-pointer cross text-black self-center mr-2 widget-icon"
                :icon="['fa-solid', widget.icon]"
            />
            <p>{{ $t(widget.title) }}</p>
        </div>
    </div>
    <!-- the RemoveWidgetButton component is teleported in the div below -->
    <div id="remove-widget-button"></div>
    <!-- 
        the checkbox below toggles the showPreview state in workshopTemplates
        when showPreview is set to true, then we prevent the user from performing any action in the template editor
        showPreview allows the user to see the created layout as it would appear on the view page for workshop templates
     -->
    <div v-if="$route.name !== 'WorkshopTemplatesShowRecord'" class="checkbox-group mt-4">
        <input
            class="checkbox-input"
            @input="toggleShowPreview"
            type="checkbox"
            name="show-preview"
            id="show-preview"
            :checked="showPreview"
        />
        <label for="show-preview" class="checkbox-label">Show Preview</label>
    </div>
</template>

<script>
import mainMixin from "../Mixins/mainMixin";

export default {
    name: "Toolbar",
    mixins: [mainMixin],
    data() {
        return {
            isDragging: false,
            widgets: [
                {
                    id: this.uuid(),
                    type: "heading",
                    icon: "fa-heading",
                    title: "Heading",
                },
                {
                    id: this.uuid(),
                    type: "paragraph",
                    icon: "fa-paragraph",
                    title: "Paragraph",
                },
                {
                    id: this.uuid(),
                    type: "text-input",
                    icon: "fa-keyboard",
                    title: "Text Input",
                },
                {
                    id: this.uuid(),
                    type: "number-input",
                    icon: "fa-1",
                    title: "Number Input",
                },
                {
                    id: this.uuid(),
                    type: "select",
                    icon: "fa-chevron-down",
                    title: "Select",
                },
                {
                    id: this.uuid(),
                    type: "checkbox",
                    icon: "fa-square-check",
                    title: "Checkbox",
                },
                {
                    id: this.uuid(),
                    type: "radio-button",
                    icon: "fa-circle-dot",
                    title: "Radio Button",
                },
                {
                    id: this.uuid(),
                    type: "table",
                    icon: "fa-table",
                    title: "Table",
                },
                {
                    id: this.uuid(),
                    type: "wysiwyg",
                    icon: "fa-newspaper",
                    title: "WYSIWYG",
                },
            ],
        };
    },
    methods: {
        /**
         * sets the type in the dataTransfer of the dragstart event
         * this type can be read on the drop event
         * @param {e} dragstart event
         * @param {type} widget type of the widget being dragged e.g heading, text-input etc
         */
        onDragstart(e, type) {
            // this will be added into the column component, it helps us track when the widget drag starts and ends
            this.$store.commit("workshopTemplates/isDragging", true); //set isDragging to true when the drag starts
            e.dataTransfer.setData("text/plain", type);
        },
        /**
         * sets the isDragging to false when the drag ends
         */
        onDragend() {
            this.$store.commit("workshopTemplates/isDragging", false);
        },
        /**
         * sets the showPreview state based on if the input is checked or not
         * sets thre selectedWidget state to null
         * @param {event} the input event
         */
        toggleShowPreview(event) {
            this.$store.commit(
                "workshopTemplates/showPreview",
                event.target.checked
            );
            this.$store.commit("workshopTemplates/selectedWidget", null);
            this.$store.commit("workshopTemplates/selectedWidgetType", "");
        },
    },
};
</script>

<style scoped>
.toolbar {
    min-height: 70px;
    display: grid;
    gap: 0.5rem;
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    padding: 1rem;
}
.widget {
    background-color: rgba(41, 87, 164, 1);
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    user-select: none;
}
.widget-icon {
    color: white;
}
</style>
