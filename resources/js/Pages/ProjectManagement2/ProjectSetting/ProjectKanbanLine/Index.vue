<template>
    <div>
        <div class="page-header">
            <div class="page-title">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 align-items-center">
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">{{
                                $t("Admin Portal")
                            }}</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">{{
                                $t("Project Settings")
                            }}</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="cursor-pointer">{{
                                $t("KABANA Lines")
                            }}</span>
                        </li>
                    </ol>
                </div>
            </div>
          
        </div>

        <div class="khabana-line-page">
            <p>
                Use columns and statuses to define how work progresses on your
                board. Store statuses in the left panel to hide their associated
                issues from the board and backlog.
            </p>
            <div class="khabana-card card mt-3 p-3">
                <div class="unassigned-status card">
                    <div class="">
                        <h3 class="card-title">
                            {{ $t("Unassigned Statuses") }}
                        </h3>
                    </div>
                    <div class="h-full">
                        <div
                            class="h-full flex items-center flex-column justify-center"
                        >
                            <CustomIcon name="bookIcon" />
                            <p style="margin-top: 40px">
                                Drag and drop a status here to hide it from the
                                board and backlog. Issue with these statuses
                                won’t be visible.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="khabana-board">
                    <div class="flow">
                        <div class="flow-card">
                            <h4>In Arbit</h4>
                            <div class="edit-mode" @click="editColumnModal()">
                                <CustomIcon name="fillEditIcon" />
                            </div>
                        </div>
                        <div class="flow-card">
                            <h4>In Arbit</h4>
                            <div class="edit-mode" @click="editColumnModal()">
                                <CustomIcon name="fillEditIcon" />
                            </div>
                        </div>
                        <div class="flow-card">
                            <h4>In Arbit</h4>
                            <div class="edit-mode" @click="editColumnModal()">
                                <CustomIcon name="fillEditIcon" />
                            </div>
                        </div>
                        <div class="flow-card">
                            <h4>In Arbit</h4>
                            <div class="edit-mode" @click="editColumnModal()">
                                <CustomIcon name="fillEditIcon" />
                            </div>
                        </div>
                        <div class="flow-add-btn" @click="createColumnModal">
                            <CustomIcon name="addFillIcon" />
                        </div>
                    </div>
                    <div class="status-for mt-3 mb-3 flex items-center">
                        <p>Statuses for</p>
                        <div class="flex items-center">
                            <span class="mr-1 ml-1"
                                ><CustomIcon name="themeIcon"
                            /></span>
                            <span class="mr-1"
                                ><CustomIcon name="initiateIcon"
                            /></span>
                            <span class="mr-1"
                                ><CustomIcon name="epicIcon"
                            /></span>
                            <span class="mr-1"
                                ><CustomIcon name="requestIcon"
                            /></span>
                            <span class="mr-1"
                                ><CustomIcon name="SupportIcon"
                            /></span>
                        </div>
                        <p>Manage workflow</p>
                    </div>
                    <div class="boards">
                        <div class="board-card">
                            <h4>In Progress <span>(02) issues</span></h4>
                        </div>
                        <div class="board-card">
                            <h4>In Test <span>(02) issues</span></h4>
                        </div>
                        <div class="board-card">
                            <h4>Deferred <span>(02) issues</span></h4>
                        </div>
                        <div class="board-card done">
                            <h4>Done <span>(01) issues</span></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <button class="primary-btn delete mr-3">
                    <span class="mr-2">
                        <CustomIcon name="DeleteIcon" />
                    </span>
                    <span>{{ $t("Discard") }}</span>
                </button>
                <button class="secondary-btn">
                    <span class="mr-2">
                        <CustomIcon name="saveIcon" />
                    </span>
                    <span>{{ $t("Save and Proceed") }}</span>
                </button>
            </div>
        </div>
    </div>
    <Modal
        title="Create Column"
        v-if="toggleColumnModal"
        @toggleModal="hideModal"
        :classSize="'modal-sm'"
    >
        <template #body>
            <div class="form-group">
                <label for="">Name <span>*</span></label>
                <input class="form-control" type="text" placeholder="In Test" />
            </div>
            <div class="form-group mt-2">
                <label for="">Maximum number of issue</label>
                <input
                    class="form-control"
                    type="text"
                    placeholder="(no maximum)"
                />
            </div>
            <p class="mt-2">
                The column highlights when there are too many issues in it
            </p>
        </template>
        <template #footer>
            <button @click="hideModal" type="button" class="primary-btn mr-3">
                <CustomIcon name="cancelIcon" />
                <span>{{ $t("Cancel") }}</span>
            </button>
            <loading-button class="secondary-btn" :loading="isLoading">
                <CustomIcon name="saveIcon" />
                <span>{{ $t("Save") }}</span>
            </loading-button>
        </template>
    </Modal>
    <Modal
        title="Edit Column"
        v-if="toggleEditColumnModal"
        @toggleModal="hideModal"
        :classSize="'modal-sm'"
    >
        <template #body>
            <div class="form-group">
                <label for="">Name <span>*</span></label>
                <input class="form-control" type="text" placeholder="In Test" />
            </div>
            <div class="form-group mt-2">
                <label for="">Maximum number of issue</label>
                <input
                    class="form-control"
                    type="text"
                    placeholder="(no maximum)"
                />
            </div>
            <p class="mt-2">
                The column highlights when there are too many issues in it
            </p>
        </template>
        <template #footer>
            <button type="button" class="primary-btn delete mr-3">
                <CustomIcon name="DeleteIcon" />
                <span>{{ $t("Discard") }}</span>
            </button>
            <button @click="hideModal2" type="button" class="primary-btn mr-3">
                <CustomIcon name="cancelIcon" />
                <span>{{ $t("Cancel") }}</span>
            </button>
            <loading-button class="secondary-btn" :loading="isLoading">
                <CustomIcon name="updateIcon" />
                <span>{{ $t("Update") }}</span>
            </loading-button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import Icon from "@/Components/Icon.vue";
import SelectInput from "@/Components/SelectInput.vue";
import Pagination from "laravel-vue-pagination";
import { mapGetters } from "vuex";
import SearchFilter from "@/Components/SearchFilter.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { debounce } from "@/utils/debounce";
import Dropdown from "@/Components/Dropdown.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextareaInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
export default {
    computed: {
        ...mapGetters("projects", ["projects"]),
    },
    components: {
        Icon,
        Pagination,
        SearchFilter,
        PageHeader,
        SelectInput,
        Dropdown,
        MultiSelectInput,
        TextInput,
        TextArea,
        Modal,
        LoadingButton,
    },

    data() {
        return {
            showSearch: false,
            toggleColumnModal: false,
            toggleEditColumnModal: false,
            form: {
                prname: "",
                participants: [],
                startDate: new Date(),
            },
        };
    },
    methods: {
        searchToggle() {
            this.showSearch = true;
        },
        closeSearch() {
            this.showSearch = false;
        },
        createColumnModal() {
            this.toggleColumnModal = true;
        },
        hideModal() {
            this.toggleColumnModal = false;
        },
        editColumnModal() {
            this.toggleEditColumnModal = true;
        },
        hideModal2() {
            this.toggleEditColumnModal = false;
        },
    },
    beforeRouteEnter(to, from, next) {
        document.body.classList.add("project-management-page");
        next();
    },
    beforeRouteLeave(to, from, next) {
        document.body.classList.remove("project-management-page");
        next();
    },
};
</script>
