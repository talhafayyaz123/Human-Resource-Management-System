<template>
    <div class="bg-white rounded-md shadow overflow-x-auto mx-2">
        <div class=" justify-between items-center px-4 pt-2">
            <p class="text-md font-normal leading-normal mt-0 text-cyan-800">
                {{ $t("Cases active open") }}
            </p>
            <div class="flex items-center">
                <p class="mr-12">{{ $t("Assignee") }}:</p>
                <MultiSelectInput
                    :showLabels="false"
                    v-model="form.assignee"
                    :options="users"
                    :multiple="false"
                    label="first_name"
                    trackBy="id"
                    class="pr-6 w-full"
                    moduleName="auth"
                    :search-param-name="'search_string'"
                    :customLabel="customLabel"
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
                            <p class="font-bold">
                                {{ $t("Email") }}
                            </p>
                        </div>
                        <hr />
                    </template>
                    <template #singleLabel="{ props }">
                        <p>
                            {{ props.option.first_name }}
                            {{ props.option.last_name }}
                        </p>
                    </template>
                    <template #option="{ props }">
                        <div
                            class="grid"
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
            </div>
        </div>
        <table class="w-full whitespace-nowrap">
            <tr class="text-left font-bold text-sm">
                <th
                    @click="sort('ticketNumber', 'surveys')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("Number")
                    }}<font-awesome-icon
                        v-if="form.sortBy === 'ticketNumber'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    @click="sort('companyId')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("Company")
                    }}<font-awesome-icon
                        v-if="form.sortBy === 'companyId'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <!-- <th class="pb-4 pt-6 px-6">
                    {{ $t("Company") }}
                </th> -->
                <th
                    @click="sort('title', 'surveys')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("Title")
                    }}<font-awesome-icon
                        v-if="form.sortBy === 'title'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    @click="sort('assignee', 'surveys')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("Assignee")
                    }}<font-awesome-icon
                        v-if="form.sortBy === 'assignee'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                 <th
                    @click="sort('state', 'surveys')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("Status")
                    }}<font-awesome-icon
                        v-if="form.sortBy === 'state'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    @click="sort('updatedAt', 'surveys')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("Updated")
                    }}<font-awesome-icon
                        v-if="form.sortBy === 'updatedAt'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
            </tr>
            <tr
                v-for="ticket in openTicketsData?.data ?? []"
                :key="ticket.id"
                class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm"
                @click.right="
                    (e) => {
                        e.preventDefault();
                        let route = $router.resolve(
                            `/tickets/${ticket.id}/edit`
                        );
                        window.open(route.href, '_blank');
                    }
                "
            >
                <td class="border-t">
                    <a
                        class="flex items-center cursor-pointer px-6 small-table-text py-4 focus:text-indigo-500"
                    >
                        {{ ticket.ticketNumber }}
                    </a>
                </td>
                <td class="border-t">
                    <a
                        class="flex items-center cursor-pointer px-6 small-table-text py-4"
                        tabindex="-1"
                    >
                        {{ ticket.companyName }}
                    </a>
                </td>
                <td class="border-t">
                    <a
                        class="flex items-center cursor-pointer px-6 small-table-text py-4"
                        tabindex="-1"
                    >
                        {{ ticket.title }}
                    </a>
                </td>
                <td class="border-t">
                    <a
                        class="flex items-center cursor-pointer px-6 small-table-text py-4"
                        tabindex="-1"
                    >
                        {{ ticket.assignee }}
                    </a>
                </td>
                 <td class="border-t">
                    <a
                        class="flex items-center cursor-pointer px-6 small-table-text py-4"
                        tabindex="-1"
                    >
                        {{ ticket.status }}
                    </a>
                </td>
                <td class="border-t">
                    <a
                        class="flex items-center cursor-pointer px-6 small-table-text py-4"
                        tabindex="-1"
                    >
                        {{ $dateFormatter(ticket.updatedAt, globalLanguage) }}
                    </a>
                </td>
            </tr>
            <tr v-if="(openTicketsData?.data?.length ?? 0) === 0">
                <td class="px-6 py-4 border-t" colspan="4">
                    {{ $t("No open tickets found") }}
                </td>
            </tr>
        </table>
        <div class="flex justify-center">
            <br />
            <br />
            <pagination
                :limit="10"
                align="center"
                :data="openTicketsData"
                @pagination-change-page="onPageChange"
            ></pagination>
        </div>
    </div>
</template>

<script>
import pagination from "laravel-vue-pagination";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["pageChanged"],
    props: {
        openTicketsData: {
            type: Object,
            default: () => ({}),
        },
    },
    mounted() {
        if (!this.users?.length) {
            this.$store.dispatch("auth/list");
        }
    },
    computed: {
        ...mapGetters("auth", ["users"]),
    },
    components: {
        MultiSelectInput,
        pagination,
    },
    watch: {
        form: {
            handler: function (val) {
                this.$emit("pageChanged", {
                    page: 1,
                    ...val,
                    assignee: val.assignee?.id,
                });
            },
            deep: true,
        },
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        onPageChange(page) {
            this.$emit("pageChanged", { page: page });
        },
    },
    data() {
        return {
            window,
            form: {
                assignee: "",
                sortBy: "",
                sortOrder: "",
            },
            openTickets: [],
        };
    },
};
</script>

<style scoped>
.small-table-text {
    padding: 0px 0px 0px 15px !important;
}
</style>
