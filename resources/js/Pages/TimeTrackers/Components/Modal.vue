<template>
    <Modal title="Legal Time Reporting" v-if="toggleCalenderModal" @toggleModal="cancel" :classSize="'modal-md'">
        <template #body>
            <div class="p-3">
            
                <table class="w-full">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Hours</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item,index) in overViewReportingData" :key="index">
                            <td>{{ ++index }}</td>
                            <td>{{ item.type }}</td>
                            <td>{{ item.description }}</td>
                            <td>{{ item.start_time }}</td>
                            <td>{{ item.end_time }}</td>
                            <td>{{getTimeDifference(item.start_time , item.end_time)}}</td>
                           
                        </tr>
                    </tbody>
                </table>
            </div>
        </template>
        <template #footer>
           
            <button @click="cancel" type="button"
                class="primary-btn mr-3">
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import TextInput from "@/Components/TextInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import SelectInput from "@/Components/SelectInput.vue";
import SelectLinkInput from "@/Components/SelectLinkInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import DateInput from "@/Components/DateInput.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";

export default {
    emits: [
        "toggleCalenderModal",
    ],
    computed: {
        ...mapGetters(["isLoading", "errors"]),
       

    },
    props: {
        toggleCalenderModal: {
            type: Boolean,
            default: false,
        },
        overViewReportingData: {
            type: Array,
            default: false,
        },
    },
    watch: {
        toggleCalenderModal(val) {
            if (!val) {
                this.resetForm();
            }
        }
    },
    components: {
        Modal,
        LoadingButton,
        FlashMessages,
        DateInput,
        MultiSelectInput,
        SelectLinkInput,
        SelectInput,
        QuillTextEditor,
        TextInput,
    },
    data() {
        return {

        };
    },
    methods: {
        
         getTimeDifference(start, end) {
           start = start.split(":");
    end = end.split(":");
    var startDate = new Date(0, 0, 0, start[0], start[1], 0);
    var endDate = new Date(0, 0, 0, end[0], end[1], 0);
    var diff = endDate.getTime() - startDate.getTime();
    var hours = Math.floor(diff / 1000 / 60 / 60);
    diff -= hours * 1000 * 60 * 60;
    var minutes = Math.floor(diff / 1000 / 60);

    // If using time pickers with 24 hours format, add the below line get exact hours
    if (hours < 0)
       hours = hours + 24;

    return (hours <= 9 ? "0" : "") + hours + ":" + (minutes <= 9 ? "0" : "") + minutes;
        },
        /**
         * resets the form to default
         */
        resetForm() {

        },
        /**
         * resets the form when the modal is toggled off
         * resets the errors
         * resets any flash messages
         */
        cancel() {
            this.resetForm();
            this.$emit("toggleCalenderModal", false);
        },
    },
};
</script>

<style></style>
