<template>
    <Modal
        :title="$t('Assume')"
        v-if="toggleAssumeModal"
        @toggleModal="cancel"
        :classSize="'modal-sm'"
    >
        <template #body
            >?
            <div class="grid items-center grid-cols-1 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :showLabels="false"
                            :required="true"
                            v-model="user"
                            :options="shareableUsers"
                            :key="user"
                            :multiple="false"
                            :textLabel="$t('User')"
                            label="first_name"
                            trackBy="id"
                            :search-param-name="'search_string'"
                            :customLabel="customLabel"
                        >
                            <template #beforeList>
                                <div
                                    class="grid p-2 gap-2"
                                    style="grid-template-columns: 25% 25% 50%"
                                >
                                    <p class="font-bold">
                                        {{ $t("First Name") }}
                                    </p>
                                    <p class="font-bold">
                                        {{ $t("Last Name") }}
                                    </p>
                                    <p class="font-bold">
                                        {{ $t("Email") }}
                                    </p>
                                </div>
                                <hr />
                            </template>
                            <template #singleLabel="{ props }">
                                <p>
                                    {{ props.option.first_name }}
                                    {{ props.option.last_name }}
                                </p>
                            </template>
                            <template #option="{ props }">
                                <div
                                    class="grid"
                                    style="grid-template-columns: 25% 25% 50%"
                                >
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
                </div>
            </div>
        </template>
        <template #footer>
            <button @click="cancel" type="button" class="primary-btn mr-3">
                {{ $t("Cancel") }}
            </button>
            <loading-button
                @click="assignToUser"
                :loading="isLoading"
                class="secondary-btn"
            >
                {{ $t("Assign") }}
            </loading-button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["toggleAssumeModal"],
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("auth", ["users", "userProfilePictures"]),
        ...mapGetters("taskBoards", ["selectedBoard"]),
        // get entire user objects from 'userProfilePictures' state filtered by board shareable users
        shareableUsers() {
            return (this.selectedBoard?.shareableUsers ?? [])
                .map((userId) => this.userProfilePictures[userId])
                .filter((user) => !!user);
        },
    },
    data() {
        return {
            user: "",
        };
    },
    props: {
        toggleAssumeModal: {
            type: Boolean,
            default: false,
        },
        task: {
            type: Object,
            default: null,
        },
    },
    components: {
        Modal,
        LoadingButton,
        MultiSelectInput,
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async assignToUser() {
            this.$store.commit("isLoading", true);
            try {
                await this.$store.dispatch("myTasks/assumeTask", {
                    taskId: this.task?.id,
                    userId: this.user?.id,
                });
                this.$emit("refresh", true);
                this.$emit("toggleAssumeModal", false);
                this.user = "";
            } catch (e) {}
        },
        cancel() {
            this.$emit("toggleAssumeModal", false);
            this.$store.commit("flashMessage", {
                show: false,
                message: "",
                type: "",
                link: "",
            });
            this.$store.commit("errors", {});
            this.user = "";
        },
    },
};
</script>

<style scoped></style>
