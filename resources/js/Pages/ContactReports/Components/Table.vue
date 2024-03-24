<template>
    <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
            <th
                @click="sort('reportNumber', 'contactReports')"
                class="pb-4 pt-6 px-6 cursor-pointer"
            >
                {{ $t("Report Number")
                }}<font-awesome-icon
                    v-if="form.sortBy === 'reportNumber'"
                    class="cursor-pointer ml-2"
                    :icon="`fa-solid fa-sort-${
                        form.sortOrder === 'asc' ? 'up' : 'down'
                    }`"
                />
            </th>
            <th
                @click="sort('companyId', 'contactReports')"
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
            <th
                @click="sort('subject', 'contactReports')"
                class="pb-4 pt-6 px-6 cursor-pointer"
            >
                {{ $t("Subject")
                }}<font-awesome-icon
                    v-if="form.sortBy === 'subject'"
                    class="cursor-pointer ml-2"
                    :icon="`fa-solid fa-sort-${
                        form.sortOrder === 'asc' ? 'up' : 'down'
                    }`"
                />
            </th>

            <th
                @click="sort('priority', 'contactReports')"
                class="pb-4 pt-6 px-6 cursor-pointer"
            >
                {{ $t("Priority")
                }}<font-awesome-icon
                    v-if="form.sortBy === 'priority'"
                    class="cursor-pointer ml-2"
                    :icon="`fa-solid fa-sort-${
                        form.sortOrder === 'asc' ? 'up' : 'down'
                    }`"
                />
            </th>
            <th
                @click="sort('ReportCategory.name', 'contactReports')"
                class="pb-4 pt-6 px-6 cursor-pointer"
            >
                {{ $t("Category")
                }}<font-awesome-icon
                    v-if="form.sortBy === 'ReportCategory.name'"
                    class="cursor-pointer ml-2"
                    :icon="`fa-solid fa-sort-${
                        form.sortOrder === 'asc' ? 'up' : 'down'
                    }`"
                />
            </th>
            <th
                @click="sort('initiatedBy', 'contactReports')"
                class="pb-4 pt-6 px-6 cursor-pointer"
            >
                {{ $t("Initiated By")
                }}<font-awesome-icon
                    v-if="form.sortBy === 'initiatedBy'"
                    class="cursor-pointer ml-2"
                    :icon="`fa-solid fa-sort-${
                        form.sortOrder === 'asc' ? 'up' : 'down'
                    }`"
                />
            </th>
            <th class="pb-4 pt-6 px-6 cursor-pointer">
                {{ $t("Source") }}
            </th>
            <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
        </tr>
    
        <tr
            v-for="report in contactReports?.data ?? []"
            :key="report.id"
            @contextmenu.stop.prevent="
                (e) => {
                    e.preventDefault();
                    let route = $router.resolve(`/contact-reports/${report.id}/edit?page=${page}`);
                    window.open(route.href, '_blank');
                }
            "
            class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
            <td class="border-t">
                <a
                    class="flex items-center cursor-pointer px-6 py-4"
                    tabindex="-1"
                >
                    {{ report.reportNumber }}
                </a>
            </td>
            <td class="border-t">
                <a
                    class="flex items-center cursor-pointer px-6 py-4"
                    tabindex="-1"
                >
                    {{ report.company }}
                </a>
            </td>
            <td class="border-t">
                <a
                    class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                >
                    {{ report.subject }}
                </a>
            </td>
            <td class="border-t">
                <a
                    class="flex items-center cursor-pointer px-6 py-4"
                    tabindex="-1"
                >
                    {{ $t(report.priority) }}
                </a>
            </td>
            <td class="border-t">
                <a
                    class="flex items-center cursor-pointer px-6 py-4"
                    tabindex="-1"
                >
                    {{ report.category }}
                </a>
            </td>
            <td class="border-t">
                <a
                    class="flex items-center cursor-pointer px-6 py-4"
                    tabindex="-1"
                >
                    {{ $t(report.initiatedBy) }}
                </a>
            </td>
            <td class="border-t">
                <div class="flex flex-wrap">
                    <span
                        v-for="source in report.contactReportSources"
                        :key="source.id"
                        class="tag"
                    >
                        {{ source.name }}
                    </span>
                </div>
            </td>
            <td class="w-px border-t text-center">
                <a
                    class="cursor-pointer"
                    @click.stop="$router.push(`/contact-reports/${report.id}?page=${page}`)"
                    v-if="$can(`${$route.meta.permission}.show`)"
                >
                    <font-awesome-icon icon="fa-solid fa-eye"/>
                </a>
                <a
                    v-if="$can(`${$route.meta.permission}.edit`)"
                    class="px-1 cursor-pointer px-2"
                    @click.stop="
                        $router.push(`/contact-reports/${report.id}/edit?page=${page}`)
                    "
                >
                    <font-awesome-icon icon="fa-regular fa-pen-to-square"/>
                </a>
                <button
                    v-if="$can(`${$route.meta.permission}.delete`)"
                    @click="$emit('destroy', report.id)"
                >
                    <font-awesome-icon icon="fa-regular fa-trash-can"/>
                </button>
            </td>
        </tr>
        <tr v-if="(contactReports?.data?.length ?? 0) === 0">
            <td class="px-6 py-4 border-t" colspan="8">
                {{ $t("No contact reports found.") }}
            </td>
        </tr>
    </table>
</template>

<script>
export default {
    props: {
        contactReports: {
            type: Object,
            default: () => ({
                data: [],
                links: [],
            }),
        },
        form: {
            type: Object,
            default: () => ({}),
        },
        page: {
            type: Number,
            default: () => ({}),
        },
    },
    data() {
        return { window };
    },
    emits: ["destroy"],
};
</script>

<style scoped></style>
