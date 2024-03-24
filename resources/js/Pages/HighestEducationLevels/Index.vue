<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

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
                    v-for="type in highestEducationLevels?.data"
                    :key="type.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @click="
                        $router.push(
                            `/highest-education-levels/${type.id}/edit`
                        )
                    "
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(`/highest-education-levels/${type.id}/edit`);
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ type.name }}
                        </a>
                    </td>
                    <td class="w-px border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-1 mr-2"
                            :to="`/highest-education-levels/${type.id}/edit`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click.stop="destroy(type.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="highestEducationLevels?.data.length === 0">
                    <td class="px-6 py-4 border-t text-center" colspan="4">
                        {{ $t("No highest education levels found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="highestEducationLevels"
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
import { debounce } from "@/utils/debounce"

import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters("highestEducationLevels", ["highestEducationLevels"]),
    },
    components: {
        Pagination,
        PageHeader,
    },
    mounted() {
        this.refresh();
    },
    async created() {
        this.debouncedFetch = debounce(async (newValue, oldValue)=>{
            try {
                await this.$store.dispatch(
                    "highestEducationLevels/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Highest Education Levels",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Highest Education Levels"),
                btn2Path: "/highest-education-levels/create",
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
    methods: {
        async refresh(bypass) {
            if (!this.highestEducationLevels?.data.length || bypass)
                await this.$store.dispatch("highestEducationLevels/list");
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
                            "highestEducationLevels/destroy",
                            id
                        );
                        this.refresh(true);
                    } catch (e) {}
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("highestEducationLevels/list", {
                page: this.page,
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
