<template>
  <div>
    <div class="flex flex-wrap pt-8">
      <select-input
        @change="refresh"
        v-model="status"
        label="Status"
        class="pb-8 pr-6 lg:w-1/4"
      >
        <option value="">{{ $t("All") }}</option>
        <option value="new">{{ $t("New") }}</option>
        <option value="done">{{ $t("Done") }}</option>
        <option value="in-work">{{ $t("In Work") }}</option>
        <option value="in-review">{{ $t("In Review") }}</option>
        <option value="blocked">{{ $t("Blocked") }}</option>
      </select-input>
      <select-input
        @change="refresh"
        v-model="priority"
        label="Priority"
        class="pb-8 pr-6 lg:w-1/4"
      >
        <option value="">{{ $t("All") }}</option>
        <option value="low">{{ $t("Low") }}</option>
        <option value="medium">{{ $t("Medium") }}</option>
        <option value="high">{{ $t("High") }}</option>
      </select-input>
      <select-input
        @change="refresh"
        v-model="milestoneId"
        label="Milestone"
        class="pb-8 pr-6 lg:w-1/4"
      >
        <option value="">{{ $t("All") }}</option>
        <option
          v-for="milestone in milestones"
          :key="'milestone-' + milestone.id"
          :value="milestone.id"
        >
          {{ milestone.name }}
        </option>
      </select-input>
      <MultiSelectInput
        @update:modelValue="refresh"
        :showLabels="false"
        class="pb-8 pr-6 lg:w-1/4"
        v-model="employeeId"
        :options="users"
        :multiple="false"
        textLabel="Assignee"
        label="first_name"
        trackBy="id"
        moduleName="auth"
        :query="{ limit_start: 0, limit_count: 25, type: 'employee' }"
        :search-param-name="'search_string'"
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
    </div>
    <div class="flex items-center justify-between mb-6">
      <button
        v-if="$can(`task.create`)"
        class="secondary-btn"
        @click="
          modalType = 'add';
          toggleTasksModal = true;
          resetFlashAndErrors();
        "
      >
        <span>{{ $t("Create") }}</span>
        <span class="hidden md:inline">&nbsp;{{ $t("Task") }}</span>
      </button>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
          <th class="pb-4 pt-6 px-6">{{ $t("Status") }}</th>
          <th class="pb-4 pt-6 px-6">
            {{ $t("Planned Start Date") }}
          </th>
          <th class="pb-4 pt-6 px-6">
            {{ $t("Actual Start Date") }}
          </th>
          <th class="pb-4 pt-6 px-6">
            {{ $t("Earliest Possible Start Date") }}
          </th>
          <th class="pb-4 pt-6 px-6">
            {{ $t("Actual Finished Date") }}
          </th>
          <th class="pb-4 pt-6 px-6">
            {{ $t("Last Expected Finished Date") }}
          </th>
          <th class="pb-4 pt-6 px-6">
            {{ $t("Planned Finished Date") }}
          </th>
          <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
        </tr>
        <tr
          v-for="task in tasks?.data ?? []"
          :key="task.id"
          class="hover:bg-gray-100 focus-within:bg-gray-100"
        >
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4 focus:text-indigo-500 overflow-text-task"
            >
              {{ task.name }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4"
              tabindex="-1"
            >
              {{ task.status }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4"
              tabindex="-1"
            >
              {{ task.plannedStartDate }}
            </a>
          </td>

          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4"
              tabindex="-1"
            >
              {{ task.actualStartDate }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4"
              tabindex="-1"
            >
              {{ task.earliestStartDate }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4"
              tabindex="-1"
            >
              {{ task.actualFinishedDate }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4"
              tabindex="-1"
            >
              {{ task.expectedFinishedDate }}
            </a>
          </td>
          <td class="border-t">
            <a
              href="javascript:void(0)"
              class="flex items-center px-6 py-4"
              tabindex="-1"
            >
              {{ task.plannedFinishedDate }}
            </a>
          </td>
          <td class="w-px border-t">
            <button
              v-if="$can(`task.edit`)"
              class="px-1"
              @click="openUpdateModal(task.id)"
            >
              <font-awesome-icon icon="fa-regular fa-pen-to-square"/>
            </button>
            <button v-if="$can(`task.delete`)" @click="destroy(task.id)">
              <font-awesome-icon icon="fa-regular fa-trash-can"/>
            </button>
          </td>
        </tr>
        <tr v-if="(tasks?.data?.length ?? 0) === 0">
          <td class="px-6 py-4 border-t" colspan="4">
            {{ $t("No task found") }}.
          </td>
        </tr>
      </table>
    </div>
    <div style="margin-top: 3rem" class="flex justify-center">
      <pagination
        :limit="10"
        align="center"
        :data="tasks"
        @pagination-change-page="changePageOrSearch"
      ></pagination>
      <br />
      <br />
    </div>
  </div>
  <TaskModal
    :users="users"
    :task="task"
    :toggleTasksModal="toggleTasksModal"
    :modalType="modalType"
    :milestones="milestones"
    @toggleTasksModal="toggleTasksModal = $event"
    @refresh-milestones="refreshMilestones"
    @create-or-update-task="createOrUpdateTask"
  />
</template>

<script>
import Pagination from "laravel-vue-pagination";
import TextInput from "@/Components/TextInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import SelectInput from "@/Components/SelectInput.vue";
import SelectLinkInput from "@/Components/SelectLinkInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import DateInput from "@/Components/DateInput.vue";
import SearchFilter from "@/Components/SearchFilter.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import TaskModal from "./TaskModal.vue";
import { mapGetters } from "vuex";

export default {
  computed: {
    ...mapGetters(["errors", "isLoading"]),
    ...mapGetters("tasks", ["tasks"]),
    ...mapGetters("auth", ["users"]),
  },
  mounted() {
    this.refresh();
  },
  components: {
    TaskModal,
    Pagination,
    LoadingButton,
    TextInput,
    SelectInput,
    DateInput,
    QuillTextEditor,
    FlashMessages,
    SelectLinkInput,
    SearchFilter,
    MultiSelectInput,
  },
  data() {
    return {
      page: 1,
      milestones: [],
      status: "",
      priority: "",
      milestoneId: "",
      employeeId: "",
      toggleTasksModal: false,
      modalType: "add",
      task: null,
    };
  },
  methods: {
    async changePageOrSearch(page = 1) {
      this.page = page;
      await this.$store.dispatch("tasks/list", {
        page: this.page,
      });
    },
    async refreshMilestones() {
      try {
        const response = await this.$store.dispatch("tasks/list", {
          projectId: this.$route.query.id,
        });
        this.milestones = response?.data?.milestones ?? [];
      } catch (e) {}
    },
    openUpdateModal(id) {
      this.modalType = "update";
      const task = this.tasks.data.find((task) => task.id == id) ?? {};
      this.task = {
        id: task.id,
        taskId: task.taskId,
        status: task.status,
        description: task.description,
        name: task.name,
        plannedStartDate: task.plannedStartDate,
        actualStartDate: task.actualStartDate,
        earliestStartDate: task.earliestStartDate,
        actualFinishedDate: task.actualFinishedDate,
        expectedFinishedDate: task.expectedFinishedDate,
        plannedFinishedDate: task.plannedFinishedDate,
        estimatedTime: task.estimatedTime,
        spentTime: task.spentTime,
        milestoneId:
          this.milestones.find(
            (milestone) => milestone.id == task.milestoneId
          ) ?? "",
        employeeId:
          this.users?.find((user) => user.id == task.employeeId) ?? "",
        priority: task.priority,
        projectId: this.$route.query.id,
        comments: task.comments,
        relationships: task.relationships,
      };
      this.toggleTasksModal = true;
      this.$store.commit("flashMessage", {
        show: false,
        message: "",
        type: "",
        link: "",
      });
      this.$store.commit("errors", {});
    },
    refresh() {
      this.$nextTick(async () => {
        try {
          this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
          });
          const response = await this.$store.dispatch("tasks/list", {
            projectId: this.$route.query.id,
            status: this.status,
            priority: this.priority,
            employeeId: this.employeeId?.id ?? "",
            milestoneId: this.milestoneId,
          });
          this.milestones = response?.data?.milestones ?? [];
          if (this.$route.query.taskId) {
            this.openUpdateModal(this.$route.query.taskId);
          }
        } catch (e) {
          console.log(e);
        }
      });
    },
    async createOrUpdateTask(form) {
      let task = { ...form };
      const date1 = new Date(task.plannedStartDate);
      const date2 = new Date(task.plannedFinishedDate);
      const diffTime = Math.abs(date2 - date1);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      let hoursPerDay = +task.estimatedTime / +diffDays;
      hoursPerDay = isNaN(hoursPerDay) ? 0 : hoursPerDay;
      task = {
        ...task,
        taskHours: (() => {
          const taskHours = {};
          let startDate = task.plannedStartDate
            ? new Date(task.plannedStartDate)
            : new Date();
          const endDate = task.plannedFinishedDate
            ? new Date(task.plannedFinishedDate)
            : new Date();
          while (
            startDate.setHours(0, 0, 0, 0) != endDate.setHours(0, 0, 0, 0) &&
            endDate.setHours(0, 0, 0, 0) >= startDate.setHours(0, 0, 0, 0)
          ) {
            taskHours[startDate.format("YYYY-MM-DD")] = {
              hours: hoursPerDay ?? 0,
            };
            startDate = startDate.addDays(1);
          }
          taskHours[startDate.format("YYYY-MM-DD")] = {
            hours: hoursPerDay ?? 0,
          };
          return taskHours;
        })(),
      };
      task.milestoneId = task.milestoneId?.id ?? task.milestoneId;
      try {
        this.$store.commit("isLoading", true);
        await this.$store.dispatch(
          `tasks/${this.modalType == "add" ? "create" : "update"}`,
          this.modalType == "add"
            ? {
                ...task,
                milestoneId: form.milestoneId?.id ?? form.milestoneId,
                employeeId: form.employeeId?.id,
              }
            : {
                id: form.id,
                data: {
                  ...task,
                  employeeId: form.employeeId?.id,
                },
              }
        );
        await this.$store.dispatch(
          "projectManagement/projectData",
          this.$route.query.id
        );
        await this.$store.dispatch("projectManagement/myTasks");
        this.refresh(true);
        this.toggleTasksModal = false;
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
                await this.$store.dispatch("tasks/destroy", id);
                this.toggleTasksModal = false;
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
.overflow-text-task {
  width: 35ch;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  display: block;
}

.overflow-text-task:hover {
  text-overflow: clip;
  white-space: pre-line;
}
</style>
