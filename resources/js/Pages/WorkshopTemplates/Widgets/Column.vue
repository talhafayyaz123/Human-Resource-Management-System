<template>
    <div
        @click.stop="selectColumn"
        @dragenter="onDragenter"
        @dragover="onDragover"
        @drop="onDrop"
        @mouseenter="addToolbar"
        @mouseleave="removeToolbar"
        :class="[
            'column p-3',
            'relative',
            showPreview ? '' : ' column-bg-color',
            selectedWidget?.id == id && selectedWidgetType === 'column'
                ? 'border-2 border-blue-500'
                : '',
        ]"
        :style="{
            ...inlineCSS,
            minHeight: column?.widgets?.length || showPreview ? '' : '70px', //If there are no widgets in a column, add a min-height to the column to make it visible
        }"
    >
        <!-- 
            for column reordering to working in vue-draggable component in case where the widget spans the entire column,
            we need to specify a handle class which when clicked and dragged will allow us to drag and drop our column widget.
            For that to work the handle class must be inside the 'item' slot of the vue-draggable component which is inside the Row component
            But for styling purposes we must add the toolbar containing that handle class inside our column component. To achieve this
            we make use of this toolbar slot
         -->
        <slot
            name="toolbar"
            :showToolbar="showToolbar"
            :selectColumn="selectColumn"
        ></slot>
        <draggable
            class="flex flex-col p-2 py-5"
            v-model="column.widgets"
            group="widgets"
            :move="() => !showPreview"
            :handle="handle"
            item-key="id"
        >
            <!-- the widget is dynamically loaded based on the type -->
            <template #item="{ element: widget, index }">
                <span
                    @mouseenter="addHandleIfWysiwyg(widget)"
                    @mouseleave="handle = ''"
                >
                    <!-- 
                        only add the toolbar hanle icon if the widget is of type 'wysiwyg'
                     -->
                    <font-awesome-icon
                        v-if="widget.type === 'wysiwyg' && handle"
                        class="cursor-pointer wysiwyg-handler absolute"
                        icon="fa-solid fa-up-down-left-right"
                    />
                    <component
                        @removeWidget="removeWidget(index)"
                        :is="widget.type"
                        :widget="widget"
                        :id="widget.id"
                        :key="index"
                    ></component>
                </span>
            </template>
        </draggable>
    </div>
</template>

<script>
import mainMixin from "../Mixins/mainMixin";
import toolbarMixin from "../Mixins/toolbarMixin";
import Heading from "./Heading.vue";
import Paragraph from "./Paragraph.vue";
import TextInput from "./TextInput.vue";
import NumberInput from "./NumberInput.vue";
import Checkbox from "./Checkbox.vue";
import RadioButton from "./RadioButton.vue";
import Table from "./Table.vue";
import Select from "./Select.vue";
import Wysiwyg from "./Wysiwyg.vue";
import { mapGetters } from "vuex";

export default {
    name: "Column",
    mixins: [mainMixin, toolbarMixin],
    computed: {
        // set by the toolbar component, is true when the widget icon drag starts and false when the drag ends
        ...mapGetters("workshopTemplates", ["isDragging"]),
        // returns an object of different CSS styles with style name as the key and the user provided value as the value for that style
        inlineCSS() {
            const css = {};
            // traverse over the inlineCSS object and add the styles to css object after transforming some styles
            for (let key in this.column.configuration.inlineCSS) {
                // if the style property is a padding, margin or width then we must add a suffix of 'px' after the value
                if (
                    key.includes("padding") ||
                    key.includes("margin") ||
                    key.includes("width")
                ) {
                    css[key] = this.column.configuration.inlineCSS[key] + "px";
                    continue;
                }
                css[key] = this.column.configuration.inlineCSS[key];
            }
            return css;
        },
    },
    data() {
        return {
            handle: "",
        };
    },
    components: {
        Heading,
        Paragraph,
        TextInput,
        NumberInput,
        Checkbox,
        RadioButton,
        Table,
        Select,
        Wysiwyg,
    },
    props: {
        column: {
            type: Object,
            default: () => ({
                widgets: [],
            }),
        },
    },
    watch: {
        // when the widgets change, then call the 'setInputConfigurations' method
        "column.widgets": {
            handler: function (widgets) {
                this.setInputConfigurations(widgets);
            },
            deep: true,
        },
    },
    mounted() {
        // on mount call the 'setInputConfigurations' method
        this.setInputConfigurations(this.column.widgets);
    },
    methods: {
        /**
         * sets the 'inputConfigurations' state by traversing over the widgets and setting a key/value pair for a widget
         * @param {widgets} widgets of the column
         */
        setInputConfigurations(widgets) {
            const inputConfigurations = { ...this.inputConfigurations };
            widgets.forEach((widget) => {
                // if the widget type is a checkbox and it has inputGroupName set then add a key/value pair based on the group name
                // and add the widget configVariableName and value as the key/value pair for that group name key

                // check if the 'inputConfigurations' contains this widget id
                if (!inputConfigurations["widgets"][widget.id]) {
                    inputConfigurations["widgets"][widget.id] = {};
                }
                if (
                    widget.type === "checkbox" &&
                    widget.configuration.inputGroupName
                ) {
                    // if group is not present then add the group with empty object string as value
                    // the object is stringified because the process-template API used to parse the layout, accepts the stringified
                    // objects as payload even when they are nested
                    if (
                        !inputConfigurations["groups"][
                            widget.configuration.inputGroupName
                        ]
                    )
                        inputConfigurations["groups"][
                            widget.configuration.inputGroupName
                        ] = "{}";
                    inputConfigurations["groups"][
                        widget.configuration.inputGroupName
                    ] = JSON.parse(
                        inputConfigurations["groups"][
                            widget.configuration.inputGroupName
                        ]
                    );
                    // set the group with widget configVariableName and value as the key/value pair
                    inputConfigurations["groups"][
                        widget.configuration.inputGroupName
                    ][widget.configuration.configVariableName] =
                        widget.configuration.value;
                    inputConfigurations["groups"][
                        widget.configuration.inputGroupName
                    ] = JSON.stringify(
                        inputConfigurations["groups"][
                            widget.configuration.inputGroupName
                        ]
                    );
                } else {
                    inputConfigurations["widgets"][widget.id] = {}; //reset the object because there can only be one key/value pair
                    inputConfigurations["widgets"][widget.id][
                        widget.configuration.configVariableName
                    ] = widget.configuration.value;
                }
            });
            this.$store.commit("workshopTemplates/inputConfigurations", {
                ...inputConfigurations,
            });
        },
        /**
         * adds a handler class to draggable component if the widget we are hovering over is of type 'wysiwyg'
         * makes the handle an empty string if it's some other type of widget
         * @param {widget} widget object passed through the mouseenter event
         */
        addHandleIfWysiwyg(widget) {
            if (widget.type === "wysiwyg") {
                this.handle = ".wysiwyg-handler";
                return;
            }
            this.handle = "";
        },
        /**
         * adds a column to the row
         */
        /**
         * sets the selectedWidget to the selected column
         * prevent column selection if showPreview is true
         */
        selectColumn() {
            if (!this.showPreview)
                this.$store.commit(
                    "workshopTemplates/selectedWidget",
                    this.column
                );
            this.$store.commit(
                "workshopTemplates/selectedWidgetType",
                "column"
            );
        },
        /**
         * calls the preventDefault method on the passed event, this allows us to make the column a drop target
         * because the default behavior of any HTML element is to not allow drop
         * @param {e} dragenter event
         */
        onDragenter(e) {
            e.preventDefault();
        },
        /**
         * calls the preventDefault method on the passed event, this allows us to make the column a drop target
         * because the default behavior of any HTML element is to not allow drop
         * @param {e} dragover event
         */
        onDragover(e) {
            e.preventDefault();
        },
        /**
         * handles the drop functionality when a widget is dropped
         * @param {e} drop event
         */
        async onDrop(e) {
            try {
                // only add the widget to the column if the widget icon is being dragged
                // this check is needed because when we reorder the widget inside the column, the drop event is triggered
                if (this.isDragging) {
                    // get the widget type from dataTransfer property on the drop event
                    // the type was set in the 'Toolbar' component on the dragstart event
                    const widgetType =
                        e.dataTransfer.getData(e.dataTransfer.types[0]) ?? "";
                    // create a basic widget and set the type as the type received from dataTransfer
                    const widget = {
                        id: this.uuid(),
                        workshopTemplatesColumnId: this.column.id,
                        type: widgetType,
                        // CSS styling for the widget
                        configuration: {
                            label: widgetType,
                            placeholder: "", //input placeholder used in text and number input
                            name: "",
                            value: "",
                            inputGroupName: "", //used with checkbox widget for grouping
                            options: [], // used when the selected widget type is a select input
                            configVariableName: "", // the token in the config file for which will be replaced by the value of this widget
                            fields: [], //contains the fields that are part of the table widget
                            records: [], //contains the row values of the table widget
                            // styles with predefined/fixed values can be applied with tailwind classes
                            styleClasses: {
                                fontSize:
                                    widgetType === "heading"
                                        ? "text-xl"
                                        : "text-base",
                                fontWeight: "font-normal",
                                italicize: "not-italic",
                                textDecoration: "no-underline",
                            },
                            // styles with non concrete/fixed values must be applied using inline CSS styling
                            inlineCSS: {
                                color: "black",
                                paddingLeft: 0,
                                paddingRight: 0,
                                paddingTop: 0,
                                paddingBottom: 0,
                                marginLeft: 0,
                                marginRight: 0,
                                marginTop: 0,
                                marginBottom: 0,
                            },
                        },
                    };
                    // trigger thre workshopTemplate widget create API
                    const response = await this.$store.dispatch(
                        "workshopTemplateWidgets/create",
                        widget
                    );
                    widget.id = response?.data?.id ?? widget.id; // sync the id with the id from the response
                    // add the created widget to the widgets array of the column
                    this.column.widgets = [
                        ...this.column.widgets,
                        { ...widget },
                    ];
                }
                e.preventDefault();
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * removes the widget from the widgets array
         * @param {index} index of the widget to be removed
         */
        async removeWidget(index) {
            try {
                // call the widget delete API
                await this.$store.dispatch(
                    "workshopTemplateWidgets/destroy",
                    this.column.widgets[index]?.id
                );
                const removedWidget = this.column.widgets.splice(index, 1); //splice from the array on the index
                // remove the sliced widget from the selectedWidget if the deleted widget was the selected one
                if (removedWidget?.[0]?.id == this.selectedWidget?.id) {
                    this.$store.commit(
                        "workshopTemplates/selectedWidget",
                        null
                    );
                    this.$store.commit(
                        "workshopTemplates/selectedWidgetType",
                        ""
                    );
                }
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

<style scoped>
.column {
    cursor: pointer;
    word-break: break-word;
}

.column-bg-color {
    background-color: rgb(235, 235, 235);
}

.wysiwyg-handler {
    top: 45%;
    right: 0.9%;
    color: white;
}
</style>
