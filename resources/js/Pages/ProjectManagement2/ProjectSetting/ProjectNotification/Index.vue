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
                                $t("Notification")
                            }}</span>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="other-items d-flex justify-center">
                <div class="right-side">
                    <button @click="addNotiModal" class="primary-btn">
                        <div class="mr-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="20"
                                height="21"
                                viewBox="0 0 20 21"
                                fill="none"
                            >
                                <path
                                    d="M9.9974 4.66602V16.3327M4.16406 10.4993H15.8307"
                                    stroke="#38414A"
                                    stroke-width="1.67"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </div>
                        <span>{{ $t("Add Notification") }}</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <p>
                    Lorem Ipsum is simply dummy text of the printing and
                    typesetting industry. Lorem Ipsum has been the industry's
                    standard dummy text ever since the 1500s, when an unknown
                    printer took a galley of type and scrambled it to make a
                    type spaecimen book. It has survived not only five
                    centuries, but also the leap into electronic typesetting,
                    remaining essentially unchanged. It was popularised in the
                    1960s with the release of Letraset sheets containing Lorem
                    Ipsum passages, and more recently with desktop publishing
                    software like Aldus PageMaker including versions of Lorem
                    Ipsum.
                </p>
            </div>
            <div class="card-header" style="padding-top: 0 !important">
                <h3 class="card-title">Email</h3>
            </div>
            <div class="card-body">
                <p>
                    Lorem Ipsum is simply dummy text of the printing and
                    typesetting industry. Lorem Ipsum has been the industry's
                    standard dummy text ever since the 1500s, when an unknown
                    printer took a galley of type and scrambled it to make a
                    type spaecimen book. It has survived not only five
                    centuries, but also the leap into electronic typesetting,
                    remaining essentially unchanged. It was popularised in the
                    1960s with the release of Letraset sheets containing Lorem
                    Ipsum passages, and more recently with desktop publishing
                    software like Aldus PageMaker including versions of Lorem
                    Ipsum.
                </p>
                <div class="form-group mt-5">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text cursor-pointer">
                                <CustomIcon name="emailInputIcon" />
                            </span>
                        </div>
                        <text-input
                            v-model="form.prname"
                            :label="$t('Email')"
                        />
                        <div class="input-group-append">
                            <span class="input-group-text cursor-pointer">
                                <button
                                    class="edit-blue-btn"
                                    @click="editNotiEmailModal"
                                >
                                    Edit
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="w-full doc-table">
                <thead>
                    <tr class="text-left">
                        <th>
                            <div class="">{{ $t("Event") }}</div>
                        </th>
                        <th>
                            <div class="">{{ $t("Recipient") }}</div>
                        </th>
                        <th>
                            <div class="text-center">{{ $t("Action") }}</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <span>Issue Created</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <span
                                    >All Watchers, Current Assignee,
                                    Reporter</span
                                >
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center justify-center">
                                <dropdown
                                    :auto-close="false"
                                    placement="bottom-end"
                                >
                                    <template #default>
                                        <div class="cursor-pointer">
                                            <CustomIcon name="dotBarIcon" />
                                        </div>
                                    </template>
                                    <template #dropdown>
                                        <div class="dropdown-menu">
                                            <ul class="dropdown-list">
                                                <li class="dropdown-item">
                                                    <div class="icon mr-2">
                                                        <CustomIcon
                                                            name="editIcon"
                                                        />
                                                    </div>
                                                    <span>{{
                                                        $t("Edit")
                                                    }}</span>
                                                </li>
                                                <li
                                                    class="dropdown-item delete"
                                                >
                                                    <div class="icon mr-2">
                                                        <CustomIcon
                                                            name="DeleteIcon"
                                                        />
                                                    </div>
                                                    <span>{{
                                                        $t("Delete")
                                                    }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </template>
                                </dropdown>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <Modal
        title="Add Notification"
        v-if="toggleNotificationModal"
        @toggleModal="hideModal"
        :classSize="'modal-sm'"
    >
        <template #body>
            <div class="">
                <p>User will be notified the next time this event occurs.</p>
                <div class="form-group">
                    <label for="">When this happens</label>
                    <MultiSelectInput
                        class=""
                        :key="form.participants"
                        v-model="form.participants"
                        :options="users ?? []"
                        :required="true"
                        :multiple="true"
                        :customLabel="customLabel"
                        placeholder="Select Event"
                        trackBy="id"
                        moduleName="auth"
                        :search-param-name="'search_string'"
                        :query="{
                            limit_start: 0,
                            limit_count: 10,
                            type: 'employee',
                        }"
                    />
                </div>
                <div class="checkbox-group mt-3">
                    <input
                        type="checkbox"
                        id="Watchers"
                        class="checkbox-input"
                    />
                    <label for="Watchers" class="checkbox-label">{{
                        $t("All Watchers")
                    }}</label>
                </div>
                <div class="checkbox-group mt-2">
                    <input
                        type="checkbox"
                        id="Assignee"
                        class="checkbox-input"
                    />
                    <label for="Assignee" class="checkbox-label">{{
                        $t("Current Assignee")
                    }}</label>
                </div>
                <div class="checkbox-group mt-2">
                    <input type="checkbox" id="User" class="checkbox-input" />
                    <label for="User" class="checkbox-label">{{
                        $t("Current User")
                    }}</label>
                </div>
                <div class="checkbox-group mt-2">
                    <input
                        type="checkbox"
                        id="Reporter"
                        class="checkbox-input"
                    />
                    <label for="Reporter" class="checkbox-label">{{
                        $t("Reporter")
                    }}</label>
                </div>
                <div class="checkbox-group mt-2">
                    <input type="checkbox" id="Lead" class="checkbox-input" />
                    <label for="Lead" class="checkbox-label">{{
                        $t("Project Lead")
                    }}</label>
                </div>
                <div class="checkbox-group mt-2">
                    <input type="checkbox" id="Role" class="checkbox-input" />
                    <label for="Role" class="checkbox-label">{{
                        $t("Project Role")
                    }}</label>
                </div>
            </div>
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
        title="Edit Notification Email"
        v-if="toggleNotificationEmailModal"
        @toggleModal="hideModal2"
        :classSize="'modal-sm'"
    >
        <template #body>
            <div class="edit-noti">
                <p>
                    To user custom email with your domain, your organization
                    admins must add it from admin.docshero.com. Alternatively,
                    you may customize the prefix of the default email. Learn
                    more about configuring DocsHero cloud to send emails.
                </p>
                <label for="" class="mt-2">Customize default email address</label>
                <div class="form-group">
                    <input type="text" placeholder="Project" class="form-control">
                    <div class="right">
                        @docshero.de
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button @click="hideModal2" type="button" class="primary-btn mr-3">
                <CustomIcon name="cancelIcon" />
                <span>{{ $t("Cancel") }}</span>
            </button>
            <loading-button class="secondary-btn" :loading="isLoading">
                <CustomIcon name="saveIcon" />
                <span>{{ $t("Save") }}</span>
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
            toggleNotificationModal: false,
            toggleNotificationEmailModal: false,
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
        addNotiModal() {
            this.toggleNotificationModal = true;
        },
        hideModal() {
            this.toggleNotificationModal = false;
        },
        editNotiEmailModal() {
            this.toggleNotificationEmailModal = true;
        },
        hideModal2() {
            this.toggleNotificationEmailModal = false;
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
