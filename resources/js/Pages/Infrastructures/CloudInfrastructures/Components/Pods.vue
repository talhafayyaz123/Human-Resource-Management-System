<template>
    <div>
        <div class="card">
            <div class="flex items-center justify-end mt-3">
                <search-filter
                    :isFilter="false"
                    v-model="search"
                    class="mr-4 w-full max-w-md"
                    @reset="reset"
                >
                </search-filter>
            </div>
            <div class="table-responsive mt-3">
                <table class="doc-table">
                    <tr class="text-left font-bold">
                        <th
                            @click="sortBy('namespace')"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Namespace") }}
                            <font-awesome-icon
                                v-if="sortField === 'namespace'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    sortOrder === 1 ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sortBy('name')"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Name") }}
                            <font-awesome-icon
                                v-if="sortField === 'name'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    sortOrder === 1 ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sortBy('node')"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Node") }}
                            <font-awesome-icon
                                v-if="sortField === 'node'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    sortOrder === 1 ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sortBy('ready')"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Ready") }}
                            <font-awesome-icon
                                v-if="sortField === 'ready'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    sortOrder === 1 ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sortBy('cpu')"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("CPU") }}
                            <font-awesome-icon
                                v-if="sortField === 'cpu'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    sortOrder === 1 ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sortBy('memory')"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Memory") }}
                            <font-awesome-icon
                                v-if="sortField === 'memory'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    sortOrder === 1 ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sortBy('restarts')"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Restarts") }}
                            <font-awesome-icon
                                v-if="sortField === 'restarts'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    sortOrder === 1 ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sortBy('status')"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Status") }}
                            <font-awesome-icon
                                v-if="sortField === 'status'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    sortOrder === 1 ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sortBy('age')"
                            class="pb-4 pt-6 px-6 cursor-pointer"
                        >
                            {{ $t("Age") }}
                            <font-awesome-icon
                                v-if="sortField === 'age'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    sortOrder === 1 ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                    </tr>
                    <tr v-if="pods?.length === 0">
                        <td class="px-6 py-4 border-t" colspan="4">
                            {{ $t("No pods found") }}.
                        </td>
                    </tr>
                    <tr
                        v-else
                        v-for="(pod, index) in sortedPods"
                        :key="'location-' + index"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                    >
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                            >
                                {{ pod.namespace }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                            >
                                {{ pod.name }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4"
                                tabindex="-1"
                            >
                                {{ pod.node }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4"
                                tabindex="-1"
                            >
                                {{ pod.ready }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4"
                                tabindex="-1"
                            >
                                {{ pod.cpu }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4"
                                tabindex="-1"
                            >
                                {{ pod.memory }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4"
                                tabindex="-1"
                            >
                                {{ pod.restarts }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4"
                                tabindex="-1"
                            >
                                {{ pod.status }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4"
                                tabindex="-1"
                            >
                                {{ pod.age }}
                            </a>
                        </td>

                        <td class="border-t text-center">
                            <div
                                class="mr-1 cursor-pointer"
                                @click="refreshPod(pod)"
                            >
                                <CustomIcon name="power" />
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

import SearchFilter from "@/Components/SearchFilter.vue";

export default {
    computed: {
        filteredPods() {
            return this.pods.filter(
                (user) =>
                    user.namespace.includes(this.search) ||
                    user.name.includes(this.search)
            );
        },
        sortedPods() {
            return this.filteredPods.slice().sort((a, b) => {
                const fieldA = a[this.sortField];
                const fieldB = b[this.sortField];

                if ( String("000000000000" + fieldA).slice(-12)  <  String("000000000000" + fieldB).slice(-12) ) {
                    return -1 * this.sortOrder;
                } else if (String("000000000000" + fieldA).slice(-12)  >  String("000000000000" + fieldB).slice(-12) ) {
                    return 1 * this.sortOrder;
                } else {
                    return 0;
                }


            });
        },
    },
    async mounted() {
        try {
            this.getPods();
        } catch (e) {
            console.log(e);
        }
    },
    data() {
        return {
            pods: [],
            systemCloudServer: [],
            search: "",
            serverPoolId: "",
            sortField: "",
            sortOrder: 1, // 1 for ascending, -1 for descending
        };
    },
    components: {
        TextInput,
        MultiSelectInput,
        SearchFilter,
    },
    methods: {
        sortBy(field) {
            if (this.sortField === field) {
                // If already sorting by this field, reverse the order
                this.sortOrder *= -1;
            } else {
                // If sorting by a new field, set the field and default to ascending order
                this.sortField = field;
                this.sortOrder = 1;
            }
        },
        async getPods() {
            this.$store
                .dispatch("systemCloud/getSystemServers", this.$route.params.id)
                .then(async (res) => {
                    const response = await this.$store.dispatch(
                        "serverPools/show",
                        { id: res?.data?.data?.server_pool_id }
                    );
                    this.serverPoolId = res?.data?.data?.server_pool_id;
                    this.pods =
                        response?.data?.data?.data?.pods?.podDetails ?? [];
                });
        },
        reset() {
            this.search = "";
        },
        async refreshPod(pod) {
            this.$swal({
                title: this.$t("Do you want to restart this pod?"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes restart it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    try {
                        const response = await this.$store.dispatch(
                            "serverPools/refreshServerPool",
                            {
                                id: this.serverPoolId,
                                pod_name: pod?.name,
                                namespace: pod?.namespace,
                            }
                        );
                        this.getPods();
                        this.$swal({
                            title: response?.data?.message,
                            icon: "info",
                            button: {
                                text: "OK",
                                className: "btn-info",
                            },
                        });
                    } catch (e) {}
                }
            });
        },
    },
};
</script>

<style scoped></style>
