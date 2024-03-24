<template>
    <Modal
        :title="$t('Share Board')"
        v-if="toggleShareableUsersBoardModal"
        @toggleModal="cancel"
        :classSize="'modal-sm'"
    >
        <template #body>
            <div class="grid items-center grid-cols-1 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :showLabels="false"
                            v-model="shareableUsers"
                            :options="users"
                            :key="shareableUsers"
                            :multiple="true"
                            :textLabel="$t('Users')"
                            label="first_name"
                            trackBy="id"
                            moduleName="auth"
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
                @click="addShareableUsers"
                :loading="isLoading"
                class="secondary-btn"
            >
                {{ $t("Share") }}
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
    emits: ["toggleShareableUsersBoardModal"],
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("auth", ["users", "user"]),
        ...mapGetters("taskBoards", ["selectedBoard"]),
    },
    data() {
        return {
            shareableUsers: [],
        };
    },
    props: {
        toggleShareableUsersBoardModal: {
            type: Boolean,
            default: false,
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
        // update the task to assign it to another board
        async addShareableUsers() {
            try {
                this.$store.commit("isLoading", true);
                let shareableUsers = this.selectedBoard?.shareableUsers ?? [];
                shareableUsers = [
                    ...shareableUsers,
                    ...this.shareableUsers.map((user) => user.id),
                ];
                await this.$store.dispatch("taskBoards/editBoardUsers", {
                    id: this.selectedBoard?.id,
                    data: {
                        shareableUsers: Array.from(new Set(shareableUsers))
                            .filter((userId) => userId != this.user.id)
                            .map((userId) => ({ userId: userId })),
                    },
                });
                // hide modal
                this.$emit("toggleShareableUsersBoardModal", false);
                // refresh task listing
                this.$emit("refresh", true);
                this.shareableUsers = [];
            } catch (e) {
                console.log(e);
            }
        },
        cancel() {
            this.$emit("toggleShareableUsersBoardModal", false);
            this.$store.commit("flashMessage", {
                show: false,
                message: "",
                type: "",
                link: "",
            });
            this.$store.commit("errors", {});
            this.shareableUsers = [];
        },
    },
};
</script>

<style scoped></style>
