<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="table-responsive">
            <table class="doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('serverId')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Hostname") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'serverId'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('ip')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("IP") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'ip'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('hostname')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Cluster ID") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'hostname'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('serverIp')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Last Healthcheck") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'serverIp'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('serverIp')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Server Uptime") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'serverIp'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('serverIp')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Creation Date") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'serverIp'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('serverIp')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("CPU Load") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'serverIp'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('serverIp')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Disks") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'serverIp'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('serverIp')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("RAM") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'serverIp'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="serverPool in serverPools"
                    :key="serverPool.id"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/infrastructures/server-pools/${serverPool.id}/edit`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ serverPool.hostname }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ serverPool.ip }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ serverPool.cluster_id }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{
                                $dateFormatter(
                                    formatDateLite(
                                        new Date(serverPool.timestamp * 1000),
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
                                formatCompactTimestamp(serverPool?.data?.uptime)
                            }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{
                                $dateFormatter(
                                    formatDateLite(
                                        new Date(
                                            serverPool.creation_date * 1000
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
                            :class="[
                                'flex',
                                'items-center',
                                'cursor-pointer',
                                'px-6',
                                'py-4',
                                'focus:text-indigo-500',
                                (serverPool?.data?.cpu?.percent ?? 0) > 80
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
                            :key="index"
                            :index="'disk-' + index"
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
                                (serverPool?.data?.ram?.percent ?? 0) > 80
                                    ? 'text-red-500'
                                    : '',
                            ]"
                        >
                            {{ serverPool?.data?.ram?.memUsed }}&nbsp;({{
                                serverPool?.data?.ram?.percent
                            }}%)&nbsp;used&nbsp;of&nbsp;{{
                                serverPool?.data?.ram?.memTotal
                            }}
                        </a>
                    </td>
                    <td class="w-px border-t">
                        <div class="flex items-center gap-2">
                            <router-link
                                :to="`/infrastructures/server-pools/${serverPool.id}`"
                                v-if="$can(`${$route.meta.permission}.show`)"
                            >
                                <font-awesome-icon icon="fa-solid fa-eye" />
                            </router-link>
                            <router-link
                                :to="`/infrastructures/server-pools/${serverPool.id}/edit`"
                                v-if="$can(`${$route.meta.permission}.edit`)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click.stop="destroy(serverPool.id)"
                            >
                                <font-awesome-icon icon="fa-regular fa-trash-can" />
                            </button>
                            <button
                                :title="$t('Connect SSH via MobaXTerm')"
                                @click="
                                    openMobaURL(
                                        serverPool.hostname,
                                        serverPool.ip,
                                        serverPool.username,
                                        serverPool.ssh_port
                                    )
                                "
                            >
                                <font-awesome-icon icon="fa-solid fa-terminal" />
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="serverPools.length === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No Server Pool found") }}.
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
                :length="serverPools.length"
                :currentPage="currentPage"
                @paginationInfo="refresh($event.start, $event.end)"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import PageHeader from "@/Components/Layouts/Page-header.vue";
import Pagination from "@/Components/Pagination.vue";
import main from "./Mixins/main.js";
import { mapGetters } from "vuex";

export default {
    mixins: [main],
    computed: {
        ...mapGetters("serverPools", ["serverPools", "count"]),
    },
    components: {
        PageHeader,
        Pagination,
    },
    data() {
        return {
            window,
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
                    active: true,
                }
            ],
            form: {
                sortBy: "serverId",
                sortOrder: "asc",
            },
            page: 1,
            currentPage: 1,
            start: 0,
            perPage: 25,
        };
    },
    mounted() {
        this.refresh(this.start, this.perPage);
    },
    methods: {
        async refresh(start, end) {
            await this.$store.dispatch("serverPools/list", {
                limit_start: start,
                limit_count: end,
            });
        },
        async destroy(id) {
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
                    await this.$store.dispatch("serverPools/destroy", {
                        id: id,
                    });
                    this.refresh(this.start, this.perPage);
                }
            });
        },
    },
};
</script>

<style scoped>
:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}
:deep(.page-link) {
    color: #2957a4;
}
</style>
