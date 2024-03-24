import draggable from "vuedraggable";
import { v4 as uuidv4 } from "uuid";
import { mapGetters } from "vuex";

export default {
    components: {
        draggable,
    },
    computed: {
        ...mapGetters("workshopTemplates", ["selectedWidget", "showPreview", "selectedWidgetType", "inputConfigurations"]),
        // returns true if the selected widget type is among the values from the array
        showRemoveWidgetButton() {
            return [
                "heading",
                "paragraph",
                "checkbox",
                "number-input",
                "radio-button",
                "table",
                "text-input",
                "select",
                "wysiwyg",
            ].includes(this.selectedWidget?.type);
        },
    },
    methods: {
        uuid() {
            return uuidv4();
        },
    },
    props: {
        // a unique identifier for the component
        id: {
            type: [String, Number], // can be both string and number
            default: uuidv4(),
        },
    },
};
