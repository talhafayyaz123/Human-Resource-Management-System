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
                    <th
                        @click="sort('subType')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Sub Type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'subType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('instanceType')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Instance Type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'instanceType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="distributedFilesystem in distributedFilesystems.data"
                    :key="distributedFilesystem.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(`/distributed-filesystem/${distributedFilesystem.id}/edit`);
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <router-link
                            :to="`/distributed-filesystem/${distributedFilesystem.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ distributedFilesystem.name }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/distributed-filesystem/${distributedFilesystem.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ distributedFilesystem.subType }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/distributed-filesystem/${distributedFilesystem.id}/edit`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ distributedFilesystem.instanceType }}
                        </router-link>
                    </td>
                    <td class="w-px border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-1 mr-2"
                            :to="`/distributed-filesystem/${distributedFilesystem.id}/edit`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click="destroy(distributedFilesystem.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="distributedFilesystems.data.length === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No distributed filesystems found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="distributedFilesystems"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";
import { debounce } from "@/utils/debounce"

export default {
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Distributed Filesystem",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Distributed Filesystem"),
                btn2Path: "/distributed-filesystem/create",
            },
            page: 1,
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
                    "distributedFilesystem/list",
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
    computed: {
        ...mapGetters("distributedFilesystem", ["distributedFilesystems"]),
    },
    components: {
        Pagination,
        PageHeader,
    },
    mounted() {
        this.fetchData();
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("distributedFilesystem/list", {
                page: this.page,
            });
        },
        async fetchData() {
            await this.$store.dispatch("distributedFilesystem/list");
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
                            "distributedFilesystem/destroy",
                            id
                        );
                        this.fetchData();
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
