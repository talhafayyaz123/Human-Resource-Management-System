<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <div class="flex items-center justify-end mb-6">
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >   <div class="flex flex-col gap-4">
                <div class="form-group">
                    <MultiSelectInput
                        v-model="form.company"
                        :options="companies"
                        :multiple="false"
                        textLabel="Company"
                        label="companyName"
                        trackBy="id"
                        moduleName="companies"
                        @asyncSearch="companiesSearch"
                    />
                </div>
                <div class="form-group">
                    <MultiSelectInput
                        :textLabel="`Contact Report Source`"
                        v-model="form.contactSources"
                        :options="contactSources?.data"
                        :multiple="true"
                        label="name"
                        trackBy="id"
                        moduleName="sources"
                    />
                </div>
                <div class="form-group">
                    <select-input v-model="form.type" :label="$t('Status')">
                        <option
                            v-for="type in ['customer', 'lead']"
                            :key="type"
                            :value="type"
                        >
                            {{ $t(type) }}
                        </option>
                    </select-input>
                </div>
                <div class="form-group">
                    <select-input v-model="form.categoryId" :label="$t('Category')">
                        <!-- :error="form.errors.categoryId" -->
                        <option
                            v-for="category in reportCategory"
                            :key="'lead-' + category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select-input>
                </div>
                </div>
            </search-filter>
        </div>
        <div class="table-responsive">
            <Table
                :contact-reports="contactReports"
                :form="form"
                :page="page"
                @destroy="destroy"
            />
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            
            <pagination
                align="center"
                :data="contactReports"
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
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import Table from "./Components/Table.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
export default {
    computed: {
        ...mapGetters("contactReports", ["contactReports"]),
        ...mapGetters("contactSource", ["contactSources"]),
    },
    components: {
        Icon,
        Pagination,
        SearchFilter,
        MultiSelectInput,
        SelectInput,
        Table,
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
                    text: "Sales",
                    to: "/contact-reports",
                },
                {
                    text: "Contact Reports",
                    active: true,
                },
            ],
            optionalItems: {
                isBtn2Show: true,
                btn2Text: this.$t("Create Contact Report"),
                btn2Path: "/contact-reports/create",
            },
            page: 1,
            form: {
                search: "",
                type: "",
                company: "",
                categoryId: "",
                contactSources: [],
                sortBy:
                    localStorage.getItem("sort_contactReports_column") ??
                    "reportNumber",
                sortOrder:
                    localStorage.getItem("sort_contactReports_order") ?? "asc",
            },
            companies: [],
            reportCategory: [],
        };
    },
    mounted() {
         var isPaginate=false;
        if(this.$route.query.page){
            this.page=this.$route.query.page;
            this.$router.replace({'query': null});
             isPaginate=true;   
        }

        this.refresh(isPaginate);
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
        "form.categoryId"(...val) {
            this.debouncedFetch(...val);
        },
        "form.contactSources"(...val) {
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
                await this.$store.dispatch("contactReports/list", {
                    ...this.form,
                    company: this.form.company?.id,
                    contactSources: this.form?.contactSources?.map(
                        ({ id }) => id
                    ),
                });
            } catch (e) {
                console.log(e);
            }
        }, 300);
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("contactReports/list", {
                page: this.page,
                search: this.form.search,
                sortBy: this.form.sortBy,
                sortOrder: this.form.sortOrder,
            });
        },
        companiesSearch(data) {
            this.companies = data?.data;
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
        async refresh(bypass) {

            if (!this.contactReports?.data?.length || bypass) {
                await this.$store.dispatch("contactReports/list", {
                    ...this.form,
                      page: this.page,
                });
            }
            if (!this.contactSources.data.length) {
                await this.$store.dispatch("contactSource/list", {
                    page: this.page,
                    search: this.form.search,
                });
            }
            this.$store
                .dispatch("companies/list", {
                    page: 1,
                    perPage: 25,
                })
                .then((res) => {
                    this.companies = res?.data?.data ?? [];
                });

            await this.$store
                .dispatch("reportCategories/list", {
                    perPage: 0,
                    page: 1,
                })
                .then((res) => {
                    this.reportCategory = res?.data?.data ?? [];
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
                    await this.$store.dispatch("contactReports/destroy", id);
                    this.refresh(true);
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
.tag {
    display: inline-block;
    padding: 6px 12px;
    margin-right: 8px;
    margin-bottom: 8px;
    margin-top: 8px;
    border-radius: 16px;
    background-color: #41b883;
    color: #fff;
    font-size: 14px;
    font-weight: 500;
}
</style>
