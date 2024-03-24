<template>
    <Modal
        :title="$t('Hand Over')"
        v-if="toggleHandOverModal"
        @toggleModal="cancel"
        :classSize="'modal-sm'"
    >
        <template #body>
            <div class="grid items-center grid-cols-1 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :showLabels="false"
                            :required="true"
                            v-model="handOverUsers"
                            :options="shareableUsers"
                            :key="user"
                            :multiple="true"
                            :textLabel="$t('Users')"
                            label="first_name"
                            trackBy="id"
                            class="pb-8 pr-6 lg:w-1/3"
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
                @click="handOver"
                :loading="isLoading"
                class="secondary-btn"
            >
                {{ $t("Hand Over") }}
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
    emits: ["toggleHandOverModal", "refresh"],
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
            handOverUsers: [],
        };
    },
    props: {
        toggleHandOverModal: {
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
        async handOver() {
            this.$store.commit("isLoading", true);
            try {
                await this.$store.dispatch("myTasks/handOver", {
                    taskId: this.task?.id,
                    assignees: this.handOverUsers.map((assignee) => {
                        return {
                            userId: assignee.id,
                        };
                    }),
                });
                this.$emit("refresh", true);
                this.$emit("toggleHandOverModal", false);
                this.handOverUsers = [];
            } catch (e) {}
        },
        cancel() {
            this.$emit("toggleHandOverModal", false);
            this.$store.commit("flashMessage", {
                show: false,
                message: "",
                type: "",
                link: "",
            });
            this.$store.commit("errors", {});
            this.handOverUsers = [];
        },
    },
};
</script>

<style scoped></style>
