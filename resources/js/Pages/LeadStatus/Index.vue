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
                    <th
                        @click="sort('name')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Name") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="status in leadStatus?.data"
                    :key="status.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(`/lead-status/${status.id}/edit?page=${page}`);
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ status.name }}
                        </a>
                    </td>
                    <td class="w-px border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="mr-2"
                            :to="`/lead-status/${status.id}/edit?page=${page}`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click.stop="destroy(status.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="status?.data.length === 0">
                    <td class="px-6 py-4 border-t text-center" colspan="4">
                        {{ $t("No Lead Status found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div class="flex justify-center my-5">
            <pagination
                :limit="10"
                align="center"
                :data="leadStatus ?? []"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
        </div>
    </div>
</template>

<script>
import SearchFilter from "../../Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";
import { debounce } from "@/utils/debounce"
import Pagination from "laravel-vue-pagination";

export default {
    computed: {
        ...mapGetters("leadStatus", ["leadStatus"]),
    },
    components: {
        SearchFilter,
        PageHeader,
        Pagination
    },
    props: {
        filters: Object,
        can: Object,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Lead Status",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Lead Status"),
                btn2Path: "/lead-status/create",
            },
            form: {
                search: "",
                sortBy: "",
                sortOrder: "",
            },
            window
        };
    },
    async created() {
        this.debouncedFetch = debounce(async (newValue, oldValue)=>{
            try {
                await this.$store.dispatch(
                    "leadStatus/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    watch: {
        'form.search'(...val) {
            // Handle the change in the search property
            this.debouncedFetch(...val);
        },
        'form.sortBy'(...val) {
            // Handle the change in the type property
            this.debouncedFetch(...val);
        },
        'form.sortOrder'(...val) {
            // Handle the change in the type property
            this.debouncedFetch(...val);
        },
    },
    mounted() {
        let isPaginate = false;
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
            isPaginate = true;
        }
        this.refresh(isPaginate);
    },
    methods: {
        /**
         * get the contactSource list based on page that was selected with pagination
         * @param {page} the page that contactSource listing should show for
         */
         async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("leadStatus/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        async refresh(bypass) {
            if (!this.leadStatus?.data.length || bypass)
                await this.$store.dispatch("leadStatus/list", {
                    page: this.page,
                    search: this.form.search,
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
                    try {
                        await this.$store.dispatch("leadStatus/destroy", id);
                        await this.refresh(true);
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
