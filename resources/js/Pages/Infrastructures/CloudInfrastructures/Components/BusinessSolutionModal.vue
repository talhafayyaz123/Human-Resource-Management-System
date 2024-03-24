<template>
    <Modal
        :title="'Select Business Solution To Install'"
        v-if="toggleBusinessSolutionModal"
        @toggleModal="$emit('toggleBusinessSolutionModal', false)"
        :classSize="'modal-md'"
    >
        <template #body>

            <div  class="card relative p-3 pt-8">
          
                <div class="grid items-center grid-cols-3 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                            class=""
                            v-model="businessSolution"
                            :textLabel="$t('Business Solution')"
                            :error="errors['partner']"
                            :key="businessSolution"
                            :required="true"
                            :options="eloBusinessSolutions"
                            :multiple="true"
                            label="name"
                            trackBy="installName"
                            moduleName="partner"
                            :query="{ customerType: 'partner' }"
                            :countStore="'partnerCount'"
                        />
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </template>
        <template #footer>
            <button
                @click="tenantCancel"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
            <button @click.prevent="addBusinessSolution" class="secondary-btn">
                {{ $t("Save") }}
            </button>
        </template>
    </Modal>
</template>


<script>
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import Modal from "@/Components/EditModal.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["toggleBusinessSolutionModal", "refresh"],
    props: {
        toggleBusinessSolutionModal: {
            type: Boolean,
            default: false,
        },
        modalType: {
            type: String,
            default: "add",
        },

        tenant:{
            type: Object,
            default: false,
        },
        eloBusinessSolutions:{
            type: Array,
            default: false,
        },
eloBusinessRepository: {
            type: String,
            default: "false",
        },
        
       
    },
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("serverPools", ["serverPools", "serverPoolId"]),
        ...mapGetters("companies", ["partners", "companies"]),
        ...mapGetters("auth", ["user"]),
       
    },
    data() {
        return {
             businessSolution:[]
        };
    },
    components: {
        TextInput,
        Modal,
        MultiSelectInput,
    },
    methods: {
        tenantCancel() {
            this.$emit("toggleBusinessSolutionModal", false);
            this.resetForm();
            this.$store.commit("flashMessage", {
                show: false,
                message: "",
                type: "",
                link: "",
            });
            this.$store.commit("errors", {});
        },
        resetForm() {
            this.businessSolution=[];
        },
        async addBusinessSolution() {
            
            try {
                this.$store.commit("isLoading", true);
                
                this.$store.commit("errors", {});

                let server = this.systemCloudServer?.data?.data;
               
                 
               if (server?.id) {
             
                    var solutions=this.businessSolution.map((item)=>{
                    return item.installName;
                 });
                
                let response = await this.$store.dispatch(
                    "serverPools/installBusinessPlan",
                    {
                    id: server?.server_pool_id,
                    business_solutions:solutions,
                    tenant:this.tenant.tenantName,
                    partner: this.tenant.partnerName,
                    repository: [this.eloBusinessRepository]
                    }
                );
                var message='Business solutions are currently installed...';
                if(response){
                    message=response?.message;
                }
                this.$swal({
                        title: this.$t(message),
                        icon: "success",
                        button: {
                            text: "OK",
                            className: "btn-success",
                        },
                    });

                }

              
               // this.$emit("refresh", true);
                this.$store.commit("isLoading", false);
            this.$emit("toggleBusinessSolutionModal", false);
            } catch (e) {
                console.log(e)
            }
        },
        
    },
};
</script>