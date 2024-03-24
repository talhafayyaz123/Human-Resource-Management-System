<template>
    <div>
        <PageHeader
            :title="title"
            :items="breadcrumbItems"
            :optionalItems="optionalItems"
        />

        <div class="flex justify-end my-5">
            <search-filter v-model="form.search" class="" @reset="reset">
                <div class="w-full mb-5">
                    <div class="form-group">
                        <select-input
                        :label="$t('Status')"
                            v-model="form.status"
                        >
                            <option value="active">{{ $t("active") }}</option>
                            <option value="inactive">{{ $t("inactive") }}</option>
                        </select-input>
                        </div>
                </div>
                <div class="w-full my-5">
                    <div class="form-group">
                        <MultiSelectInput
                            v-model="form.product_category_id"
                            :options="productCategories.data"
                            :multiple="false"
                            :textLabel="$t('Product Category')"
                            label="name"
                            :trackBy="'id'"
                            :moduleName="'productCategory'"
                            :taggable="true"
                        />
                    </div>
                </div>
                <div class="w-full my-5">
                    <div class="form-group">
                        <MultiSelectInput
                            v-model="form.product_software_id"
                            :options="softwares.data"
                            :multiple="false"
                            :textLabel="$t('Software')"
                            label="name"
                            :trackBy="'id'"
                            :moduleName="'Software'"
                            :taggable="true"
                        />
                    </div>
                </div>
                <div class="w-full my-5">
                    <div class="form-group">
                        <MultiSelectInput
                            class=""
                            v-model="form.productPriceId"
                            :options="productPrices?.data ?? []"
                            :multiple="false"
                            :textLabel="$t('Price List')"
                            label="name"
                            :trackBy="'id'"
                            :moduleName="'productPrice'"
                            :taggable="true"
                        />
                    </div>
                </div>
                <div class="w-full mt-5">
                    <div class="form-group">
                        <select-input
                            class=""
                            v-model="form.type"
                            :label="$t('Type')"
                        >
                            <option value="">{{ $t("All") }}</option>
                            <option value="software">{{ $t("Software") }}</option>
                            <option value="service">{{ $t("Service") }}</option>
                            <option value="pauschal">{{ $t("Pauschal") }}</option>
                            <option value="ams">{{ $t("AMS") }}</option>
                            <option value="hosting">{{ $t("Hosting") }}</option>
                            <option value="cloud-software">
                                {{ $t("Cloud Software") }}
                            </option>
                            <option value="traveling">
                                {{ $t("Traveling") }}
                            </option>
                        </select-input>
                    </div>
                </div>    
            </search-filter>
        </div>
        <div class="table-responsive">
            <table class="doc-table">
                <tr class="text-left">
                    <th
                        @click="sort('articleNumber', 'products')"
                        class="cursor-pointer"
                    >
                        {{ $t("Article Number") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'articleNumber'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('name', 'products')"
                        class="cursor-pointer"
                    >
                        {{ $t("Product Name") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('ProductGroup.name', 'products')"
                        class="cursor-pointer"
                    >
                        {{ $t("Product Group") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'ProductGroup.name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('paymentDetailsType', 'products')"
                        class="cursor-pointer"
                    >
                        {{ $t("Product Type") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'paymentDetailsType'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('ProductSoftware.name', 'products')"
                        class="cursor-pointer"
                    >
                        {{ $t("Product Software") }}
                        <font-awesome-icon
                            v-if="form.sortBy === 'ProductSoftware.name'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('salePrice_numeric', 'products')"
                        class="cursor-pointer"
                    >
                        <p
                            :class="
                                globalLanguage === 'de' ? 'float-right' : ''
                            "
                        >
                            {{ $t("Sale price") }}
                        </p>
                        <font-awesome-icon
                            v-if="form.sortBy === 'salePrice_numeric'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="cursor-pointer">
                        {{ $t("Action") }}
                    </th>
                </tr>
                <tr
                    v-for="product in products?.data ?? []"
                    :key="product.id"
                    :class="[
                        'hover:bg-gray-100',
                        'focus-within:bg-gray-100',
                        product.status === 'active'
                            ? 'status-active'
                            : 'status-inactive',
                    ]"
                    @click.stop.right="
                        (e) => {
                            e.preventDefault();
                            let route = $router.resolve(
                                `/products/${product.id}/edit?page=${page}`
                            );
                            window.open(route.href, '_blank');
                        }
                    "
                >
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer  focus:text-indigo-500"
                        >
                            {{ product.articleNumber }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer  focus:text-indigo-500"
                        >
                            {{ product.name }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer  focus:text-indigo-500"
                        >
                            {{ product.productGroup }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer  focus:text-indigo-500"
                        >
                            {{ product.type }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            class="flex items-center cursor-pointer  focus:text-indigo-500"
                        >
                            {{ product.productSoftware }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            v-if="product.type !== 'hosting'"
                            :class="`flex items-center cursor-pointer   ${
                                globalLanguage === 'de' ? 'float-right' : ''
                            }`"
                            tabindex="-1"
                        >
                            {{ $formatter(product.salePrice, globalLanguage) }}
                        </a>
                        
                        <a
                            v-if="product.type == 'hosting'"
                            :class="`flex items-center cursor-pointer   ${
                                globalLanguage === 'de' ? 'float-right' : ''
                            }`"
                            tabindex="-1"
                        >
                            {{ $formatter(product.pricePerPeriod, globalLanguage) }}
                        </a>
                    </td>
                    <td class="w-px  text-center">
                        
                        <div class="flex gap-2">
                            <a
                            @click.stop="
                                $router.push(`/products/${product.id}/edit?page=${page}`)
                            "
                            v-if="$can(`${$route.meta.permission}.edit`)"
                            class="cursor-pointer"
                        >
                            <font-awesome-icon
                                icon="fa-regular fa-pen-to-square"
                            />
                        </a>
                        <button
                            v-if="$can(`${$route.meta.permission}.delete`)"
                            @click.stop="destroy(product.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can" />
                        </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="(products?.data?.length ?? 0) === 0">
                    <td class=" " colspan="4">
                        {{ $t("No products found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="products"
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
import SearchFilter from "../../Components/SearchFilter.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "../../utils/debounce";
export default {
    mounted() {
        this.form = { ...this.filter }; // get the product filter info from store and add it to the filter form
        const productsColumn =
            localStorage.getItem("sort_products_column") ?? "articleNumber";
        const productsOrder =
            localStorage.getItem("sort_products_order") ?? "asc";

        // Apply the stored values to the form object for 'customers' table
        if (productsColumn) {
            this.form.sortBy = productsColumn;
        }

        if (productsOrder) {
            this.form.sortOrder = productsOrder;
        }
        this.refresh();
        this.$store.dispatch("productprice/list", {
            status: "active",
        });
        if (!this.productCategories.data.length) {
            this.$store.dispatch("productCategory/list");
        }
        if (!this.softwares.data.length) {
            this.$store.dispatch("softwares/list");
        }
    },
    computed: {
        ...mapGetters("products", ["products", "filter"]),
        ...mapGetters("productCategory", {
            productCategories: "data",
        }),
        ...mapGetters("softwares", ["softwares"]),
        ...mapGetters("productprice", ["productPrices"]),
    },
    components: {
        Icon,
        Pagination,
        SearchFilter,
        MultiSelectInput,
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
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Master Data",
                    to: "/products",
                },
                {
                    text: "Products",
                    active: true,
                },
            ],
            optionalItems: {
                isBtnShow: true,
                btnText: "Import Product",
                path: "products/import",
                isBtn2Show: true,
                btn2Text: "Create Product",
                btn2Path: "/products/create",
            },
            window,
            page: 1,
            form: {
                product_category_id: "",
                status: "",
                search: "",
                product_software_id: "",
                productPriceId: "",
                type: "",
                sortBy:
                    localStorage.getItem("sort_products_column") ??
                    "articleNumber",
                sortOrder: localStorage.getItem("sort_products_order") ?? "asc",
            },
        };
    },
    watch: {
        "form.product_category_id"(...val) {
            this.debouncedFetch(...val);
        },
        "form.status"(...val) {
            this.debouncedFetch(...val);
        },
        "form.search"(...val) {
            this.debouncedFetch(...val);
        },
        "form.product_software_id"(...val) {
            this.debouncedFetch(...val);
        },
        "form.productPriceId"(...val) {
            this.debouncedFetch(...val);
        },
        "form.type"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortBy"(...val) {
            this.debouncedFetch(...val);
        },
        "form.sortOrder"(...val) {
            this.debouncedFetch(...val);
        },
    },
    async created() {
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            try {
                this.$store.commit("products/filter", {
                    ...this.form,
                });

                await this.$store.dispatch("products/list", {
                    ...this.pickBy({
                        ...this.form,
                        productCategoryId:
                            this.form.product_category_id?.id ?? "",
                        productSoftwareId:
                            this.form.product_software_id?.id ?? "",
                        productPriceId: this.form.productPriceId?.id ?? "",
                    }),
                });
            } catch (e) {
                console.error(e);
            }
        }, 300);
    },
    methods: {
        async refresh() {
        
        if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
            
        }
            await this.$store.dispatch("products/list", {
                page: this.page,
                search: this.form.search,
                ...this.form,
                ...this.filter,
                productCategoryId: this.filter.product_category_id?.id ?? "",
                productSoftwareId: this.filter.product_software_id?.id ?? "",
                productPriceId: this.form.productPriceId?.id ?? "",
            });
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("products/list", {
                page: this.page,
                search: this.form.search,
                ...this.filter,
                productCategoryId: this.filter.product_category_id?.id ?? "",
                productSoftwareId: this.filter.product_software_id?.id ?? "",
                productPriceId: this.form.productPriceId?.id ?? "",
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
                    await this.$store.dispatch("products/destroy", id);
                    this.refresh();
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

.product-select-all {
    user-select: all;
}
</style>
