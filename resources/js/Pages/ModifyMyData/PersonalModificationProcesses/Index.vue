<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                :isFilter="false"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">
                        {{ $t("Date") }}/{{ $t("Time") }}
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Process") }}</th>
                    <th class="pb-4 pt-6 px-6">
                        {{ $t("Person in charge") }}
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Status") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="process in processes?.data ?? []"
                    :key="process.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t">
                        <router-link
                            :to="`/personal-modification-processes/${process.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{
                                $dateFormatter(
                                    process.createdAt,
                                    globalLanguage
                                )
                            }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/personal-modification-processes/${process.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ $dashedToRegularFormat(process.process) }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/personal-modification-processes/${process.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ process.personInCharge }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/personal-modification-processes/${process.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ process.status }}
                        </router-link>
                    </td>
                    <td class="w-px border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="mr-2"
                            :to="`/personal-modification-processes/${process.id}/edit`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click="destroy(process.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="(processes?.data?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t text-center" colspan="4">
                        {{ $t("No processes found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="processes"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";
import SearchFilter from "@/Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters("personalModificationProcesses", ["processes"]),
    },
    components: {
        Pagination,
        SearchFilter,
        PageHeader
    },
    data() {
        return {
            page: 1,
            form: {
                search: "",
            },
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Modification Processes"),
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Personal Modification Process"),
                btn2Path: "/personal-modification-processes/create",
            },
        };
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        await this.$store.dispatch(
                            "personalModificationProcesses/list",
                            this.pickBy(this.form)
                        );
                    } catch (e) {}
                }, 150)();
            },
        },
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("personalModificationProcesses/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        async refresh(bypass) {
            try {
                if (!this.data?.data?.length || bypass)
                    await this.$store.dispatch(
                        "personalModificationProcesses/list"
                    );
            } catch (e) {}
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
                    try {
                        await this.$store.dispatch(
                            "personalModificationProcesses/destroy",
                            id
                        );
                        this.refresh(true);
                    } catch (e) {}
                }
            });
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
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
