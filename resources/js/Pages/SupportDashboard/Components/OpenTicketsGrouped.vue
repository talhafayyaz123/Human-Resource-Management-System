<template>
    <div class="bg-white rounded-md shadow overflow-x-auto mt-2 mx-2">
        <div class="flex justify-between items-center px-4 pt-2 mb-4">
            <p class="text-md font-normal leading-normal mt-0 text-cyan-800">
                {{ $t("Cases active open (grouped)") }}
            </p>
            <div class="flex w-1/2 items-center">
                <p class="w-1/2">{{ $t("Grouped By") }}:</p>
                <MultiSelectInput
                    class="pr-6 w-full"
                    v-model="form.groupedBy"
                    :key="form.groupedBy"
                    :options="options"
                    :multiple="false"
                    label="label"
                    trackBy="value"
                />
            </div>
        </div>
        <table class="whitespace-nowrap">
            <tr class="text-left font-bold text-sm">
                <th
                    class="pb-4 pt-6 px-6 cursor-pointer"
                    @click="groupSort('ticketNumber')"
                >
                    {{ $t("Number") }}
                    <font-awesome-icon
                        v-if="form.groupSortBy === 'ticketNumber'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.groupSortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="pb-4 pt-6 need-attention-column cursor-pointer"
                >
                    {{ $t("Needs Attention") }}
                </th>
                <th
                    class="pb-4 pt-6 px-6 short-description-column cursor-pointer"
                    @click="groupSort('title')"
                >
                    {{ $t("Short Description") }}
                    <font-awesome-icon
                        v-if="form.groupSortBy === 'title'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.groupSortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="pb-4 pt-6 px-6 cursor-pointer"
                    @click="groupSort('state')"
                >
                    {{ $t("Status") }}
                    <font-awesome-icon
                        v-if="form.groupSortBy === 'state'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.groupSortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="pb-4 pt-6 px-6 cursor-pointer"
                    @click="groupSort('updatedAt')"
                >
                    {{ $t("Updated") }}
                    <font-awesome-icon
                        v-if="form.groupSortBy === 'updatedAt'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.groupSortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    class="pb-4 pt-6 px-6 cursor-pointer"
                    @click="groupSort('priority')"
                >
                    {{ $t("Priority") }}
                    <font-awesome-icon
                        v-if="form.groupSortBy === 'priority'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.groupSortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
            </tr>
        </table>
        <div class="hover:bg-gray-100 focus-within:bg-gray-100">
            <div class="tabs">
                <div
                    v-for="(value, key) in openTicketsGroupedData"
                    class="relative tab"
                    :key="key"
                >
                    <input
                        class="tab-checkbox"
                        type="checkbox"
                        :id="'ticket-accordion-' + key"
                        :checked="false"
                    />
                    <label
                        style="background-color: #e5e9ec"
                        class="tab-label styles-configurator-tab-label flex p-1 text-sm"
                        :for="'ticket-accordion-' + key"
                    >
                        <font-awesome-icon
                            class="cursor-pointer self-center mr-2 text-xs"
                            icon="fas fa-chevron-right"
                        />
                        <p class="self-center">
                            {{ value?.[0]?.companyName ?? "" }}
                        </p>
                    </label>
                    <div class="tab-content border overflow-auto">
                        <table class="whitespace-nowrap">
                            <tr
                                v-for="ticket in value ?? []"
                                :key="ticket.id"
                                class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm"
                            >
                                <td class="border-t">
                                    <a
                                        class="flex items-center cursor-pointer focus:text-indigo-500 small-table-text"
                                    >
                                        {{ ticket.ticketNumber }}
                                    </a>
                                </td>
                                <td class="border-t need-attention-column">
                                    <a
                                        class="flex items-center cursor-pointer small-table-text"
                                        tabindex="-1"
                                    >
                                        {{
                                            ticket.status === "resolved"
                                                ? $t("No")
                                                : $t("Yes")
                                        }}
                                    </a>
                                </td>
                                <td class="border-t short-description-column">
                                    <a
                                        class="flex items-center cursor-pointer description-box small-table-text"
                                        tabindex="-1"
                                    >
                                        {{ ticket.title }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        class="flex items-center cursor-pointer small-table-text"
                                        tabindex="-1"
                                    >
                                        {{ ticket.status }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        class="flex items-center cursor-pointer small-table-text"
                                        tabindex="-1"
                                    >
                                        {{
                                            $dateFormatter(
                                                ticket.updatedAt,
                                                globalLanguage
                                            )
                                        }}
                                    </a>
                                </td>
                                <td class="border-t">
                                    <a
                                        class="flex items-center cursor-pointer small-table-text"
                                        tabindex="-1"
                                    >
                                        {{ ticket.priority }}
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="(value?.length ?? 0) === 0">
                                <td class="px-6 py-4 border-t" colspan="4">
                                    {{ $t("No open tickets found") }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MultiSelectInput from "@/Components/MultiSelectInput.vue";

export default {
    props: {
        openTicketsGroupedData: {
            type: Object,
            default: () => ({}),
        },
    },
    components: {
        MultiSelectInput,
    },
     watch: {
        form: {
            handler: function (val) {
                this.$emit("pageChanged", {
                    page: 1,
                    ...val
                });
            },
            deep: true,
        },
    },
   methods: {
        groupSort(columnName, tableName = "") {
            try {
                this.form.groupSortBy = columnName;
                this.form.groupSortOrder =
                    this.form.groupSortOrder === "asc" ? "desc" : "asc";
                const key = `sort_${tableName}`;
                localStorage.setItem(`${key}_column`, columnName); // Store the column name
                localStorage.setItem(`${key}_order`, this.form.sortOrder); // Store the sort order
            } catch (e) {
                console.log(e);
            }
        },
    },
    data() {
        return {
            form: {
                groupedBy: {
                    label: "Company",
                    value: "company",
                },
                groupSortBy: "",
                groupSortOrder: "",
            },
            openTickets: [],
            options: [
                {
                    label: "Company",
                    value: "company",
                },
            ],
        };
    },
};
</script>
<style lang="scss" scoped>
.small-table-text {
    padding: 0px 0px 0px 0px !important;
}

.description-box {
    width: 250px; /* Set a fixed width or adjust as needed */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
}

th,
td {
    width: 100px;
    padding: 0;
    margin: 0;
}

.short-description-column {
    min-width: 250px;
}
.need-attention-column {
    min-width: 150px;
}

li {
    list-style: none;
}
.tabs {
    overflow: hidden;
}

.tab {
    width: 100%;
    color: black;
    overflow: hidden;
    &-label {
        display: flex;
        cursor: pointer;
    }
    &-content {
        display: none;
        max-height: 0;
        color: black;
        transition: all 0.35s;
    }
    &-close {
        display: flex;
        justify-content: flex-end;
        font-size: 0.75em;
        cursor: pointer;
        &:hover {
        }
    }
}
.styles-configurator-tab-label::after {
    margin-top: 0px !important;
}

// :checked
input:checked {
    ~ .tab-content {
        display: block;
        max-height: 100vh;
    }

    ~ .tab-label .fa-chevron-right {
        transform: rotate(90deg);
    }
}

.inner-accordion-label::after {
    transform: rotate(90deg);
    transform-origin: center;
}

input[class="tab-checkbox"] {
    position: absolute;
    opacity: 0;
    z-index: -1;
}

.inner-accordion {
    display: none;
}
</style>
