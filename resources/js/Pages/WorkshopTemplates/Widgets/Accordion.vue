<template>
    <div
        :class="[
            'workshop-card',
            // highlight the card with a border when the id of the selectedWidget matches the id of the component
            selectedWidget?.id == id && selectedWidgetType == 'card' ? '' : '',
        ]"
    >
        <div class="workshop-card-header">
            <h3 class="card-title">{{ $t(card.configuration.heading) }}</h3>
            <font-awesome-icon
                v-if="!showPreview"
                @click.stop="$emit('removeCard', true)"
                class="cursor-pointer cross text-black self-center"
                icon="fa-solid fa-xmark"
            />
        </div>
        <div class="workshop-card-body">
            <draggable
                v-model="card.rows"
                :move="() => !showPreview"
                group="rows"
                item-key="id"
            >
                <!-- The row component is the component that will hold our columns which in turn hold all the widgets -->
                <template #item="{ element: row, index }">
                    <Row
                        @removeRow="removeRow(index)"
                        :row="row"
                        :id="row.id"
                    />
                </template>
            </draggable>
            <!-- 
                    This section displays a add row button that adds a row to the card 
                    if the showPreview state is set to true then hide this section
                -->
            <div v-if="!showPreview" class="flex justify-center">
                <div
                    @click="addRow"
                    class="secondary-btn cursor-pointer"
                >
                    <span class="flex"
                        ><font-awesome-icon
                            class="cursor-pointer cross text-black self-center mr-2"
                            icon="fa-solid fa-plus"
                        />
                        <h1>Add Row</h1></span
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import mainMixin from "../Mixins/mainMixin";

import Row from "./Row.vue";

export default {
    name: "Accordion",
    emits: ["removeCard"],
    mixins: [mainMixin],
    components: {
        Row,
    },
    props: {
        // the card object that contains all the properties related to the card
        card: {
            type: Object,
            default: () => ({
                rows: [],
            }),
        },
    },
    methods: {
        /**
         * adds a row in the accordion/card body
         */
        async addRow() {
            try {
                const row = {
                    id: this.uuid(), //unique identifier
                    type: "row", // widget type
                    workshopTemplatesCardId: this.card.id,
                    columns: [
                        // add a default column whenever a row is created, because a row always has atleast one column
                        {
                            id: this.uuid(),
                            type: "column",
                            widgets: [],
                            // Configuration for the column
                            configuration: {
                                // styles with non concrete/fixed values must be applied using inline CSS styling
                                inlineCSS: {
                                    paddingLeft: 0,
                                    paddingRight: 0,
                                    paddingTop: 0,
                                    paddingBottom: 0,
                                    marginLeft: 0,
                                    marginRight: 0,
                                    marginTop: 0,
                                    marginBottom: 0,
                                    width: 300,
                                },
                                equallySizedColumns: false,
                            },
                        },
                    ], // contains the column components
                    // Configuration for the row
                    configuration: {
                        // styles with non concrete/fixed values must be applied using inline CSS styling
                        inlineCSS: {
                            paddingLeft: 0,
                            paddingRight: 0,
                            paddingTop: 0,
                            paddingBottom: 0,
                            marginLeft: 0,
                            marginRight: 0,
                            marginTop: 0,
                            marginBottom: 0,
                        },
                        equallySizedColumns: false, // makes all the columns in the row equal sized when set to true
                    },
                };
                // run the create row API
                let response = await this.$store.dispatch(
                    "workshopTemplateRows/create",
                    row
                );
                row.id = response?.data?.id; // set the row id to the id from the response
                row.columns[0]["workshopTemplatesRowId"] = row.id; // set the workshopTemplatesRowId of the column to the one received from the response
                // run the create column API
                response = await this.$store.dispatch(
                    "workshopTemplateColumns/create",
                    row.columns[0]
                );
                row.columns[0]["id"] = response?.data?.id; // set the column id to the id from the response
                this.card.rows = [...this.card.rows, { ...row }];
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * removes the row from the rows array
         * @param {index} index of the row to be removed
         */
        async removeRow(index) {
            try {
                // call the row delete API
                await this.$store.dispatch(
                    "workshopTemplateRows/destroy",
                    this.card.rows[index]?.id
                );
                const removedRow = this.card.rows.splice(index, 1); //splice from the array on the index
                // remove the sliced row from the selectedWidget if the deleted row was the selected one
                if (removedRow?.[0]?.id == this.selectedWidget?.id) {
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
