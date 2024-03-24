<template>
    <div class="main-grid mx-2">
        <div class="grid-row">
            <div
                class="card rounded shadow p-2 flex flex-col items-center justify-center"
            >
                <div class="flex justify-center">
                    <font-awesome-icon
                        class="self-center mr-2"
                        icon="fas fa-clock"
                        color="green"
                    />
                    <div>
                        <p class="mb-0 font-small">{{ $t("Cases") }}</p>
                    </div>
                </div>
                <p class="text-center text-large">
                    {{ casesStatsData?.openAndNewTicketsCount ?? 0 }}
                </p>
            </div>
            <div
                class="card rounded shadow p-2 flex flex-col items-center justify-center"
            >
                <p class="text-center font-small">{{ $t("Incidents") }}</p>
                <p class="text-center text-large">
                    {{ casesStatsData?.incidentTicketsCount ?? 0 }}
                </p>
            </div>
        </div>
        <div class="card rounded shadow">
            <table class="w-full whitespace-nowrap table-layout-auto">
                <tr class="text-left font-bold text-sm">
                    <th @click="sortFront('name')" class="pb-4 pt-6 px-6">
                        {{ $t("Assignee") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th @click="sortFront('count')" class="pb-4 pt-6 px-6">
                        {{ $t("Open Tickets")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'count'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                </tr>
                <tr
                    v-for="assignee in assignees"
                    :key="assignee.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm"
                >
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer small-table-text px-6 focus:text-indigo-500"
                        >
                            {{ assignee.name }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer small-table-text px-6"
                            tabindex="-1"
                        >
                            {{ assignee.count }}
                        </a>
                    </td>
                </tr>
                <tr v-if="(assignees?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No assignees found") }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="grid-row">
            <div
                class="card rounded shadow p-2 flex flex-col items-center justify-center"
            >
                <div class="flex justify-center">
                    <font-awesome-icon
                        class="self-center mr-2"
                        icon="fas fa-clock"
                        color="green"
                    />
                    <p class="font-small">{{ $t("New") }}</p>
                </div>
                <p class="text-center text-large text-green-700">
                    {{ casesStatsData?.newTicketsTotal ?? 0 }}
                </p>
            </div>
            <div
                class="card rounded shadow p-2 flex flex-col items-center justify-center"
            >
                <div class="flex justify-center">
                    <font-awesome-icon
                        class="self-center mr-2"
                        icon="fas fa-clock"
                        color="green"
                    />
                    <p class="font-small">
                        {{ $t("Cases updated in last 15 mins") }}
                    </p>
                </div>
                <p class="text-center text-large">
                    {{ casesStatsData?.lastUpdatedRecords ?? 0 }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        casesStatsData: {
            type: Object,
            default: () => ({}),
        },
    },
    watch: {
        casesStatsData: {
            handler: async function () {
                try {
                    let response = null;
                    let assigneeIds = Object.keys(
                        this.casesStatsData?.assigneeGroupTicketCount ?? {}
                    ).filter((id) => !!id);
                    if (!assigneeIds.some((id) => !id) && !!assigneeIds.length)
                        response = await this.$store.dispatch("auth/show", {
                            id: assigneeIds,
                        });
                    this.assignees.splice(0, this.assignees.length);
                    (response?.data ?? []).forEach((assignee) => {
                        this.assignees.push({
                            id: assignee.id,
                            name:
                                (assignee?.first_name ?? "") +
                                " " +
                                (assignee?.last_name ?? ""),
                            count: (this.casesStatsData
                                ?.assigneeGroupTicketCount ?? {})?.[
                                assignee.id ?? 0
                            ],
                        });
                    });
                    this.sortArrayOfObjects(this.assignees, "name", "asc");
                } catch (e) {
                    console.log(e);
                }
            },
            deep: true,
        },
    },
    data() {
        return {
            form: {
                sortOrder: "",
                sortBy: "",
            },
            assignees: [],
        };
    },

    methods: {
        sortFront(key) {
            this.form.sortBy = key;
            this.form.sortOrder = this.form.sortOrder == "asc" ? "desc" : "asc";
            this.assignees = this.sortArrayOfObjects(
                this.assignees,
                this.form.sortBy,
                this.form.sortOrder
            );
        },

        sortArrayOfObjects(arr, property, sortOrder) {
            return arr.sort((a, b) => {
                const aValue = a[property];
                const bValue = b[property];

                if (sortOrder === "asc") {
                    return aValue < bValue ? -1 : aValue > bValue ? 1 : 0;
                } else if (sortOrder === "desc") {
                    return bValue < aValue ? -1 : bValue > aValue ? 1 : 0;
                } else {
                    return aValue < bValue ? -1 : aValue > bValue ? 1 : 0;
                }
            });
        },
    },
};
</script>

<style scoped>
.small-table-text {
    padding: 0px 0px 0px 15px !important;
}

.main-grid {
    display: grid;
    grid-template-columns: 20% 60% 20%;
}

.grid-row {
    display: grid;
    grid-template-rows: 50% 50%;
}

.text-large {
    font-size: 3rem;
}

.table-layout-auto {
    table-layout: auto;
}

.font-small {
    font-size: 0.8rem;
}
</style>
