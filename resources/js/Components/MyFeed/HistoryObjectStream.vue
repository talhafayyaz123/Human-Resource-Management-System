<template>
    <div class="feeds-col history-deleted-feeds">
        <div
            class="history-accrod"
            v-for="(ownStreamData, index) in ownStream"
            :key="index"
        >
            <div class="history-accrod-header">
                <h3>
                    {{ ownStreamData.deletedAt }} :
                    {{ ownStreamData.deletedTime }}
                </h3>
                <CustomIcon name="downIcon" />
            </div>
            <div class="history-accrod-body">
                <p class="deletebyuser">
                    {{ $t("User: ") }}
                    {{ ownStreamData.userName }}
                    {{ "deleted Feed entry" }}
                </p>
                <div
                    class="post-card relative m-0"
                    v-bind:class="{
                        commented:
                            ownStreamData.comments &&
                            ownStreamData.comments.length > 0,
                    }"
                >
                    <ul>
                        <li>
                            <div class="post-header">
                                <div class="user-det">
                                    <div
                                        class="profile-img"
                                        :style="{
                                            backgroundImage:
                                                'url(' +
                                                (userProfilePictures?.[
                                                    ownStreamData.userId
                                                ]?.profile_image ?? '') +
                                                ')',
                                        }"
                                    ></div>

                                    <div class="ml-2">
                                        <h4>{{ ownStreamData.userName }}</h4>
                                        <p>
                                            $ {{ ownStreamData.moduleName }}
                                            {{
                                                ownStreamData?.moduleInformation
                                                    ?.nameOrNumber
                                            }}
                                        </p>
                                    </div>
                                </div>
                                <div class="post-meta">
                                    <div class="mr-3">
                                        <div class="flex items-center">
                                            <div class="mr-1">
                                                <icon name="dateIcon" />
                                            </div>
                                            <!-- <p>{{ ownStreamData.createdAt }}</p> -->
                                            <p>{{ $dateFormatter(ownStreamData.createdAt, globalLanguage) }}</p>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="mr-1">
                                                <icon name="clockIcon" />
                                            </div>
                                            <p>
                                                {{ ownStreamData.createdTime }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="post-body">
                                <p>
                                    {{ ownStreamData.text }}
                                </p>
                                <div class="tags">
                                    <template
                                        v-for="tag in ownStreamData.tags"
                                        :key="tag"
                                    >
                                        <span class="mr-1">{{ tag }}</span>
                                    </template>
                                </div>

                                <div
                                    v-if="ownStreamData.images?.length > 0"
                                    v-for="(
                                        image, index
                                    ) in ownStreamData?.images"
                                    :key="index"
                                    class="image-preview"
                                >
                                    <img class="mt-2" :src="image?.base64" />
                                </div>
                                <p
                                    :class="{
                                        'poll-feed-question':
                                            $route.path === '/my-feeds',
                                        'poll-question':
                                            $route.path !== '/my-feeds',
                                    }"
                                    class=""
                                    v-if="ownStreamData.pollQuestion"
                                >
                                    Q. {{ ownStreamData.pollQuestion }}
                                </p>
                                <p
                                    :class="{
                                        'poll-feed-date':
                                            $route.path === '/my-feeds',
                                        'poll-date':
                                            $route.path !== '/my-feeds',
                                    }"
                                    v-if="ownStreamData.pollDueDate"
                                >
                                    Due Date:
                                    {{
                                        this.formatDateAndTime(
                                            ownStreamData.pollDueDate
                                        )
                                    }}
                                </p>
                                <p
                                    :class="{
                                        'poll-feed-date':
                                            $route.path === '/my-feeds',
                                        'poll-date':
                                            $route.path !== '/my-feeds',
                                    }"
                                    v-if="
                                        ownStreamData.pollQuestion &&
                                        currentDate >
                                            new Date(ownStreamData.pollDueDate)
                                    "
                                >
                                    Poll is over!
                                </p>
                                <div
                                    class="post-polls"
                                    v-if="ownStreamData.IsVote"
                                >
                                    <ul>
                                        <template
                                            v-for="(
                                                voteAnswer, pollIndex
                                            ) in ownStreamData.voteAnswers"
                                            :key="pollIndex"
                                        >
                                            <li
                                                :style="{
                                                    background: `${getBackgroundColor(
                                                        voteAnswer.totalVotes,
                                                        voteAnswer.isVoted,
                                                        ownStreamData.voteAnswers
                                                    )}`,
                                                }"
                                            >
                                                <p>{{ voteAnswer.text }}</p>
                                                <p
                                                    v-if="
                                                        showPercentage(
                                                            ownStreamData.voteAnswers
                                                        ) ||
                                                        ownStreamData.pollFinished ===
                                                            1 ||
                                                        new Date() >
                                                            new Date(
                                                                ownStreamData.pollDueDate
                                                            )
                                                    "
                                                >
                                                    {{
                                                        answerPercentage(
                                                            voteAnswer.totalVotes,
                                                            ownStreamData.voteAnswers
                                                        )
                                                    }}%
                                                </p>

                                                <p
                                            v-else 
                                        >
                                            Not Voted
                                        </p>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </li>

                        <li
                            v-for="(comment, index) in ownStreamData?.comments"
                            :key="index"
                            class="relative"
                        >
                            <div class="post-header">
                                <div class="user-det">
                                    <div
                                        class="profile-img"
                                        :style="{
                                            backgroundImage:
                                                'url(' +
                                                (userProfilePictures?.[
                                                    comment.userId
                                                ]?.profile_image ?? '') +
                                                ')',
                                        }"
                                    ></div>

                                    <div class="ml-2">
                                        <h4>{{ comment?.userName }}</h4>
                                    </div>
                                </div>
                                <div class="post-meta">
                                    <div class="mr-3">
                                        <div class="flex items-center">
                                            <div class="mr-1">
                                                <icon name="dateIcon" />
                                            </div>
                                            <!-- <p>{{ comment.createdAt }}</p> -->
                                            <p>{{ $dateFormatter(comment.createdAt, globalLanguage) }}</p>

                                        </div>
                                        <div class="flex items-center">
                                            <div class="mr-1">
                                                <icon name="clockIcon" />
                                            </div>
                                            <p>{{ comment.createdTime }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex">
                                <div
                                    class="w-full flex bg-[#6485bd] mx-10 my-2"
                                    style="border-radius: 0px 10px 10px 10px"
                                >
                                    <p class="text-white p-2">
                                        {{ comment.text }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import Icon from "../Icon.vue";
import { mapGetters } from "vuex";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";
import Dropdown from "@/Components/Dropdown.vue";

export default {
    components: { Icon, EmojiPicker, Dropdown },
    computed: {
        ...mapGetters("auth", ["user", "userProfilePictures"]),
        ...mapGetters(["pusher"]),
    },
    props: {
        ownStream: {
            type: Array, // Assuming tagStream is an array, you can adjust the type accordingly
            required: true,
        },
        users: {
            type: Array,
            required: true,
        },
    },

    mounted() {
        this.currentDateInterval = setInterval(() => {
            this.currentDate = new Date();
        }, 1000);
    },
    unmounted() {
        clearInterval(this.currentDateInterval);
    },

    data() {
        return {
            currentDateInterval: null,
            currentDate: new Date(),
            isToggleEmoji: [],
            currentIndex: null,
            commentText: {},
            show: false,
            isTogglePostEmoji: false,
            togglePollModal: false,
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
            visibleComments: 2,
            streamComments: [],
        };
    },
    methods: {
        handleCommentFromOwnStream(newArray) {
            this.streamComments = newArray.map((stream) => stream.comments);
        },
        async handleLoadMoreComment(index) {
            if (
                this.visibleComments * 5 >
                this.ownStream[index].commentsCount
            ) {
                await this.$store
                    .dispatch("myFeeds/getFeedComments", {
                        id: this.ownStream[index].id,
                        page: this.visibleComments,
                    })
                    .then((res) => {
                        this.visibleComments += 1;
                        this.streamComments[index].push(...res.data.data);
                    });
            }
        },
        handleLoadLessComment(index) {
            console.log("clicked");
            this.streamComments[index] = this.streamComments[index].slice(
                this.streamComments[index].length - 5,
                this.streamComments[index].length
            );
        },
        resetImage(index) {
            if (index >= 0 && index < this.ownStream[index].images.length) {
                this.ownStream[index].images.splice(index, 1);
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
                dueDate: data.dueDate,
                options: data.voteAnswers,
            };
            this.togglePollModal = true;
            this.pollToBeEdited = pollData;
            this.seletedPollFeedId = index;
        },
        closePollModal() {
            this.togglePollModal = false;
        },
        updatePollFeed(data) {
            const answers = Object.values(data.form.options);
            this.ownStream[data.id].isVote = 1;
            this.ownStream[data.id].voteAnswers = answers.map((answer) => {
                return {
                    id: this.ownStream[data.id].voteAnswers.length + answer,
                    text: answer,
                    totalVotes: 0,
                    isVoted: 0,
                };
            });

            this.ownStream[data.id].pollFinished = 0;
            this.ownStream[data.id].pollQuestion = data.form.question;
            this.ownStream[data.id].pollDueDate = data.form.dueDate;

            this.closePollModal();
        },
        async addVote(pollIndex, index) {
            if (new Date() > new Date(this.ownStream[index].pollDueDate)) {
                this.$emit("getFeeds");
                this.pusher.sendMessage(
                    JSON.stringify({
                        action: "FeedPollFinished",
                    }),
                    this.users.map((user) => user.id),
                    null
                );
            } else {
                this.ownStream[index].voteAnswers[pollIndex].isVoted = 1;

                this.ownStream[index].voteAnswers[pollIndex].totalVotes =
                    this.ownStream[index].voteAnswers[pollIndex].totalVotes + 1;

                await this.$store
                    .dispatch("myFeeds/addVote", {
                        myFeedId: this.ownStream[index].id,
                        answerId:
                            this.ownStream[index].voteAnswers[pollIndex].id,
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
        filteredFeedMentions(index) {
            const currentFeedText = this.ownStream[index].text || "";
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
            this.selectedImageDisplay = this.ownStream[index].image;
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
            let editFeedTags = [];
            const tagsRegex = /#(\w+)/g;
            const mentionsRegex = /@(\w+)/g;
            const textWithoutTags = inputValue.replace(tagsRegex, "");
            const matches = inputValue.match(tagsRegex);
            const mentionMatches = inputValue.match(mentionsRegex);
            if (matches) {
                editFeedTags = matches.map((tag) => tag.slice(0));
                editFeedTags = [...editFeedTags, ...data.tags];
            } else {
                editFeedTags = [...data.tags];
            }

            const filterOutText = data.voteAnswers.map(
                (voteAnswer) => voteAnswer.text
            );

            data["text"] = textWithoutTags;
            data["isVote"] = data?.IsVote;
            data["voteAnswers"] = filterOutText;
            data["modelName"] = data?.moduleName;
            data["moduleId"] = data?.moduleInformation?.id;

            data["tags"] = editFeedTags;
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
            const lastChar = this.ownStream[index].text.slice(-1);
            this.showFeedMentionDropdown[id] = lastChar !== " ";
        },
        handleInput(id) {
            //     this.showMentionDropdown = false; // Hide the dropdown on input
            const lastChar = this.commentText[id].slice(-1);
            this.showMentionDropdown[id] = lastChar !== " ";
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
                    this.ownStream[index].text.substring(0, selectionStart) +
                    emoji.i +
                    this.ownStream[index].text.substring(selectionEnd);
                this.ownStream[index].text = newText;
                input.selectionStart = selectionStart + emoji.length;
                input.selectionEnd = selectionStart + emoji.length;
            }
        },
        handleTogglePostEmoji() {
            this.isTogglePostEmoji = !this.isTogglePostEmoji;
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
                    this.mentionUserIds = [];
                    this.$emit("getFeeds");
                    this.pusher.sendMessage(
                        JSON.stringify({
                            action: "OwnFeedCommentCreated", // pusher action
                        }),
                        this.users.map((user) => user.id), // list of user IDs to whom the pusher message will be sent to. We exclude the current user from the list since he has made the change and already has latest changes
                        null
                    );
                });
        },
        editComment(index) {
            this.editedCommentIndex = index;
            this.$refs["commentInput_" + index][0].focus();
        },
        async deleteComment(id) {
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
                        await this.$store
                            .dispatch("myFeeds/deleteComment", { id })
                            .then(() => {
                                this.$emit("getFeeds");
                                this.pusher.sendMessage(
                                    JSON.stringify({
                                        action: "OwnFeedCommentDeleted",
                                    }),
                                    this.users.map((user) => user.id),
                                    null
                                );
                            });
                        this.fetchData();
                    } catch (e) {}
                }
            });
        },
        async updateComment(id, feedId, comment) {
            const inputValue = this.$refs["commentInput_" + id][0].value;

            comment["text"] = inputValue;

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

        async mentionFeedUser(user, index) {
            const lastMentionIndex =
                this.ownStream[index].text.lastIndexOf("@");

            if (lastMentionIndex !== -1) {
                this.ownStream[index].text =
                    this.ownStream[index].text.substring(0, lastMentionIndex) +
                    "@" +
                    user.first_name +
                    " ";
            } else {
                this.ownStream[index].text = "@" + user.first_name + " ";
            }

            this.mentionFeedUserIds.push(user.id);

            setTimeout(() => {
                this.showFeedMentionDropdown[id] = false;
            }, 100);
        },
        async mentionUser(user, id) {
            const lastMentionIndex = this.commentText[id].lastIndexOf("@");
            if (lastMentionIndex !== -1) {
                this.commentText[id] =
                    this.commentText[id].substring(0, lastMentionIndex) +
                    "@" +
                    user.first_name +
                    " ";
            } else {
                this.commentText[id] = "@" + user.first_name + " ";
            }

            this.mentionUserIds.push(user.id); // Add user id to form.mentionUserIds

            setTimeout(() => {
                this.showMentionDropdown[id] = false;
            }, 100);
        },
        async deleteFeed(id) {
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
                        this.fetchData();
                    } catch (e) {}
                }
            });
        },
    },
};
</script>

<style scoped lang="scss">
.history-deleted-feeds {
    .deletebyuser {
        font-size: 14px;
        color: #fff;
        padding: 5px 20px;
        text-align: left;
    }
    .history-accrod-body {
        height: auto !important;
        padding: 0 !important;
    }
    .post-card {
        border: none !important;
        padding: 0 !important;
        background: none !important;
        box-shadow: none !important;
        margin: 0 !important;
        padding-bottom: 40px !important;
        .post-body {
            border: none !important;
        }
    }
}

.image-preview {
    position: relative;
    color: white;
}

.image-preview .delete-img {
    position: absolute;
    top: -10px;
    right: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f59630;
    color: white;
    border-radius: 50%;
    cursor: pointer;
}
.image-preview .delete-img svg path {
    fill: white !important;
}

.add-vote-btn {
    font-size: 20px !important;
    font-weight: 500 !important;
    cursor: pointer;
}

.poll-question {
    color: white;
    font-size: 14px;
    padding: 0 15px 15px;
}

.poll-date {
    color: white !important;
    font-size: 14px;
    padding-bottom: 10px;
    padding-left: 15px;
}
</style>
