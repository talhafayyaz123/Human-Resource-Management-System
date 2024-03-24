<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div>
            <form @submit.prevent="update">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Update Server Pool Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-wrap">
                            <text-input
                                v-model="form.hostname"
                                :error="errors.hostname"
                                :required="true"
                                class="pb-8 pr-6 w-1/2"
                                :label="$t('Hostname')"
                            />
                            <text-input
                                v-model="form.ip"
                                :error="errors.ip"
                                :required="true"
                                class="pb-8 pr-6 w-1/2"
                                :label="$t('IP')"
                            />
                            <text-input
                                v-model="form.username"
                                :error="errors.username"
                                :required="true"
                                class="pb-8 pr-6 w-1/2"
                                :label="$t('Username')"
                            />
                            <text-input
                                v-model="form.sshPort"
                                :error="errors.sshPort"
                                :required="true"
                                type="number"
                                class="pb-8 pr-6 w-1/2"
                                :label="$t('SSH Port')"
                            />
                            <MultiSelectInput
                                @open="fetchCompanies"
                                v-model="form.companyId"
                                :key="form.companyId"
                                :required="true"
                                :options="companies.data"
                                :multiple="false"
                                textLabel="Customer"
                                label="companyName"
                                trackBy="id"
                                :error="errors.companyId"
                                moduleName="companies"
                                :query="{ customerType: 'customer' }"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                            />
                            <MultiSelectInput
                                @open="fetchPartners"
                                v-model="form.tenantId"
                                :key="form.tenantId"
                                :required="true"
                                :options="partners.data"
                                :multiple="false"
                                textLabel="Tenant"
                                label="companyName"
                                trackBy="id"
                                :error="errors.tenantId"
                                moduleName="companies"
                                :query="{ customerType: 'partner' }"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                            />
                            <MultiSelectInput
                                @open="fetchSystems"
                                v-model="form.clusterId"
                                :key="form.clusterId"
                                :required="true"
                                :options="systemCloud.data"
                                :multiple="false"
                                textLabel="Cluster"
                                label="name"
                                trackBy="id"
                                :error="errors.clusterId"
                                moduleName="systemCloud"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                            />
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <loading-button
                        @click="
                            openMobaURL(
                                form.hostname,
                                form.ip,
                                form.username,
                                form.sshPort
                            )
                        "
                        class="secondary-btn mr-8"
                    >
                        {{ $t("Connect SSH via MobaXTerm") }}
                    </loading-button>
                    <div class="flex">
                        <router-link
                            to="/infrastructures/server-pools"
                            class="primary-btn mr-3"
                        >
                            <span class="mr-1">
                                <CustomIcon name="cancelIcon" />
                            </span>
                            <span>{{ $t("Cancel") }}</span>
                        </router-link>
                        <loading-button
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            :loading="isLoading"
                            class="secondary-btn mr-8"
                        >
                            <span class="mr-1">
                                <CustomIcon name="saveIcon" />
                            </span>
                            {{ $t("Save and Proceed") }}
                        </loading-button>
                    </div>
                </div>
            </form>
            <div class="flex justify-between">
                <h6
                    class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800 self-end"
                >
                    {{ $t("CPU Load Over Time") }}
                </h6>
                <SelectInput
                    class="mt-5 mr-6 w-1/4"
                    :label="$t('Time')"
                    v-model="time"
                    @update:model-value="refresh"
                >
                    <option value="15">{{ "15" + $t("min") }}</option>
                    <option value="30">{{ "30" + $t("min") }}</option>
                    <option value="60">{{ "1" + $t("h") }}</option>
                    <option value="120">{{ "2" + $t("h") }}</option>
                    <option value="240">{{ "4" + $t("h") }}</option>
                    <option value="480">{{ "8" + $t("h") }}</option>
                    <option value="720">{{ "12" + $t("h") }}</option>
                    <option value="1440">{{ "24" + $t("h") }}</option>
                </SelectInput>
            </div>
            <CPULoadGraph :time="time" :statistics="statistics" />
            <div class="grid gap-2 grid-cols-[1fr_1fr]">
                <DiskUsageGraph :time="time" :statistics="statistics" />
                <RAMUsageGraph :time="time" :statistics="statistics" />
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import CPULoadGraph from "./Components/CPULoadGraph.vue";
import DiskUsageGraph from "./Components/DiskUsageGraph.vue";
import RAMUsageGraph from "./Components/RAMUsageGraph.vue";
import main from "./Mixins/main.js";
import { mapGetters } from "vuex";

export default {
    mixins: [main],
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("systemCloud", ["systemCloud"]),
    },
    components: {
        LoadingButton,
        TextInput,
        PageHeader,
        MultiSelectInput,
        SelectInput,
        CPULoadGraph,
        DiskUsageGraph,
        RAMUsageGraph,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Infrastructure"),
                    to: "/infrastructures/server-pools",
                },
                {
                    text: this.$t("Server Pool"),
                    to: "/infrastructures/server-pools",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                hostname: "",
                clusterId: "",
                tenantId: "",
                companyId: "",
                ip: "",
                username: "",
                sshPort: "",
            },
            statistics: [],
            time: 15,
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async fetchCompanies() {
            try {
                this.$store.dispatch("companies/list");
            } catch (e) {
                console.log(e);
            }
        },
        async fetchPartners() {
            try {
                this.$store.dispatch("companies/list", {
                    customerType: "partner",
                });
            } catch (e) {
                console.log(e);
            }
        },
        async fetchSystems() {
            try {
                this.$store.dispatch("systemCloud/list");
            } catch (e) {
                console.log(e);
            }
        },
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "serverPools/show",
                    { id: this.$route.params.id, time: this.time }
                );
                const serverPools = response?.data?.data ?? {};
                this.statistics = response?.data?.stats ?? [];
                this.form = {
                    hostname: serverPools?.hostname ?? "",
                    clusterId: serverPools?.cluster_id ?? "",
                    tenantId: serverPools?.tenant_id ?? "",
                    companyId: serverPools?.company_id ?? "",
                    ip: serverPools?.ip ?? "",
                    username: serverPools?.username ?? "",
                    sshPort: serverPools?.ssh_port ?? "",
                };
                try {
                    if (!!this.form.companyId) {
                        const response = await this.$store.dispatch(
                            "companies/show",
                            this.form.companyId
                        );
                        this.form.companyId = response?.data?.modelData ?? "";
                    }
                } catch (e) {
                    console.log(e);
                }
                try {
                    if (!!this.form.tenantId) {
                        const response = await this.$store.dispatch(
                            "companies/show",
                            this.form.tenantId
                        );
                        this.form.tenantId = response?.data?.modelData ?? "";
                    }
                } catch (e) {
                    console.log(e);
                }
                try {
                    if (!!this.form.clusterId) {
                        const response = await this.$store.dispatch(
                            "systemCloud/show",
                            this.form.clusterId
                        );
                        this.form.clusterId = response?.data?.systems ?? "";
                    }
                } catch (e) {
                    console.log(e);
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("serverPools/update", {
                id: this.$route.params.id,
                hostname: this.form.hostname,
                cluster_id: this.form.clusterId?.id ?? "",
                tenant_id: this.form.tenantId?.id ?? "",
                company_id: this.form.companyId?.id ?? "",
                ip: this.form.ip ?? "",
                username: this.form.username ?? "",
                ssh_port: this.form.sshPort ?? "",
            });
            await this.$store.dispatch("serverPools/list");
            this.$router.push("/infrastructures/server-pools");
        },
    },
};
</script>
