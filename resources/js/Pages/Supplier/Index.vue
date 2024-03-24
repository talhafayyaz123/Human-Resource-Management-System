<template>
    <div>
        <PageHeader
            :items="breadcrumbItems"
            :optionalItems="optionalItems"
            @csvBtn1="downloadCSV"
            @csvBtn2="downloadLatestCsv"
        />

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
            <table class="doc-table">
                <tr class="text-left">
                    <th style="width: 240px"
                        @click="sort('supplierNumber', 'suppliers')"
                        class="cursor-pointer"
                    >
                        {{ $t("Supplier Number")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'supplierNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th style="width: 300px"
                        @click="sort('supplierName', 'suppliers')"
                        class="cursor-pointer"
                    >
                        {{ $t("Supplier Name")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'supplierName'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th style="width: 400px"
                        @click="sort('url', 'suppliers')"
                        class="cursor-pointer"
                    >
                        {{ $t("URL")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'url'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('vatId', 'suppliers')"
                        class="cursor-pointer"
                    >
                        {{ $t("VAT")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'vatId'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="text-center">
                        {{ $t("Actions") }}
                    </th>
                </tr>
                <tr
                    v-for="customer in suppliers?.data ?? []"
                    :key="customer.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/suppliers/${customer.id}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer  focus:text-indigo-500"
                        >
                            {{ customer.supplierNumber }}
                            <!--implement this on weekend
                            <icon
                                v-if="customer.deleted_at"
                                name="trash"
                                class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                            /> -->
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer  focus:text-indigo-500"
                        >
                            {{ customer.supplierName }}
                            <!--implement this on weekend
                            <icon
                                v-if="customer.deleted_at"
                                name="trash"
                                class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                            /> -->
                        </a>
                    </td>

                    <td class="">
                        <a class="" tabindex="-1">
                            <a
                                :href="
                                    customer.url
                                        ? customerLink(customer.url)
                                        : '#'
                                "
                                :target="
                                    customerLink(customer.url) ? '_blank' : null
                                "
                                class="flex items-center cursor-pointer  cursor-pointer hover:text-blue-500 transition duration-200 ease-in-out"
                                tabindex="-1"
                                @click.stop
                            >
                                {{ customer.url }}
                            </a>
                        </a>
                    </td>

                    <td class="">
                        <a
                            class="flex items-center cursor-pointer "
                            tabindex="-1"
                        >
                            {{ customer.vatId }}
                        </a>
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center">
                            <a
                                class="cursor-pointer"
                                v-if="$can(`${$route.meta.permission}.show`)"
                                @click.stop="
                                    $router.push(`/suppliers/${customer.id}?page=${page}`)
                                "
                            >
                                <font-awesome-icon icon="fa-solid fa-eye" />
                            </a>
                            <a
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                class="px-4"
                                @click.stop="
                                    $router.push(
                                        `/suppliers/${customer.id}/edit?page=${page}`
                                    )
                                "
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                    class="cursor-pointer"
                                ></font-awesome-icon>
                            </a>
                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click.stop="destroy(customer.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="(suppliers?.data?.length ?? 0) === 0">
                    <td class=" " colspan="4">
                        {{ $t("No suppliers found.") }}
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="suppliers"
                @pagination-change-page="changePageOrSearch"
            >
            </pagination>
            <br />
            <br />
        </div>
    </div>
</template>

<script>
import Icon from "../../Components/Icon.vue";

import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";
import SearchFilter from "../../Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce";
export default {
    computed: {
        ...mapGetters("suppliers", ["suppliers"]),
    },
    mounted() {
        this.refresh();
    },
    components: {
        Icon,
        Pagination,
        SearchFilter,
        PageHeader,
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
                    text: "Master Data",
                    to: "/suppliers",
                },
                {
                    text: "Suppliers",
                    active: true,
                },
            ],
            optionalItems: {
                csvBtn1Show: true,
                csvBtn1Text: this.$t("Export CSV"),
                csvBtn2Show: true,
                csvBtn2Text: this.$t("Export Latest CSV"),
                isBtn2Show: true,
                btn2Text: this.$t("Create Supplier"),
                btn2Path: "/suppliers/create",
            },
            page: 1,
            window,
            form: {
                search: "",
                sortBy:
                    localStorage.getItem("sort_suppliers_column") ??
                    "supplierNumber",
                sortOrder:
                    localStorage.getItem("sort_suppliers_order") ?? "asc",
            },
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortOrder"(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                await this.$store.dispatch(
                    "suppliers/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("suppliers/list", {
                page: this.page,
                search: this.form.search,
                sortBy: this.form.sortBy,
                sortOrder: this.form.sortOrder,
            });
        },
        async refresh() {
            if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
            }
            await this.$store.dispatch("suppliers/list", {
                    ...this.pickBy({
                        ...this.form,
                        page: this.page,
                    }),
                });
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
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
                    await this.$store.dispatch("suppliers/destroy", id);
                    this.refresh();
                }
            });
        },
        async downloadCSV() {
            try {
                await this.$store.dispatch("suppliers/download");
            } catch (e) {}
        },
        async downloadLatestCsv() {
            try {
                await this.$store.dispatch("suppliers/downloadLatestCsv");
            } catch (e) {}
        },
        customerLink(incommingUrl) {
            if (incommingUrl) {
                let url = "";
                url = incommingUrl.trim();
                if (!/^https?:\/\//i.test(url)) {
                    url = `https://${url}`;
                }
                return url;
            }
            return incommingUrl;
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
