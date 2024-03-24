<template>
    <Modal
        :title="$t('Cancel Vacation')"
        :classSize="'modal-md'"
        v-if="toggleCancelVacationModal"
        @toggleModal="$emit('toggleCancelVacationModal', false)"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                
                <div class="w-full col-span-2">
                    <div class="form-group">
                        <textarea-input
                            v-model="reason"
                            :label="$t('Reason for cancelling the vacation')"
                        ></textarea-input>
                    </div>
                </div>
             
            </div>
        </template>
        <template #footer>
            <button
                type="button"
                class="primary-btn mr-3"
                @click="$emit('toggleCancelVacationModal', false)"
            >
                <span class="flex">Close</span>
            </button>
            <loading-button
                :loading="isLoading"
                @click="cancel"
                class="secondary-btn"
                type="button"
            >
                {{ $t("Save") }}
            </loading-button>
        </template>
    </Modal>
</template>

<script>
import DateInput from "../../Components/DateInput.vue";
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import TextareaInput from "../../Components/TextareaInput.vue";
import Modal from "@/Components/EditModal.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin";


export default {
    mixins: [userProfilePicturesMixin],
    emits: ["toggleCancelVacationModal", "refresh", "search"],
    data() {
        return {
            reason:'',
        };
    },
   
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("auth", ["user", "userProfilePictures"]),
        ...mapGetters("userProfile", {
            users: "userProfiles",
        }),
       
    },
    props: {
        toggleCancelVacationModal: {
            type: Boolean,
            default: false,
        },
        selectedVacationId: {
            type: Number,
            default: false,
        },
        modalData: {
            type: Object,
            default: () => ({}),
        },
    },
    methods: {
        /**
         * triggers the update API for the vacation request
         */
        async cancel() {
            try {
                this.$store.commit("isLoading", true);
                const payLoad={
                    vacationId : this.selectedVacationId,
                    cancelReason : this.reason  
                };
                
                 await this.$store.dispatch("vacationRequest/cancelVacation", {
                        ...payLoad,
                 });
                 this.reason='';
                 this.$emit("refresh", true);
                this.$emit("toggleCancelVacationModal", false);
                this.$emitter.emit("search", true); 
            } catch (e) {
                console.log(e);
            }
        },
      
    },
    
    components: {
        Modal,
        LoadingButton,
        TextInput,
        SelectInput,
        TextareaInput,
    },
};
</script>