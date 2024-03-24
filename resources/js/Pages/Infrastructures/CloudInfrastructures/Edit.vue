<template>
    <div>
        <PageHeader
            v-if="activeTab == `systems`"
            :items="breadcrumbItems"
            :optionalItems="optionalItems"
            @csvBtn2="openAddServer()"
        />
        <PageHeader
            v-else-if="activeTab == `tenants`"
            :items="breadcrumbItems"
            :optionalItems="optionalItemsTenant"
            @csvBtn2="openAddTenantServer()"
        />
        <div class="tab-header">
            <ul>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'systems'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `systems`"
                    >
                        {{ $t("Systems") }}
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'tenants'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `tenants`"
                    >
                        {{ $t("Tenants") }}
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'pods'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `pods`"
                    >
                        {{ $t("Pods") }}
                    </a>
                </li>
            </ul>
        </div>
        <div v-if="activeTab == `systems`">
            <form>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Fill System Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-wrap">
                            <text-input
                                v-model="form.name"
                                :required="true"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                                :label="$t('Name')"
                                :error="errors?.name ?? ''"
                            />
                            <select-input
                                v-if="form.subType"
                                :required="true"
                                v-model="form.subType"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                                :label="$t('Sub Type')"
                                :error="errors?.subType ?? ''"
                            >
                                <!-- :error="form.errors.subType" -->
                                <option value="public">
                                    {{ $t("Public") }}
                                </option>
                                <option value="private">
                                    {{ $t("Private") }}
                                </option>
                            </select-input>

                            <select-input
                                v-if="form.instanceType"
                                :required="true"
                                v-model="form.instanceType"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                                :label="$t('Instance Type')"
                                :error="errors?.instanceType ?? ''"
                            >
                                <!-- :error="form.errors.instanceType" -->
                                <option value="development">
                                    {{ $t("Development System") }}
                                </option>
                                <option value="test">
                                    {{ $t("Test System") }}
                                </option>
                                <option value="productive">
                                    {{ $t("Productive System") }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                </div>
                <Modal
                    :title="(moduleType == 'add' ? 'Add' : 'Edit') + ' Server'"
                    v-if="toggleModal"
                    @toggleModal="cancel"
                    :classSize="'modal-md'"
                >
                    <template #body>
                        <div class="grid items-center grid-cols-2 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        v-model="server.serverPoolId"
                                        :key="server.serverPoolId"
                                        :required="true"
                                        :options="filteredServerPools"
                                        :multiple="false"
                                        :textLabel="
                                            $t('Select Server from Server Pool')
                                        "
                                        label="hostname"
                                        :search-param-name="'search_string'"
                                        trackBy="id"
                                        :error="errors.serverPoolId"
                                        moduleName="serverPools"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="checkbox-group">
                                    <input
                                        type="checkbox"
                                        :key="server.isMaster"
                                        class="checkbox-input"
                                        v-model="server.isMaster"
                                        id="isMaster"
                                    />
                                    <label
                                        for="isMaster"
                                        class="checkbox-label"
                                    >
                                        {{ $t("Is Master") }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #footer>
                        <button
                            @click="cancel"
                            type="button"
                            class="primary-btn"
                        >
                            {{ $t("Cancel") }}
                        </button>
                        <button
                            v-if="moduleType == 'add'"
                            @click.prevent="addServers"
                            class="secondary-btn"
                        >
                            {{ $t("Save") }}
                        </button>
                        <button
                            v-else
                            @click.prevent="editServer"
                            class="secondary-btn"
                        >
                            {{ $t("Save") }}
                        </button>
                    </template>
                </Modal>
                <div class="table-responsive mt-3">
                    <table class="doc-table">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">{{ $t("Hostname") }}</th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Cluster ID") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Last Healthcheck") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Server Uptime") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Creation Date") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">{{ $t("CPU Load") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Disks") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("RAM") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                        </tr>
                        <tr v-if="cloudServerPools?.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">
                                {{ $t("No servers found") }}.
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(serverPool, index) in cloudServerPools"
                            :key="'location-' + index"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ serverPool.hostname }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ serverPool.cluster_id }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{
                                        $dateFormatter(
                                            formatDateLite(
                                                new Date(
                                                    serverPool.timestamp * 1000
                                                ),
                                                true
                                            ),
                                            globalLanguage
                                        )
                                    }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                                >
                                    {{
                                        formatCompactTimestamp(
                                            serverPool?.data?.uptime
                                        )
                                    }}
                                </a>
                            </td>
                            <td class="border-t">
                                {{
                                    $dateFormatter(
                                        formatDateLite(
                                            new Date(
                                                serverPool.creation_date * 1000
                                            )
                                        ),
                                        globalLanguage
                                    )
                                }}
                            </td>
                            <td class="border-t">
                                <a
                                    :class="[
                                        'flex',
                                        'items-center',
                                        'cursor-pointer',
                                        'px-6',
                                        'py-4',
                                        'focus:text-indigo-500',
                                        (serverPool?.data?.cpu?.percent ?? 0) >
                                        80
                                            ? 'text-red-500'
                                            : '',
                                    ]"
                                >
                                    {{ serverPool?.data?.cpu?.percent }}%
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    v-for="(disk, index) in serverPool?.data?.disk
                                        ?.disks ?? []"
                                    :index="'disk-' + index"
                                    :key="index"
                                    style="word-break: auto-phrase"
                                    :class="[
                                        'flex',
                                        'items-center',
                                        'cursor-pointer',
                                        'px-6',
                                        'py-4',
                                        'focus:text-indigo-500',
                                        (disk?.used_percentage ?? 0) > 80
                                            ? 'text-red-500'
                                            : '',
                                    ]"
                                >
                                    {{ disk.filesystem }}<br />
                                    {{ disk.used }}&nbsp;({{
                                        disk.used_percentage
                                    }}%)&nbsp;used&nbsp;of&nbsp;{{ disk.size }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    style="word-break: auto-phrase"
                                    :class="[
                                        'flex',
                                        'items-center',
                                        'cursor-pointer',
                                        'px-6',
                                        'py-4',
                                        'focus:text-indigo-500',
                                        (serverPool?.data?.ram?.percent ?? 0) >
                                        80
                                            ? 'text-red-500'
                                            : '',
                                    ]"
                                >
                                    {{
                                        serverPool?.data?.ram?.memUsed
                                    }}&nbsp;({{
                                        serverPool?.data?.ram?.percent
                                    }}%)&nbsp;used&nbsp;of&nbsp;{{
                                        serverPool?.data?.ram?.memTotal
                                    }}
                                </a>
                            </td>
                            <td class="w-px border-t">
                                <router-link
                                    :to="`/infrastructures/server-pools/${serverPool.id}`"
                                    class="px-1"
                                >
                                    <font-awesome-icon icon="fa-solid fa-eye" />
                                </router-link>
                                <router-link
                                    :to="`/infrastructures/server-pools/${serverPool.id}/edit`"
                                    class="px-1"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                                <button
                                    @click.prevent="detach(serverPool.id)"
                                    class="px-1"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="margin-top: 3rem" class="flex justify-center">
                    <pagination
                        :limit="10"
                        align="center"
                        :count="count"
                        :perPage="perPage"
                        :start="start"
                        :length="cloudServerPools?.length"
                        :currentPage="currentPage"
                        @paginationInfo="
                            showCloudServers($event.start, $event.end)
                        "
                    ></pagination>
                    <br />
                    <br />
                </div>
                <div class="flex items-center justify-end mt-4">
                    <router-link
                        :to="`/infrastructures/cloud-infrastructures?page=${this.$route.query.page}`"
                        class="primary-btn mr-3"
                    >
                        <span class="mr-1">
                            <CustomIcon name="cancelIcon" />
                        </span>
                        <span>{{ $t("Cancel") }}</span>
                    </router-link>
                    <loading-button
                        @click.prevent="update()"
                        :loading="isLoading"
                        class="secondary-btn"
                    >
                        <span class="mr-1">
                            <CustomIcon name="updateIcon" />
                        </span>
                        {{ $t("Update") }}
                    </loading-button>
                </div>
            </form>
        </div>
        <div v-else-if="activeTab == `tenants`">
            <Modal
                :title="'Add Repository'"
                v-if="toggleRepositoryModal"
                @toggleModal="toggleRepositoryModal = false"
                :width="'50%'"
            >
                <template #body>
                    <div
                        class="flex flex-wrap -mb-8 -mr-6 pb-8"
                        style="text-align: justify"
                    >
                        <div class="w-full lg:w-full">
                            <MultiSelectInput
                                v-model="tenantRepo"
                                :required="true"
                                :options="this.repositoryArray"
                                :multiple="false"
                                :textLabel="$t('Select Repository')"
                                label="name"
                                trackBy="id"
                                @select="getPods"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                            />
                        </div>
                        <div
                            v-for="pod in serverPods"
                            :key="pod.name"
                            class="w-full lg:w-1/2 mb-8 pr-3"
                        >
                            <div class="bg-white border rounded p-4 shadow-md">
                                <div class="flex justify-between mb-4">
                                    <div class="font-bold">
                                        {{ pod.display_name }}
                                    </div>
                                    <div :class="getStatusClass(pod.status)">
                                        {{ pod.status }}
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <strong>Memory:</strong> {{ pod.memory }}
                                </div>
                                <loading-button
                                    @click.prevent="
                                        refreshCloudWithRepo(pod.name)
                                    "
                                    :loading="isLoading"
                                    class="secondary-btn"
                                >
                                    <span class="mr-1">
                                        <CustomIcon name="updateIcon" />
                                    </span>
                                    {{ $t("Restrat") }}
                                </loading-button>
                                <!-- Display URLs with links -->
                                <div
                                    v-if="pod.urls && pod.urls.length > 0"
                                    class="mt-4"
                                >
                                    <strong>URLs:</strong>
                                    <div
                                        v-for="(url, index) in pod.urls"
                                        :key="index"
                                        class="mb-2 mt-2"
                                    >
                                        <a
                                            :href="url.url"
                                            target="_blank"
                                            class="text-blue-500 hover:underline"
                                        >
                                            {{ url.name }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </Modal>
            <Modal
                :title="'Business Solutions'"
                v-if="installBusinessSolutions"
                @toggleModal="installBusinessSolutions = false"
                :width="'50%'"
            >
                <template #body>
                    <div class="flex flex-wrap w-full -mb-8 -mr-6 px-8 pb-8">
                        <MultiSelectInput
                            :required="true"
                            :options="[
                                { id: 1, name: 'Invoice' },
                                { id: 2, name: 'Contract' },
                                { id: 3, name: 'Meeting' },
                                { id: 4, name: 'HR' },
                                { id: 5, name: 'Recruiting' },
                                { id: 6, name: 'ELO Learning' },
                                { id: 7, name: 'HEROsign' },
                                { id: 8, name: 'HEROdocgen' },
                            ]"
                            :multiple="true"
                            :textLabel="
                                $t('Select Business solutions to install')
                            "
                            label="name"
                            trackBy="id"
                            class="pb-8 pr-6 w-full lg:w-full"
                        />
                    </div>
                    <div
                        class="flex items-center justify-end -mb-8 mr-6 px-8 pb-8"
                    >
                        <div
                            @click="installBusinessSolutions = false"
                            class="primary-btn mr-3"
                        >
                            <span class="mr-1">
                                <CustomIcon name="cancelIcon" />
                            </span>
                            <span>{{ $t("Cancel") }}</span>
                        </div>
                        <loading-button class="secondary-btn">
                            <span class="mr-1">
                                <CustomIcon name="saveIcon" />
                            </span>
                            {{ $t("Save") }}
                        </loading-button>
                    </div>
                </template>
            </Modal>
            <TenantModal
                v-if="toggleTenantModal"
                :tenantToEdit="tenantToEdit"
                :modalType="moduleType"
                :toggleTenantModal="toggleTenantModal"
                @toggleTenantModal="
                    toggleTenantModal = $event;
                    moduleType = 'add';
                    tenantToEdit = null;
                "
                @refresh="allTenants"
            />
            <div class="card">
                <div class="table-responsive mt-3">
                    <table class="doc-table">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">{{ $t("Partner") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Customer") }}</th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Tenant Name") }}
                            </th>

                            <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                        </tr>
                        <tr v-if="tenantsList?.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">
                                {{ $t("No tenants found") }}.
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(server, index) in tenantsList"
                            :key="'location-' + index"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ server.partnerName }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ server.companyName }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4"
                                    tabindex="-1"
                                >
                                    {{ server.tenant_name }}
                                </a>
                            </td>

                            <td class="border-t text-center">
                                <div class="flex align-center gap-2">
                                    <router-link
                                        :to="`/tenant-pods/${server.id}`"
                                        class="px-1"
                                    >
                                        <font-awesome-icon
                                            icon="fa-solid fa-eye"
                                        />
                                    </router-link>
                                    <font-awesome-icon
                                        class="cursor-pointer"
                                        icon="fa-solid fa-download"
                                        @click="installBusinessSolutions = true"
                                    />
                                    <div
                                        @click="refreshCloudServer(server.id)"
                                        class="cursor-pointer"
                                    >
                                        <icon name="powerOff" />
                                    </div>

                                    <div
                                        @click="changeConfigOfTanent(server)"
                                        class="cursor-pointer"
                                    >
                                        <icon name="plusIcon" />
                                    </div>
                                    <a
                                        @click="getTenant(index)"
                                        href="#"
                                        class="font-medium text-black-600 dark:text-black-500 hover:underline"
                                        ><font-awesome-icon
                                            icon="fa-regular fa-pen-to-square"
                                    /></a>
                                    <a
                                        @click="deleteTenant(server)"
                                        href="#"
                                        class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                        ><font-awesome-icon
                                            icon="fa-regular fa-trash-can"
                                    /></a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div v-else-if="activeTab == `pods`">
            <Pods />
        </div>
    </div>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import Pods from "./Components/Pods.vue";
import TenantModal from "./Components/TenantModal.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import Pagination from "@/Components/Pagination.vue";
import icon from "@/Components/Icon.vue";
import { Store, mapGetters } from "vuex";
export default {
    components: {
        TenantModal,
        LoadingButton,
        SelectInput,
        TextInput,
        PageHeader,
        Modal,
        MultiSelectInput,
        Pagination,
        Pods,
        icon,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("serverPools", ["serverPools", "serverPoolId"]),
        ...mapGetters("companies", ["partners"]),
        filteredServerPools() {
            return this.serverPools.filter((pool) => !pool.cluster_id);
        },
    },
    mounted() {
        this.refresh();
        this.allTenants();
        this.showServerPools(0, 25);
        this.showCloudServers(this.start, this.perPage);
    },
    data() {
        return {
            count: 0,
            page: 1,
            currentPage: 1,
            start: 0,
            perPage: 10,
            installBusinessSolutions: false,
            tenantRepo: "",
            singleTenant: {},
            repositoryArray: [],
            toggleRepositoryModal: false,
            tenantToEdit: null,
            moduleType: "add",
            toggleModal: false,
            toggleTenantModal: false,
            moduleTenantType: "add",
            activeTab: "systems",
            activeClasses: "active",
            inactiveClasses: "inactive",
            cloudServerPools: [],
            optionalItems: {
                csvBtn2Show: true,
                csvBtn2Text: this.$t("Add Server"),
            },
            optionalItemsTenant: {
                csvBtn2Show: true,
                csvBtn2Text: "Add Tenant",
            },
            selectedId: "",
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Infrastructure"),
                    to: "/infrastructures/cloud-infrastructures",
                },
                {
                    text: this.$t("Systems"),
                    to: `/infrastructures/cloud-infrastructures?page=${this.$route.query.page}`,
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            customers: [],
            companies: [],
            form: {
                type: "cloud",
                name: "",
                instanceType: "",
                subType: "",
                cloudServers: [],
            },
            tenantsList: [],
            repositoryForm: {
                databaseSize: "",
                dataSize: "",
                name: "",
            },
            repositoriesErrors: {},
            server: {
                serverPoolId: "",
                isMaster: false,
            },
            systemCloudServer: "",
            serverPods: [],
        };
    },
    methods: {
        async refreshCloudWithRepo(name) {
            let server = this.systemCloudServer?.data?.data;
            let partner = this.partners?.data.find(
                (partner) => partner.id === this.singleTenant?.partner_id
            );
            if (server?.id) {
                let response = await this.$store.dispatch(
                    "serverPools/refreshServerPool",
                    {
                        id: server?.server_pool_id,
                        pod_name: name,
                        tenant: this.singleTenant?.tenant_name,
                        repository: this.tenantRepo?.name,
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
        async refreshCloudServer(id) {
            this.tenantRepo = "";
            this.serverPods = [];
            this.systemCloudServer = await this.$store.dispatch(
                "systemCloud/getSystemServers",
                this.$route.params.id
            );
            this.singleTenant = this.tenantsList?.find((data) => {
                return data.id === id;
            });
            this.repositoryArray = this.singleTenant.cloud_tenant_repositories;
            this.toggleRepositoryModal = true;
        },
        async deleteTenant(server) {
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
                    await this.$store.dispatch(
                        "serverPools/getServer",
                        this.$route.params.id
                    );
                    const servicePoolDeleteTanentPayload = {
                        id: this?.serverPoolId,
                        tenant: server?.tenant_name,
                        partner: server?.partnerName,
                    };
                    await this.$store.dispatch("serverPools/deleteTanent", {
                        ...servicePoolDeleteTanentPayload,
                    });

                    await this.$store.dispatch(
                        "systemCloud/deleteInfrastrctureTenant",
                        server?.id
                    );

                    this.allTenants();
                }
            });
        },
        async getTenant(index) {
            try {
                this.tenantToEdit = this.tenantsList[index];
                this.moduleType = "edit";
                this.toggleTenantModal = true;
            } catch (e) {
                console.log(e);
            }
        },
        async changeConfigOfTanent(server) {
            this.$store.dispatch("serverPools/selectedTenentData", server);
            this.$router.push(
                `/infrastructures/cloud-infrastructures/${this.$route.params.id}/change-config`
            );
        },
        getStatusClass(status) {
            return status === "Running" ? "text-green-500" : "text-red-500";
        },
        formatCompactTimestamp(timestamp) {
            const secondsInMinute = 60;
            const secondsInHour = 60 * secondsInMinute;
            const secondsInDay = 24 * secondsInHour;

            const days = Math.floor(timestamp / secondsInDay);
            const hours = Math.floor(
                (timestamp % secondsInDay) / secondsInHour
            );
            const minutes = Math.floor(
                (timestamp % secondsInHour) / secondsInMinute
            );
            return `${days}d ${hours}h ${minutes}m`;
        },
        async showServerPools(start, end) {
            await this.$store.dispatch("serverPools/list", {
                limit_start: start,
                limit_count: end,
            });
        },
        async getPods(data) {
            let server = this.systemCloudServer?.data?.data;
            let partner = this.partners?.data.find(
                (partner) => partner.id === this.singleTenant?.partner_id
            );
            if (server?.id && data) {
                let response = await this.$store.dispatch(
                    "serverPools/getServerPods",
                    {
                        id: server?.server_pool_id,
                        tenant: this.singleTenant?.tenant_name,
                        repository: data?.name,
                        partner: partner?.companyName,
                    }
                );
                //this is for viewing default data and for testing purposes
                /*   let response = await this.$store.dispatch(
                    "serverPools/getServerPods",
                    {
                        id: "18c3a7a0-626b-343c-014a-0886791548a5",
                        tenant: "enova",
                        repository: "enova",
                        partner: "kumavision",
                    }
                );*/
                this.serverPods = response.data?.elo_pods;
            }
        },
        showCloudServers(start, end) {
            this.$store
                .dispatch("serverPools/list", {
                    limit_start: start,
                    limit_count: end,
                    cluster_id: this.$route.params.id,
                })
                .then((res) => {
                    this.cloudServerPools = res?.data?.data;
                    this.count = res?.data?.count;
                });
        },
        cancel() {
            this.toggleModal = false;
            this.resetForm();
            this.$store.commit("flashMessage", {
                show: false,
                message: "",
                type: "",
                link: "",
            });
            this.$store.commit("errors", {});
        },

        openAddServer() {
            this.toggleModal = true;
            this.moduleType = "add";
        },
        openAddTenantServer() {
            this.toggleTenantModal = true;
            this.moduleTenantType = "add";
        },
        resetForm() {
            this.server = {
                serverPoolId: "",
                isMaster: false,
            };
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("systemCloud/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                    },
                });
                this.$router.push(
                    `/infrastructures/cloud-infrastructures?page=${this.$route.query.page}`
                );
            } catch (e) {}
        },
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "systemCloud/show",
                    this.$route.params.id
                );
                this.systems = response?.data?.data ?? {};
                this.form = {
                    type: "cloud",
                    name: this.systems.name,
                    instanceType: this.systems.instanceType,
                    subType: this.systems.subType,
                    cloudServers: [],
                };
            } catch (e) {
            } finally {
                this.$store.commit("showLoader", false);
            }

            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                customerType: "partner",
            });

            // get companies list
            this.$store
                .dispatch("companies/list", {
                    perPage: 25,
                    page: 1,
                    isSystem: true,
                })
                .then((res) => {
                    this.companies = res?.data?.data ?? [];
                });
        },
        async addServers() {
            this.$store.commit("errors", {});
            if (!this.server.serverPoolId) {
                this.errors.serverPoolId = "Server Pool is required.";
            }
            if (Object.keys(this.errors).length === 0) {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("serverPools/update", {
                    id: this.server.serverPoolId.id,
                    cluster_id: this.$route.params.id,
                });
                this.showCloudServers(this.start, this.perPage);
                this.showServerPools(0, 25);
                await this.$store.dispatch("serverPools/attachServerPool", {
                    serverPoolId: this.server.serverPoolId.id,
                    isMaster: this.server.isMaster,
                    clusterId: this.$route.params.id,
                });
                this.server = {
                    serverPoolId: "",
                    isMaster: false,
                };
                this.toggleModal = false;
            }
        },
        async allTenants() {
            const response = await this.$store.dispatch(
                "systemCloud/showTenants",
                this.$route.params.id
            );
            this.tenantsList = response?.data?.tenants ?? {};
        },
        async detach(id) {
            this.$swal({
                title: this.$t("Do you want to detach this server pool?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes detach it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    try {
                        await this.$store.dispatch("serverPools/update", {
                            id: id,
                            cluster_id: "",
                        });
                        this.showCloudServers(this.start, this.perPage);
                        this.showServerPools(0, 25);
                        await this.$store.dispatch(
                            "serverPools/detachServerPool",
                            {
                                serverPoolId: id,
                                clusterId: this.$route.params.id,
                            }
                        );
                    } catch (e) {
                        console.log(e);
                    }
                }
            });
        },
    },
};
</script>

<style></style>
