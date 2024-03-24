<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div>
            <form @submit.prevent="">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Server Pool Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="flex flex-wrap -mb-8 -mr-6">
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("Hostname") }}:</label
                                >
                                <p>{{ serverPool.hostname }}</p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("IP") }}:</label
                                >
                                <p>{{ serverPool.ip }}</p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("Username") }}:</label
                                >
                                <p>{{ serverPool.username }}</p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("SSH Port") }}:</label
                                >
                                <p>{{ serverPool.ssh_port }}</p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("Cluster ID") }}:</label
                                >
                                <p>{{ serverPool.cluster_id ?? "-" }}</p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("Last Healthcheck") }}:</label
                                >
                                <p>
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
                                </p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("Server Uptime") }}:</label
                                >
                                <p>
                                    {{
                                        formatCompactTimestamp(
                                            serverPool?.data?.uptime
                                        )
                                    }}
                                </p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("Creation Date") }}:</label
                                >
                                <p>
                                    {{
                                        $dateFormatter(
                                            formatDateLite(
                                                new Date(
                                                    serverPool.creation_date *
                                                        1000
                                                )
                                            ),
                                            globalLanguage
                                        )
                                    }}
                                </p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("CPU Load") }}:</label
                                >
                                <p
                                    :class="[
                                        (serverPool?.data?.cpu?.percent ?? 0) >
                                        80
                                            ? 'text-red-500'
                                            : '',
                                    ]"
                                >
                                    {{ serverPool?.data?.cpu?.percent }}%
                                </p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("Company") }}:</label
                                >
                                <p>
                                    {{
                                        serverPool?.companyId?.companyName ??
                                        "-"
                                    }}
                                </p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("Tenant") }}:</label
                                >
                                <p>
                                    {{
                                        serverPool?.tenantId?.companyName ?? "-"
                                    }}
                                </p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4"></div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("Disks") }}:</label
                                >
                                <p
                                    v-for="(disk, index) in serverPool?.data
                                        ?.disk?.disks ?? []"
                                    :key="index"
                                    :index="'disk-' + index"
                                    style="word-break: auto-phrase"
                                    :class="[
                                        'flex',
                                        'items-center',
                                        'cursor-pointer',
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
                                </p>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/4">
                                <label class="form-label font-bold"
                                    >{{ $t("RAM") }}:</label
                                >
                                <P
                                    style="word-break: auto-phrase"
                                    :class="[
                                        'flex',
                                        'items-center',
                                        'cursor-pointer',
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
                                </P>
                            </div>
                            <div class="pb-8 pr-6 w-full lg:w-1/2">
                                <label class="form-label font-bold"
                                    >{{ $t("CPU Details") }}:</label
                                >
                                <div class="grid gap-2 grid-cols-[20%_40%]">
                                    <label class="form-label font-meduim"
                                        >{{ $t("Model Name") }}:</label
                                    >
                                    <p>
                                        {{ serverPool?.data?.cpu?.model_name }}
                                    </p>
                                </div>
                                <div class="grid gap-2 grid-cols-[20%_40%]">
                                    <label class="form-label font-meduim"
                                        >{{ $t("Cache Size") }}:</label
                                    >
                                    <p>
                                        {{ serverPool?.data?.cpu?.cache_size }}
                                    </p>
                                </div>
                                <div class="grid gap-2 grid-cols-[20%_40%]">
                                    <label class="form-label font-meduim"
                                        >{{ $t("Cores") }}:</label
                                    >
                                    <p>
                                        {{ serverPool?.data?.cpu?.cores }}
                                    </p>
                                </div>
                                <div class="grid gap-2 grid-cols-[20%_40%]">
                                    <label class="form-label font-meduim"
                                        >{{ $t("Clock Speed") }}:</label
                                    >
                                    <p>
                                        {{
                                            (
                                                (+serverPool?.data?.cpu
                                                    ?.clock_speed ?? 0) / 1024
                                            ).toFixed(2)
                                        }}&nbsp;GHz
                                    </p>
                                </div>
                            </div>
                        </div>
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
import PageHeader from "@/Components/Layouts/Page-header.vue";
import SelectInput from "@/Components/SelectInput.vue";
import CPULoadGraph from "./Components/CPULoadGraph.vue";
import DiskUsageGraph from "./Components/DiskUsageGraph.vue";
import RAMUsageGraph from "./Components/RAMUsageGraph.vue";
import main from "./Mixins/main.js";
import { mapGetters } from "vuex";

export default {
    mixins: [main],
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        PageHeader,
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
                    text: this.$t("Show"),
                    active: true,
                },
            ],
            serverPool: {},
            statistics: [],
            time: 15,
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "serverPools/show",
                    { id: this.$route.params.id, time: this.time }
                );
                this.serverPool = response?.data?.data ?? {};
                this.statistics = response?.data?.stats ?? [];
                try {
                    if (!!this.serverPool.company_id) {
                        const response = await this.$store.dispatch(
                            "companies/show",
                            this.serverPool.company_id
                        );
                        this.serverPool.companyId =
                            response?.data?.modelData ?? "";
                    }
                } catch (e) {
                    console.log(e);
                }
                try {
                    if (!!this.serverPool.tenant_id) {
                        const response = await this.$store.dispatch(
                            "companies/show",
                            this.serverPool.tenant_id
                        );
                        this.serverPool.tenantId =
                            response?.data?.modelData ?? "";
                    }
                } catch (e) {
                    console.log(e);
                }
                try {
                    if (!!this.serverPool.cluster_id) {
                        const response = await this.$store.dispatch(
                            "systemCloud/show",
                            this.serverPool.cluster_id
                        );
                        this.serverPool.clusterId =
                            response?.data?.systems ?? "";
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
    },
};
</script>
