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
                    <th style="width: 140px"
                        @click="sort('companyNumber', 'partners')"
                        class="cursor-pointer"
                    >
                        {{ $t("Partner Number")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'companyNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th style="width: 240px"
                        @click="sort('companyName', 'partners')"
                        class="cursor-pointer"
                    >
                        {{ $t("Partner Name")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'companyName'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th style="width: 140px"
                        @click="sort('customerType', 'partners')"
                        class="cursor-pointer"
                    >
                        {{ $t("Type")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'customerType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th style="width: 500px"
                        @click="sort('url', 'partners')"
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
                        @click="sort('vatId', 'partners')"
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
                    <th class="">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="customer in partners?.data ?? []"
                    :key="customer.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                '/partners/' + customer.id
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer  focus:text-indigo-500"
                        >
                            {{ customer.companyNumber }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex cursor-pointer items-center  focus:text-indigo-500"
                        >
                            {{ customer.companyName }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer "
                            tabindex="-1"
                        >
                            {{ customer.type }}
                        </a>
                    </td>
                    <td class="">
                        <a class="cursor-pointer" tabindex="-1">
                            <a
                                :href="
                                    customer.url
                                        ? customerLink(customer.url)
                                        : '#'
                                "
                                :target="
                                    customerLink(customer.url) ? '_blank' : null
                                "
                                class="flex items-center  cursor-pointer hover:text-blue-500 transition duration-200 ease-in-out"
                                tabindex="-1"
                                @click.stop
                            >
                                {{ customer.url }}
                            </a>
                        </a>
                    </td>

                    <td class="">
                        <a
                            class="flex cursor-pointer items-center "
                            tabindex="-1"
                        >
                            {{ customer.vatId }}
                        </a>
                    </td>
                    <td class="w-px  text-center">
                        <div class="flex items-center">
                            <router-link
                                v-if="$can(`${$route.meta.permission}.show`)"
                                :to="`/partners/${customer.id}?page=${page}`"
                            >
                                <font-awesome-icon icon="fa-solid fa-eye" />
                            </router-link>
                            <a
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                class="px-4 cursor-pointer"
                                @click.stop="
                                    $router.push(
                                        `/partners/${customer.id}/edit?page=${page}`
                                    )
                                "
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
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
                <tr v-if="(partners?.data?.length ?? 0) === 0">
                    <td class=" " colspan="4">
                        {{ $t("No partners found.") }}
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="partners"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
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
        ...mapGetters("companies", ["partners"]),
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
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Partner Management"),
                    to: "/partners",
                },
                {
                    text: this.$t("Partner"),
                    active:true
                },
            ],
            optionalItems: {
                csvBtn1Show: true,
                csvBtn1Text: this.$t("Export CSV"),
                csvBtn2Show: true,
                csvBtn2Text: this.$t("Export Latest CSV"),
                isBtn2Show: true,
                btn2Text: this.$t("Create Partners"),
                btn2Path: "/partners/create",
            },
            window,
            page: 1,
            form: {
                search: "",
                customerType: "partner",
                sortBy:
                    localStorage.getItem("sort_customers_column") ??
                    "companyNumber",
                sortOrder:
                    localStorage.getItem("sort_customers_order") ?? "asc",
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
                    "companies/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("companies/list", {
                page: this.page,
                search: this.form.search,
                sortBy: this.form.sortBy,
                sortOrder: this.form.sortOrder,
                customerType: "partner",
            });
        },
        async refresh() {
            if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
            }
            await this.$store.dispatch("companies/list", {
                ...this.pickBy(this.form),
                page: this.page,
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
                    await this.$store.dispatch("companies/destroy", id);
                    this.refresh();
                }
            });
        },
        async downloadCSV() {
            try {
                await this.$store.dispatch("companies/download", {
                    type: "partner",
                });
            } catch (e) {}
        },
        async downloadLatestCsv() {
            try {
                await this.$store.dispatch("companies/downloadLatestCsv", {
                    type: "partner",
                });
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
