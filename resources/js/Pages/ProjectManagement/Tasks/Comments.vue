<template>
    <label>{{ $t("Comments") }}:</label>
    <div :id="'task-comments-' + taskId" class="flex w-full mt-3 pr-6">
        <div
            :style="{
                backgroundImage:
                    'url(' +
                    (userProfilePictures?.[user?.id]?.profile_image ?? '') +
                    ')',
            }"
            class="flex justify-center items-center relative mr-2 self-start"
            style="
                background-position: center center;
                background-repeat: no-repeat;
                background-size: cover;
                min-height: 45px;
                min-width: 45px;
                border-radius: 50%;
                background-color: #ed7d31;
                color: white;
                overflow: hidden;
            "
        >
            <p
                style="font-size: 0.9rem"
                v-if="!userProfilePictures?.[user?.id]?.profile_image"
            >
                {{
                    getInitials(
                        (user?.first_name ?? "") + " " + (user?.last_name ?? "")
                    )
                }}
            </p>
        </div>
        <TextInput
            @click="showQuill = true"
            v-if="!showQuill"
            :placeholder="$t('Add a comment...')"
            class="w-full com-input"
        ></TextInput>
        <div id="comment-save-section" class="pb-5" v-else>
            <QuillTextEditor
                :content="form.text"
                :error="errors.text"
                @delta="form.text = $event"
                :saveUploadedImagesToStorage="true"
            />
            <button @click="save" class="px-3 py-2 secondary-btn">
                {{ $t("Save") }}
            </button>
            <button @click="showQuill = false" class="px-3 py-2">
                {{ $t("Cancel") }}
            </button>
        </div>
    </div>
    <div>
        <div
            class="flex mt-5"
            v-for="(comment, index) in comments"
            :key="'comment-' + index"
        >
            <div
                :style="{
                    backgroundImage:
                        'url(' +
                        (userProfilePictures?.[comment?.userId]
                            ?.profile_image ?? '') +
                        ')',
                }"
                class="flex justify-center items-center relative mr-2 self-start"
                style="
                    background-position: center center;
                    background-repeat: no-repeat;
                    background-size: cover;
                    min-height: 45px;
                    min-width: 45px;
                    border-radius: 50%;
                    background-color: #ed7d31;
                    color: white;
                    overflow: hidden;
                "
            >
                <p
                    style="font-size: 0.9rem"
                    v-if="
                        !userProfilePictures?.[comment?.userId]?.profile_image
                    "
                >
                    {{
                        getInitials(
                            (userProfilePictures?.[comment?.userId]
                                ?.first_name ?? "") +
                                " " +
                                (userProfilePictures?.[comment?.userId]
                                    ?.last_name ?? "")
                        )
                    }}
                </p>
            </div>
            <div>
                <p class="font-medium" style="font-size: 0.9rem">
                    {{
                        (userProfilePictures?.[comment?.userId]?.first_name ??
                            "") +
                        " " +
                        (userProfilePictures?.[comment?.userId]?.last_name ??
                            "")
                    }}
                    <span class="font-normal ml-2">
                        <!-- {{ comment.createdAt }} -->
                        {{ $dateFormatter(comment.createdAt, globalLanguage) }}
                    </span>
                </p>
                <p
                    v-if="
                        !toggleEdit ||
                        (toggleEdit && editCommentId !== comment.id)
                    "
                    v-html="comment.body"
                ></p>
                <div
                    id="comment-edit-section"
                    class="pb-5"
                    v-if="toggleEdit && editCommentId === comment.id"
                >
                    <QuillTextEditor
                        :content="editText"
                        :error="errors.text"
                        @delta="editText = $event"
                        :saveUploadedImagesToStorage="true"
                    />
                    <button @click="edit" class="px-3 py-2 secondary-btn">
                        {{ $t("Save") }}
                    </button>
                    <button
                        @click="
                            toggleEdit = false;
                            editText = '';
                            editCommentId = null;
                        "
                        class="px-3 py-2"
                    >
                        {{ $t("Cancel") }}
                    </button>
                </div>
                <template
                    v-if="
                        !toggleEdit ||
                        (toggleEdit && editCommentId !== comment.id)
                    "
                >
                    <button
                        @click="
                            toggleEdit = true;
                            editText = comment.body;
                            editCommentId = comment.id;
                        "
                        class="text-gray-500 mr-3"
                    >
                        {{ $t("Edit") }}
                    </button>
                    <button
                        @click="deleteComment(comment.id)"
                        class="text-gray-500"
                    >
                        {{ $t("Delete") }}
                    </button>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import TextInput from "@/Components/TextInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin.js";

export default {
    mixins: [userProfilePicturesMixin],
    props: {
        taskId: {
            type: Number,
        },
        showQuillParent: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        ...mapGetters("auth", ["userProfilePictures", "user"]),
        ...mapGetters(["errors"]),
    },
    components: {
        TextInput,
        QuillTextEditor,
    },
    data() {
        return {
            editCommentId: null,
            toggleEdit: false,
            editText: "",
            form: {
                text: "",
            },
            showQuill: false,
            comments: [],
        };
    },
    watch: {
        showQuill(val) {
            if (val) {
                this.$nextTick(() => {
                    document
                        .querySelector("#comment-save-section")
                        .scrollIntoView({
                            behavior: "smooth",
                            block: "end",
                            inline: "end",
                        });
                });
            }
        },
        toggleEdit(val) {
            if (val) {
                this.$nextTick(() => {
                    document
                        .querySelector("#comment-edit-section")
                        .scrollIntoView({
                            behavior: "smooth",
                            block: "end",
                            inline: "end",
                        });
                });
            }
        },
    },
    mounted() {
        this.$nextTick(() => {
            this.showQuill = this.showQuillParent;
        });
        this.refresh();
    },
    methods: {
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "taskComments/list",
                    {
                        taskId: this.taskId,
                    }
                );
                this.comments = response?.data?.data ?? [];
                this.getUserProfilePictures(this.comments, "userId");
            } catch (e) {
                console.log(e);
            }
        },
        async edit() {
            try {
                await this.$store.dispatch("taskComments/update", {
                    id: this.editCommentId,
                    data: {
                        body: this.editText,
                        userId: this.user.id,
                        taskId: this.taskId,
                    },
                });
                this.toggleEdit = false;
                this.editText = "";
                this.editCommentId = null;
                this.refresh();
            } catch (e) {
                console.log(e);
            }
        },
        async save() {
            try {
                await this.$store.dispatch("taskComments/create", {
                    body: this.form.text,
                    userId: this.user.id,
                    taskId: this.taskId,
                });
                this.showQuill = false;
                this.form.text = "";
                this.refresh();
            } catch (e) {
                console.log(e);
            }
        },
        getInitials(name) {
            try {
                const tokens = name?.split(" ");
                if (tokens)
                    return `${tokens?.[0]?.[0] ?? ""}${
                        tokens?.[1]?.[0] ?? ""
                    }`.toUpperCase();
                else return "";
            } catch (e) {
                return "";
            }
        },
        deleteComment(id) {
            this.$nextTick(
                () =>
                    (document.querySelector(".swal2-container").style.zIndex =
                        "99999")
            );
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
                customClass: { container: "z-index-99999" },
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    await this.$store.dispatch("taskComments/destroy", id);
                    this.refresh();
                }
            });
        },
    },
};
</script>

<style scoped>
:deep(input::placeholder) {
    opacity: 1;
}
:deep(.view-source) {
    display: none;
}
.z-index-99999 {
    z-index: 99999 !important;
}
</style>
