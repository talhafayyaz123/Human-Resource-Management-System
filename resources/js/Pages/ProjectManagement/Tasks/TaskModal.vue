<template>
  <Modal
    :title="(modalType == 'add' ? 'Add' : 'Edit') + ' Task'"
    v-if="toggleTasksModal"
    @toggleModal="cancel"
  >
    <template #body>
      <div class="px-4">
        <FlashMessages />
      </div>
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">
        <text-input
          v-model="form.name"
          :required="true"
          :error="errors.name"
          class="pb-8 pr-6 lg:w-1/3"
          :label="$t('Name')"
        />
        <select-input
          :required="true"
          v-model="form.status"
          :error="errors.status"
          label="Status"
          class="pb-8 pr-6 lg:w-1/3"
        >
          <option value="new">{{ $t("New") }}</option>
          <option value="done">{{ $t("Done") }}</option>
          <option value="in-work">{{ $t("In Work") }}</option>
          <option value="in-review">{{ $t("In Review") }}</option>
          <option value="blocked">{{ $t("Blocked") }}</option>
        </select-input>
        <select-link-input
          :required="!fromMyTasks"
          :isReadonly="false"
          :options="milestones"
          v-model="form.milestoneId"
          :error="errors.milestoneId"
          :pStore="'milestone'"
          :selectValue="form.milestoneId"
          @refresh="$emit('refreshMilestones', true)"
          class="pb-8 pr-6 w-full lg:w-1/3"
          label="Milestone"
        ></select-link-input>
      </div>
      <div class="-mr-6 p-8 pt-0">
        <QuillTextEditor
          class="pb-2 pr-6 w-full"
          :content="form.description"
          @delta="form.description = $event"
          label="Description"
          :error="errors.description"
        />
      </div>
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">
        <DateInput
          v-model="form.plannedStartDate"
          class="pb-8 pr-6 lg:w-1/3"
          label="Planned Start Date"
          :error="errors.plannedStartDate"
        />
        <DateInput
          v-model="form.plannedFinishedDate"
          class="pb-8 pr-6 lg:w-1/3"
          label="Planned Finished Date"
          :setCurrentDate="!form.plannedFinishedDate"
          :error="errors.plannedFinishedDate"
        />
        <DateInput
          v-model="form.actualStartDate"
          class="pb-8 pr-6 lg:w-1/3"
          label="Actual Start Date"
          :error="errors.actualStartDate"
        />
      </div>
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">
        <DateInput
          v-model="form.actualFinishedDate"
          class="pb-8 pr-6 lg:w-1/3"
          label="Actual Finished Date"
          :error="errors.actualFinishedDate"
        />
        <DateInput
          v-model="form.earliestStartDate"
          class="pb-8 pr-6 lg:w-1/3"
          label="Earliest Start Date"
          :error="errors.earliestStartDate"
        />
        <DateInput
          v-model="form.expectedFinishedDate"
          class="pb-8 pr-6 lg:w-1/3"
          label="Expected Finished Date"
          :setCurrentDate="!form.expectedFinishedDate"
          :error="errors.expectedFinishedDate"
        />
      </div>
      <div class="flex flex-wrap -mb-8 -mr-6 p-8">
        <MultiSelectInput
          :key="form.employeeId"
          :showLabels="false"
          class="pb-8 pr-6 lg:w-1/3"
          v-model="form.employeeId"
          :options="users"
          :multiple="false"
          :required="fromMyTasks"
          textLabel="Assignee"
          label="first_name"
          trackBy="id"
          moduleName="auth"
          :search-param-name="'search_string'"
          :error="errors.employeeId"
        >
          <template #beforeList>
            <div
              class="grid p-2 gap-2"
              style="grid-template-columns: 25% 25% 50%"
            >
              <p class="font-bold">First Name</p>
              <p class="font-bold">Last Name</p>
              <p class="font-bold">Email</p>
            </div>
            <hr />
          </template>
          <template #singleLabel="{ props }">
            {{ props.option.first_name }}
            {{ props.option.last_name }}
          </template>
          <template #option="{ props }">
            <div class="grid gap-2" style="grid-template-columns: 25% 25% 50%">
              <p class="overflow-text-users-table">
                {{ props.option.first_name }}
              </p>
              <p class="overflow-text-users-table">
                {{ props.option.last_name }}
              </p>
              <p class="overflow-text-users-table">
                {{ props.option.email }}
              </p>
            </div>
          </template>
        </MultiSelectInput>
        <text-input
          v-model="form.estimatedTime"
          :error="errors.estimatedTime"
          class="pb-8 pr-6 lg:w-1/3"
          label="Estimated Time (hrs)"
          type="number"
        />
        <text-input
          v-model="form.spentTime"
          :error="errors.spentTime"
          class="pb-8 pr-6 lg:w-1/3"
          label="Spent Time (hrs)"
          type="number"
        />
        <select-input
          :required="true"
          :key="form.priority"
          v-model="form.priority"
          :error="errors.priority"
          label="Priority"
          class="pb-8 pr-6 lg:w-1/3"
        >
          <option value="low">{{ $t("Low") }}</option>
          <option value="medium">{{ $t("Medium") }}</option>
          <option value="high">{{ $t("High") }}</option>
        </select-input>
      </div>
    </template>
    <template #footer>
      <loading-button
        @click="$emit('createOrUpdateTask', form)"
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
import Modal from "@/Components/EditModal.vue";
import TextInput from "@/Components/TextInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import SelectInput from "@/Components/SelectInput.vue";
import SelectLinkInput from "@/Components/SelectLinkInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import DateInput from "@/Components/DateInput.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";

export default {
  emits: [
    "toggleTasksModal",
    "refreshMilestones",
    "createOrUpdateTask",
    "resetFlashAndErrors",
  ],
  computed: {
    ...mapGetters(["isLoading", "errors"]),
  },
  props: {
    fromMyTasks: {
      type: Boolean,
      default: false,
    },
    users: {
      type: Array,
      default: () => [],
    },
    task: {
      type: Object,
      default: null,
    },
    toggleTasksModal: {
      type: Boolean,
      default: false,
    },
    modalType: {
      type: String,
      default: "add",
    },
    milestones: {
      type: Array,
      default: () => [],
    },
  },
  watch: {
    toggleTasksModal(val) {
      if (!val) {
        this.resetForm();
      }
    },
    task(val) {
      if (typeof val === "object") {
        this.form = { ...val };
      }
    },
  },
  components: {
    Modal,
    LoadingButton,
    FlashMessages,
    DateInput,
    MultiSelectInput,
    SelectLinkInput,
    SelectInput,
    QuillTextEditor,
    TextInput,
  },
  data() {
    return {
      form: {
        taskId: "fnkwfn",
        status: "new",
        description: "",
        priority: "medium",
        employeeId: "",
        name: "",
        plannedStartDate: "",
        actualStartDate: "",
        earliestStartDate: "",
        actualFinishedDate: "",
        expectedFinishedDate: "",
        plannedFinishedDate: "",
        estimatedTime: "",
        spentTime: "",
        milestoneId: "",
        comments: "",
        relationships: [],
      },
    };
  },
  methods: {
    /**
     * resets the form to default
     */
    resetForm() {
      this.form = {
        taskId: "fnkwfn",
        status: "new",
        description: "",
        priority: "medium",
        employeeId: "",
        name: "",
        plannedStartDate: "",
        actualStartDate: "",
        earliestStartDate: "",
        actualFinishedDate: "",
        expectedFinishedDate: "",
        plannedFinishedDate: "",
        estimatedTime: "",
        spentTime: "",
        milestoneId: "",
        comments: "",
        relationships: [],
      };
    },
    /**
     * returns a custom label value for the selected option in multiselect component
     * @param {props} the properties of the options
     */
    customLabel(props) {
      return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
    },
    /**
     * resets the form when the modal is toggled off
     * resets the errors
     * resets any flash messages
     */
    cancel() {
      this.resetForm();
      this.$emit("toggleTasksModal", false);
      this.$store.commit("flashMessage", {
        show: false,
        message: "",
        type: "",
        link: "",
      });
      this.$store.commit("errors", {});
    },
  },
};
</script>

<style></style>
