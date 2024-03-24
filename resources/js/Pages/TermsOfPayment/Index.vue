<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <!-- <search-filter
                :isFilter="false"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
            </search-filter> -->
        </div>
        <div class="table-responsive">
            <table class="w-full doc-table">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('paymentTerms')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Payment Terms") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'paymentTerms'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
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
                        @click="sort('percentage_1')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Percentage (%)") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'percentage_1'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('noOfDays_1')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("No.of Days") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'noOfDays_1'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('offerText')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Text On Offers") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'offerText'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('orderText')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Text On Offer Confirmation") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'orderText'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('invoiceText')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Text On Invoices") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'invoiceText'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                </tr>
                <tr
                    v-for="term in termsOfPayment.data"
                    :key="term.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                    @contextmenu.stop.prevent="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(`/terms-of-payment/${term.id}/edit?page=${page}`);
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="border-t">
                        <router-link
                            :to="`/terms-of-payment/${term.id}/edit?page=${page}`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ term.paymentTerms }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/terms-of-payment/${term.id}/edit?page=${page}`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ term.name }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/terms-of-payment/${term.id}/edit?page=${page}`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ term.percentage1 }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/terms-of-payment/${term.id}/edit?page=${page}`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ term.noOfDays1 }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/terms-of-payment/${term.id}/edit?page=${page}`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ term.offerText }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/terms-of-payment/${term.id}/edit?page=${page}`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ term.orderText }}
                        </router-link>
                    </td>
                    <td class="border-t">
                        <router-link
                            :to="`/terms-of-payment/${term.id}/edit?page=${page}`"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ term.invoiceText }}
                        </router-link>
                    </td>
                    <td class="w-px border-t">
                        <router-link
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="mr-2"
                            :to="`/terms-of-payment/${term.id}/edit?page=${page}`"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </router-link>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click="destroy(term.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                    </td>
                </tr>
                <tr v-if="termsOfPayment.data.length === 0">
                    <td class="px-6 py-4 border-t text-center" colspan="4">
                        {{ $t("No terms of payment found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <!-- <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="termsOfPayment"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div> -->
        <div class="flex justify-center my-5">
            <pagination
                :limit="10"
                align="center"
                :data="termsOfPayment"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
        </div>
    </div>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";
import SearchFilter from "../../Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce"

export default {
    computed: {
        ...mapGetters("termsOfPayment", ["termsOfPayment"]),
    },
    components: {
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
                    text: "Terms Of Payment",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Term Of Payment"),
                btn2Path: "/terms-of-payment/create",
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
                    "termsOfPayment/list",
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
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("termsOfPayment/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        async refresh(bypass) {
            if (!this.termsOfPayment?.data?.length || bypass)
                await this.$store.dispatch("termsOfPayment/list");
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
                        await this.$store.dispatch("termsOfPayment/destroy", id);
                        this.refresh(true);
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
