<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

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
                    <th class="">{{ $t("Department") }}</th>
                    <th class="">{{ $t("Team") }}</th>
                    <th class="">{{ $t("Requester") }}</th>
                    <th class="">{{ $t("Job Title") }}</th>
                    <th class="">{{ $t("Contract Type") }}</th>
                    <th class="">{{ $t("Start Date") }}</th>
                    <th class="">{{ $t("Status") }}</th>
                </tr>
                <tr
                    v-for="requirement in requirements?.data ?? []"
                    :key="requirement.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center"
                        >
                            {{ requirement.department }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center"
                        >
                            {{ requirement.team }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center"
                        >
                            {{ requirement.requester }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center"
                        >
                            {{ requirement.job }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center"
                        >
                            {{ requirement.contractType }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center"
                        >
                            {{
                                $dateFormatter(
                                    requirement.startDate,
                                    globalLanguage
                                )
                            }}
                        </a>
                    </td>
                    <td class="">
                        <select-input
                            @update:model-value="
                                updateStatus($event, requirement.id)
                            "
                            :isReadOnly="requirement.status == 'approved'"
                            :model-value="requirement.status"
                            :key="requirement.status"
                        >
                            <option
                                v-if="
                                    requirement.status !== 'approved' &&
                                    requirement.status !== 'rejected'
                                "
                                value="pending"
                            >
                                {{ $t("Pending") }}
                            </option>
                            <option value="approved">
                                {{ $t("Approved") }}
                            </option>
                            <option value="rejected">
                                {{ $t("Rejected") }}
                            </option>
                        </select-input>
                    </td>
                </tr>
                <tr v-if="(requirements?.data?.length ?? 0) === 0">
                    <td class="" colspan="4">
                        {{ $t("No personal requirements found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="requirements"
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
import SearchFilter from "@/Components/SearchFilter.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce"
export default {
    computed: {
        ...mapGetters("personalRequirements", ["requirements"]),
    },
    components: {
        Pagination,
        SearchFilter,
        SelectInput,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Management"),
                    to: "/personal-requirements",
                },
                {
                    text: this.$t("Personal Requirements"),
                    active: true,
                }
            ],
            page: 1,
            form: {
                search: "",
            },
        };
    },
    watch: {
        'form.search'(...val) {
            this.debouncedFetch(...val);
        },
        'form.sortBy'(...val) {
            this.debouncedFetch(...val);
        },
        'form.sortOrder'(...val) {
            this.debouncedFetch(...val);
        },
    },
    created() {
        this.debouncedFetch = debounce(async (newValue, oldValue)=>{
            try {
                await this.$store.dispatch(
                    "personalRequirements/list",
                    this.pickBy(this.form)
                );
            } catch (e) {}
        }, 300);
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async updateStatus(status, id) {
            try {
                await this.$store.dispatch("personalRequirements/setStatus", {
                    id: id,
                    status: status,
                });
                this.refresh();
            } catch (e) {
                console.log(e);
            }
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("personalRequirements/list", {
                page: this.page,
                search: this.form.search,
            });
        },
        async refresh() {
            try {
                await this.$store.dispatch("personalRequirements/list");
            } catch (e) {}
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
