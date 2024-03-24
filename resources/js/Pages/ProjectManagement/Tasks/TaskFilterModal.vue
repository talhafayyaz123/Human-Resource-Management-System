<template>
    <Modal title="Filters" v-if="toggleFilterTasksModal" @toggleModal="cancel">
        <template #body>
            <div class="">
                <select-input v-model="status" label="Status" class="" @update:modelValue="filter">
                    <option value="">{{ $t("All") }}</option>
                    <option value="new">{{ $t("New") }}</option>
                    <option value="done">{{ $t("Done") }}</option>
                    <option value="in-work">{{ $t("In Work") }}</option>
                    <option value="in-review">{{ $t("In Review") }}</option>
                    <option value="blocked">{{ $t("Blocked") }}</option>
                </select-input>
                <select-input v-if="$can(`milestone.list`)" v-model="milestone" label="Milestone" class=""
                    @update:modelValue="filter">
                    <option value="">All</option>
                    <option v-for="milestone in milestones" :key="'milestone-' + milestone.id" :value="milestone.id">
                        {{ milestone.name }}
                    </option>
                </select-input>
                <MultiSelectInput v-if="$can(`project.list`)" class="" v-model="project" :options="projects.data"
                    :multiple="false" :textLabel="$t('Project')" label="name" trackBy="id" moduleName="projects"
                    @update:modelValue="filter" />
            </div>
        </template>
        <template #footer>
            <loading-button :loading="isLoading" class="btn-green ml-2">
                {{ ("Apply") }}
            </loading-button>
            <button @click="cancel" type="button"
                class="primary-btn mr-3">
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";

export default {
    emits: [
        "toggleFilterTasksModal",
    ],
    computed: {
        ...mapGetters(["isLoading", "errors"]),
    },
    props: {
        toggleFilterTasksModal: {
            type: Boolean,
            default: false,
        }
    },
    watch: {
        toggleFilterTasksModal(val) {
            if (!val) {
                this.resetForm();
            }
        }
    },
    components: {
        Modal,
        LoadingButton,
        MultiSelectInput,
        SelectInput,
    },
    data() {
        return {
            milestones: [],
            status: "",
            milestone: "",
            project: { id: "all", name: "All" },
        };
    },
    methods: {
        cancel() {
            this.$emit("toggleFilterTasksModal", false);
        },
    },
};
</script>

<style></style>
