import RemoveWidgetButton from "../Components/RemoveWidgetButton.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["removeWidget"],
    mounted() {
        // when the 'resetConfigurationInputs' is emitted from the save method in Edit and Create page
        // then reset the value, records, options, fields in the configuration of the widget
        this.$emitter.on("resetConfigurationInputs", () => {
            this.widget.configuration.value = "";
            this.widget.configuration.records = [];
            // remove all the options that do not have name and value set
            this.widget.configuration.options =
                this.widget.configuration.options.filter(
                    (option) => option.name && option.value
                );
            // remove all the fields that do not have variableName and displayName set
            this.widget.configuration.fields =
                this.widget.configuration.fields.filter(
                    (option) => option.variableName && option.displayName
                );
        });
    },
    props: {
        widget: {
            type: Object,
            default: () => ({}),
        },
    },
    methods: {
        /**
         * sets the 'selectedWidget' state to the widget from which the 'selectWidget' method is being called
         */
        selectWidget() {
            // if the showPreview state is set to true then prevent widget selection
            if (!this.showPreview) {
                this.$store.commit(
                    "workshopTemplates/selectedWidget",
                    this.widget
                );
                this.$store.commit(
                    "workshopTemplates/selectedWidgetType",
                    this.widget.type
                );
            }
        },
    },
    components: {
        RemoveWidgetButton,
    },
    computed: {
        ...mapGetters("workshopTemplates", ["showPreview"]),
        // returns an array of class names retrieved from 'styleClasses' object of the selected widget
        styleClasses() {
            return Object.values(this.widget.configuration.styleClasses);
        },
        // returns an object of different CSS styles with style name as the key and the user provided value as the value for that style
        inlineCSS() {
            const css = {};
            // traverse over the inlineCSS object and add the styles to css object after transforming some styles
            for (let key in this.widget.configuration.inlineCSS) {
                // if the style property is a padding or margin then we must add a suffix of 'px' after the value
                if (key.includes("padding") || key.includes("margin")) {
                    css[key] = this.widget.configuration.inlineCSS[key] + "px";
                    continue;
                }
                css[key] = this.widget.configuration.inlineCSS[key];
            }
            return css;
        },
    },
};
