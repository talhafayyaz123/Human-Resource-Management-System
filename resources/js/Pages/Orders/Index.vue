<template>
    <div>
        <PageHeader
            :items="breadcrumbItems"
            :optionalItems="optionalItems"
        />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('orderNumber')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Order Number")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'orderNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('Company.companyName')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Customer")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'Company.companyName'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                     <th
                        @click="sort('createdAt')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Order Creation Date")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'createdAt'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('status')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Status")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'status'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        class="pb-4 pt-6 px-6 cursor-pointer"
                        @click="sort('identifier')"
                    >
                        {{ $t("Identifier") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'identifier'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('Project.name')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Project")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'Project.name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('nettoTotal')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Netto Total")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'nettoTotal'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="order in orders?.data ?? []"
                    :key="order.id"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/partner-management/orders/${order.id}/edit?page${page}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ order?.orderNumber }}
                        </a>
                    </td>
                   
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4 focus:text-indigo-500"
                        >
                            {{ order?.receiverName }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                        {{
                                $dateFormatter(
                                    order?.createdAt,
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ order?.status }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ order?.identifier }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                            {{ order?.projectName }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            class="flex items-center cursor-pointer px-6 py-4"
                            tabindex="-1"
                        >
                        {{
                            $formatter(
                                order?.nettoTotal?.toFixed(2),
                                globalLanguage
                            )
                        }}
                        </a>
                    </td>
                    <td @click.stop class="w-px border-t">
                        <div class="flex items-center gap-x-2">
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.show`)"
                                :to="`/partner-management/orders/${order.id}?page=${page}`"
                            >
                                <font-awesome-icon icon="fa-solid fa-eye" />
                            </router-link>
                            <router-link
                                @click.stop=""
                                v-if="$can(`${$route.meta.permission}.edit`)"
                                :to="`/partner-management/orders/${order.id}/edit?page=${page}`"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </router-link>
                            <button
                                v-if="$can(`${$route.meta.permission}.delete`)"
                                @click.stop="destroy(order.id)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="(orders?.data?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No orders found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="orders ?? []"
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
import SearchFilter from "../../Components/SearchFilter.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce";
export default {
    components: {
        MultiSelectInput,
        Icon,
        Pagination,
        SearchFilter,
        SelectInput,
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
                    to: "/partner-management/orders",
                },
                {
                    text: "Partner Orders",
                    active: true,
                },
            ],
            optionalItems: {
               isBtn2Show: true,
                btn2Text: this.$t("Create Order"),
                btn2Path: "/partner-management/orders/create",
            },
            window,
            orders: [],
            processing: false,
            toggleModal: false,
            page: 1,
            form: {
                search: "",
                company: "",
                type: "",
                sortBy:
                    "",
                sortOrder:
                    "",
            },
            companies: [],
        };
    },
    watch: {
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.type"(...val) {
            this.debouncedFetch(...val);
        },
        "form.company"(...val) {
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
                await this.$store.dispatch("orders/list", {
                    ...this.form,
                    company: this.form.company?.id,
                }).then((res) => {
                    this.orders = res?.data?.partnerOrder ?? [];
                });
            } catch (e) {}
        }, 300);
    },
    async mounted() {
        this.refresh();
    },
    methods: {
       
        async refresh(bypass) {
            if(this.$route.query.page){
                    this.page=this.$route.query.page;
                    this.$router.replace({'query': null});
            }
            try {
                this.$store.dispatch("orders/list", {
                    ...this.form,
                    page: this.page,
                }).then((res) => {
                    this.orders = res?.data?.partnerOrder ?? [];
                });
                this.$store
                    .dispatch("companies/list", {
                        page: 1,
                        perPage: 25,
                    })
                    .then((res) => {
                        this.companies = res?.data?.data ?? [];
                    });
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
                    await this.$store.dispatch("orders/destroy", id);
                    this.refresh(true);
                }
            });
        },
        companiesSearch(data) {
            this.companies = data?.data;
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("orders/list", {
                page: this.page,
                search: this.form.search,
                sortBy: this.form.sortBy,
                sortOrder: this.form.sortOrder,
                ...this.form,
            }).then((res) => {
                this.orders = res?.data?.partnerOrder ?? [];
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
