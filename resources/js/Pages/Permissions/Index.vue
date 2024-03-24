<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />
        <div class="flex items-center justify-end mb-6">
            <div class="flex">
                <div class="form-group ml-2 min-w-[100px]">
                    <select-input
                        :label="$t('Per Page')"
                        @input="
                            refresh(true, 0, $event.target.value);
                            currentPage = 1;
                        "
                        v-model="perPage"
                        name=""
                        id=""
                    >
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select-input>
                </div>
            </div>
            <div class="form-group w-full lg:w-1/4">
                <search-filter
                    class="self-center ml-5"
                    v-model="form.search"
                    @reset="reset"
                    :isFilter="false"
                >
                </search-filter>
            </div>
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Grouping") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Tenant") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Company") }}</th>
                    <th class="pb-4 pt-6 px-6 text-center">
                        {{ $t("Actions") }}
                    </th>
                </tr>
                <tr
                    v-for="permission in permissions ?? []"
                    :key="permission.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(`/permissions/${permission.id}/edit`);
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ permission.title }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ permission.grouping }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ permission.tenant }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ permission.company }}
                        </a>
                    </td>
                    <td class="w-px border-t text-center">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="px-1 mr-2"
                            :to="`/permissions/${permission.id}/edit`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click="destroy(permission.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="(permissions?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No permissions found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :count="count"
                :perPage="perPage"
                :start="start"
                :length="permissions.length"
                :currentPage="currentPage"
                @paginationInfo="refresh(true, $event.start, $event.end)"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import Pagination from "../../Components/Pagination.vue";
import Icon from "../../Components/Icon.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { mapGetters } from "vuex";
import SearchFilter from "@/Components/SearchFilter.vue";
import { debounce } from "../../utils/debounce";

export default {
    components: {
        SearchFilter,
        Icon,
        Pagination,
        MultiSelectInput,
        PageHeader,
        SelectInput
    },
    computed: {
        ...mapGetters("permissions", ["permissions", "count"]),
    },
    props: { filters: Object, roles: Object, can: Object },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Permissions",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Permission"),
                btn2Path: "/permissions/create",
            },

            currentPage: 1,
            start: 0,
            perPage: 25,
            form: {
                search: "",
            },
            window
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
    },
    mounted() {
        this.refresh(false, this.start, this.perPage);
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.refresh(true, this.start, this.perPage);
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    methods: {
        async changePageOrSearch(page = 1) {
            await this.$store.dispatch("permissions/list", {
                limit_start: 0,
                limit_count: 100,
                search_string: this.form.search
            });
        },
        async refresh(bypass, start, end) {
            if (!this.permissions?.data?.length || bypass)
                await this.$store.dispatch("permissions/list", {
                    limit_start: start,
                    limit_count: end,
                    search_string: this.form.search
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
                    await this.$store.dispatch("permissions/destroy", {
                        id: id,
                    });
                    this.refresh(true, this.start, this.perPage);
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
.table-layout-fixed {
    table-layout: fixed;
}
:deep(.page-item.active .page-link) {
    background-color: #2957a4;
    border-color: #2957a4;
}
:deep(.page-link) {
    color: #2957a4;
}
</style>
