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
                <li v-if="form.operatingSystem == `linux`" class="nav-item">
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
                <div class="card">
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
                                        :textLabel="$t('Customer')"
                                        :required="true"
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
                                        v-model="form.displayPremise"
                                        :required="true"
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
                                        <option value="database">
                                            {{ $t("Database System") }}
                                        </option>
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
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        :textLabel="
                                            $t('Reverse Shell Client Id')
                                        "
                                        :error="errors?.clients ?? ''"
                                        v-model="form.clientId"
                                        :options="clients"
                                        :multiple="false"
                                        label="hostName"
                                        trackBy="clientId"
                                        :key="clientId"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4" v-if="form.type == 'premise'">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Fill Premise Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="grid items-center grid-cols-2 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        v-model="form.systemName"
                                        :label="$t('System Name')"
                                        :error="errors?.systemName ?? ''"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        v-model="form.serverIp"
                                        :required="true"
                                        :label="$t('Server Ip')"
                                        :error="errors?.serverIp ?? ''"
                                    />
                                    <!-- :error="form.errors.serverIp" -->
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
                                    <MultiSelect
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
                                            :key="form.inputType"
                                            :required="true"
                                            :type="inputType"
                                            v-model="form.password"
                                            :label="$t('Password')"
                                            :error="errors?.password ?? ''"
                                            :show-password="showPassword"
                                            @child-event="handleChildEvent"
                                        />
                                    </div>

                                    <!-- :error="form.errors.password" -->
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
                <router-link to="/systems/on-premise" class="primary-btn mr-3">
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
import MultiSelect from "@/Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    components: {
        MultiSelect,
        MultiSelectInput,
        LoadingButton,
        SelectInput,
        TextInput,
        SelectLinkInput,
        SystemSelectProducts,
        ProductTabs,
        PageHeader,
    },
    remember: "form",
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies"]),
        ...mapGetters("operatingSystem", ["operatingSystem"]),
        ...mapGetters("softwares", ["softwares"]),
        /*...mapGetters("versions", ["versions"]),*/
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
                    to: "/systems/on-premise",
                },
                {
                    text: this.$t("Systems"),
                    to: "/systems/on-premise",
                },
                {
                    text: "On Premise",
                    to: "/systems/on-premise",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            productToggle: false,
            form: {
                systemUser: "",
                type: "premise",
                systemProducts: [],
                systemName: "",
                serverIp: "",
                serverPort: "",
                username: "",
                password: "",
                namespace: "",
                instanceType: "",
                operatingSystem: "",
                clientId: "",
                software: "",
                eloVersion: "",
                displayPremise: "On Premise",
            },
            clients: [],
            versions: [],
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
            this.products = productResponse?.data ?? [];

            let clients = await this.$store.dispatch("systems/getShellClients");
            console.log(clients);
            this.clients = clients?.data;
        } catch (e) {}
    },
    methods: {
        addProducts(products) {
            this.form.systemProducts = products;
        },
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("systems/create", {
                    ...this.form,
                    operatingSystemId: this.form.operatingSystem?.id,
                    software: this.form.software?.id,
                    eloVersion: this.form.eloVersion?.id,
                    systemUser: this.form.systemUser?.id,
                    reverseClientId: this.form.clientId.clientId,
                });
                await this.$store.dispatch("systems/list", {
                    type: "premise",
                });
                this.$router.push("/systems/on-premise");
            } catch (e) {
                this.activeTab = "systems";
            }
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
