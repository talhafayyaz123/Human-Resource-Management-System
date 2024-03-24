<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <button
                v-if="$can(`milestone.create`)"
                class="secondary-btn"
                @click="
                    modalType = 'add';
                    toggleMilestonesModal = true;
                    resetFlashAndErrors();
                "
            >
                <span>{{ $t("Create") }}</span>
                <span class="hidden md:inline"
                    >&nbsp;{{ $t("Milestone") }}</span
                >
            </button>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <tr class="text-left font-bold">
                    <th
                        @click="sort('name')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Name")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'name'"
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
                        @click="sort('plannedStartDate')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Planned Start Date")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'plannedStartDate'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('actualStartDate')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Actual Start Date")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'actualStartDate'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('earliestStartDate')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Earliest Possible Start Date")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'earliestStartDate'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('actualFinishedDate')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Actual Finished Date")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'actualFinishedDate'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('expectedFinishedDate')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Last Expected Finished Date")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'expectedFinishedDate'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th
                        @click="sort('plannedFinishedDate')"
                        class="pb-4 pt-6 px-6 cursor-pointer"
                    >
                        {{ $t("Planned Finished Date")
                        }}<font-awesome-icon
                            v-if="form.sortBy === 'plannedFinishedDate'"
                            class="cursor-pointer ml-2"
                            :icon="`fa-solid fa-sort-${
                                form.sortOrder === 'asc' ? 'up' : 'down'
                            }`"
                        />
                    </th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                </tr>
                <tr
                    v-for="milestone in milestones?.data ?? []"
                    :key="milestone.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500 overflow-text-milestone"
                        >
                            {{ milestone.name }}
                            <!--implement this on weekend
                            <icon
                                v-if="project.deleted_at"
                                name="trash"
                                class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400"
                            /> -->
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4"
                            tabindex="-1"
                        >
                            {{ milestone.status }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $dateFormatter(milestone.plannedStartDate, globalLanguage) }}
                        </a>
                    </td>

                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $dateFormatter(milestone.actualStartDate, globalLanguage) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $dateFormatter(milestone.earliestStartDate, globalLanguage) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $dateFormatter(milestone.actualFinishedDate, globalLanguage) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $dateFormatter(milestone.expectedFinishedDate, globalLanguage) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4"
                            tabindex="-1"
                        >
                            {{ $dateFormatter(milestone.plannedFinishedDate, globalLanguage) }}
                        </a>
                    </td>
                    <td class="w-px border-t">
                        <button
                            v-if="$can(`milestone.edit`)"
                            class="px-1"
                            @click="openUpdateModal(milestone.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-pen-to-square"/>
                        </button>
                        <button
                            v-if="$can(`milestone.delete`)"
                            @click="destroy(milestone.id)"
                        >
                            <font-awesome-icon icon="fa-regular fa-trash-can"/>
                        </button>
                    </td>
                </tr>
                <tr v-if="(milestones?.data?.length ?? 0) === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No milestone found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="milestones"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
    <Modal
        :title="(modalType == 'add' ? 'Add' : 'Edit') + ' Milestone'"
        v-if="toggleMilestonesModal"
        @toggleModal="toggleMilestonesModal = $event"
    >
        <template #body>
            <div class="px-4">
                <FlashMessages />
            </div>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <text-input
                    :required="true"
                    v-model="form.name"
                    :error="errors.name"
                    class="pb-8 pr-6 lg:w-1/2"
                    label="Name"
                />
                <select-input
                    :required="true"
                    v-model="form.status"
                    :error="errors.status"
                    :label="$t('Status')"
                    class="pb-8 pr-6 lg:w-1/2"
                >
                    <option value="new">{{ $t("New") }}</option>
                    <option value="done">{{ $t("Done") }}</option>
                    <option value="in-work">{{ $t("In Work") }}</option>
                    <option value="in-review">{{ $t("In Review") }}</option>
                    <option value="blocked">{{ $t("Blocked") }}</option>
                </select-input>
            </div>
            <div class="-mr-6 p-8 pt-0">
                <QuillTextEditor
                    class="pb-8 pr-6 w-full"
                    :content="form.description"
                    @delta="form.description = $event"
                    :error="errors.description"
                    :label="$t('Description')"
                />
            </div>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <DateInput
                    :required="true"
                    v-model="form.plannedStartDate"
                    class="pb-8 pr-6 lg:w-1/3"
                    :label="$t('Planned Start Date')"
                    :error="errors.plannedStartDate"
                />
                <DateInput
                    v-model="form.plannedFinishedDate"
                    :error="errors.plannedFinishedDate"
                    class="pb-8 pr-6 lg:w-1/3"
                    :label="$t('Planned Finished Date')"
                />
                <DateInput
                    v-model="form.actualStartDate"
                    class="pb-8 pr-6 lg:w-1/3"
                    :label="$t('Actual Start Date')"
                    :error="errors.actualStartDate"
                />
            </div>
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <DateInput
                    v-model="form.actualFinishedDate"
                    :error="errors.actualFinishedDate"
                    class="pb-8 pr-6 lg:w-1/3"
                    :label="$t('Actual Finished Date')"
                />
                <DateInput
                    v-model="form.earliestStartDate"
                    :error="errors.earliestStartDate"
                    class="pb-8 pr-6 lg:w-1/3"
                    :label="$t('Earliest Start Date')"
                />
                <DateInput
                    v-model="form.expectedFinishedDate"
                    :error="errors.expectedFinishedDate"
                    class="pb-8 pr-6 lg:w-1/3"
                    :label="$t('Expected Finished Date')"
                />
            </div>
        </template>
        <template #footer>
            <loading-button
                @click="createOrUpdateMilestone"
                :loading="isLoading"
                class="btn-green ml-2"
            >
                {{ modalType == "add" ? "Add" : "Edit" }}
            </loading-button>
            <button
                @click="cancel"
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
import Modal from "@/Components/EditModal.vue";
import TextInput from "@/Components/TextInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import SelectInput from "@/Components/SelectInput.vue";
import DateInput from "@/Components/DateInput.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("milestones", ["milestones"]),
    },
    watch: {
        toggleMilestonesModal(val) {
            if (val === false) {
                this.form = {
                    milestoneId: "fnkwfn",
                    status: "new",
                    description: "",
                    name: "",
                    plannedStartDate: "",
                    actualStartDate: "",
                    earliestStartDate: "",
                    actualFinishedDate: "",
                    expectedFinishedDate: "",
                    plannedFinishedDate: "",
                    projectId: this.$route.query.id,
                    comments: "",
                };
            }
        },
    },
    mounted() {
        this.refresh();
    },
    components: {
        LoadingButton,
        Pagination,
        Modal,
        TextInput,
        SelectInput,
        DateInput,
        QuillTextEditor,
        FlashMessages,
    },
    data() {
        return {
            page: 1,
            form: {
                milestoneId: "fnkwfn",
                status: "new",
                description: "",
                name: "",
                plannedStartDate: "",
                actualStartDate: "",
                earliestStartDate: "",
                actualFinishedDate: "",
                expectedFinishedDate: "",
                plannedFinishedDate: "",
                projectId: this.$route.query.id,
                comments: "",
            },
            toggleMilestonesModal: false,
            modalType: "add",
        };
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("milestones/list", {
                page: this.page,
            });
        },
        resetFlashAndErrors() {
            // this.$page.props.flash = {};
            // this.$page.props.errors = {};
        },
        cancel() {
            this.form = {
                milestoneId: "fnkwfn",
                status: "new",
                description: "",
                name: "",
                plannedStartDate: "",
                actualStartDate: "",
                earliestStartDate: "",
                actualFinishedDate: "",
                expectedFinishedDate: "",
                plannedFinishedDate: "",
                projectId: this.$route.query.id,
                comments: "",
            };
            this.toggleMilestonesModal = false;
            this.resetFlashAndErrors();
        },
        openUpdateModal(id) {
            this.modalType = "update";
            const milestone =
                this.milestones.data.find((milestone) => milestone.id == id) ??
                {};
            this.form = {
                id: milestone.id,
                milestoneId: milestone.milestoneId,
                status: milestone.status,
                description: milestone.description,
                name: milestone.name,
                plannedStartDate: milestone.plannedStartDate,
                actualStartDate: milestone.actualStartDate,
                earliestStartDate: milestone.earliestStartDate,
                actualFinishedDate: milestone.actualFinishedDate,
                expectedFinishedDate: milestone.expectedFinishedDate,
                plannedFinishedDate: milestone.plannedFinishedDate,
                projectId: this.$route.query.id,
                comments: milestone.comments,
            };
            this.toggleMilestonesModal = true;
            this.resetFlashAndErrors();
        },
        async refresh(bypass) {
            this.form = {
                milestoneId: "fnkwfn",
                status: "new",
                description: "",
                name: "",
                plannedStartDate: "",
                actualStartDate: "",
                earliestStartDate: "",
                actualFinishedDate: "",
                expectedFinishedDate: "",
                plannedFinishedDate: "",
                projectId: this.$route.query.id,
                comments: "",
            };
            try {
                if (!this.milestones?.data?.length || bypass)
                    await this.$store.dispatch(
                        "milestones/milestonesByProject",
                        this.$route.query.id
                    );
            } catch (e) {}
        },
        async createOrUpdateMilestone() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(
                    `milestones/${
                        this.modalType == "add" ? "create" : "update"
                    }`,
                    this.modalType == "add"
                        ? { ...this.form }
                        : {
                              id: this.form.id,
                              data: { ...this.form },
                          }
                );
                this.toggleMilestonesModal = false;
                await this.$store.dispatch(
                    "projectManagement/projectData",
                    this.$route.query.id
                );
                this.refresh(true);
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
                    try {
                        await this.$store.dispatch("milestones/destroy", id);
                        await this.$store.dispatch(
                            "projectManagement/projectData",
                            this.$route.query.id
                        );
                        this.refresh(true);
                    } catch (e) {}
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
.overflow-text-milestone {
    width: 35ch;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    display: block;
}
.overflow-text-milestone:hover {
    text-overflow: clip;
    white-space: pre-line;
}
</style>
