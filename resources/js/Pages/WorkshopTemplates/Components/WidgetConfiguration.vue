<template>
    <!-- 
        ------------------------------------------------------------------------------------------------------------------------
                                                                Gear icon
        ------------------------------------------------------------------------------------------------------------------------
        This section holds the code that displays the gear icon which when clicked toggles the configuration card for the selected widget
     -->
    <div
        :style="`right: ${
            configurationToggle ? '25' : '0'
        }%; z-index: 999; height: auto;`"
        class="widget-config-btn"
        @click="configurationToggle = !configurationToggle"
    >
        <CustomIcon name="settingRightBarIcon" />
    </div>
    <!-- 
        ------------------------------------------------------------------------------------------------------------------------
                                                                end section
        ------------------------------------------------------------------------------------------------------------------------
     -->

    <!-- 
        ------------------------------------------------------------------------------------------------------------------------
                                                            Configuration card 
        ------------------------------------------------------------------------------------------------------------------------
        The configuration card body toggled when the gear icon is clicked
      -->
    <div v-if="configurationToggle" class="rightbar-card">
        <!-- 
            the name of the selectedWidget type
        -->
        <div class="divider"></div>
        <div class="rightbar-card-title">
            <h2>{{ selectedWidget?.type }}</h2>
        </div>
        <div class="divider"></div>
        <div class="rightbar-body">
            <!-- 
            shows different configuration options for differnet widget types.
            'reloadConfig' is used to reload the latest configuration when the selectedWidget is changed
         -->
            <div v-if="selectedWidget && reloadConfig" class="px-2 pt-2 pb-40">
                <div v-if="selectedWidget?.type === 'card'">
                    <div class="form-group">
                        <TextInput
                            @change="labelInputChanged($event, 'card')"
                            :modelValue="selectedWidget.configuration.heading"
                            type="text"
                            :label="$t('Heading')"
                        />
                    </div>
                </div>

                <div
                    class="config-card"
                    v-if="
                        !['row', 'column', 'card'].includes(
                            selectedWidget?.type
                        )
                    "
                >
                    <div class="form-group mb-3">
                        <TextInput
                            @change="labelInputChanged($event, 'widget')"
                            type="text"
                            :modelValue="selectedWidget.configuration.label"
                            :label="$t('Label')"
                        />
                    </div>
                    <div
                        class="form-group mb-3"
                        v-if="
                            selectedWidget?.type === 'text-input' ||
                            selectedWidget?.type === 'number-input'
                        "
                    >
                        <TextInput
                            v-model="selectedWidget.configuration.placeholder"
                            type="text"
                            :label="$t('Placeholder')"
                        />
                    </div>
                    <div class="" v-if="showForAllInputWidgets">
                        <div class="form-group mb-3">
                            <TextInput
                                v-model="selectedWidget.configuration.name"
                                type="text"
                                :label="$t('Name')"
                            />
                        </div>
                        <div class="form-group mb-3">
                            <!-- variable in the config file which will be replaced with the value of the widget -->
                            <label class="input-label" :for="id"
                                ><span v-if="required" style="color: red"
                                    >*</span
                                >&nbsp;{{ $t("Config Variable Name") }}:</label
                            >
                            <input
                                @change="setConfigVariableName"
                                @input="checkConfigVariableName"
                                :value="
                                    selectedWidget.configuration
                                        .configVariableName
                                "
                                class="form-control"
                                type="text"
                            />
                            <div
                                v-if="errors.configVariableName"
                                class="form-error"
                            >
                                {{ $t(errors.configVariableName) ?? "" }}
                            </div>
                        </div>
                        <div class="form-group">
                            <!-- readonly fields which will display the value as a visual aid for the user, indicating the value which will
                    be replaced in the config file for the above added config variable name
                -->
                            <TextInput
                                v-model="selectedWidget.configuration.value"
                                type="text"
                                :label="$t('Value')"
                                :isReadonly="true"
                            />
                        </div>
                    </div>
                </div>

                <!-- 
                when the selected widget is of 'select', we must give the user the ability to add and remove options
                of the select input, the section below enables us to do that
             -->
                <div
                    class="config-card mb-2 relative mt-3"
                    v-if="selectedWidget?.type === 'select'"
                >
                    <font-awesome-icon
                        @click="removeOption(index)"
                        class="cursor-pointer text-black absolute right-2 text-white"
                        icon="fa-solid fa-xmark"
                    />
                    <div class="mb-4">
                        <h3 class="card-title">{{ $t("Options") }}</h3>
                    </div>
                    <div
                        class="grid items-center grid-cols-2 gap-6 mt-4"
                        v-for="(option, index) in selectedWidget.configuration
                            .options"
                        :key="'option-' + index"
                    >
                        <div class="w-full">
                            <div class="form-group">
                                <TextInput
                                    v-model="option.name"
                                    type="text"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <TextInput
                                    v-model="option.value"
                                    type="text"
                                    :label="$t('Value')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <button v-if="selectedWidget?.type === 'select'"
                    @click="addOption"
                    class="secondary-btn btn-border w-full mb-3"
                >
                    {{ $t("Add Option") }}
                </button>
                <!-- 
                when the selected widget is of type 'table', we must give the user the ability to add and remove fields that are part of
                that table, the section below enables us to do that
             -->
                <div
                    class="flex flex-col mb-2"
                    v-if="selectedWidget?.type === 'table'"
                >
                    <label>{{ $t("Fields") }}:</label>
                    <div
                        v-for="(field, index) in selectedWidget.configuration
                            .fields"
                        :key="'field-' + index"
                        class="flex gap-1 p-2 mb-2 card rounded shadow relative"
                    >
                        <font-awesome-icon
                            @click="removeField(index)"
                            class="cursor-pointer text-black absolute right-2"
                            icon="fa-solid fa-xmark"
                        />
                        <TextInput
                            v-model="field.displayName"
                            type="text"
                            :label="$t('Display Name')"
                            class="pb-2 w-1/2"
                        />
                        <TextInput
                            v-model="field.variableName"
                            type="text"
                            :label="$t('Variable Name')"
                            class="pb-2 w-1/2"
                        />
                    </div>
                    <button
                        @click="addField"
                        class="py-2 px-3 mt-2 rounded docsHeroColorBlue"
                    >
                        {{ $t("Add Field") }}
                    </button>
                </div>

                <!-- 
                configuration specific to checkbox
             -->
                <div class="mb-2" v-if="selectedWidget?.type === 'checkbox'">
                    <TextInput
                        v-model="selectedWidget.configuration.inputGroupName"
                        type="text"
                        :label="$t('Input Group Name')"
                        class="pb-2 w-full"
                    />
                </div>

                <!-- 
                contains all the configurations for a widget related to styling
            -->
                <StylesConfiguration />

                <!-- 
                column specific configurations
             -->
                <div class="form-group mb-3" v-if="selectedWidget?.type === 'column'">
                    <TextInput
                        :isReadonly="
                            selectedWidget.configuration.equallySizedColumns
                        "
                        type="number"
                        v-model="selectedWidget.configuration.inlineCSS.width"
                        min="50"
                        :label="$t('Width')"
                    />
                </div>

                <!-- 
                row specific configurations
             -->
                <div class="checkbox-group" v-if="selectedWidget?.type === 'row'">
                    <div class="flex justify-between">
                       
                        <input
                            @input="sizeColumnsEqually(selectedWidget)"
                            :checked="
                                selectedWidget.configuration.equallySizedColumns
                            "
                            v-model="
                                selectedWidget.configuration.equallySizedColumns
                            "
                            type="checkbox"
                            id="equal-columns"
                            class="checkbox-input"
                        />
                        <label for="equal-columns" class="checkbox-label text-white">{{
                            $t("Equal Columns")
                        }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 
        ------------------------------------------------------------------------------------------------------------------------
                                                                end section
        ------------------------------------------------------------------------------------------------------------------------
     -->
</template>

<script>
import { mapGetters } from "vuex";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import StylesConfiguration from "./StylesConfiguration.vue";
import rowConfigMixin from "../Mixins/rowConfigMixin";

export default {
    name: "WidgetConfiguration",
    mixins: [rowConfigMixin],
    computed: {
        ...mapGetters("workshopTemplates", [
            "selectedWidget",
            "inputConfigurations",
        ]),
        ...mapGetters(["errors"]),
        showForAllInputWidgets() {
            return [
                "text-input",
                "number-input",
                "checkbox",
                "radio-button",
                "select",
            ].includes(this.selectedWidget?.type);
        },
    },
    data() {
        return {
            configurationToggle: false, // toggles the configuration card
            reloadConfig: false, // reload the config after selectedWidget is changed
        };
    },
    watch: {
        // when the selectedWidget is changed, reload (hide and then show after a little delay)
        // the widget configuration to get the configuration related to the selectedWidget
        async selectedWidget() {
            this.reloadConfig = false;
            await this.$nextTick();
            this.reloadConfig = true;
        },
    },
    methods: {
        /**
         * flattens the 'inputConfigurations' object and returns an object containing the inner most object key/value pairs
         */
        flattenObject() {
            let objectArray = Object.values(
                this.inputConfigurations["widgets"]
            );
            let flattenedObject = {};
            objectArray.forEach((obj) => {
                flattenedObject = { ...flattenedObject, ...obj };
            });
            return flattenedObject;
        },
        /**
         * checks if the configVariableName being entered already exists in the inputConfigurations state
         * if it exists then sets an error and warns the user
         * else it resets the errors state object
         * @param {event} input event
         */
        checkConfigVariableName(event) {
            this.selectedWidget.configuration.configVariableName =
                event.target.value;
            if (this.flattenObject()[event.target.value] !== undefined) {
                this.$store.commit("errors", {
                    configVariableName:
                        "This variable already exists, and will not be saved. Try setting another name",
                });
            } else {
                this.$store.commit("errors", {});
            }
        },
        /**
         * sets the configVariableName of the selectedWidget to the value entered by the user from the event
         * if an error exists for configVariableName in the errors state, then resets the configVariableName value. The error object is set in 'checkConfigVariableName' method
         * else it sets configVariableName to the value entered by the user
         * @param {event} change event
         */
        setConfigVariableName(event) {
            if (this.errors.configVariableName) {
                this.selectedWidget.configuration.configVariableName = "";
            } else {
                this.selectedWidget.configuration.configVariableName =
                    event.target.value;
            }
        },
        /**
         * Updated the value of the label or the heading of the selectedWidget on change event
         * if the value is left empty then it adds a default value to the label or the heading (for widgets other than text-input and number-input)
         * @param {event} the change event
         * @param {type} type of widget, possible values are 'widget' and 'card'
         */
        labelInputChanged(event, type) {
            if (
                !event.target.value &&
                !["text-input", "number-input", "table"].includes(
                    this.selectedWidget.type
                )
            ) {
                this.selectedWidget["configuration"][
                    type === "card" ? "heading" : "label"
                ] = this.selectedWidget.type;
                return;
            }
            this.selectedWidget["configuration"][
                type === "card" ? "heading" : "label"
            ] = event.target.value;
        },
        /**
         * adds a default option to the options array of the selected widget
         */
        addOption() {
            const option = {
                name: "",
                value: "",
            };
            this.selectedWidget.configuration.options = [
                ...this.selectedWidget.configuration.options,
                { ...option },
            ];
        },
        /**
         * removes the option in the given index from the options array of the selected widget
         * @param {index} index of the option to be removed
         */
        removeOption(index) {
            this.selectedWidget.configuration.options.splice(index, 1);
        },
        /**
         * adds a default field to the fields array of the selected widget
         */
        addField() {
            const field = {
                displayName: "",
                variableName: "",
            };
            this.selectedWidget.configuration.fields = [
                ...this.selectedWidget.configuration.fields,
                { ...field },
            ];
        },
        /**
         * removes the field in the given index from the fields array of the selected widget
         * @param {index} index of the field to be removed
         */
        removeField(index) {
            this.selectedWidget.configuration.fields.splice(index, 1);
        },
    },
    components: {
        TextInput,
        SelectInput,
        StylesConfiguration,
    },
};
</script>

<style scoped>
.widget-configuration {
    width: 20%;
    right: 1%;
    top: 15%;
    z-index: 1;
    max-height: 100vh;
    min-height: 100vh;
    overflow: auto;
}
.toggled-card::-webkit-scrollbar {
    width: 4px;
    height: 4px;
}

.toggled-card::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
}

.toggled-card::-webkit-scrollbar-thumb {
    background-color: darkgrey;
    outline: 1px solid slategrey;
    border-radius: 5px;
}

.card-header {
    display: flex;
    justify-content: center;
    align-items: center;
    text-transform: capitalize;
    background-color: rgb(242, 242, 242);
    font-weight: bold;
}
</style>
