<template>
    <PageHeader :items="breadcrumbItems" />

    <div class="teanut-show">
        <div class="flex items-center mb-4">
            <h3 class="card-title">{{ $t("Tenant") }}</h3>
            <div class="ml-2 cursor-pointer">
                <CustomIcon name="iIcon" />
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header flex items-center justify-between">
                <div class="flex items-center mb-4">
                    <h3 class="card-title">{{ tenant.tenantName }}</h3>
                    <div class="ml-2 cursor-pointer">
                        <CustomIcon name="iIcon" />
                    </div>
                </div>


                <div class='flex items-center'> 
                    
                 <button class="secondary-btn mr-2">
                        <CustomIcon name="addIcon" />
                        <span class="ml-1" @click="addRepositoryModel()">{{
                            $t("Add Repository")
                        }}</span>
                 </button>

                 <button class="secondary-btn">
                    <CustomIcon name="MonitoringIcon" />
                    <span class="ml-1">{{ $t("Monitoring") }}</span>
                </button>
                
                </div>
               
            </div>
            
            <div class="card-body">
                <div class="grid items-center grid-cols-3 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="tenant.partnerName"
                                :label="$t('Partner')"
                                :required="false"
                                :isReadonly="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="tenant.companyName"
                                :label="$t('Customer')"
                                :required="false"
                                :isReadonly="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="tanent.name"
                                :label="$t('Tenant Name')"
                                :required="false"
                                :isReadonly="true"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center mb-4">
            <h3 class="card-title">{{ $t("Repository") }}</h3>
            <div class="ml-2 cursor-pointer">
                <CustomIcon name="iIcon" />
            </div>
        </div>

        <div
            class="card mb-4 p-8 teanut-card"
            v-for="(tenantRepo, index) in tenantRepositories"
            :key="tenantRepo.cloud_tenant_id"
        >
            <div class="card flex items-center justify-between p-1">
                <div class="flex items-center gap-1 cursor-pointer" @click="toggleItem(index)">
                    <div class="">
                        <CustomIcon name="downIcon"/>
                    </div>
                    <h3
                    class="card-title "
                    
                >
                    {{ tenantRepo.name }}
                </h3>
                </div>

                <div class="flex items-center">
                    <button class="secondary-btn mr-2">
                        <CustomIcon name="backupIcon" />
                        <span class="ml-1">{{ $t("Manage Backup") }}</span>
                    </button>
                    <button class="secondary-btn mr-2">
                        <CustomIcon name="installSolIcon" />
                        <span class="ml-1" @click="addBusinessSolutionModel(tenantRepo.name )">{{
                            $t("Install Business Solutions")
                        }}</span>
                    </button>
                    <button class="secondary-btn mr-2">
                        <CustomIcon name="configIcon" />
                        <span class="ml-1">{{ $t("Change Config") }}</span>
                    </button>
                    <button class="secondary-btn mr-2">
                        <CustomIcon name="restartIcon" />
                        <span class="ml-1" @click="restartRepository(tenantRepo.name)">{{ $t("Restart Repository") }}</span>
                    </button>
                   
                    <button class="primary-btn delete">
                        <CustomIcon name="DeleteIcon" />
                        <span
                            class="ml-1"
                            @click="deleteRepository(tenantRepo.id,tenantRepo.name)"
                        >
                            {{ $t("Delete Repository") }}
                        </span>
                    </button>
                </div>
            </div>
            <div class="pt-8 px-5" v-if="isOpen(index)">
                <div class="grid items-center grid-cols-3 gap-6 mb-5">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="tenantRepo.name"
                                :label="$t('Repository')"
                                :required="false"
                                :isReadonly="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="tenantRepo.data_size"
                                :label="$t('Data Size in mb')"
                                :required="false"
                                :isReadonly="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="tenantRepo.database_size"
                                :label="$t('Database Size in mb')"
                                :required="false"
                                :isReadonly="true"
                            />
                        </div>
                    </div>
                </div>
                <div
                    class="grid grid-cols-4 gap-6"
                    v-if="tenantRepo?.pods?.length > 0"
                >
                    <div
                        class="w-full"
                        v-for="pod in tenantRepo.pods"
                        :key="pod.name"
                    >
                        <div class="repo-card">
                            <div class="flex items-center justify-between">
                                <div class="">
                                    <h3 class="card-title">
                                        {{ pod.display_name }}
                                    </h3>
                                    <!-- <p><span>Version:</span> 23.02.008</p> -->
                                </div>
                                    
                                        <loading-button
                                            @click.prevent="
                                                refreshCloudWithRepo(
                                                    pod.name,
                                                    tenantRepo
                                                )
                                            "
                                            :loading="isLoading"
                                            class="secondary-btn gap-2 cursor-pointer"
                                        >
                                        <CustomIcon name="restartIcon" />
                                            {{ $t("Restart") }}
                                        </loading-button>
                            </div>
                            <div class="status">
                                <h3 class="card-title">{{ $t("Status") }}:</h3>
                                <div :class="getStatusClass(pod.status)">
                                    {{ pod.status }}
                                </div>
                            </div>
                            <div class="memory">
                                <h3 class="card-title">{{ $t("Memory") }}:</h3>
                                <div class="flex items-center justify-between">
                                    <h4>{{ pod.memory }}</h4>
                                </div>
                            </div>
                            <div class="component" v-if="pod.urls && pod.urls.length > 0">
                                <h3 class="card-title">{{ $t("Urls") }}:</h3>
                                <div
                                    class="grid items-center grid-cols-2 gap-2">
                                    <div
                                        class="w-full"
                                        v-for="(url, index) in pod.urls"
                                        :key="index"
                                    >
                                        <div class="component-btn">
                                            <span class="mr-2"
                                                ><CustomIcon name="linkIcon"
                                            /></span>
                                            <span
                                                ><a
                                                    :href="url.url"
                                                    target="_blank"
                                                    class="text-blue-500 hover:underline"
                                                >
                                                    {{ url.name }}
                                                </a></span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="grid grid-cols-4 gap-6"
                    v-if="tenantRepo?.pods?.length == 0"
                >
                    No Data Available!
                </div>
            </div>
        </div>
    </div>
    <RepositoryModal
        v-if="toggleRepositoryModal"
        modalType="add"
        :systemCloudServer="systemCloudServer"
        :tenant="tenant"
        :toggleRepositoryModal="toggleRepositoryModal"
        @toggleRepositoryModal="
            toggleRepositoryModal = $event;
            moduleType = 'add';
        "
        @refresh="refresh"
    />

    <BusinessSolutionModal
        v-if="toggleBusinessSolutionModal"
        modalType="add"
        :eloBusinessSolutions="eloBusinessSolutions"
        :eloBusinessRepository="eloBusinessRepository"
        :tenant="tenant"
        :toggleBusinessSolutionModal="toggleBusinessSolutionModal"
        @toggleBusinessSolutionModal="
            toggleBusinessSolutionModal = $event;
            moduleType = 'add';
        "
    />
    <!--   <div
        v-for="tenantRepo in tenantRepositories"
        :key="tenantRepo.cloud_tenant_id"
        class="mb-4 mt-5"
    >
        <TenantPodsDropdown
            :tenantRepo="tenantRepo"
            :refreshCloudWithRepo="refreshCloudWithRepo"
        />
    </div> -->
</template>

<script>
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import TenantPodsDropdown from "./Components/TenantPodsDropdown.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import RepositoryModal from "./Components/RepositoryModal.vue";
import BusinessSolutionModal from "./Components/BusinessSolutionModal.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("serverPools", ["serverPools", "serverPoolId"]),
        ...mapGetters("companies", ["partners"]),
    },
    components: {
        PageHeader,
        TenantPodsDropdown,
        MultiSelectInput,
        TextInput,
        LoadingButton,
        RepositoryModal,
        BusinessSolutionModal
    },
    data() {
        return {
            tenant: {},
            toggleRepositoryModal: false,
            toggleBusinessSolutionModal: false,
            systemCloudServer: {},
            tenantRepositories: [],
            eloBusinessSolutions:[],
            eloBusinessRepository:'',
            openSections: [],
            form: {
                prname: "",
                participants: [],
                startDate: new Date(),
            },
            tanent: {
                name: "",
            },
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Systems"),
                    to: "/infrastructures/cloud-infrastructures",
                },
                {
                    text: this.$t("Tenant"),
                    active: true,
                },
            ],
        };
    },
    async mounted() {
        this.refresh();
        this.showServerPools(0, 25);
    },
    methods: {
        async showServerPools(start, end) {
            await this.$store.dispatch("serverPools/list", {
                limit_start: start,
                limit_count: end,
            });
        },
        addRepositoryModel() {
            this.toggleRepositoryModal = true;
        },
        addBusinessSolutionModel(name) {
             this.eloBusinessRepository=name;
            this.toggleBusinessSolutionModal = true;
        },
        
       async restartRepository(name){
        let server = this.systemCloudServer?.data?.data;
  
        if (server?.id) {
                let response = await this.$store.dispatch(
                    "serverPools/restartRepository",
                    {
                        id: server?.server_pool_id,
                        tenant:this.tenant.tenantName,
                       partner:this.tenant.partnerName,
                       repository: name
                    }
                );
                
                    this.$swal({
                        title: this.$t('Repository Restarted Successfully.'),
                        icon: "success",
                        button: {
                            text: "OK",
                            className: "btn-success",
                        },
                    });
            }else{

                this.$swal({
                        title: this.$t('No server with master exists.'),
                        icon: "warning",
                        button: {
                            text: "OK",
                            className: "btn-danger",
                        },
                    });   
            }
         
        },
        deleteRepository(repositoryId,name) {
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    
                     /* await this.$store.dispatch(
                        "serverPools/getServer",
                        this.$route.params.id
                    ); */
                    let server = this.systemCloudServer?.data?.data;
                    if (server?.id) {
                        const servicePoolDeleteTanentPayload = {
                        id: server?.server_pool_id,
                        tenant: this.tenant.tenantName,
                        partner: this.tenant.partnerName,
                        repository: [name]
                    };
                    
                     await this.$store.dispatch("serverPools/deleteRepository", {
                        ...servicePoolDeleteTanentPayload,
                    });

                    }
                   
                    await this.$store.dispatch(
                        "systemCloud/deleteTenantRepository",
                        repositoryId
                    );

                    this.refresh();  
                }
            });
        },
        async refresh() {
            //get system tetant
            const response = await this.$store.dispatch(
                "systemCloud/getSystemTenant",
                this.$route.params.id
            );
            //get all the partners
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                customerType: "partner",
            });
            //make tenant equal to a variable
            this.tenant = response?.data?.tenant ?? {};

            this.tanent.name = this.tenant.tenantName;
            
            //get repo of the tenant
            this.tenantRepositories = this.tenant.cloudTenantRepositories;
          
            const output = await this.$store.dispatch(
                "systemCloud/businessSolutionList",
                this.$route.params.id
            );
             this.eloBusinessSolutions=output?.data?.data;
            
            //get the cloud server
            this.systemCloudServer = await this.$store.dispatch(
                "systemCloud/getSystemServers",
                this.tenant.systemId
            );
            
            //loop through the repo and get the pods of that repo
            this.tenantRepositories?.forEach(async (repository) => {
                let pods = await this.getPods(repository);
                repository.pods = pods;
            });
        },
        getStatusClass(status) {
            return status === "Running"
                ? "status-tag running"
                : "status-tag error";
        },
        toggleItem(index) {
            const indexOfSection = this.openSections.indexOf(index);
            if (indexOfSection === -1) {
                this.openSections.push(index);
            } else {
                this.openSections.splice(indexOfSection, 1);
            }
        },
        isOpen(index) {
            return this.openSections.includes(index);
        },
        async getPods(data) {
            let server = this.systemCloudServer?.data?.data;
            let partner = this.partners?.data.find(
                (partner) => partner.id === this.tenant?.partnerId
            );
            if (server?.id && data) {
                let response = await this.$store.dispatch(
                    "serverPools/getServerPods",
                    {
                        id: server?.server_pool_id,
                        tenant: this.tenant?.tenantName,
                        repository: data?.name,
                        partner: partner?.companyName,
                    }
                );

                /*   let response = await this.$store.dispatch(
                     "serverPools/getServerPods",
                     {
                         id: "18c3a7a0-626b-343c-014a-0886791548a5",
                         tenant: "enova",
                         repository: "enova",
                         partner: "kumavision",
                     }
                 ); */
                return response.data?.elo_pods;
            }
            return [];
        },
        async refreshCloudWithRepo(name, repo) {
            let server = this.systemCloudServer?.data?.data;
            let partner = this.partners?.data.find(
                (partner) => partner.id === this.tenant?.partnerId
            );
            if (server?.id) {
                let response = await this.$store.dispatch(
                    "serverPools/refreshServerPool",
                    {
                        id: server?.server_pool_id,
                        pod_name: name,
                        tenant: this.tenant?.tenantName,
                        repository: repo?.name,
                        partner: partner?.companyName,
                    }
                );
                this.toggleRepositoryModal = false;
                if (response?.data?.message)
                    this.$swal({
                        title: this.$t(response?.data?.message),
                        icon: "success",
                        button: {
                            text: "OK",
                            className: "btn-success",
                        },
                    });
            }
        },
    },
};
</script>

<style lang="scss"></style>
