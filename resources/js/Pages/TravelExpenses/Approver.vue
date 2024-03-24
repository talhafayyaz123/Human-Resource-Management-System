<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h3 class="card-title">
            {{ $t("Approval List") }}
        </h3>
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
                <tr class="text-left font-bold">
                    <th class="">{{ $t("Requester") }}</th>

                    <th class="">{{ $t("From Date") }}</th>

                    <th class="">{{ $t("To Date") }}</th>

                    <th class="">{{ $t("Request Type") }}</th>

                    <th class="">{{ $t("Action") }}</th>
                </tr>

                <tr
                    v-for="item in travelExpensesApprove.data"
                    :key="item.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="">
                        {{ item.requester }}
                    </td>
                    <td class="">
                        {{ $dateFormatter(item.fromDate, globalLanguage) }}
                    </td>
                    <td class="">
                        {{ $dateFormatter(item.toDate, globalLanguage) }}
                    </td>
                    <td class="">
                        {{ item.requestType }}
                    </td>

                    <td class="">
                        <SelectInput
                            class="w-1/2"
                            :required="true"
                            v-model="item.status"
                            @update:model-value="setApprovalStatus(item)"
                        >
                            <option value="approved">
                                {{ $t("Approved") }}
                            </option>
                            <option value="rejected">
                                {{ $t("Rejected") }}
                            </option>
                            <option value="pending">{{ $t("Pending") }}</option>
                        </SelectInput>
                    </td>
                </tr>
                <tr v-if="travelExpensesApprove.data.length === 0">
                    <td class="" colspan="7">
                        {{ $t("No requests found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="travelExpensesApprove"
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
import SelectInput from "../../Components/SelectInput.vue";

export default {
    computed: {
        ...mapGetters("travelExpense", ["travelExpensesApprove"]),
    },
    components: {
        Pagination,
        SearchFilter,
        SelectInput,
    },
    props: {
        filters: Object,
        can: Object,
    },
    data() {
        return {
            form: {
                search: "",
            },
        };
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        await this.$store.dispatch(
                            "travelExpense/approveList",
                            this.form
                        );
                    } catch (e) {
                        console.log(e);
                    }
                }, 150)();
            },
        },
    },
    mounted() {
        this.refresh();
    },
    methods: {
        openModal(item, index) {
            this.selectedItem = { ...item };
            this.selectedIndex = index;
            this.toggleModal = true;
        },
        async changePageOrSearch(page = 1) {
            await this.$store.dispatch("travelExpense/approveList", {
                page: page,
                search: this.form.search,
            });
        },
        async refresh(bypass) {
            this.$store.commit("showLoader", true);
            if (!this.roles?.data?.length || bypass)
                await this.$store.dispatch(
                    "travelExpense/approveList",
                    this.pickBy(this.form)
                );
            this.$store.commit("showLoader", false);
        },
        reset() {
            this.form = this.mapValues(this.form, () => null);
        },
        async setApprovalStatus(item) {
            try {
                await this.$store.dispatch("travelExpense/setApprovalStatus", {
                    id: item.id,
                    status: item.status,
                });
                this.refresh();
            } catch (e) {}
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
