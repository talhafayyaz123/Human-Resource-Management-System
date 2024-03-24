<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

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
                <thead>
                    <tr class="text-left font-bold">
                        <th class="">
                            {{ $t("Date") }}/{{ $t("Time") }}
                        </th>
                        <th class="">{{ $t("Process") }}</th>
                        <th class="">{{ $t("Employee") }}</th>
                        <th class="">
                            {{ $t("Personnel Number") }}
                        </th>
                        <th class="">
                            {{ $t("Administrator") }}
                        </th>
                        <th class="">{{ $t("Status") }}</th>
                        <th class="">{{ $t("Action") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="process in processes?.data ?? []"
                        :key="process.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                    >
                        <td class="">
                            <router-link
                                :to="`/change-processes/${process.id}/edit`"
                                class="flex items-center"
                            >
                                {{
                                    $dateFormatter(
                                        process.createdAt,
                                        globalLanguage
                                    )
                                }}
                            </router-link>
                        </td>
                        <td class="">
                            <router-link
                                :to="`/change-processes/${process.id}/edit`"
                                class="flex items-center"
                            >
                                {{ process.process }}
                            </router-link>
                        </td>
                        <td class="">
                            <router-link
                                :to="`/change-processes/${process.id}/edit`"
                                class="flex items-center"
                            >
                                {{ process.employee }}
                            </router-link>
                        </td>
                        <td class="">
                            <router-link
                                :to="`/change-processes/${process.id}/edit`"
                                class="flex items-center"
                            >
                                {{ process.personnelNumber }}
                            </router-link>
                        </td>
                        <td class="">
                            <router-link
                                :to="`/change-processes/${process.id}/edit`"
                                class="flex items-center"
                            >
                                {{ process.administrator }}
                            </router-link>
                        </td>
                        <td class="">
                            <router-link
                                :to="`/change-processes/${process.id}/edit`"
                                class="flex items-center"
                            >
                                {{ process.status }}
                            </router-link>
                        </td>
                        <td class="w-px ">
                            <router-link
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                class="px-1 mr-2"
                                :to="`/change-processes/${process.id}/edit`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                        </td>
                    </tr>
                    <tr v-if="(processes?.data?.length ?? 0) === 0">
                        <td class="px-6 py-4  text-center" colspan="4">
                            {{ $t("No processes found") }}.
                        </td>
                    </tr>
                </tbody>
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
import { debounce } from "@/utils/debounce";
export default {
    computed: {
        ...mapGetters("changeProcesses", ["processes"]),
    },
    components: {
        Pagination,
        SearchFilter,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Management"),
                    to: "/change-processes",
                },
                {
                    text: this.$t("Change Processes"),
                    active: true,
                }
            ],
            page: 1,
            form: {
                search: "",
            },
        };
    },

    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch(
                    "changeProcesses/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("changeProcesses/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        async refresh(bypass) {
            try {
                if (!this.data?.data?.length || bypass)
                    await this.$store.dispatch("changeProcesses/list");
            } catch (e) {}
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
