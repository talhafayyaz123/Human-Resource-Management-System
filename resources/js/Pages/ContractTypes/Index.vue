<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6"></div>
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
                    v-for="contractType in modifiedContractTypes?.data"
                    :key="contractType.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(`/contract-types/${contractType.id}/edit?page=${page}`);
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ contractType.name }}
                        </a>
                    </td>
                    <td class="w-px border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="mr-2"
                            :to="`/contract-types/${contractType.id}/edit?page=${page}`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click.stop="destroy(contractType.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="contractTypes?.data.length === 0">
                    <td class="px-6 py-4 border-t text-center" colspan="4">
                        {{ $t("No contract types found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="contractTypes"
                @pagination-change-page="changePageOrSearch"
            >
            </pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import SearchFilter from "../../Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";
import { debounce } from "@/utils/debounce";
import Pagination from "laravel-vue-pagination";

export default {
    computed: {
        ...mapGetters("contractTypes", ["contractTypes"]),
        ...mapGetters("documentService", ["documentServices"]),
    },
    components: {
        SearchFilter,
        PageHeader,
        Pagination
    },
    data() {
        return {
            page:1,
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Contract Types"),
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Contract Type"),
                btn2Path: "/contract-types/create",
            },
            modifiedContractTypes: {
                data: [],
                links: [],
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
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch(
                    "contractTypes/list",
                    this.pickBy(this.form)
                );
                this.modifiedContractTypes.data = this.contractTypes?.data?.map(
                    (contractType) => {
                        return {
                            ...contractType,
                            template:
                                this.documentServices?.data?.find(
                                    (document) =>
                                        document.id == contractType.template
                                )?.name ?? contractType.template,
                        };
                    }
                );
            } catch (e) {}
        }, 300);
    },
    watch: {
        "form.search"(...val) {
            // Handle the change in the search property
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            // Handle the change in the type property
            this.debouncedFetch(...val);
        },
        "form.sortOrder"(...val) {
            // Handle the change in the type property
            this.debouncedFetch(...val);
        },
    },
    async mounted() {
        
        if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
        }
        // fetch the document services listing
        try {
            if (!this.documentServices?.data?.length)
                await this.$store.dispatch("documentService/list", {
                    limit_start: 0,
                    limit_count: 25,
                });
        } catch (e) {
            console.log(e);
        }
        // fetch the contract types listing
        this.refresh();
    },
    methods: {
        /**
         * get the contactSource list based on page that was selected with pagination
         * @param {page} the page that contactSource listing should show for
         */
         async changePageOrSearch(page = 1) {
            this.page = page;
            this.refresh();
        },
        /**
         * get the contract types listing
         */
        async refresh() {
            try {
                await this.$store.dispatch("contractTypes/list", {
                    page: this.page,
                    search: this.form.search,
                });
                // map the contract type template to the object found from documentServices listing
                this.modifiedContractTypes.data = this.contractTypes?.data?.map(
                    (contractType) => {
                        return {
                            ...contractType,
                            template:
                                this.documentServices?.data?.find(
                                    (document) =>
                                        document.id == contractType.template
                                )?.name ?? contractType.template,
                        };
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * hit the contract type delete API
         * @param {id} id of contract type to be deleted
         */
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
                        await this.$store.dispatch("contractTypes/destroy", id);
                        this.refresh();
                    } catch (e) {}
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
