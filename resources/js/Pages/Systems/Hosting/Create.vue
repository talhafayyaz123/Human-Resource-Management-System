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
        <form @submit.prevent="store">
            <div v-if="activeTab == `systems`">
                <div class="card my-5">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Fill System Details") }}
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
                                        :required="true"
                                        v-model="form.type"
                                        :label="$t('System Type')"
                                        :isReadonly="true"
                                        :error="errors?.type ?? ''"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <select-input
                                        :required="true"
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
                                    />
                                </div>
                            </div>
                            <!--                        <select-input
                              :required="true"
                              v-model="form.operatingSystem"
                              class="pb-8 pr-6 w-full lg:w-1/2"
                              :label="$t('Operating System')"
                              @input="addPortValue"
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
                        <div class="grid items-center grid-cols- gap-6">
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
                                                    {{ $t("IP Address") }}
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

                <div class="card my-5">
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
                                        :textLabel="$t('Software')"
                                        :required="true"
                                        :error="errors?.software ?? ''"
                                        v-model="form.software"
                                        :options="softwares.data"
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
                                    <text-input
                                        :key="inputType"
                                        :required="true"
                                        v-model="form.password"
                                        :label="$t('Password')"
                                        :type="inputType"
                                        :error="errors?.password ?? ''"
                                        :show-password="showPassword"
                                        @child-event="handleChildEvent"
                                    />
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
            </div>
        </form>
        <system-select-products
            v-if="productToggle"
            @selected="addProducts"
            @cancelled="productToggle = false"
            :selectedItems="form.systemProducts"
            :products="products"
        ></system-select-products>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import SelectLinkInput from "@/Components/SelectLinkInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import SystemSelectProducts from "@/Components/SystemSelectProducts.vue";
import ProductTabs from "../Components/ProductTabs.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        MultiSelectInput,
        LoadingButton,
        SelectInput,
        TextInput,
        SelectLinkInput,
        SystemSelectProducts,
        ProductTabs,
        PageHeader,
    },
    props: {
        SystemSelectProductsts: Object,
    },
    remember: "form",
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
            await this.$nextTick(() => {
                this.form.eloVersion = ""; // Clear eloVersion after the next rendering cycle
            });
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
                    to: "/systems/hosting",
                },
                {
                    text: "Hosting",
                    to: "/systems/hosting",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create System"),
                btn2Path: "/systems/hosting/create",
            },
            productToggle: false,
            form: {
                host: "",
                systemUser: "",
                serverIp: "",
                serverPort: "",
                username: "",
                password: "",
                type: "hosting",
                systemProducts: [],
                instanceType: "",
                operatingSystem: "",
                virtualMachine: "",
                hostingType: "vm",
                systemLanguage: "",
                systemSize: "",
                databaseSize: "",
                url: "",
                software: "",
                eloVersion: "",
                databaseCloud: [],
                distributedFileSystem: [],
                partnerId: "",
            },
            versions: [],
            databaseCloudOptions: [],
            distributedFileSystemOptions: [],
            activeTab: "systems",
            activeClasses: "active",
            inactiveClasses: "inactive",
            products: [],
            showPassword: false,
            inputType: "password"


        };
    },
    async mounted() {
        try {
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
            const hostResponse = await this.$store.dispatch("hosts/list", {
                page: 1,
            });
            if (!this.partners?.data?.length)
                await this.$store.dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                    customerType: "partner",
                });
            this.products = productResponse?.data ?? [];
            this.customers = response?.data?.data ?? [];

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
        } catch (e) {}
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("systems/create", {
                    ...this.form,
                    operatingSystemId: this.form.operatingSystem?.id,
                    systemUser: this.form.systemUser?.id,
                    software: this.form.software?.id,
                    eloVersion: this.form.eloVersion?.id,
                    databaseCloud: this.form?.databaseCloud.map(
                        (item) => item.id
                    ),
                    distributedFileSystem: this.form?.distributedFileSystem.map(
                        (item) => item.id
                    ),
                    partnerId: this.form.partnerId?.id,
                });
                await this.$store.dispatch("systems/list", {
                    type: "hosting",
                });
                this.$router.push("/systems/hosting");
            } catch (e) {
                this.activeTab = "systems";
            }
        },
        addProducts(products) {
            this.form.systemProducts = products;
        },
        addPortValue(event) {
            if (event.target.value == "windows") {
                this.form.serverPort = "3389";
            } else {
                this.form.serverPort = "";
            }
        },
        handleChildEvent() {
            this.showPassword = !this.showPassword;
            this.inputType = this.showPassword ? "text" : "password";
        },


    },
};
</script>
