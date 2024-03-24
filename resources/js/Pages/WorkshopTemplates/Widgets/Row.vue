<template>
    <div
        :class="[
            'relative',
            showPreview ? '' : 'py-2', //remove padding if showPreview is set to true
        ]"
    >
        <!-- 
            on mouseenter we show the toolbar around the row
            similarly we remove the toolbar on mouseleave
         -->
        <div
            :id="'row-' + id"
            @mouseenter="addToolbar"
            @mouseleave="removeToolbar"
            @click.stop="selectRow"
            :style="{ ...inlineCSS }"
            :class="[
                'row',
                showToolbar ? 'toolbar' : '',
                selectedWidget?.id == id && selectedWidgetType == 'row'
                    ? 'border-2 border-blue-500'
                    : '',
            ]"
        >
            <!-- cross icon used to remove a row, shows when showToolbar is set to true -->
            <font-awesome-icon
                v-if="showToolbar"
                @click.stop="$emit('removeRow', true)"
                class="cursor-pointer cross text-black self-center absolute remove-row-icon"
                icon="fa-solid fa-xmark"
            />
            <!-- plus icon used to add a column to the row, shows when showToolbar is set to true -->
            <font-awesome-icon
                v-if="showToolbar"
                @click="addColumn"
                class="cursor-pointer cross text-black self-center absolute add-column-icon"
                icon="fa-solid fa-plus"
            />
            <!-- hand pointer icon used to select the row, shows when showToolbar is set to true -->
            <font-awesome-icon
                v-if="showToolbar"
                @click="selectRow"
                class="cursor-pointer cross text-black self-center absolute select-row-icon"
                icon="fa-solid fa-hand-pointer"
            />
            <draggable
                :move="checkMove"
                class="flex gap-1"
                v-model="row.columns"
                handle=".column-handler"
                group="columns"
                item-key="id"
            >
                <!-- The column component is the component that will hold all the widgets -->
                <template #item="{ element: column, index }">
                    <Column :column="column" :id="column.id">
                        <!-- the toolbar slot contains the actions that can be performed on a column widget -->
                        <template #toolbar="{ showToolbar, selectColumn }">
                            <div
                                v-if="showToolbar"
                                class="column-toolbar"
                            >
                                <font-awesome-icon
                                    v-if="showToolbar"
                                    @click="removeColumn(index)"
                                    class="cursor-pointer cross text-black self-center mr-2"
                                    icon="fa-solid fa-xmark"
                                />
                                <font-awesome-icon
                                    @click="selectColumn"
                                    class="cursor-pointer cross text-black self-center column-handler"
                                    icon="fa-solid fa-hand-pointer"
                                />
                            </div>
                        </template>
                    </Column>
                </template>
            </draggable>
        </div>
    </div>
</template>

<script>
import mainMixin from "../Mixins/mainMixin";
import toolbarMixin from "../Mixins/toolbarMixin";
import rowConfigMixin from "../Mixins/rowConfigMixin";
import Column from "./Column.vue";

export default {
    name: "Row",
    mixins: [mainMixin, toolbarMixin, rowConfigMixin],
    emits: ["removeRow"],
    props: {
        row: {
            type: Object,
            default: () => ({
                columns: [],
            }),
        },
    },
    mounted() {
        // give equal width to columns if equallySizedColumns is set to true
        this.sizeColumnsEqually(this.row);
    },
    computed: {
        // returns an object of different CSS styles with style name as the key and the user provided value as the value for that style
        inlineCSS() {
            const css = {};
            // traverse over the inlineCSS object and add the styles to css object after transforming some styles
            for (let key in this.row.configuration.inlineCSS) {
                // if the style property is a padding or margin then we must add a suffix of 'px' after the value
                if (key.includes("padding") || key.includes("margin")) {
                    css[key] = this.row.configuration.inlineCSS[key] + "px";
                    continue;
                }
                css[key] = this.row.configuration.inlineCSS[key];
            }
            return css;
        },
    },
    methods: {
        /**
         * sets the selectedWidget to the selected row
         * prevent row selection if showPreview is true
         */
        selectRow() {
            if (!this.showPreview)
                this.$store.commit(
                    "workshopTemplates/selectedWidget",
                    this.row
                );
            this.$store.commit("workshopTemplates/selectedWidgetType", "row");
        },
        async addColumn() {
            try {
                const column = {
                    id: this.uuid(),
                    type: "column",
                    workshopTemplatesRowId: this.row.id,
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
                        equallySizedColumns:
                            this.row.configuration.equallySizedColumns,
                    },
                };
                // call the workshop templates column create API
                const response = await this.$store.dispatch(
                    "workshopTemplateColumns/create",
                    column
                );
                column.id = response?.data?.id ?? column.id; // sync the column id with the one received from the response
                this.row.columns = [...this.row.columns, { ...column }];
                // give equal width to columns if equallySizedColumns is set to true
                this.sizeColumnsEqually(this.row);
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * disables drag if the row only has 1 column
         */
        checkMove() {
            return this.row?.columns?.length > 1 && !this.showPreview;
        },
        /**
         * removes the column from the columns array
         * @param {index} index of the column to be removed
         */
        async removeColumn(index) {
            try {
                // if the row only has one column then stop the user from deleting that column
                if (this.row?.columns?.length > 1) {
                    // call the column delete API
                    await this.$store.dispatch(
                        "workshopTemplateColumns/destroy",
                        this.row.columns[index]?.id
                    );
                    const removedColumn = this.row.columns.splice(index, 1); //splice from the array on the index
                    // remove the sliced column from the selectedWidget if the deleted column was the selected one
                    if (removedColumn?.[0]?.id == this.selectedWidget?.id) {
                        this.$store.commit(
                            "workshopTemplates/selectedWidget",
                            null
                        );
                        this.$store.commit(
                            "workshopTemplates/selectedWidgetType",
                            ""
                        );
                    }
                }
            } catch (e) {
                console.log(e);
            }
        },
    },
    components: {
        Column,
    },
};
</script>

<style scoped>
.row {
    cursor: move;
}
.toolbar {
    border-top: 5px solid rgba(41, 87, 164, 0.8);
    border-left: 25px solid rgba(41, 87, 164, 0.8);
    border-bottom: 5px solid rgba(41, 87, 164, 0.8);
    border-right: 25px solid rgba(41, 87, 164, 0.8);
}

.remove-row-icon {
    right: 0.5%;
    top: 25%;
    color: white;
}

.add-column-icon {
    right: 0.4%;
    bottom: 25%;
    color: white;
}

.select-row-icon {
    left: 0.4%;
    top: 40%;
    color: white;
}

.column-toolbar {
    padding: 0.2rem 1rem 0.2rem 1rem;
    background-color: rgba(41, 87, 164, 0.5);
    z-index: 9999 !important;
    top: 0%;
    right: 0;
    display: flex;
    justify-content: space-between;
    padding: 0px 10px;
    height: 30px;
    width: 100%;
}
</style>
