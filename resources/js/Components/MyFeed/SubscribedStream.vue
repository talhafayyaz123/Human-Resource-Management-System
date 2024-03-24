<template>
    <div
        v-if="mentionedStream"
        class="feeds-col mention-stream-posts feeds-overflow subscribe-stream stream-columns"
    >
        <div
            class="post-card"
            v-for="(mentionedStreamData, index) in mentionedStream"
            :key="index"
            v-bind:class="{
                commented:
                    mentionedStreamData.comments &&
                    mentionedStreamData.comments.length > 0,
            }"
        >
            <ul>
                <li class="post-card-data">
                    <div class="post-header">
                        <div class="user-det">
                            <div
                                class="profile-img"
                                :style="{
                                    backgroundImage:
                                        'url(' +
                                        (userProfilePictures?.[
                                            mentionedStreamData.userId
                                        ]?.profile_image ?? '') +
                                        ')',
                                }"
                            ></div>
                            <router-link
                                v-if="mentionedStreamData.feedRoute"
                                :to="mentionedStreamData.feedRoute"
                                class="ml-2"
                            >
                                <h4>{{ mentionedStreamData.userName }}</h4>
                                <p>
                                    $ {{ mentionedStreamData.moduleName }}
                                    {{
                                        mentionedStreamData?.moduleInformation
                                            ?.nameOrNumber
                                    }}
                                </p>
                            </router-link>
                            <div v-else class="ml-2">
                                <h4>{{ mentionedStreamData.userName }}</h4>
                                <p>
                                    $ {{ mentionedStreamData.moduleName }}
                                    {{
                                        mentionedStreamData?.moduleInformation
                                            ?.nameOrNumber
                                    }}
                                </p>
                            </div>
                        </div>
                        <div class="post-meta">
                            <div class="mr-3 flex">
                                <div class="flex mr-2">
                                    <div class="mr-1">
                                        <icon name="dateIcon" />
                                    </div>
                                    <!-- <p>{{ mentionedStreamData.createdAt }}</p> -->
                                    <p>{{ $dateFormatter(mentionedStreamData.createdAt, globalLanguage) }}</p>
                                </div>
                                <div class="flex">
                                    <div class="mr-1">
                                        <icon name="clockIcon" />
                                    </div>
                                    <p>{{ mentionedStreamData.createdTime }}</p>
                                </div>
                            </div>

                            <div
                                v-if="mentionedStreamData.userId == user.id"
                                class="flex items-start action"
                            >
                                <dropdown placement="bottom-end">
                                    <template #default>
                                        <div
                                            class="group flex cursor-pointer select-none"
                                        >
                                            <icon name="postbarIcon" />
                                        </div>
                                    </template>
                                    <template #dropdown>
                                        <div class="dropdown-menu">
                                            <div class="dropdown-list">
                                                <div
                                                    class="dropdown-item"
                                                    as="button"
                                                    @click="
                                                        editItem(
                                                            mentionedStreamData.id,
                                                            index
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="flex justify-between"
                                                    >
                                                        <span>{{
                                                            $t("Edit")
                                                        }}</span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="dropdown-item"
                                                    as="button"
                                                    @click="
                                                        deleteFeed(
                                                            mentionedStreamData.id
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="flex justify-between"
                                                    >
                                                        <span>{{
                                                            $t("Delete")
                                                        }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </dropdown>
                            </div>
                        </div>
                    </div>
                    <div class="post-body">
                        <div
                            class="edit-post"
                            v-if="editedIndex === mentionedStreamData.id"
                        >
                            <textarea
                                type="text"
                                placeholder="Type..."
                                v-model="mentionedStreamData.text"
                                @input="
                                    handlePostInput(
                                        index,
                                        mentionedStreamData.id
                                    )
                                "
                                @focus="setActiveIndex(index)"
                                :ref="'input_' + mentionedStreamData.id"
                                class="expandable-textarea"
                                :class="{
                                    'text-black': $route.path === '/my-feeds',
                                    'text-white': $route.path !== '/my-feeds',
                                }"
                            ></textarea>

                            <input
                                id="edit-upload-image"
                                type="file"
                                ref="file"
                                @change="
                                    (event) =>
                                        handleUploadFile(
                                            event,
                                            mentionedStreamData.id
                                        )
                                "
                                name="files[]"
                                accept="image/*"
                                multiple
                                class="hidden"
                            />
                        </div>
                        <p
                            v-else
                            v-html="
                                highlightText(
                                    mentionedStreamData?.text?.replace(
                                        /#(\w+)/g,
                                        ''
                                    )
                                )
                            "
                        ></p>
                        <div
                            class="tags"
                            v-if="editedIndex !== mentionedStreamData.id"
                        >
                            <template
                                v-for="tag in mentionedStreamData.tags"
                                :key="tag"
                            >
                                <span class="mr-1">{{ tag }}</span>
                            </template>
                        </div>

                        <div
                            v-if="selectedImage?.length > 0"
                            class="image-preview grid grid-cols-2 gap-2 mb-2"
                        >
                            <div
                                class="img-box"
                                v-for="(image, key) in selectedImage"
                                :key="key"
                                :class="{
                                    'col-span-2': selectedImage.length === 1,
                                }"
                            >
                                <span
                                    v-if="
                                        image?.base64 &&
                                        image.key === mentionedStreamData.id
                                    "
                                >
                                    <div
                                        v-if="
                                            editedIndex ===
                                            mentionedStreamData.id
                                        "
                                        @click="resetSelectedImage(key)"
                                        class="delete-img"
                                    >
                                        <icon name="DeleteIcon" />
                                    </div>
                                    <img
                                        class="cursor-pointer"
                                        :src="
                                            'data:image/jpeg;base64,' +
                                            image?.base64
                                        "
                                        @click="
                                            handleOpenImageViewer(
                                                'data:image/jpeg;base64,' +
                                                    image?.base64
                                            )
                                        "
                                    />
                                </span>
                            </div>
                        </div>
                        <div
                            v-if="mentionedStreamData.images?.length > 0"
                            class="image-preview grid grid-cols-2 gap-2"
                        >
                            <div
                                class="img-box"
                                v-for="(
                                    image, key
                                ) in mentionedStreamData?.images"
                                :class="{
                                    'col-span-2':
                                        mentionedStreamData.images.length === 1,
                                }"
                                :key="key"
                            >
                                <div
                                    v-if="
                                        editedIndex === mentionedStreamData.id
                                    "
                                    @click="resetImage(index, key)"
                                    class="delete-img"
                                    v-show="image?.base64?.includes('base64,')"
                                >
                                    <icon name="DeleteIcon" />
                                </div>
                                <img
                                    v-if="image?.base64?.includes('base64,')"
                                    :src="image?.base64"
                                    class="cursor-pointer"
                                    @click="
                                        handleOpenImageViewer(image?.base64)
                                    "
                                />
                            </div>
                        </div>
                        <div class="poll-create">
                            <div class="flex items-center justify-between">
                                <h3
                                    :class="{
                                        'poll-feed-question':
                                            $route.path === '/my-feeds',
                                        'poll-question':
                                            $route.path !== '/my-feeds',
                                    }"
                                    class=""
                                    v-if="mentionedStreamData.pollQuestion"
                                >
                                    Q. {{ mentionedStreamData.pollQuestion }}
                                </h3>
                                <span
                                    v-if="
                                        editedIndex ===
                                            mentionedStreamData.id &&
                                        mentionedStreamData.IsVote
                                    "
                                    class="cursor-pointer"
                                    @click="
                                        openPollModal(
                                            mentionedStreamData,
                                            index
                                        )
                                    "
                                >
                                    <Icon name="editIcon" color="orange"
                                /></span>
                            </div>
                            <div
                                class="post-polls"
                                v-if="mentionedStreamData.IsVote"
                            >
                                <ul>
                                    <template
                                        v-for="(
                                            voteAnswer, pollIndex
                                        ) in mentionedStreamData.voteAnswers"
                                        :key="pollIndex"
                                    >
                                        <li
                                            :style="{
                                                background: `${getBackgroundColor(
                                                    voteAnswer.totalVotes,
                                                    voteAnswer.isVoted,
                                                    mentionedStreamData.voteAnswers
                                                )}`,
                                            }"
                                            :class="
                                                voteAnswer.isVoted
                                                    ? 'active'
                                                    : ''
                                            "
                                        >
                                            <p>{{ voteAnswer.text }}</p>
                                            <p
                                                v-if="
                                                    editedIndex !==
                                                        mentionedStreamData.id &&
                                                    (showPercentage(
                                                        mentionedStreamData.voteAnswers
                                                    ) ||
                                                        mentionedStreamData.pollFinished ===
                                                            1 ||
                                                        new Date() >
                                                            new Date(
                                                                mentionedStreamData.pollDueDate
                                                            ))
                                                "
                                            >
                                                {{
                                                    answerPercentage(
                                                        voteAnswer.totalVotes,
                                                        mentionedStreamData.voteAnswers
                                                    )
                                                }}%
                                            </p>
                                            <p
                                                v-else-if="
                                                    editedIndex !==
                                                        mentionedStreamData.id &&
                                                    (!showPercentage(
                                                        mentionedStreamData.voteAnswers
                                                    ) ||
                                                        mentionedStreamData.pollFinished !==
                                                            1 ||
                                                        new Date() >
                                                            new Date(
                                                                mentionedStreamData.pollDueDate
                                                            ))
                                                "
                                                class="add-vote-btn"
                                                @click="
                                                    addVote(pollIndex, index)
                                                "
                                            >
                                                O
                                            </p>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            <p
                                :class="{
                                    'poll-feed-date':
                                        $route.path === '/my-feeds',
                                    'poll-date': $route.path !== '/my-feeds',
                                }"
                                v-if="mentionedStreamData.pollDueDate"
                            >
                                Due Date:
                                {{
                                    this.formatDateAndTime(
                                        mentionedStreamData.pollDueDate
                                    )
                                }}
                            </p>
                            <p
                                :class="{
                                    'poll-feed-date':
                                        $route.path === '/my-feeds',
                                    'poll-date': $route.path !== '/my-feeds',
                                }"
                                v-if="
                                    mentionedStreamData.pollQuestion &&
                                    currentDate >
                                        new Date(
                                            mentionedStreamData.pollDueDate
                                        )
                                "
                            >
                                Poll is over!
                            </p>
                            <div
                                class="bottom-tweet edit-mode-tweet"
                                v-if="editedIndex === mentionedStreamData.id"
                            >
                                <ul class="flex items-center gap-2">
                                    <li>
                                        <span
                                            class="cursor-pointer"
                                            @click="handleTogglePostEmoji"
                                            id="button"
                                            type="button"
                                        >
                                            <icon name="emojiIcon" />
                                        </span>
                                        <EmojiPicker
                                            class="absolute z-20"
                                            v-if="isTogglePostEmoji"
                                            :native="true"
                                            @select="
                                                (emoji) =>
                                                    onSelectPostEmoji(
                                                        index,
                                                        mentionedStreamData.id,
                                                        emoji
                                                    )
                                            "
                                        />
                                    </li>
                                    <li>
                                        <label
                                            for="edit-upload-image"
                                            class="cursor-pointer"
                                            type="button"
                                        >
                                            <icon name="gallaryIcon" />
                                        </label>
                                    </li>
                                    <li
                                        v-if="!mentionedStreamData.IsVote"
                                        class="cursor-pointer relative"
                                        type="button"
                                        style="color: white"
                                        @click="
                                            openPollModal(
                                                mentionedStreamData,
                                                index
                                            )
                                        "
                                    >
                                        <icon name="pollsIcon" />
                                    </li>
                                </ul>
                                <div class="flex items-center">
                                    <div
                                        class="cursor-pointer x-icon mr-2"
                                        title="Cancel"
                                        @click="handleCancelUpdate(index)"
                                    >
                                        <icon name="xIcon" />
                                    </div>
                                    <div
                                        class="cursor-pointer update-icon"
                                        title="Update"
                                        @click="
                                            updateFeed(
                                                mentionedStreamData.id,
                                                mentionedStreamData
                                            )
                                        "
                                    >
                                        <icon name="CheckIcon" />
                                    </div>
                                </div>
                                <div
                                    v-if="
                                        showFeedMentionDropdown[
                                            mentionedStreamData.id
                                        ]
                                    "
                                    class="mention-dropdown z-20"
                                >
                                    <div
                                        v-for="user in filteredFeedMentions(
                                            index
                                        )"
                                        :key="user.id"
                                        @click="mentionFeedUser(user, index)"
                                    >
                                        {{ user.first_name }}
                                        {{ user.last_name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="post-footer"
                        v-if="editedIndex !== mentionedStreamData.id"
                    >
                        <div class="add-comment">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span
                                            class="cursor-pointer"
                                            @click="
                                                toggleEmoji(
                                                    index,
                                                    mentionedStreamData.id
                                                )
                                            "
                                            id="button"
                                            type="button"
                                        >
                                            <icon name="emoteIcon" />
                                        </span>
                                    </div>
                                    <input
                                        type="text"
                                        class="form-control"
                                        @input="
                                            handleInput(mentionedStreamData.id)
                                        "
                                        v-model="
                                            commentText[mentionedStreamData.id]
                                        "
                                        @keydown="handleKeyDown"
                                        placeholder="Add a comment..."
                                    />
                                    <div class="input-group-append">
                                        <span
                                            @click="
                                                saveComment(
                                                    commentText[
                                                        mentionedStreamData.id
                                                    ],
                                                    mentionedStreamData.id
                                                )
                                            "
                                            class="input-group-text cursor-pointer"
                                        >
                                            <icon name="sendIcon" />
                                        </span>
                                    </div>
                                    <div
                                        v-if="showMentionDropdown"
                                        class="mention-dropdown"
                                    >
                                        <div
                                            v-for="user in filteredMentions(
                                                mentionedStreamData.id
                                            )"
                                            :key="user.id"
                                            @click="
                                                mentionUser(
                                                    user,
                                                    mentionedStreamData.id
                                                )
                                            "
                                        >
                                            {{ user.first_name }}
                                            {{ user.last_name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="emoji-overlay"
                                @click="closeToggleEmoji()"
                                v-if="isToggleEmoji[index]"
                            ></div>

                            <EmojiPicker
                                class="absolute z-10"
                                :id="'emojiPicker_' + index"
                                v-if="isToggleEmoji[index]"
                                :native="true"
                                @select="onSelectEmoji"
                            />
                        </div>
                    </div>
                </li>
                <li
                    v-if="editedIndex !== mentionedStreamData.id"
                    v-for="(
                        comment, commentIndex
                    ) in mentionedStreamData?.comments"
                    :key="commentIndex"
                    class="relative commented-loop"
                >
                    <div class="post-header">
                        <div class="user-det">
                            <div
                                class="profile-img"
                                :style="{
                                    backgroundImage:
                                        'url(' +
                                        (userProfilePictures?.[comment.userId]
                                            ?.profile_image ?? '') +
                                        ')',
                                }"
                            ></div>
                            <!-- <img
                                src="@/assets/images/avatar-2.png"
                                alt=""
                                class=""
                            /> -->
                            <div class="ml-2">
                                <h4>{{ comment?.userName }}</h4>
                                <!-- <p>$ offer 2023-77767</p> -->
                            </div>
                        </div>
                        <div class="post-meta">
                            <div class="mr-3 flex">
                                <div class="flex mr-2">
                                    <div class="mr-1">
                                        <icon name="dateIcon" />
                                    </div>
                                    <!-- <p>{{ comment.createdAt }}</p> -->
                                    <p>{{ $dateFormatter(comment.createdAt, globalLanguage) }}</p>
                                </div>
                                <div class="flex">
                                    <div class="mr-1">
                                        <icon name="clockIcon" />
                                    </div>
                                    <p>{{ comment.createdTime }}</p>
                                </div>
                            </div>
                            <div
                                v-if="comment.userId == user.id"
                                class="flex items-start action"
                            >
                                <dropdown placement="bottom-end">
                                    <template #default>
                                        <div
                                            class="group flex cursor-pointer select-none"
                                        >
                                            <icon name="postbarIcon" />
                                        </div>
                                    </template>
                                    <template #dropdown>
                                        <div class="dropdown-menu">
                                            <div class="dropdown-list">
                                                <div
                                                    class="dropdown-item"
                                                    as="button"
                                                    @click="
                                                        editComment(comment.id)
                                                    "
                                                >
                                                    <div
                                                        class="flex justify-between"
                                                    >
                                                        <span>{{
                                                            $t("Edit")
                                                        }}</span>
                                                    </div>
                                                </div>
                                                <div
                                                    class="dropdown-item"
                                                    as="button"
                                                    @click="
                                                        deleteComment(
                                                            comment.id
                                                        )
                                                    "
                                                >
                                                    <div
                                                        class="flex justify-between"
                                                    >
                                                        <span>{{
                                                            $t("Delete")
                                                        }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </dropdown>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div
                            class="comment-area-content"
                            v-if="editedCommentIndex === comment.id"
                        >
                            <textarea
                                type="text"
                                placeholder="Type..."
                                @input="
                                    handleEditInput(
                                        index,
                                        commentIndex,
                                        comment.id
                                    )
                                "
                                v-model="comment.text"
                                class="comment-text-input"
                                :ref="'commentInput__' + comment.id"
                            ></textarea>
                            <div
                                v-if="showFeedMentionDropdown[comment.id]"
                                class="mention-dropdown z-20"
                            >
                                <div
                                    v-for="user in filteredMentionsEditComment(
                                        index,
                                        commentIndex
                                    )"
                                    :key="user.id"
                                    @click="
                                        mentionUserEditComment(
                                            user,
                                            index,
                                            commentIndex
                                        )
                                    "
                                >
                                    {{ user.first_name }}
                                    {{ user.last_name }}
                                </div>
                            </div>
                            <div
                                class="edit-mode-tweet flex items-center justify-end"
                            >
                                <div
                                    class="x-icon cursor-pointer mr-2"
                                    title="Cancel"
                                    @click="
                                        handleCancelCommentUpdate(
                                            index,
                                            commentIndex
                                        )
                                    "
                                >
                                    <icon name="xIcon" />
                                </div>
                                <div
                                    class="update-icon cursor-pointer"
                                    title="Update"
                                    @click="
                                        updateComment(
                                            comment.id,
                                            mentionedStreamData.id,
                                            comment
                                        )
                                    "
                                >
                                    <icon name="CheckIcon" />
                                </div>
                            </div>
                        </div>
                        <div v-else class="comment-area-content">
                            <div class="comment-text-box">
                                <div class="comment-text">
                                    <p v-html="highlightText(comment.text)"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div
                class="w-full flex items-center justify-between relative load-more"
            >
                <div class="">
                    <p
                        v-if="
                            mentionedStreamData?.commentsCount > 5 &&
                            this.streamComments[index]?.length <
                                mentionedStreamData.commentsCount
                        "
                        @click="handleLoadMoreComment(index)"
                        class="text-black cursor-pointer hover:underline"
                    >
                        Load more comments...
                    </p>
                </div>
                <div class="" v-if="this.streamComments[index]?.length > 5">
                    <p
                        v-if="this.streamComments[index]?.length > 5"
                        @click="handleLoadLessComment(index)"
                        class="text-black cursor-pointer hover:underline"
                    >
                        Show less comments...
                    </p>
                </div>
            </div>
        </div>
        <div v-if="this.openImageModal">
            <ImageViewer
                :image="this.clickedImage"
                :closeModal="this.handleCloseImageModal"
            />
        </div>
        <div
            v-if="
                pollToBeEdited.question !== null ||
                pollToBeEdited.dueDate !== null ||
                pollToBeEdited.options.length !== 0
            "
        >
            <CreatePollModal
                v-if="togglePollModal"
                :togglePollModal="togglePollModal"
                @close="closePollModal"
                modalType="Edit"
                :poll="pollToBeEdited"
                @save="updatePollFeed"
                :moduleId="seletedPollFeedId"
                :moduleName="moduleName"
            />
        </div>
        <div v-else>
            <CreatePollModal
                v-if="togglePollModal"
                :togglePollModal="togglePollModal"
                @close="closePollModal"
                modalType="add"
                :poll="pollToBeEdited"
                @save="createPollFeed"
                :moduleId="seletedPollFeedId"
                :moduleName="moduleName"
            />
        </div>
    </div>
</template>

<script>
import { createPopper } from "@popperjs/core";
import Icon from "../Icon.vue";
import { mapGetters } from "vuex";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";
import CreatePollModal from "@/Components/Layouts/RightBar/Components/CreatePollModal.vue";
import Dropdown from "@/Components/Dropdown.vue";
import ImageViewer from "@/Components/ImageViewer.vue";

export default {
    components: { Icon, EmojiPicker, Dropdown, CreatePollModal, ImageViewer },
    props: {
        mentionedStream: {
            type: Array, // Assuming tagStream is an array, you can adjust the type accordingly
            required: true,
        },
        users: {
            type: Array,
            required: true,
        },
    },
    computed: {
        ...mapGetters("auth", ["user", "userProfilePictures"]),
        ...mapGetters(["pusher"]),
        streamComments() {
            const commentsArray = this.mentionedStream.map(
                (stream) => stream.comments
            );
            return commentsArray;
        },
        streamCommentsPage() {
            return new Array(this.mentionedStream.length).fill(2);
        },
    },
    data() {
        return {
            currentDateInterval: null,
            currentDate: new Date(),
            isToggleEmoji: [],
            currentIndex: null,
            show: false,
            commentText: {},
            isTogglePostEmoji: false,
            togglePollModal: false,
            openImageModal: false,
            clickedImage: "",
            selectedId: "",
            showMentionDropdown: [],
            showFeedMentionDropdown: [],
            mentionUserIds: [],
            mentionFeedUserIds: [],
            tags: [],
            editedIndex: null,
            editedCommentIndex: null,
            activePostIndex: null,
            pollToBeEdited: {},
            seletedPollFeedId: "",
            selectedImage: [],
            selectedImageDisplay: null,
            visibleComments: 5,
            feedTextToBeEdited: "",
            commentTextToBeEdited: "",
        };
    },
    methods: {
        handleOpenImageViewer(image) {
            this.clickedImage = image;
            this.openImageModal = true;
        },
        handleCloseImageModal() {
            this.clickedImage = "";
            this.openImageModal = false;
        },
        handleCancelUpdate(index) {
            this.editedIndex = null;
            this.mentionedStream[index].text = this.feedTextToBeEdited;
        },
        handleCancelCommentUpdate(index, commentIndex) {
            this.editedCommentIndex = null;
            this.mentionedStream[index].comments[commentIndex].text =
                this.commentTextToBeEdited;
        },
        highlightText(text) {
            let highlightedText = text;

            // Highlight user names
            this.users?.forEach((user) => {
                const fullName = `${user.first_name} ${user.last_name}`;
                const regex = new RegExp(fullName, "g");
                highlightedText = highlightedText?.replace(
                    regex,
                    `<span class="orange-text">${fullName}</span>`
                );
            });

            // Highlight "@" symbol
            highlightedText = highlightedText?.replace(
                /@/g,
                '<span class="orange-text">@</span>'
            );

            return highlightedText;
        },

        async handleLoadMoreComment(index) {
            await this.$store
                .dispatch("myFeeds/getFeedComments", {
                    id: this.mentionedStream[index].id,
                    page: this.streamCommentsPage[index],
                })
                .then((res) => {
                    this.streamCommentsPage[index] += 1;
                    this.streamComments[index].push(...res.data.data);
                })
                .catch((err) => console.log(err));
        },
        handleLoadLessComment(index) {
            if (this.streamComments[index]?.length <= 10) {
                this.streamComments[index].splice(5);
            } else {
                this.streamComments[index].splice(-5);
            }

            this.streamCommentsPage[index] -= 1;
        },
        resetImage(index, key) {
            if (key >= 0 && key < this.mentionedStream[index].images.length) {
                this.mentionedStream[index].images.splice(key, 1);
            }
        },
        resetSelectedImage(index) {
            if (index >= 0 && index < this.selectedImage.length) {
                this.selectedImage.splice(index, 1);
            }
        },
        answerPercentage(optionVotes, allOptions) {
            const totalVotes = allOptions.reduce(
                (total, answer) => total + answer.totalVotes,
                0
            );
            if (optionVotes === 0 || totalVotes === 0) {
                return 0;
            }
            return (optionVotes / totalVotes) * 100;
        },
        setActiveIndex(index) {
            this.activePostIndex = index;
        },
        formatDateAndTime(dateString) {
            const date = new Date(dateString);

            const formattedDate = date.toLocaleDateString("en-GB", {
                day: "2-digit",
                month: "2-digit",
                year: "numeric",
            });
            const formattedTime = date.toLocaleTimeString("en-GB", {
                hour: "2-digit",
                minute: "2-digit",
                hour12: false,
            });

            return `${formattedDate}, ${formattedTime}`;
        },

        getBackgroundColor(optionVotes, isVoted, options) {
            const totalVotes = options.reduce(
                (total, answer) => total + answer.totalVotes,
                0
            );
            const percentage = (optionVotes / totalVotes) * 100;
            let backgroundColor = "";
            if (isVoted) {
                backgroundColor =
                    percentage === 0
                        ? "#6485bd"
                        : percentage === 100
                        ? "#f59630"
                        : `linear-gradient(90deg, #f59630 ${percentage}%, #6485bd ${
                              100 - percentage
                          }%)`;
            } else {
                backgroundColor = "#6485bd";
            }
            return backgroundColor;
        },
        openPollModal(data, index) {
            const pollData = {
                question: data.pollQuestion,
                dueDate: data.pollDueDate,
                options: data.voteAnswers,
            };
            this.togglePollModal = true;
            this.pollToBeEdited = pollData;
            this.seletedPollFeedId = index;
        },
        closePollModal() {
            this.togglePollModal = false;
        },
        createPollFeed(data) {
            const answers = Object.values(data.form.options);
            this.mentionedStream[data.id].IsVote = 1;
            this.mentionedStream[data.id].pollFinished = 0;
            this.pollToBeEdited = {
                question: data.form.question,
                dueDate: data.form.dueDate,
                options: answers,
            };
            this.mentionedStream[data.id].voteAnswers = answers.map(
                (answer) => {
                    return {
                        id:
                            this.mentionedStream[data.id].voteAnswers.length +
                            answer,
                        text: answer,
                        totalVotes: 0,
                        isVoted: 0,
                    };
                }
            );

            this.mentionedStream[data.id].pollQuestion = data.form.question;
            this.mentionedStream[data.id].pollDueDate = data.form.dueDate;

            this.closePollModal();
        },
        updatePollFeed(data) {
            const answers = Object.values(data.form.options);
            this.mentionedStream[data.id].isVote = 1;
            this.mentionedStream[data.id].voteAnswers = answers.map(
                (answer) => {
                    return {
                        id:
                            this.mentionedStream[data.id].voteAnswers.length +
                            answer,
                        text: answer,
                        totalVotes: 0,
                        isVoted: 0,
                    };
                }
            );

            this.mentionedStream[data.id].pollFinished = 0;
            this.mentionedStream[data.id].pollQuestion = data.form.question;
            this.mentionedStream[data.id].pollDueDate = data.form.dueDate;

            this.closePollModal();
        },

        async addVote(pollIndex, index) {
            if (
                new Date() > new Date(this.mentionedStream[index].pollDueDate)
            ) {
                this.$emit("getFeeds");
                this.pusher.sendMessage(
                    JSON.stringify({
                        action: "FeedPollFinished",
                    }),
                    this.users.map((user) => user.id),
                    null
                );
            } else {
                this.mentionedStream[index].voteAnswers[pollIndex].isVoted = 1;

                this.mentionedStream[index].voteAnswers[pollIndex].totalVotes =
                    this.mentionedStream[index].voteAnswers[pollIndex]
                        .totalVotes + 1;

                await this.$store
                    .dispatch("myFeeds/addVote", {
                        myFeedId: this.mentionedStream[index].id,
                        answerId:
                            this.mentionedStream[index].voteAnswers[pollIndex]
                                .id,
                    })
                    .then((res) => {
                        this.$emit("getFeeds");
                        this.pusher.sendMessage(
                            JSON.stringify({
                                action: "FeedPollVoteAdded", // pusher action
                            }),
                            this.users.map((user) => user.id), // list of user IDs to whom the pusher message will be sent to. We exclude the current user from the list since he has made the change and already has latest changes
                            null
                        );
                    })
                    .catch((err) => console.log(err));
            }
        },

        showPercentage(voteAnswers) {
            return voteAnswers.some((answer) => answer.isVoted);
        },
        createTaskFeed() {
            this.getModuleFeed();
            this.resetFeed();
        },

        handleMentions(text) {
            const mentionIndex = text.lastIndexOf("@");
            if (mentionIndex !== -1) {
                const keyword = text.substring(mentionIndex + 1).toLowerCase();

                return this.users.filter(
                    (user) =>
                        user.first_name.toLowerCase().includes(keyword) ||
                        user.last_name.toLowerCase().includes(keyword)
                );
            }
        },

        filteredMentionsEditComment(index, commentIndex) {
            const currentCommentText =
                this.mentionedStream[index].comments[commentIndex].text || "";
            const mentionIndex = currentCommentText.lastIndexOf("@");

            if (mentionIndex !== -1) {
                const keyword = currentCommentText
                    .substring(mentionIndex + 1)
                    .toLowerCase();

                return this.users.filter(
                    (user) =>
                        user.first_name.toLowerCase().includes(keyword) ||
                        user.last_name.toLowerCase().includes(keyword)
                );
            }
            return [];
        },

        filteredFeedMentions(index) {
            const currentFeedText = this.mentionedStream[index].text || "";
            const mentionIndex = currentFeedText.lastIndexOf("@");

            if (mentionIndex !== -1) {
                const keyword = currentFeedText
                    .substring(mentionIndex + 1)
                    .toLowerCase();

                return this.users.filter(
                    (user) =>
                        user.first_name.toLowerCase().includes(keyword) ||
                        user.last_name.toLowerCase().includes(keyword)
                );
            }
            return [];
        },

        filteredMentions(index) {
            const currentCommentText = this.commentText[index] || "";
            const mentionIndex = currentCommentText.lastIndexOf("@");

            if (mentionIndex !== -1) {
                const keyword = currentCommentText
                    .substring(mentionIndex + 1)
                    .toLowerCase();

                return this.users.filter(
                    (user) =>
                        user.first_name.toLowerCase().includes(keyword) ||
                        user.last_name.toLowerCase().includes(keyword)
                );
            }
            return [];
        },

        editItem(id, index) {
            // Set the editedIndex to the current index
            this.editedIndex = id;
            this.selectedImageDisplay = this.mentionedStream[index].image;
            this.feedTextToBeEdited = this.mentionedStream[index].text;
            // Focus on the corresponding input field using refs
            this.$refs["input_" + id][0].focus();
        },

        base64toBlob(base64) {
            const byteCharacters = atob(base64);
            const byteNumbers = new Array(byteCharacters.length);

            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }

            return new Blob([new Uint8Array(byteNumbers)]);
        },

        convertBase64ToFormat(base64Data, fileName = "editedImage") {
            const [, base64Content] = base64Data.split(",");

            const blob = this.base64toBlob(base64Content);

            const fileSizeMB = (blob.size / (1024 * 1024)).toFixed(2);

            const result = {
                base64: base64Content,
                name: fileName,
                size: `${fileSizeMB} MB`,
            };

            return result;
        },

        async updateFeed(id, data) {
            const inputValue = this.$refs["input_" + id][0].value;
            // update logic here
            const tagsRegex = /#(\w+)/g;
            let feedTags = inputValue.match(tagsRegex) || [];

            const mentionsRegex = /@(\w+)/g;
            // const textWithoutTags = inputValue.replace(tagsRegex, "");
            // const matches = inputValue.match(tagsRegex);
            // const mentionMatches = inputValue.match(mentionsRegex);
            // if (matches) {
            //     editFeedTags = matches.map((tag) => tag.slice(0));
            //     editFeedTags = [...editFeedTags, ...data.tags];
            // } else {
            //     editFeedTags = [...data.tags];
            // }

            const filterOutText = data.voteAnswers.map(
                (voteAnswer) => voteAnswer.text
            );

            const images = data?.images.map((image) => {
                return {
                    name: image.name,
                    base64: image.base64.split(",")[1],
                    size: image.size,
                };
            });
            let editNewImages = this.selectedImage;

            if (this.selectedImage.length > 0 && data?.images.length > 0) {
                data["images"] = editNewImages.concat(images);
            } else if (
                this.selectedImage.length > 0 &&
                data?.images.length === 0
            ) {
                data["images"] = editNewImages;
            } else if (
                this.selectedImage.length === 0 &&
                data?.images.length > 0
            ) {
                data["images"] = images;
            } else {
                data["images"] = null;
            }

            data["text"] = inputValue;
            data["isVote"] = data?.IsVote;
            data["voteAnswers"] = filterOutText;
            data["modelName"] = data?.moduleName;
            data["moduleId"] = data?.moduleInformation?.id;

            data["tags"] = feedTags;
            data["pollQuestion"] = data.pollQuestion;
            data["pollFinished"] = data.pollFinished;
            await this.$store
                .dispatch("myFeeds/updateComment", {
                    id: id,
                    data: { ...data },
                })
                .then(async (res) => {
                    this.editedIndex = null;
                    this.selectedImage = [];
                    this.$emit("getFeeds");
                    this.pusher.sendMessage(
                        JSON.stringify({
                            action: "OwnFeedUpdated",
                        }),
                        this.users.map((user) => user.id),
                        null
                    );
                });
        },

        toggleEmoji(index, id) {
            this.currentIndex = index;
            this.isToggleEmoji[index] = !this.isToggleEmoji[index];
            this.selectedId = id;
        },
        closeToggleEmoji() {
            this.isToggleEmoji[this.currentIndex] = false;
        },
        handlePostInput(index, id) {
            //     this.showMentionDropdown = false; // Hide the dropdown on input
            const lastChar = this.mentionedStream[index].text.slice(-1);
            this.showFeedMentionDropdown[id] = lastChar !== " ";
        },
        handleInput(id) {
            //     this.showMentionDropdown = false; // Hide the dropdown on input
            const lastChar = this.commentText[id].slice(-1);
            this.showMentionDropdown[id] = lastChar !== " ";
        },
        handleEditInput(index, commentIndex, id) {
            //     this.showMentionDropdown = false; // Hide the dropdown on input
            const lastChar =
                this.mentionedStream[index].comments[commentIndex].text.slice(
                    -1
                );
            this.showFeedMentionDropdown[id] = lastChar !== " ";
        },

        onSelectPostEmoji(index, id, emoji) {
            const input = this.$refs["input_" + id][0];
            if (
                input &&
                this.activePostIndex !== null &&
                this.activePostIndex === index
            ) {
                const { selectionStart, selectionEnd } = input;
                const newText =
                    this.mentionedStream[index].text.substring(
                        0,
                        selectionStart
                    ) +
                    emoji.i +
                    this.mentionedStream[index].text.substring(selectionEnd);
                this.mentionedStream[index].text = newText;
                input.selectionStart = selectionStart + emoji.length;
                input.selectionEnd = selectionStart + emoji.length;
            }
        },

        processFile(file, feedId) {
            let base64Content = "";
            var reader = new FileReader();
            reader.onload = (event) => {
                const fileName = file.name;
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                const fileExtension = fileName
                    .substring(fileName.lastIndexOf(".") + 1)
                    .toLowerCase();

                const dataUri = event.target.result;
                const parts = dataUri.split(",");
                if (parts.length === 2) {
                    base64Content = parts[1];
                    this.selectedImage.push({
                        base64: base64Content,
                        name: fileName,
                        size: `${fileSizeMB} MB`,
                        key: feedId,
                    });
                }
            };
            reader.readAsDataURL(file);
        },
        handleUploadFile(event, feedId) {
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                this.processFile(files[i], feedId);
            }
        },

        handleTogglePostEmoji() {
            this.isTogglePostEmoji = !this.isTogglePostEmoji;
        },
        onSelectEmoji(emoji) {
            if (this.commentText[this.selectedId] == undefined) {
                this.commentText[this.selectedId] = "";
            }
            this.commentText[this.selectedId] =
                this.commentText[this.selectedId] + emoji.i;
        },
        async saveComment(comment, dataId) {
            await this.$store
                .dispatch("myFeeds/createComment", {
                    myFeedId: dataId,
                    text: comment,
                    mentionUserIds: this.mentionUserIds,
                })
                .then((res) => {
                    this.commentText[dataId] = "";
                    this.$emit("getFeeds");
                });
        },
        editComment(index, feedIndex, commentIndex) {
            this.editedCommentIndex = index;
            this.commentTextToBeEdited =
                this.mentionedStream[feedIndex].comments[commentIndex].text;
            this.$refs["commentInput_" + index][0].focus();
        },
        async deleteComment(id) {
            await this.$store
                .dispatch("myFeeds/deleteComment", { id })
                .then(() => {
                    this.$emit("getFeeds");
                });
        },
        async updateComment(id, feedId, comment) {
            const inputValue = comment.text;

            //   const date = new Date();

            //   let day = date.getDate();
            //   let month = date.getMonth() + 1;
            //   let year = date.getFullYear();

            //   let hours = date.getHours();
            //   let minutes = date.getMinutes();
            //   let seconds = date.getSeconds();

            comment["text"] = inputValue;
            //   comment["createdAt"] = `${hours}:${minutes}:${seconds}`;
            //   comment["createdDate"] = `${day}-${month}-${year}`;

            this.$store.commit("isLoading", true);

            await this.$store
                .dispatch("myFeeds/updatePostComment", {
                    id: id,
                    data: { ...comment, myFeedId: feedId },
                })
                .then(async (res) => {
                    this.editedCommentIndex = null;
                    this.$emit("getFeeds");
                    this.pusher.sendMessage(
                        JSON.stringify({
                            action: "OwnFeedCommentUpdated",
                        }),
                        this.users.map((user) => user.id),
                        null
                    );
                });
        },

        // handleKeyDown(event) {
        //     if (event.key === "@") {
        //         this.showMentionDropdown = true; // Show dropdown when @ is pressed
        //     } else {
        //         this.showMentionDropdown = false;
        //     }
        // },

        async mentionFeedUser(user, index) {
            const lastMentionIndex =
                this.mentionedStream[index].text.lastIndexOf("@");

            if (lastMentionIndex !== -1) {
                this.mentionedStream[index].text =
                    this.mentionedStream[index].text.substring(
                        0,
                        lastMentionIndex
                    ) +
                    "@" +
                    user.first_name +
                    " " +
                    user.last_name +
                    " ";
            } else {
                this.mentionedStream[index].text =
                    "@" + user.first_name + " " + user.last_name + " ";
            }

            this.mentionFeedUserIds.push(user.id);

            setTimeout(() => {
                this.showFeedMentionDropdown[id] = false;
            }, 100);
        },
        async mentionUserEditComment(user, index, commentIndex) {
            const lastMentionIndex =
                this.mentionedStream[index].comments[
                    commentIndex
                ].text.lastIndexOf("@");
            if (lastMentionIndex !== -1) {
                this.mentionedStream[index].comments[commentIndex].text =
                    this.mentionedStream[index].comments[
                        commentIndex
                    ].text.substring(0, lastMentionIndex) +
                    "@" +
                    user.first_name +
                    " " +
                    user.last_name +
                    " ";
            } else {
                this.mentionedStream[index].comments[commentIndex].text =
                    "@" + user.first_name + " " + user.last_name + " ";
            }
            // const mentionText = `${user.first_name} `;
            // this.commentText[id] += mentionText; // Append mention to input
            this.mentionUserIds.push(user.id); // Add user id to form.mentionUserIds
            // this.showMentionDropdown = false; // Hide the dropdown after selection
            // console.log('asdasd',this.users)
            setTimeout(() => {
                this.showMentionDropdown[id] = false;
            }, 100);
        },
        async mentionUser(user, id) {
            const lastMentionIndex = this.commentText[id].lastIndexOf("@");
            if (lastMentionIndex !== -1) {
                this.commentText[id] =
                    this.commentText[id].substring(0, lastMentionIndex) +
                    "@" +
                    user.first_name +
                    " " +
                    user.last_name +
                    " ";
            } else {
                this.commentText[id] =
                    "@" + user.first_name + " " + user.last_name + " ";
            }
            // const mentionText = `${user.first_name} `;
            // this.commentText[id] += mentionText; // Append mention to input
            this.mentionUserIds.push(user.id); // Add user id to form.mentionUserIds
            // this.showMentionDropdown = false; // Hide the dropdown after selection
            // console.log('asdasd',this.users)
            setTimeout(() => {
                this.showMentionDropdown[id] = false;
            }, 100);
        },

        async deleteFeed(id) {
            if (confirm("Are you sure you want to delete this feed?")) {
                try {
                    await this.$store
                        .dispatch("myFeeds/ownStreamDestroy", id)
                        .then(async (res) => {
                            this.$emit("getFeeds");
                            this.pusher.sendMessage(
                                JSON.stringify({
                                    action: "OwnFeedDeleted",
                                }),
                                this.users.map((user) => user.id),
                                null
                            );
                        });
                } catch (e) {}
            }
        },
    },
};
</script>

<style lang="scss">
.subscribe-stream {
    .image-preview {
        position: relative;
        color: white;
        .img-box {
            position: relative;
        }
    }

    .image-preview .delete-img {
        position: absolute;
        right: -9px;
        top: -9px;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #f59630;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
        svg {
            width: 22px;
            height: 22px;

            path {
                fill: #fff !important;
            }
        }
    }

    .add-vote-btn {
        font-size: 20px !important;
        font-weight: 500 !important;
        cursor: pointer;
    }
    textarea.expandable-textarea {
        width: 100%;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.15);
        overflow: initial;
        border: none;
        color: #fff;
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px;
        padding: 4px;

        &::-webkit-scrollbar {
            display: none;
        }

        &::placeholder {
            opacity: 1 !important;
            color: #fff;
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 24px;
        }
    }

    .expandable-textarea {
        overflow: hidden;
        resize: none;
        min-height: 100px;
    }
}

.orange-text {
    color: orange;
}
.normal-text {
    color: black; /* Set your default text color here */
}
</style>
