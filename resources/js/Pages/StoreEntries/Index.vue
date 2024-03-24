<template>
    <div>
        <!-- <h1 class="header mb-8 text-3xl font-bold">
            {{ $t("Store Entries") }}
        </h1>
        <div class="flex items-center justify-between mb-6">
            <router-link v-if="$can(`${$route.meta.permission}.create`)" class="secondary-btn" to="/store-entries/create">
                <span>{{ $t("Create") }}</span>
                <span class="hidden md:inline">&nbsp;{{ $t("Store Entries") }}</span>
            </router-link>
        </div> -->

        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />
        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr class="text-left">
                        <th @click="sort('itemNumber')" class="cursor-pointer">
                            {{ $t("Item Number") }}
                            <font-awesome-icon
                                v-if="form.sortBy === 'itemNumber'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                        </th>
                        <th
                            @click="sort('productTitle')"
                            class="cursor-pointer"
                        >
                            <font-awesome-icon
                                v-if="form.sortBy === 'productTitle'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                            {{ $t("Product Title") }}
                        </th>
                        <th
                            @click="sort('isVisibleForPartner')"
                            class="cursor-pointer"
                        >
                            <font-awesome-icon
                                v-if="form.sortBy === 'isVisibleForPartner'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                            {{ $t("Visible for Partners") }}
                        </th>
                        <th
                            @click="sort('isVisibleForCustomer')"
                            class="cursor-pointer"
                        >
                            <font-awesome-icon
                                v-if="form.sortBy === 'isVisibleForCustomer'"
                                class="cursor-pointer ml-2"
                                :icon="`fa-solid fa-sort-${
                                    form.sortOrder === 'asc' ? 'up' : 'down'
                                }`"
                            />
                            {{ $t("Visible for Customers") }}
                        </th>
                        <th class="">{{ $t("Actions") }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="entry in storeEntries?.data ?? []"
                        :key="entry.id"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                        @contextmenu.stop.prevent="
                            (e) => {
                                e.preventDefault();
                                let route = $router.resolve(
                                    `/store-entries/${entry.id}/edit?page=${page}`
                                );
                                window.open(route.href, '_blank');
                            }
                        "
                    >
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ entry.itemNumber }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ entry.productTitle }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    entry?.isVisibleToPartner === true
                                        ? "Yes"
                                        : "No"
                                }}
                            </a>
                        </td>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{
                                    entry?.isVisibleToCustomer === true
                                        ? "Yes"
                                        : "No"
                                }}
                            </a>
                        </td>
                        <td class="w-px text-center">
                            <div class="flex items-center">
                                <!--                        <router-link-->
                                <!--                            v-if="$can(`${$route.meta.permission}.show`)"-->
                                <!--                            :to="`/store-entries/${entry.id}`"-->
                                <!--                        >-->
                                <!--                            <i class="fa-solid fa-eye"></i>-->
                                <!--                        </router-link>-->
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.edit`)
                                    "
                                    class="px-4"
                                    :to="`/store-entries/${entry.id}/edit?page=${page}`"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </router-link>
                                <button
                                    v-if="
                                        $can(`${$route.meta.permission}.delete`)
                                    "
                                    @click="destroy(entry.id)"
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="(storeEntries?.data?.length ?? 0) === 0">
                        <td class=" " colspan="4">
                            {{ $t("No Requests found") }}.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="storeEntries ?? []"
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
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";
import { debounce } from "../../utils/debounce";

import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    computed: {
        ...mapGetters("storeEntries", ["storeEntries", "count"]),
    },
    components: {
        Icon,
        Pagination,
        MultiSelectInput,
        SelectInput,
        PageHeader,
    },
    props: {
        filters: Object,
    },

    data() {
        return {
            page: 1,
            form: {
                search: "",
                sortBy: "itemNumber",
                sortOrder: "asc",
            },
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Store Entries",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Store Entry"),
                btn2Path: "/store-entries/create",
            },
            window,
        };
    },
    watch: {
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
                    "storeEntries/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    async mounted() {
        let isPaginate = false;
        if (this.$route.query.page) {
            this.page = this.$route.query.page;
            this.$router.replace({ query: null });
            isPaginate = true;
        }
        await this.refresh(isPaginate);
    },
    methods: {
        /**
         * get the groups list based on page that was selected with pagination
         * @param page
         */
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("storeEntries/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        async refresh(bypass) {
            if (!this.storeEntries.length || bypass)
                await this.$store.dispatch("storeEntries/list", {
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
                    await this.$store.dispatch("storeEntries/destroy", id);
                    await this.refresh(true);
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

.roles > :only-child {
    overflow: inherit !important;
    text-overflow: clip !important;
    white-space: normal !important;
    word-break: break-all !important;
}
</style>
