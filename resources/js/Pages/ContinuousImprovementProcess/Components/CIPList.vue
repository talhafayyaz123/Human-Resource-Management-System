<template>
    <div>
        <div class="table-responsive">
            <table class="doc-table">
                <tr class="text-left font-bold">
                    <th class="">{{ $t("Request Number") }}</th>
                    <th class="">{{ $t("Date") }}</th>
                    <th class="">{{ $t("Title") }}</th>
                    <th class="">{{ $t("Status") }}</th>
                    <th class="">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="process in continuousImprovementProcesses?.data"
                    :key="process.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ process.requestNumber }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ $dateFormatter(process.date, globalLanguage) }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ process.title }}
                        </a>
                    </td>
                    <td class="">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center  focus:text-indigo-500"
                        >
                            {{ process.status }}
                        </a>
                    </td>
                    <td class="w-px">
                        <div v-if="process.status !== 'approved'">
                            <button
                                class="px-2"
                                @click="openUpdateModal(process.id)"
                                v-if="$can(`${$route.meta.permission}.edit`)"
                            >
                                <font-awesome-icon icon="fa-regular fa-pen-to-square"/>
                            </button>
                            <button
                                @click="destroy(process.id)"
                                v-if="$can(`${$route.meta.permission}.delete`)"
                            >
                                <font-awesome-icon icon="fa-regular fa-trash-can"/>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="continuousImprovementProcesses?.data?.length === 0">
                    <td class=" " colspan="4">
                        {{ $t("No Requests found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="continuousImprovementProcesses"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
    <CIPModal
        @cancel="toggleModal = false"
        :toggleModal="toggleModal"
        :action-form="form"
    />
</template>

<script>
import { mapGetters } from "vuex";
import Pagination from "laravel-vue-pagination";
import CIPModal from "./CIPModal.vue";

export default {
    data() {
        return {
            toggleModal: false,
            form: {
                processNumber: "",
                title: "",
                date: new Date(),
                description: "",
                suggestedSolution: "",
                affectedGroup: "",
                files: [],
                userId: "",
            },
        };
    },
    computed: {
        ...mapGetters("continuousImprovementProcess", [
            "continuousImprovementProcesses",
        ]),
    },
    components: {
        Pagination,
        CIPModal,
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh(bypass) {
            this.$store.commit("showLoader", true);
            if (!this.continuousImprovementProcesses?.data?.length || bypass)
                await this.$store.dispatch("continuousImprovementProcess/list");
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
                        await this.$store.dispatch(
                            "continuousImprovementProcess/destroy",
                            id
                        );
                        this.refresh(true);
                    } catch (e) {}
                }
            });
        },
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("continuousImprovementProcess/list", {
                page: this.page,
            });
        },
        async openUpdateModal(id) {
            try {
                const response = await this.$store.dispatch(
                    "continuousImprovementProcess/show",
                    id
                );
                this.form = response?.data?.data ?? this.form;
                this.form.date = new Date(this.form.date);
                this.toggleModal = true;
            } catch (e) {
                console.log(e);
            }
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
