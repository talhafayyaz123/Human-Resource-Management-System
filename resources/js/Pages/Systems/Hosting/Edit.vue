<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
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
                <li class="nav-item" v-if="form.operatingSystem == `linux`">
                    <a
                        class="nav-link"
                        :class="
                            activeTab === 'products'
                                ? activeClasses
                                : inactiveClasses
                        "
                        @click="activeTab = `products`"
                    >
                        {{ $t("Products") }}
                    </a>
                </li>
            </ul>
        </div>
        <form @submit.prevent="update">
            <div v-if="activeTab == `systems`">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Update Systems Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid items-center grid-cols-2 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        v-model="form.partnerId"
                                        :key="form.partnerId"
                                        :options="partners?.data ?? []"
                                        :multiple="false"
                                        textLabel="Tenant"
                                        label="companyName"
                                        :error="errors.partnerId"
                                        trackBy="id"
                                        moduleName="companies"
                                        :query="{ customerType: 'partner' }"
                                        :countStore="'partnerCount'"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        :textLabel="$t('Customer')"
                                        :required="true"
                                        :key="form.systemUser"
                                        v-model="form.systemUser"
                                        :options="companies.data"
                                        :multiple="false"
                                        label="companyName"
                                        trackBy="id"
                                        moduleName="companies"
                                    >
                                        <template #beforeList>
                                            <div
                                                class="grid p-2 gap-2"
                                                style="
                                                    grid-template-columns: 60% 25%;
                                                "
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
                                                style="
                                                    grid-template-columns: 60% 25%;
                                                "
                                            >
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{
                                                        props.option.companyName
                                                    }}
                                                </p>
                                                <p
                                                    style="
                                                        text-transform: capitalize;
                                                    "
                                                    class="overflow-text-users-table"
                                                >
                                                    {{
                                                        props.option
                                                            .customerType
                                                    }}
                                                </p>
                                            </div>
                                        </template>
                                    </MultiSelectInput>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        v-model="form.type"
                                        :required="true"
                                        :label="$t('System Type')"
                                        :isReadonly="true"
                                        :error="errors?.type ?? ''"
                                    />
                                </div>
                            </div>
                            <!--    <MultiSelectInput
                                v-if="form.systemProducts"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                                v-model="form.systemProducts"
                                :options="products"
                                :multiple="true"
                                textLabel="Products"
                                label="name"
                                trackBy="id"
                            /> -->
                            <div class="w-full">
                                <div class="form-group">
                                    <select-input
                                        :required="true"
                                        v-if="form.instanceType"
                                        v-model="form.instanceType"
                                        :label="$t('Instance Type')"
                                        :error="errors?.instanceType ?? ''"
                                    >
                                        <!-- :error="form.errors.instanceType" -->
                                        <option value="database">
                                            {{ $t("Database System") }}
                                        </option>
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
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        :textLabel="$t('Operating System')"
                                        :required="true"
                                        :error="errors?.operatingSystem ?? ''"
                                        v-model="form.operatingSystem"
                                        :options="operatingSystem.data"
                                        :multiple="false"
                                        label="name"
                                        trackBy="id"
                                        :key="form.operatingSystem"
                                    />
                                </div>
                            </div>
                            <!--                        <select-input
                                :required="true"
                                v-if="form.operatingSystem"
                                v-model="form.operatingSystem"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                                :label="$t('Operating System')"
                                :error="errors?.operatingSystem ?? ''"
                            >
                                &lt;!&ndash; :error="form.errors.operatingSystem" &ndash;&gt;
                                <option value="windows">{{ $t("Windows") }}</option>
                                <option value="linux">{{ $t("Linux") }}</option>
                            </select-input>-->
                            <div class="w-full">
                                <div class="form-group">
                                    <!-- Database Cloud multi-select -->
                                    <MultiSelectInput
                                        v-model="form.databaseCloud"
                                        :options="databaseCloudOptions"
                                        :multiple="true"
                                        :textLabel="$t('Database')"
                                        label="name"
                                        trackBy="id"
                                        :key="form.databaseCloud"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <!-- Distributed Filesystem multi-select -->
                                    <MultiSelectInput
                                        v-model="form.distributedFileSystem"
                                        :options="distributedFileSystemOptions"
                                        :multiple="true"
                                        :textLabel="$t('Filesystem')"
                                        label="name"
                                        trackBy="id"
                                        :key="form.distributedFileSystem"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card my-5">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Host System Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid items-center grid-cols-2 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <!-- :error="form.errors.companyEmployees" -->
                                    <MultiSelectInput
                                        :required="true"
                                        :key="form.host"
                                        :showLabels="false"
                                        v-model="form.host"
                                        :options="hosts.data"
                                        :multiple="false"
                                        :textLabel="$t('Hosts')"
                                        label="username"
                                        trackBy="id"
                                        moduleName="hosts"
                                        :error="errors?.host"
                                    >
                                        <template #beforeList>
                                            <div
                                                class="grid p-2 gap-2"
                                                style="
                                                    grid-template-columns: 50% 50%;
                                                "
                                            >
                                                <p class="font-bold">
                                                    {{ $t("Username") }}
                                                </p>
                                                <p class="font-bold">
                                                    {{ $t("Ip Address") }}
                                                </p>
                                            </div>
                                            <hr />
                                        </template>
                                        <template #singleLabel="{ props }">
                                            <div
                                                class="grid gap-2"
                                                style="
                                                    grid-template-columns: 50% 50%;
                                                "
                                            >
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{ props.option.username }}
                                                </p>
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{ props.option.serverIp }}
                                                </p>
                                            </div>
                                        </template>
                                        <template #option="{ props }">
                                            <div
                                                class="grid"
                                                style="
                                                    grid-template-columns: 50% 50%;
                                                "
                                            >
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{ props.option.username }}
                                                </p>
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{ props.option.serverIp }}
                                                </p>
                                            </div>
                                        </template>
                                    </MultiSelectInput>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Fill Hosting Details") }}
                            <!--                        <span v-if="form.operatingSystem === 'windows'">
                                {{ $t("for RDP") }}</span
                            >
                            <span v-else-if="form.operatingSystem === 'linux'">
                                {{ $t("for Linux") }}</span
                            >-->
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid items-center grid-cols-2 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <select-input
                                        :required="true"
                                        v-if="form.hostingType"
                                        v-model="form.hostingType"
                                        :label="$t('Hosting Type')"
                                        :error="errors?.hostingType ?? ''"
                                    >
                                        <!-- :error="form.errors.instanceType" -->
                                        <option value="docker">
                                            {{ $t("Docker") }}
                                        </option>
                                        <option value="vm">
                                            {{ $t("VM") }}
                                        </option>
                                    </select-input>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.virtualMachine"
                                        :label="$t('System Name')"
                                        :error="errors?.virtualMachine ?? ''"
                                    />
                                </div>
                            </div>
                        </div>
                        <div
                            class="grid items-center grid-cols-2 gap-6 my-5"
                            v-if="form.hostingType === 'vm'"
                        >
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.serverIp"
                                        :label="$t('Host')"
                                        :error="errors?.serverIp ?? ''"
                                    />
                                    <!-- :error="form.errors.serverIp" -->
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.serverPort"
                                        :label="$t('Server Port')"
                                        :error="errors?.serverPort ?? ''"
                                    />
                                    <!-- :error="form.errors.serverPort" -->
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        :error="errors?.software ?? ''"
                                        v-model="form.software"
                                        :key="form.software"
                                        :options="softwares.data"
                                        :textLabel="$t('Software')"
                                        :required="true"
                                        :multiple="false"
                                        label="name"
                                        trackBy="id"
                                    />
                                    <!-- :error="form.errors.software" -->
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        :error="errors?.eloVersion ?? ''"
                                        v-model="form.eloVersion"
                                        :key="form.eloVersion"
                                        :options="versions"
                                        :required="true"
                                        :multiple="false"
                                        :textLabel="$t('Software Version')"
                                        label="name"
                                        :trackBy="'id'"
                                        :moduleName="'version'"
                                    />
                                    <!-- :error="form.errors.versions" -->
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.username"
                                        :label="$t('Username')"
                                        :error="errors?.username ?? ''"
                                    />
                                    <!-- :error="form.errors.username" -->
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <CustomIcon name="lckIcon" />
                                            </span>
                                        </div>
                                    <text-input
                                        :required="true"
                                        v-model="form.password"
                                        :label="$t('Password')"
                                        :key="inputType"
                                        :type="inputType"
                                        :error="errors?.password ?? ''"
                                        :show-password="showPassword"
                                        @child-event="handleChildEvent"
                                    />
                                    </div>
                                    <!-- :error="form.errors.password" -->
                                </div>
                            </div>
                        </div>
                        <div
                            class="grid items-center grid-cols-2 gap-6 my-5"
                            v-else-if="form.hostingType === 'docker'"
                        >
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.databaseSize"
                                        :label="$t('Database Disk Size')"
                                        :error="errors?.databaseSize ?? ''"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.systemLanguage"
                                        :label="$t('System Language')"
                                        :error="errors?.systemLanguage ?? ''"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <select-input
                                        :required="true"
                                        v-model="form.systemSize"
                                        :label="$t('System Size')"
                                        :error="errors?.systemSize ?? ''"
                                    >
                                        <option value="1">
                                            {{ $t("Small") }}
                                        </option>
                                        <option value="2">
                                            {{ $t("Medium") }}
                                        </option>
                                        <option value="3">
                                            {{ $t("Big") }}
                                        </option>
                                        <option value="4">
                                            {{ $t("Huge") }}
                                        </option>
                                    </select-input>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        v-model="form.url"
                                        :label="$t('Url')"
                                        :error="errors?.url ?? ''"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else-if="activeTab == `products`">
                <product-tabs
                    :products="products"
                    @selected="addProducts"
                    :selectedProducts="form.systemProducts"
                ></product-tabs>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/systems/hosting" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button :loading="isLoading" class="secondary-btn">
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
                <div class="flex" v-if="form.operatingSystem == 'linux'">
                    <div>
                        <button
                            id="dropdownDefaultButton"
                            data-dropdown-toggle="dropdown"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded text-sm px-4 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            type="button"
                            @click="isDropdown = !isDropdown"
                        >
                            {{ $t("Install Systems") }}
                            <svg
                                class="w-4 h-4 ml-2"
                                aria-hidden="true"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                ></path>
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div
                            v-if="isDropdown"
                            id="dropdown"
                            class="z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700"
                        >
                            <ul
                                class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                aria-labelledby="dropdownDefaultButton"
                            >
                                <li>
                                    <a
                                        @click="install"
                                        href="#"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                        >{{ $t("Install Products") }}</a
                                    >
                                </li>
                                <li>
                                    <a
                                        @click="
                                            () => {
                                                installedToggle = true;
                                                isDropdown = false;
                                            }
                                        "
                                        href="#"
                                        class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                        >{{ $t("View Installed Products") }}</a
                                    >
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div
                style="justify-content: space-between"
                class="mt-4 max-w-3xl flex flex-row-reverse"
            >
                <loading-button
                    :loading="isLoading"
                    style="height: fit-content"
                    class="btn-green"
                    >{{ $t("Update System") }}</loading-button
                >

            </div> -->
        </form>
        <terminal
            v-if="isInstall"
            :isInstallation="isInstallation"
            :commands="commands"
            @cancelled="cancelTerminal"
            :id="this.$route.params.id"
            :isInvoice="false"
        ></terminal>
        <view-installed-products
            v-if="installedToggle"
            @cancelled="installedToggle = false"
            :products="form.installedProducts"
        ></view-installed-products>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import PriceInput from "@/Components/PriceInput.vue";
import SelectLinkInput from "@/Components/SelectLinkInput.vue";
import TrashedMessage from "@/Components/TrashedMessage.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import { NativeEventSource, EventSourcePolyfill } from "event-source-polyfill";
import Terminal from "../../Invoices/Terminal.vue";
import ViewInstalledProducts from "@/Components/ViewInstalledProducts.vue";
import ProductTabs from "../Components/ProductTabs.vue";
import NumberInput from "@/Components/NumberInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                isSystem: true,
            });
            await this.$store.dispatch("operatingSystem/list", {
                page: 1,
            });
            await this.$store.dispatch("softwares/list", {
                page: 1,
            });
            const productResponse = await this.$store.dispatch(
                "products/productsWithQuantity",
                { page: 1, type: "software" }
            );
            this.customers = response?.data?.data ?? [];
            this.products = productResponse?.data ?? [];
        } catch (e) {}
        await this.refresh();
    },
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        TextAreaInput,
        PriceInput,
        SelectLinkInput,
        TrashedMessage,
        MultiSelectInput,
        Terminal,
        ViewInstalledProducts,
        ProductTabs,
        NumberInput,
        PageHeader,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("hosts", ["hosts"]),
        ...mapGetters("operatingSystem", ["operatingSystem"]),
        ...mapGetters("softwares", ["softwares"]),
    },
    watch: {
        async "form.software"(val) {
            this.versions = [];
            //when page render first time this will not excute
            if (this.isData) {
                await this.$nextTick(() => {
                    this.form.eloVersion = ""; // Clear eloVersion after the next rendering cycle
                });
            } else this.isData = true;

            if (val != null) {
                const response = await this.$store.dispatch(
                    "versions/getVersions",
                    val.id
                );
                this.versions = response?.data ?? [];
            }
        },
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Master Data",
                    to: "/systems/hosting",
                },
                {
                    text: this.$t("Systems"),
                    to: `/systems/hosting?page=${this.returnPage}`,
                },
                {
                    text: "Hosting",
                    to: `/systems/hosting?page=${this.returnPage}`,
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            systems: {},
            productToggle: false,
            form: {
                host: "",
                systemUser: "",
                type: "",
                systemProducts: "",
                serverIp: "",
                serverPort: "",
                username: "",
                password: "",
                namespace: "",
                instanceType: "",
                operatingSystem: "",
                virtualMachine: "",
                hostingType: "",
                systemLanguage: "",
                systemSize: "",
                databaseSize: "",
                url: "",
                software: "",
                eloVersion: "",
                installedProducts: [],
                databaseCloud: [],
                distributedFileSystem: [],
                partnerId: "",
            },
            versions: [],
            isData: false,
            returnPage: "",
            products: [],
            isInstall: false,
            isInstallation: true,
            commands: [],
            isDropdown: false,
            installedToggle: false,
            activeTab: "systems",
            activeClasses: "active",
            inactiveClasses: "inactive",
            showPassword: false,
            inputType: "password"
        };
    },
    methods: {
        async refresh() {
            if (this.$route.query.page) {
                this.returnPage = this.$route.query.page;
            }
            try {
                const response = await this.$store.dispatch(
                    "systems/show",
                    this.$route.params.id
                );
                this.systems = response?.data?.systems ?? {};
                document.title = "Edit Hosting " + this.systems?.virtualMachine;
                const hostResponse = await this.$store.dispatch("hosts/list", {
                    page: 1,
                });
                this.hosts = hostResponse?.data ?? [];

                //Get distributed filesystem data
                const filesystemResponse = await this.$store.dispatch(
                    "distributedFilesystem/list"
                );
                this.distributedFileSystemOptions =
                    filesystemResponse?.data?.data ?? [];

                //Get cloud database data
                const cloudResponse = await this.$store.dispatch(
                    "databaseCloud/list"
                );
                this.databaseCloudOptions = cloudResponse?.data?.data ?? [];
                var systemUser =
                    this.companies.data.find(
                        (customer) => customer.id == this.systems.systemUser
                    ) ?? null;
                // if the company does not exist in the companies listing then use the show API to fetch the company separately
                if (!systemUser && this.systems.systemUser) {
                    const singleCompanyResponse = await this.$store.dispatch(
                        "companies/show",
                        this.systems.systemUser
                    );
                    systemUser = singleCompanyResponse?.data?.modelData ?? "";
                }
                if (!this.partners?.data?.length)
                    await this.$store.dispatch("companies/list", {
                        page: 1,
                        perPage: 25,
                        customerType: "partner",
                    });

                this.form = {
                    systemUser: systemUser,
                    host:
                        this.hosts.data.find(
                            (host) => host.id == this.systems.host
                        ) ?? null,
                    type: this.systems.type,
                    systemProducts: this.systems.systemProducts,
                    serverIp: this.systems.serverIp,
                    serverPort: this.systems.serverPort,
                    username: this.systems.username,
                    password: this.systems.password,
                    namespace: this.systems.namespace,
                    instanceType: this.systems.instanceType,
                    operatingSystem: this.systems.operatingSystem,
                    virtualMachine: this.systems.virtualMachine,
                    installedProducts: this.systems.installedProducts,
                    hostingType: this.systems.hostingType,
                    systemLanguage: this.systems.systemLanguage,
                    systemSize: this.systems.systemSize,
                    databaseSize: this.systems.databaseSize,
                    url: this.systems.url,
                    software: this.systems.software ?? {},
                    eloVersion: this.systems.eloVersion ?? {},
                    databaseCloud: this.systems.databaseCloud,
                    distributedFileSystem: this.systems.distributedFileSystem,
                    partnerId:
                        this.partners?.data?.find(
                            (partner) => partner.id === this.systems.partnerId
                        ) ?? "",
                };
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("systems/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        operatingSystemId: this.form.operatingSystem?.id,
                        systemUser: this.form.systemUser?.id,
                        software: this.form.software?.id,
                        eloVersion: this.form.eloVersion?.id,
                        databaseCloud: this.form?.databaseCloud.map(
                            (item) => item.id
                        ),
                        distributedFileSystem:
                            this.form?.distributedFileSystem.map(
                                (item) => item.id
                            ),
                        partnerId: this.form.partnerId?.id,
                    },
                });
                await this.$store.dispatch("systems/list", {
                    type: "hosting",
                });
                this.$router.push("/systems/hosting?page=" + this.returnPage);
            } catch (e) {
                this.activeTab = "systems";
            }
        },
        addProducts(products) {
            this.form.systemProducts = products;
            this.productToggle = false;
        },
        cancelTerminal() {
            this.isInstall = false;
            this.refresh();
        },
        install() {
            this.isDropdown = false;
            this.isInstall = true;
            this.commands = ["Starting Installations"];
            let es = new EventSourcePolyfill(
                "/api/install-products/" + this.$route.params.id,
                {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                }
            );
            let i = 0;
            es.addEventListener(
                "message",
                (event) => {
                    i++;
                    this.commands[i] = event.data;
                },
                false
            );
            es.onerror = (err) => {
                es.close();
                this.isInstallation = false;
                console.error("EventSource failed:", err);
            };
        },
        handleChildEvent() {
            this.showPassword = !this.showPassword;
            this.inputType = this.showPassword ? "text" : "password";
        },
    },
};
</script>
