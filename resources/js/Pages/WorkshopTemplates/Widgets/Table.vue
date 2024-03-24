<template>
    <!-- 
        the component below renders the remove widget button in the Toolbar component
     -->
    <RemoveWidgetButton :type="widget.type" :id="id" @removeWidget="$emit('removeWidget', true)" />
    <!-- 
        inlineCSS is an object containing different style properties.
        styleClasses an array of predefined tailwind classes 
     -->
    <div :class="[...styleClasses]" :style="{ ...inlineCSS }">
        <div class="flex justify-between mb-3">
            <h1 class="self-center">{{ $t(widget.configuration.label) }}</h1>
            <button
                @click="toggle = true"
                class="px-3 py-2 rounded docsHeroColorBlue"
            >
                + {{ $t("Add") }}
            </button>
        </div>
        <table
            @click.stop="selectWidget"
            :class="[
                'm-1 self-start',
                'w-full whitespace-nowrap',
                selectedWidget?.id == id && selectedWidgetType === widget.type
                    ? 'border-2 border-blue-500'
                    : '',
            ]"
        >
            <thead>
                <tr class="text-left font-bold border-2 border-black">
                    <th
                        v-for="(field, index) in widget.configuration.fields"
                        :key="'table-column-' + index"
                        class="pb-4 pt-6 cursor-pointer text-white text-center bg-blue border-2 border-black"
                    >
                        {{ $t(field.displayName) }}
                    </th>
                    <th
                        class="pb-4 pt-6 cursor-pointer text-white text-center bg-blue border-2 border-black"
                    >
                        {{ $t("Action") }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(record, index) in widget.configuration.records"
                    :key="'table-record-' + index"
                    class="pb-4 pt-6 cursor-pointer text-center border-2 border-black"
                >
                    <td
                        v-for="(item, itemIndex) in record"
                        :key="'field-' + key + '-' + itemIndex"
                    >
                        {{ item.value }}
                    </td>
                    <td class="w-px border-t text-center">
                        <button @click="toggleUpdateModal(index)" class="px-1">
                            <font-awesome-icon icon="fa-regular fa-pen-to-square"/>
                        </button>
                        <button @click="deleteRecord(itemIndex)" class="px-1">
                            <font-awesome-icon icon="fa-regular fa-trash-can"/>
                        </button>
                    </td>
                </tr>
                <tr
                    v-if="!widget.configuration?.records?.length"
                    class="p-5 block"
                >
                    {{
                        $t("No Items Exist")
                    }}
                </tr>
            </tbody>
        </table>
    </div>
    <TableModal
        v-if="toggle"
        :widget="widget"
        :toggle="toggle"
        :actionType="actionType"
        :record="record"
        @cancel="cancel"
        @save="save"
        @update="update"
    />
</template>

<script>
import mainMixin from "../Mixins/mainMixin";
import widgetMixin from "../Mixins/widgetMixin";
import TableModal from "../Components/TableModal.vue";

export default {
    name: "TemplateTable",
    mixins: [mainMixin, widgetMixin],
    components: {
        TableModal,
    },
    data() {
        return {
            toggle: false, // toggles the table modal
            actionType: "Add", // type of modal action, possible values are 'Add' and 'Edit',
            record: null, // contains the record of the selected widget to be updated,
            editIndex: "", // index of the record being edited, used to replace the old value with the new updated value
        };
    },
    methods: {
        /**
         * adds a record/row to the table from the record array received from the event and hides the modal
         * the record array is pushed onto the records array of the selected widget, which contains all the entries entered by the user
         * for this table
         * @param {record} an array of objects that contains the values entered by the user in the add form in the TableModal component
         */
        save(record) {
            this.widget.configuration.records.push(record);
            this.toggle = false;
        },
        /**
         * updates the record in the records array of the widget on the index on the 'editIndex'
         * sets the 'editIndex' value to default after editing
         * hides the modal
         * @param {record} an array of objects that contains the values edited by the user in the edit form in the TableModal component
         */
        update(record) {
            this.widget.configuration.records[this.editIndex] = record;
            this.editIndex = "";
            this.toggle = false;
        },
        /**
         * removed the row from the records array of the widget based on the index
         * @param {index} index of the record to be removed
         */
        deleteRecord(index) {
            this.widget.configuration.records.splice(index, 1);
        },
        /**
         * sets the action type of the modal to 'Edit'
         * toggles the update modal of the record on the given index
         * sets the record to the record of the widget records that is to be edited
         * sets the 'editIndex' to the index of the record to be edited
         * @param {index} index of the record to be updated
         */
        toggleUpdateModal(index) {
            this.actionType = "Edit";
            this.editIndex = index;
            this.record = [...this.widget.configuration.records[index]];
            this.toggle = true;
        },
        /**
         * hides the modal
         * resets the record, actionType and editIndex to default values
         */
        cancel() {
            this.toggle = false;
            this.record = null;
            this.actionType = "Add";
            this.editIndex = "";
        },
    },
};
</script>

<style scoped>
.color-blue {
    color: #2957a4;
}
.bg-blue {
    background-color: #2957a4;
}

th,
td {
    border: 1px solid black;
}
</style>
