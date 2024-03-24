<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <router-link
                v-if="$can(`${$route.meta.permission}.create`)"
                class="secondary-btn"
                :to="`/contact-reports/create?lead=${$route.params.id}`"
            >
                <span>&nbsp;{{ $t("Create Contact Report") }}</span>
            </router-link>
            <search-filter
                v-model="form.search"
                class="mr-4 w-full max-w-md"
                @reset="reset"
            >
                <MultiSelectInput
                    :textLabel="`Contact Report Source`"
                    v-model="form.contactSources"
                    :options="contactSources?.data"
                    :multiple="true"
                    label="name"
                    trackBy="id"
                    moduleName="sources"
                />
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
            </search-filter>
        </div>
        <div class="table-responsive">
            <Table
                :contact-reports="contactReports"
                :form="form"
                @destroy="destroy"
            />
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
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
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import Table from "@/Pages/ContactReports/Components/Table.vue";
import SearchFilter from "@/Components/SearchFilter.vue";
import { mapGetters } from "vuex";
import Pagination from "laravel-vue-pagination";

export default {
    components: {
        SelectInput,
        MultiSelectInput,
        Pagination,
        Table,
        SearchFilter,
    },
    computed: {
        ...mapGetters("contactReports", ["contactReports"]),
        ...mapGetters("contactSource", ["contactSources"]),
    },
    data() {
        return {
            page: 1,
            form: {
                search: "",
                categoryId: "",
                contactSources: [],
                sortBy:
                    localStorage.getItem("sort_contactReports_column") ?? "",
                sortOrder:
                    localStorage.getItem("sort_contactReports_order") ?? "asc",
            },
            reportCategory: [],
        };
    },
    mounted() {
        this.refresh();
    },
    unmounted() {
        this.$store.commit("contactReports/contactReports", {
            data: [],
            links: [],
        });
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        await this.$store.dispatch("contactReports/list", {
                            ...this.form,
                            company: this.$route.params.id,
                            contactSources: this.form?.contactSources?.map(
                                ({ id }) => id
                            ),
                        });
                    } catch (e) {
                        console.log(e);
                    }
                }, 150)();
            },
        },
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("contactReports/list", {
                page: this.page,
                search: this.form.search,
                company: this.$route.params.id,
            });
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
        async refresh() {
            this.$store.commit("showLoader", true);
            await this.$store.dispatch("contactReports/list", {
                ...this.form,
                company: this.$route.params.id,
            });
            if (!this.contactSources.data.length) {
                await this.$store.dispatch("contactSource/list", {
                    page: this.page,
                    search: this.form.search,
                });
            }

            await this.$store
                .dispatch("reportCategories/list", {
                    perPage: 0,
                    page: 1,
                })
                .then((res) => {
                    this.reportCategory = res?.data?.data ?? [];
                });
            this.$store.commit("showLoader", false);
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
                        await this.$store.dispatch("contactReports/destroy", id);
                        this.refresh();
                    } catch (e) {}
                }
            });
        },
    },
};
</script>

<style scoped></style>
