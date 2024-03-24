<template>
    <Modal
        :title="(modalType == 'add' ? 'Add' : 'Edit') + ' Tenant'"
        v-if="toggleTenantModal"
        @toggleModal="$emit('toggleTenantModal', false)"
        :classSize="'modal-md'"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6 mb-3">
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            class=""
                            v-model="tenant.partner_id"
                            :textLabel="$t('Partner')"
                            :error="errors['partner']"
                            :key="tenant.partner_id"
                            :required="true"
                            :options="partners?.data ?? []"
                            :multiple="false"
                            label="companyName"
                            trackBy="id"
                            moduleName="partner"
                            :query="{ customerType: 'partner' }"
                            :countStore="'partnerCount'"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :required="true"
                            class=""
                            :key="tenant.systemUser"
                            :textLabel="$t('Customer')"
                            v-model="tenant.systemUser"
                            :error="errors['tenant.systemUser']"
                            :options="companies?.data ?? []"
                            :multiple="false"
                            label="companyName"
                            trackBy="id"
                            moduleName="companies"
                        >
                            <template #beforeList>
                                <div
                                    class="grid p-2 gap-2"
                                    style="grid-template-columns: 60% 25%"
                                >
                                    <p class="font-bold">
                                        {{ $t("Name") }}
                                    </p>
                                    <p class="font-bold">
                                        {{ $t("Type") }}
                                    </p>
                                </div>
                                <hr />
                            </template>
                            <template #option="{ props }">
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 60% 25%"
                                >
                                    <p class="overflow-text-users-table">
                                        {{ props.option.companyName }}
                                    </p>
                                    <p
                                        style="text-transform: capitalize"
                                        class="overflow-text-users-table"
                                    >
                                        {{ props.option.customerType }}
                                    </p>
                                </div>
                            </template>
                        </MultiSelectInput>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :required="true"
                            v-model="tenant.tenantEmail"
                            class=""
                            :label="$t('Send LogIn-Details')"
                            :error="errors['tenant.tenantEmail'] ?? ''"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :required="true"
                            v-model="tenant.tenantName"
                            class=""
                            :label="$t('Tenant Name')"
                            :error="errors['tenant.tenantName'] ?? ''"
                            :placeholder="
                                $t(
                                    'Tenant Name must only contain letters, dashes, and underscores'
                                )
                            "
                        />
                    </div>
                </div>
            </div>

            <h3 class="card-title text-left">
                {{ $t("Add Repositories for Tenant") }}
            </h3>

            <div
                v-for="(repository, index) in tenant.repositoriesArray"
                :key="index"
                class="card relative p-3 pt-8"
            >
                <div
                    role="button"
                    class="absolute top-2 right-2 z-10"
                    @click="tenant.repositoriesArray.splice(index, 1)"
                >
                    <CustomIcon name="DeleteIcon" />
                </div>

                <div class="grid items-center grid-cols-3 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="repository.name"
                                class=""
                                :error="errors[`repository.${index}.name`]"
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
                                v-model="repository.databaseSize"
                                class=""
                                :label="$t('Database Size in mb')"
                                type="number"
                                placeholder=" "
                                :error="
                                    errors[`repository.${index}.databaseSize`]
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
                                :error="errors[`repository.${index}.dataSize`]"
                                class=""
                                :label="$t('Data Size in mb')"
                                type="number"
                                placeholder=" "
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                </div>
                <div class="w-full pb-8 pt-6 flex flex-row-reverse">
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                :required="false"
                                class="w-full lg:w-full"
                                :key="repository.businessSolutions"
                                :textLabel="
                                    $t('Select business solution to install')
                                "
                                v-model="repository.businessSolutions"
                                :error="
                                    errors[
                                        `repository.${index}.businessSolutions`
                                    ]
                                "
                                :options="hosts?.data ?? []"
                                :multiple="true"
                                label="name"
                                trackBy="id"
                                moduleName="businessSolution"
                            >
                            </MultiSelectInput>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full pb-8 pr-6 flex flex-row-reverse mt-3">
                <button
                    class="flex items-center secondary-btn"
                    role="button"
                    @click.prevent="addRepository"
                >
                    {{ $t("Add Repository") }}
                </button>
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
    emits: ["toggleTenantModal", "refresh"],
    props: {
        toggleTenantModal: {
            type: Boolean,
            default: false,
        },
        modalType: {
            type: String,
            default: "add",
        },
        tenantToEdit: {
            type: Object,
        },
    },
    computed: {
        ...mapGetters(["errors"]),
        ...mapGetters("eloBusinessSolutions", ["hosts"]),
        ...mapGetters("serverPools", ["serverPools", "serverPoolId"]),
        ...mapGetters("companies", ["partners", "companies"]),
        ...mapGetters("auth", ["user"]),
        systemUser() {
            return this.tenant.systemUser;
        },
    },
    async mounted() {
        try {
            if (!!this.tenantToEdit) {
                this.tenant = {
                    ...this.tenantToEdit,
                    partner_id: this.tenantToEdit?.partner_id ?? "",
                    systemUser: this.tenantToEdit?.customer_id ?? "",
                    tenantName: this.tenantToEdit?.tenant_name ?? "",
                    tenantEmail: this.tenantToEdit?.tenant_email ?? "",
                    repositoriesArray: (
                        this.tenantToEdit?.cloud_tenant_repositories ?? []
                    ).map((repository) => {
                        return {
                            ...repository,
                            databaseSize: repository.database_size,
                            dataSize: repository.data_size,
                            businessSolutions:
                                repository?.elo_business_solutions,
                        };
                    }),
                };
                if (!!this.tenant.partner_id) {
                    try {
                        const response = await this.$store.dispatch(
                            "companies/show",
                            this.tenant.partner_id
                        );
                        this.tenant.partner_id = response?.data?.modelData;
                    } catch (e) {}
                }
                if (!!this.tenant.systemUser) {
                    try {
                        const response = await this.$store.dispatch(
                            "companies/show",
                            this.tenant.systemUser
                        );
                        this.tenant.systemUser = response?.data?.modelData;
                    } catch (e) {}
                }
            }
            if (this.modalType === "add") {
                this.tenant.tenantEmail = this.user.email;
            }
            await this.$store.dispatch("eloBusinessSolutions/list");
        } catch (e) {
            console.log(e);
        }
    },
    watch: {
        systemUser: {
            handler: function (val, oldVal) {
                this.tenant.tenantName = val?.companyName?.replace(/ /g, "-");
            },
            deep: true,
        },
    },
    data() {
        return {
            tenant: {
                partner_id: "",
                systemUser: "",
                tenantName: "",
                tenantEmail: "",
                repositoriesArray: [
                    {
                        databaseSize: "",
                        dataSize: "",
                        name: "",
                        businessSolutions: [],
                    },
                ],
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
            this.$emit("toggleTenantModal", false);
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
            this.tenant = {
                partner_id: "",
                systemUser: "",
                tenantName: "",
                repositoriesArray: [
                    {
                        databaseSize: "",
                        dataSize: "",
                        name: "",
                        businessSolutions: [],
                    },
                ],
            };
        },
        async addTenant() {
            try {
                
                this.$store.commit("isLoading", true);
                const payLoad = {
                    systemId: this.$route.params.id,
                    partnerId: this.tenant.partner_id?.id,
                    systemUser: this.tenant.systemUser?.id,
                    tenantName: this.tenant.tenantName,
                    tenantEmail: this.tenant.tenantEmail,
                    repositoriesArray: this.tenant.repositoriesArray,
                };
                this.$store.commit("errors", {});
                
                await this.$store.dispatch(
                    `systemCloud/${
                        this.modalType === "add" ? "create" : "update"
                    }Tenant`,
                    this.modalType === "add"
                        ? {
                              ...payLoad,
                          }
                        : {
                              id: this.tenantToEdit.id,
                              data: { ...payLoad },
                          }
                );

                await this.$store.dispatch(
                    "serverPools/getServer",
                    this.$route.params.id
                );
                const databaseSize = this.tenant?.repositoriesArray.map(
                    (repoArr) => {
                        return repoArr.databaseSize;
                    }
                );
                const dataSize = this.tenant?.repositoriesArray.map(
                    (repoArr) => {
                        return repoArr.dataSize;
                    }
                );
                const repository = this.tenant?.repositoriesArray.map(
                    (repoArr) => {
                        return repoArr.name;
                    }
                );
        
          
                const partner = this.partners?.data.filter((partner) => {
                    if (partner.id === this.tenant.partner_id?.id) {
                        return partner.companyName;
                    }
                });
               
             
                const servicePoolCreateTanentPayload = {
                    id: this?.serverPoolId,
                    database_size: databaseSize,
                    data_size: dataSize,
                    repository: repository,
                    tenant: this.tenant.tenantName,
                    partner: partner[0]?.companyName,
                    mail: this.user?.email,
                    business_solutions: this.tenant.repositoriesArray.map(
                        (repo) =>
                            (repo.businessSolutions || []).map(
                                (bs) => bs.installName ?? bs.install_name
                            )
                    ),
                };
                await this.$store.dispatch("serverPools/createTanent", {
                    ...servicePoolCreateTanentPayload,
                });
                this.$emit("refresh", true);
                this.$store.commit("isLoading", false);
                this.$emit("toggleTenantModal", false);
            } catch (e) {}
        },
        addRepository() {
            this.tenant.repositoriesArray.push({
                databaseSize: "",
                dataSize: "",
                name: "",
                businessSolutions: [],
            });
        },
    },
};
</script>

<style scoped></style>
