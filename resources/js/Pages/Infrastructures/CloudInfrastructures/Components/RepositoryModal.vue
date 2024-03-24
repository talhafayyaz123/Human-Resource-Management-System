<template>
    <Modal
        :title="(modalType == 'add' ? 'Add' : 'Edit') + ' Tenant Repository'"
        v-if="toggleRepositoryModal"
        @toggleModal="$emit('toggleRepositoryModal', false)"
        :classSize="'modal-md'"
    >
        <template #body>

            <div  class="card relative p-3 pt-8">
              
                <div class="grid items-center grid-cols-3 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="repository.name"
                                class=""
                                :error="errors[`repository.name`]"
                                :label="$t('Repository Name')"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="repository.dataBaseSize"
                                class=""
                                :label="$t('Database Size in mb')"
                                type="number"
                                placeholder=" "
                                :error="
                                    errors[`repository.dataBaseSize`]
                                "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="repository.dataSize"
                                :error="errors[`repository.dataSize`]"
                                class=""
                                :label="$t('Data Size in mb')"
                                type="number"
                                placeholder=" "
                                :floatingLabel="true"
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
            <button @click.prevent="addTenant" class="secondary-btn">
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
    emits: ["toggleRepositoryModal", "refresh"],
    props: {
        toggleRepositoryModal: {
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

       
    },
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("serverPools", ["serverPools", "serverPoolId"]),
        ...mapGetters("companies", ["partners", "companies"]),
        ...mapGetters("auth", ["user"]),
       
    },
    data() {
        return {
            repository: {
                dataBaseSize: "",
                dataSize: "",
                name: "",
            },
        };
    },
    components: {
        TextInput,
        Modal,
        MultiSelectInput,
    },
    methods: {
        tenantCancel() {
            this.$emit("toggleRepositoryModal", false);
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
            this.repository={
                dataBaseSize: "",
                dataSize: "",
                name: "",
            };
        },
        async addTenant() {
            
            try {
                this.$store.commit("isLoading", true);
                const payLoad = {
                    ...this.repository,
                    cloudTenantId: this.$route.params.id,
                };
                this.$store.commit("errors", {});

                let server = this.systemCloudServer?.data?.data;
               
                if (server?.id) {
             

                let response = await this.$store.dispatch(
                    "serverPools/createRepository",
                    {
                        id: server?.server_pool_id,
                    database_size:[this.repository.dataBaseSize],
                    data_size:[this.repository.dataSize],
                    tenant:this.tenant.tenantName,
                    partner: this.tenant.partnerName,
                     mail:"m.rupprecht@docshero.de",
                    repository: [this.repository.name]
                    }
                );

                }

                await this.$store.dispatch(
                    `systemCloud/createTenantRepository`, {
                              ...payLoad,
                          }
                        
                ); 

                this.$emit("refresh", true);
                this.$store.commit("isLoading", false);
            this.$emit("toggleRepositoryModal", false);
            } catch (e) {
                console.log(e)
            }
        },
        
    },
};
</script>

<style scoped></style>
 