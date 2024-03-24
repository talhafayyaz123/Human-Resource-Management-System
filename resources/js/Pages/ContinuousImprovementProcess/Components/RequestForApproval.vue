<template>
    <div>
        <div class="table-responsive">
            <table class="doc-table">
                <tr class="text-left font-bold">
                    <th class="">{{ $t("Request Number") }}</th>
                    <th class="">{{ $t("Process Number") }}</th>
                    <th class="">{{ $t("Title") }}</th>
                    <th class="pb-4 pt-6 px-6">
                        {{ $t("Date") }}
                    </th>
                    <th class="">{{ $t("Affected Group") }}</th>
                    <th class="">{{ $t("Status") }}</th>
                </tr>
                <tr
                    v-for="(item, index) in requestsForApproval.data"
                    :key="item.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="">
                        {{ item.requestNumber }}
                    </td>
                    <td class="">
                        {{ item.processNumber }}
                    </td>
                    <td class="">
                        {{ item.title }}
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ $dateFormatter(item.date, globalLanguage) }}
                        </a>
                    </td>
                    <td class="">
                        {{ item.affectedGroup }}
                    </td>
                    <td class="w-px ">
                        <p
                            class="capitalize"
                            v-if="
                                item.status === 'approved' ||
                                item.status === 'rejected'
                            "
                        >
                            {{ item.status }}
                        </p>
                        <button
                            @click="openModal(item, index)"
                            v-else
                            class="docsHeroColorBlue py-2 px-3 rounded"
                        >
                            Change Status
                        </button>
                    </td>
                </tr>
                <tr v-if="(requestsForApproval?.data?.length ?? 0) === 0">
                    <td class="" colspan="4">
                        {{ $t("No Requests found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="requestsForApproval"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
    <Modal
        title="Update Status"
        v-if="toggleModal"
        @toggleModal="toggleModal = $event"
        width="50%"
    >
        <template #body>
            <div class="flex flex-col ml-6 mr-6 border mb-3 p-3">
                <SelectInput
                    :error="errors.status"
                    class="w-1/2"
                    label="Status"
                    :required="true"
                    v-model="selectedItem.status"
                >
                    <option value="approved">
                        {{ $t("Approved") }}
                    </option>
                    <option value="rejected">
                        {{ $t("Rejected") }}
                    </option>
                    <option value="pending">{{ $t("Pending") }}</option>
                </SelectInput>
            </div>
        </template>
        <template #footer>
            <button
                @click="changeStatus()"
                type="button"
                class="secondary-btn"
            >
                {{ $t("Update") }}
            </button>
            <button
                @click="toggleModal = false"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
</template>

<script>
import Pagination from "laravel-vue-pagination";
import SelectInput from "@/Components/SelectInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import Modal from "@/Components/EditModal.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors"]),
    },
    components: {
        TextareaInput,
        Modal,
        SelectInput,
        Pagination,
        MultiSelectInput,
    },
    data() {
        return {
            requestsForApproval: {
                data: [],
                links: [],
            },
            page: 1,
            selectedItem: null,
            toggleModal: false,
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        /**
         * open the change status modal
         * @param {item} the approver object
         * @param {index} indx of the approver object in the array
         */
        openModal(item, index) {
            this.selectedItem = { ...item };
            this.selectedIndex = index;
            this.toggleModal = true;
        },
        /**
         * goto the specified page
         * @param {page} page to switch to in pagination
         */
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch(
                "continuousImprovementProcess/approvalListing",
                {
                    page: this.page,
                }
            );
        },
        /**
         * change the status
         */
        async changeStatus() {
            await this.$nextTick();
            const payload = {
                id: this.selectedItem.id,
                status: this.selectedItem.status,
            };
            try {
                await this.$store.dispatch(
                    "continuousImprovementProcess/setApprovalStatus",
                    payload
                );
                this.toggleModal = false;
                this.selectedItem = null;
                this.errors = {};
                this.refresh();
            } catch (e) {}
        },
        /**
         * get the approval listing
         */
        async refresh() {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "continuousImprovementProcess/approvalListing"
            );
            this.requestsForApproval =
                response?.data ?? this.requestsForApproval;
            this.$store.commit("showLoader", false);
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
.table-layout-fixed {
    table-layout: fixed;
}
</style>
