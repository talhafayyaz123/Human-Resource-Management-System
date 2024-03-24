<template>
    <div>
        <div class="bg-white rounded-md shadow">
            <table class="w-full whitespace-nowrap table-layout-fixed">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Status") }}</th>
                    <th class="pb-4 pt-6 px-6">
                        {{ $t("Planned Start Date") }}
                    </th>
                    <th class="pb-4 pt-6 px-6">
                        {{ $t("Planned Finished Date") }}
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Assign User") }}</th>
                </tr>
                <tr
                    v-for="task in backlog?.data ?? []"
                    :key="task.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500 overflow-text-backlog"
                        >
                            {{ task.name }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4"
                            tabindex="-1"
                        >
                            {{ task.status }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $dateFormatter(task.plannedStartDate, globalLanguage) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $dateFormatter(task.plannedFinishedDate, globalLanguage) }}
                        </a>
                    </td>
                    <td class="w-px border-t p-2">
                        <MultiSelectInput
                            @update:modelValue="removeFromBacklog(task, $event)"
                            :showLabels="false"
                            :options="users"
                            :multiple="false"
                            label="first_name"
                            trackBy="id"
                            moduleName="auth"
                            :query="{
                                limit_start: 0,
                                limit_count: 25,
                                type: 'employee',
                            }"
                            :search-param-name="'search_string'"
                        >
                            <template #beforeList>
                                <div
                                    class="grid p-2 gap-2"
                                    style="grid-template-columns: 25% 25% 50%"
                                >
                                    <p class="font-bold">
                                        {{ $t("First Name") }}
                                    </p>
                                    <p class="font-bold">
                                        {{ $t("Last Name") }}
                                    </p>
                                    <p class="font-bold">{{ $t("Email") }}</p>
                                </div>
                                <hr />
                            </template>
                            <template #singleLabel="{ props }">
                                {{ props.option.first_name }}
                                {{ props.option.last_name }}
                            </template>
                            <template #option="{ props }">
                                <div
                                    class="grid gap-2"
                                    style="grid-template-columns: 25% 25% 50%"
                                >
                                    <p class="overflow-text-users-table">
                                        {{ props.option.first_name }}
                                    </p>
                                    <p class="overflow-text-users-table">
                                        {{ props.option.last_name }}
                                    </p>
                                    <p class="overflow-text-users-table">
                                        {{ props.option.email }}
                                    </p>
                                </div>
                            </template>
                        </MultiSelectInput>
                    </td>
                </tr>
                <tr v-if="(backlog?.data?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("Backlog is empty") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="backlog"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import SelectInput from "@/Components/SelectInput.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters("auth", ["users"]),
    },
    mounted() {
        this.refresh();
    },
    components: {
        MultiSelectInput,
        Pagination,
        SelectInput,
        FlashMessages,
    },
    data() {
        return {
            page: 1,
            backlog: {},
        };
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("tasks/backlog", {
                page: this.page,
            });
        },
        resetFlashAndErrors() {
            this.$page.props.flash = {};
            this.$page.props.errors = {};
        },
        async refresh() {
            try {
                const response = await this.$store.dispatch("tasks/backlog");
                this.backlog = response?.data?.backlog ?? {};
                this.milestones = response?.data?.milestones ?? [];
                this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "employee",
                });
            } catch (e) {}
        },
        async removeFromBacklog(task, event) {
            const modifiedTask = { ...task };
            modifiedTask.employeeId = event?.id;
            try {
                await this.$store.dispatch("tasks/update", {
                    id: task.id,
                    data: { ...modifiedTask },
                });
                await this.$store.dispatch("tasks/list");
                await this.$store.dispatch(
                    "projectManagement/projectData",
                    this.$route.query.id
                );
                this.refresh();
            } catch (e) {}
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
.overflow-text-backlog {
    width: 35ch;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: block;
}
.overflow-text-backlog:hover {
    text-overflow: clip;
    white-space: normal;
    word-break: break-all;
}
.table-layout-fixed {
    table-layout: fixed;
}
</style>
