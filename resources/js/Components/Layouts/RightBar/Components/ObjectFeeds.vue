<template>
    <div class="object-feeds">
        <div class="close-overlay" @click="closefeeds()"></div>
        <div class="feeds">
            <img src="@/assets/images/Union.svg" class="union-img" alt="" />
            <div class="close-feeds" @click="closefeeds()">
                <img src="@/assets/images/feeds.svg" alt="" />
            </div>
            <div class="feeds-border"></div>
            <div class="feeds-header">
                <div class="search cursor-pointer" @click="searchToggle">
                    <icon name="searchIcon" />
                </div>
                <h2>{{ $t("Feeds") }}</h2>
                <div class="post-action">
                    <div class="sub-btn">
                        <div
                            v-if="isSubscribed == false"
                            @click="subscribe()"
                            class="subscribe-btn"
                        >
                            {{ $t("Subscribe") }}
                        </div>
                        <div
                            v-else
                            @click="unSubscribe()"
                            class="unsubscribe-btn"
                        >
                            {{ $t("Unsubscribe") }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="search-input" v-if="searchTog">
                <input
                    type="text"
                    :placeholder="$t('Search here')"
                    v-model="feedSearch"
                />
                <div
                    v-if="searchMentionDropdown"
                    class="mention-dropdown"
                    style="margin-top: 110px"
                >
                    <div
                        v-for="user in filteredSearchMentions()"
                        :key="user.id"
                        @click="mentionSearchUser(user)"
                    >
                        {{ user.first_name }} {{ user.last_name }}
                    </div>
                </div>
            </div>
            <div class="feeds-border"></div>
            <div class="feeds-body pb-5">
                <div class="object-feeds-layout">
                    <div class="write-tweet">
                        <div class="head-tweet">
                            <div
                                class="profile-img"
                                :style="{
                                    backgroundImage:
                                        'url(' +
                                        (user.profile_image ?? '') +
                                        ')',
                                }"
                            ></div>
                            <!-- <img src="@/assets/images/avatar-1.png" alt=""> -->
                            <!-- <textarea
                            type="text"
                            placeholder="Type..."
                            @input="handleInput"
                            @keydown="handleKeyDown"
                            v-model="inputText"
                        ></textarea> -->
                            <textarea
                                type="text"
                                placeholder="Type..."
                                @input="handleInput"
                                @keydown="handleKeyDown"
                                v-model="inputText"
                                ref="textarea"
                                class="expandable-textarea"
                            ></textarea>
                        </div>
                        <div class="w-full">
                            <div v-if="this.form.tags.length > 0">
                                <div
                                    v-for="tag in this.form.tags"
                                    :key="tag"
                                    class="tag"
                                >
                                    {{ tag }}
                                </div>
                            </div>

                            <div
                                class="poll-create"
                                v-if="
                                    this.form.pollQuestion &&
                                    this.form.pollDueDate &&
                                    this.form.voteAnswers.length > 0
                                "
                            >
                                <h3 class="poll-question">
                                    Q. {{ this.form.pollQuestion }}
                                </h3>
                                <div class="post-polls">
                                    <ul>
                                        <template
                                            v-for="(
                                                voteAnswer, voteIndex
                                            ) in this.form.voteAnswers"
                                            :key="voteIndex"
                                        >
                                            <li>
                                                <p>{{ voteAnswer.text }}</p>
                                                <!-- <input
                                                style="
                                                    background-color: transparent;
                                                    color: white;
                                                "
                                                v-model="voteAnswer.text"
                                            />
                                            <span
                                                @click="deleteOption(voteIndex)"
                                                class="delete-option"
                                                >X</span
                                            > -->
                                            </li>
                                        </template>
                                    </ul>
                                    <!-- <div class="flex flex-col">
                                    <input
                                        class="add-option-input"
                                        v-model="answerInput"
                                        placeholder="Add Option..."
                                    />
                                    <button
                                        class="secondary-btn h-10 w-[150px] mt-2 ml-4 add-option"
                                        :disabled="!answerInput"
                                        @click="addNewOption"
                                    >
                                        Add Option
                                    </button>
                                </div> -->
                                </div>
                                <p class="poll-date">
                                    Due Date:
                                    {{
                                        this.formatDateAndTime(
                                            this.form.pollDueDate
                                        )
                                    }}
                                </p>
                            </div>
                        </div>
                        <input
                            id="hidden-input"
                            type="file"
                            ref="file"
                            @change="addFiles"
                            name="files[]"
                            accept="image/*"
                            multiple
                            class="hidden"
                        />
                        <div
                            v-if="form?.images?.length > 0"
                            class="image-preview grid grid-cols-2 gap-3"
                        >
                            <div
                                class="img-box"
                                v-for="(image, index) in form?.images"
                                :class="{
                                    'col-span-2': form?.images.length === 1,
                                }"
                            >
                                <div
                                    @click="resetImage(index)"
                                    class="delete-img"
                                >
                                    <icon name="DeleteIcon" />
                                </div>
                                <img
                                    :src="
                                        'data:image/jpeg;base64,' +
                                        image?.base64
                                    "
                                    class="cursor-pointer"
                                    @click="
                                        handleOpenImageViewer(
                                            'data:image/jpeg;base64,' +
                                                image?.base64
                                        )
                                    "
                                />
                            </div>
                        </div>

                        <!-- <button @click="$refs.file.click()" id="button" type="button"
                                                class="mt-2 rounded-sm px-3 py-1 bg-gray-200 hover:bg-gray-300 focus:shadow-outline focus:outline-none">
                                            {{ $t("Upload a file") }}
                                        </button> -->
                        <div class="bottom-tweet">
                            <div
                                class="w-full h-full flex justify-between items-center"
                            >
                                <ul class="pt-2">
                                    <li title="Smilies" class="h-full">
                                        <span
                                            class="cursor-pointer"
                                            @click="toggleEmoji()"
                                            id="button"
                                            type="button"
                                        >
                                            <icon
                                                name="emojiIcon"
                                                color="white"
                                                height="22px"
                                                width="22px"
                                            />
                                        </span>
                                        <div
                                            class="emoji-overlay"
                                            @click="closeToggleEmoji()"
                                            v-if="isToggleEmoji"
                                        ></div>
                                        <EmojiPicker
                                            class="absolute z-20"
                                            v-if="isToggleEmoji"
                                            :native="true"
                                            @select="onSelectEmoji"
                                        />
                                    </li>
                                    <li title="Add Image" class="h-full">
                                        <span
                                            @click="$refs.file.click()"
                                            id="button"
                                            type="button"
                                            class="cursor-pointer"
                                        >
                                            <icon
                                                name="gallaryIcon"
                                                height="20px"
                                                width="20px"
                                            />
                                        </span>
                                    </li>
                                    <li
                                        title="Create Task"
                                        @click="openModal()"
                                        class="cursor-pointer h-full mb-[5px]"
                                    >
                                        <icon
                                            name="callenIcon"
                                            height="20px"
                                            width="20px"
                                        />
                                    </li>
                                    <li
                                        title="Voting"
                                        class="cursor-pointer text-white h-full mb-[5px]"
                                        @click="openPollModal()"
                                    >
                                        <icon
                                            name="pollsIcon"
                                            height="22px"
                                            width="22px"
                                        />
                                    </li>
                                </ul>
                                <div
                                    class="cursor-pointer p-0 m-0 send-icon"
                                    @click="createFeed()"
                                >
                                    <icon
                                        name="sendIcon"
                                        height="34px"
                                        width="34px"
                                    />
                                </div>
                            </div>
                            <div
                                v-if="showMentionDropdown"
                                class="mention-dropdown"
                            >
                                <div
                                    v-for="user in filteredMentions()"
                                    :key="user.id"
                                    @click="mentionUser(user)"
                                >
                                    {{ user.first_name }} {{ user.last_name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="this.openImageModal">
                        <ImageViewer
                            :image="this.clickedImage"
                            :closeModal="this.handleCloseImageModal"
                        />
                    </div>
                    <CreateTaskModal
                        v-if="toggleTasksModal"
                        :toggleTasksModal="toggleTasksModal"
                        @toggleTasksModal="toggleTasksModal = false"
                        :modalType="modalType"
                        :status="taskStatus"
                        :upperOrder="upperOrder"
                        :task="taskToBeEdited"
                        @refresh="createTaskFeed"
                        :moduleId="id"
                        :moduleName="moduleName"
                    />

                    <CreatePollModal
                        v-if="togglePollModal"
                        :togglePollModal="togglePollModal"
                        @close="closePollModal"
                        modalType="add"
                        :poll="pollToBeEdited"
                        @save="createPollFeed"
                        :moduleId="id"
                        :moduleName="moduleName"
                    />

                    <OwnObjectStream
                        :users="users"
                        :ownStream="feeds"
                        @getFeeds="getModuleFeed()"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Icon from "../../../Icon.vue";
import OwnObjectStream from "../.././../MyFeed/OwnObjectStream.vue";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";
import CreateTaskModal from "./CreateTaskModal.vue";
import CreatePollModal from "./CreatePollModal.vue";
import ImageViewer from "@/Components/ImageViewer.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import { mapGetters } from "vuex";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin";
import { debounce } from "../../../../utils/debounce";
export default {
    mixins: [userProfilePicturesMixin],
    computed: {
        textWithoutTags() {
            // return this.inputText.replace(/#(\w+)/g, "").replace(/@(\w+)/g, "");
            return this.inputText.replace(/#(\w+)/g, "");
        },
        // filteredMentions() {
        //     const keyword = this.inputText.substring(this.inputText.lastIndexOf("@") + 1).toLowerCase();
        //     return this.users.filter((mention) => mention.first_name.toLowerCase().includes(keyword) ||
        //                                         mention.last_name.toLowerCase().includes(keyword));
        // },
        ...mapGetters("auth", ["user", "userProfilePictures"]),
        ...mapGetters(["pusher"]),
    },
    data() {
        return {
            searchTog: false,
            toggleTasksModal: false,
            togglePollModal: false,
            feedSearch: "",
            feeds: "",
            users: "",
            answerInput: "",
            isSubscribed: false,
            isToggleEmoji: false,
            openImageModal: false,
            clickedImage: "",
            inputText: "",
            tags: [],
            mentionedUsers: [
                { id: 1, name: "User1" },
                { id: 2, name: "User2" },
            ],
            showMentionDropdown: false,
            searchMentionDropdown: false,
            mentionUserId: "",
            form: {
                text: "",
                isVote: 0,
                moduleId: this.id,
                modelName: this.moduleName,
                tags: [],
                mentionUserIds: [],
                voteAnswers: [],
                pollTotalVotes: 0,
                pollQuestion: "",
                pollDueDate: "",
                images: [],
                currentRoutePath: this.$route.path,
            },
            pollToBeEdited: {},
            //debouncedSearch: _.debounce(this.getModuleFeed, 500), // Adjust the delay time as needed
        };
    },
    props: {
        id: {
            type: String,
            default: "",
        },
        moduleName: {
            type: String,
            default: "",
        },
    },
    watch: {
        inputText(val) {
            if (val == "") {
                this.showMentionDropdown = false;
            } else {
                const lastChar = val.slice(-1);
                this.showMentionDropdown = lastChar !== " ";
            }
        },
        textWithoutTags(newValue) {
            // if (newValue == '')
            //     this.showMentionDropdown = false;
            this.form.text = this.inputText;
            const tagsRegex = /#(\w+)/g;
            const mentionsRegex = /@(\w+)/g;
            const matches = this.inputText.match(tagsRegex);
            const mentionMatches = this.inputText.match(mentionsRegex);
            if (matches) {
                this.form.tags = matches.map((tag) => tag.slice(0));
            } else {
                this.form.tags = [];
            }

            if (mentionMatches) {
                // Fetch users based on mentions and update mentionedUsers array
                // Assuming you have an API call for this
                // For example: this.fetchUsers(mentionMatches.map(match => match.slice(1)))
                //   .then(users => {
                //     this.mentionedUsers = users;
                //   });
                // this.mentionedUsers = this.users; // Example data
                // this.showMentionDropdown = true; // Show dropdown when there are mentions
            } else {
                // this.form.mentionUserIds = [];
                // this.mentionedUsers = [];
                // this.showMentionDropdown = false; // Hide dropdown when there are no mentions
            }
        },
        feedSearch(val) {
            if (val === "") {
                this.mentionUserId = "";
                this.searchMentionDropdown = false;
            } else {
                const lastChar = val.slice(-1);
                this.searchMentionDropdown = lastChar !== " ";
            }
            this.debouncedFetch(...val);
        },
    },
    components: {
        OwnObjectStream,
        Icon,
        EmojiPicker,
        CreateTaskModal,
        CreatePollModal,
        TextAreaInput,
        ImageViewer,
    },
    async created() {
        this.$emitter.on("getFeeds", () => {
            this.getModuleFeed();
        });

        const users = await this.$store.dispatch("assets/listEmployees");
        this.users = users.data.data;
        this.debouncedFetch = debounce((newValue, oldValue) => {
            this.getModuleFeed();
        }, 500);
    },
    async mounted() {
        await this.getModuleFeed();
        this.form.isVote = 0;
        this.form.voteAnswers = [];
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
        resetImage(index) {
            // Ensure that the index is within the valid range
            if (index >= 0 && index < this.form.images.length) {
                // Remove the image at the specified index
                this.form.images.splice(index, 1);
            }
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
        filteredMentions() {
            const currentCommentText = this.inputText || "";
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
        filteredSearchMentions() {
            const currentCommentText = this.feedSearch || "";
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

        openModal() {
            this.toggleTasksModal = true;
        },
        openPollModal() {
            this.togglePollModal = true;
        },
        closePollModal() {
            this.togglePollModal = false;
        },
        deleteOption(index) {
            this.form.voteAnswers.splice(index, 1);
        },
        addNewOption() {
            this.form.voteAnswers.push({
                id: this.form.voteAnswers.length + this.answerInput,
                text: this.answerInput,
                totalVotes: 0,
                isVoted: 0,
            });
            this.form.isVote = 1;
            this.answerInput = "";
        },
        searchToggle() {
            this.searchTog = true;
        },
        onSelectEmoji(emoji) {
            this.inputText = this.inputText + emoji.i;
        },
        toggleEmoji() {
            this.isToggleEmoji = !this.isToggleEmoji;
        },
        closeToggleEmoji() {
            this.isToggleEmoji = false;
        },

        // addFiles(event) {
        //     const files = event.target.files[0];
        //     this.processFile(files);
        // },
        addFiles(event) {
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                this.processFile(files[i]);
            }
        },
        async subscribe() {
            await this.$store
                .dispatch("myFeeds/subscribe", {
                    moduleId: this.id,
                    moduleName: this.moduleName,
                })
                .then((res) => {
                    this.getModuleFeed();
                });
        },
        async unSubscribe() {
            await this.$store
                .dispatch("myFeeds/unSubscribe", {
                    moduleId: this.id,
                    moduleName: this.moduleName,
                })
                .then((res) => {
                    this.getModuleFeed();
                });
        },
        // processFile(file) {
        //     let base64Content = "";
        //     var reader = new FileReader();
        //     reader.onload = (event) => {
        //         const fileName = file.name;
        //         const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
        //         const fileExtension = fileName
        //             .substring(fileName.lastIndexOf(".") + 1)
        //             .toLowerCase();
        //
        //         const dataUri = event.target.result;
        //         const parts = dataUri.split(",");
        //         if (parts.length === 2) {
        //             base64Content = parts[1];
        //             this.form.image = {
        //                 base64: base64Content,
        //                 name: fileName,
        //                 size: `${fileSizeMB} MB`,
        //             };
        //         }
        //     };
        //     reader.readAsDataURL(file);
        // },
        processFile(file) {
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
                    this.form.images.push({
                        base64: base64Content,
                        name: fileName,
                        size: `${fileSizeMB} MB`,
                    });
                }
            };
            reader.readAsDataURL(file);
        },

        closefeeds() {
            this.$emit("closefeeds");
        },
        async createFeed() {
            this.$store.commit("isLoading", true);
            const filterOutText = this.form.voteAnswers.map(
                (voteAnswer) => voteAnswer.text
            );
            this.users.forEach((user) => {
                if (user && user.first_name && user.last_name) {
                    const fullName = `${user.first_name} ${user.last_name}`;
                    const regex = new RegExp(fullName, "g");

                    if (regex.test(this.form.text)) {
                        this.form.mentionUserIds.push(user.id);
                    }
                }
            });
            await this.$store
                .dispatch("assets/createFeed", {
                    ...this.form,
                    voteAnswers: filterOutText,
                })
                .then((res) => {
                    this.$emit("getFeeds");
                    this.resetFeed();
                    this.getModuleFeed();
                    this.pusher.sendMessage(
                        JSON.stringify({
                            action: "FeedPollFinished", // pusher action
                        }),
                        this.users.map((user) => user.id), // list of user IDs to whom the pusher message will be sent to. We exclude the current user from the list since he has made the change and already has latest changes
                        null
                    );
                });
            //this.getModuleFeed();
        },
        createTaskFeed() {
            this.getModuleFeed();
            this.resetFeed();
        },
        createPollFeed(data) {
            const answers = Object.values(data.form.options);
            this.form.isVote = 1;
            this.form.pollFinished = 0;
            this.pollToBeEdited = {
                question: data.form.question,
                dueDate: data.form.dueDate,
                options: answers,
            };
            this.form.voteAnswers = answers.map((answer) => {
                return {
                    id: this.form.voteAnswers.length + answer,
                    text: answer,
                    totalVotes: 0,
                    isVoted: 0,
                };
            });

            this.form.pollQuestion = data.form.question;
            this.form.pollDueDate = data.form.dueDate;

            this.closePollModal();
        },
        resetFeed() {
            this.form.text = "";
            this.form.tags = [];
            this.form.mentionUserIds = [];
            this.inputText = "";
            this.tags = [];
            this.form.images = "";
            this.form.isVote = 0;
            this.form.voteAnswers = [];
            this.form.pollQuestion = "";
            this.form.pollDueDate = "";
            this.pollToBeEdited = {
                question: null,
                dueDate: null,
                options: [],
            };
        },
        async getModuleFeed() {
            this.isSubscribed = false;
            const feedData = await this.$store.dispatch(
                "assets/getModuleFeed",
                {
                    moduleId: this.id,
                    moduleName: this.moduleName,
                    search: this?.feedSearch,
                    mentionUserId: this.mentionUserId,
                }
            );

            this.feeds = feedData.data.data;
            this.getUserProfilePictures(this.feeds ?? [], "userId");
            feedData?.data?.data.forEach((feed) => {
                if (feed.isSubscribed == 1) {
                    this.isSubscribed = true;
                }
            });
        },
        handleInput() {
            //     this.showMentionDropdown = false; // Hide the dropdown on input
            // const lastChar = this.inputText.slice(-1);
            // this.showMentionDropdown = lastChar !== " ";
            this.autoExpand();
        },
        autoExpand() {
            // Reset the height to auto to allow the textarea to expand
            this.$refs.textarea.style.height = "auto";
            // Set the new height based on the scrollHeight property
            this.$refs.textarea.style.height =
                this.$refs.textarea.scrollHeight + "px";
        },

        // handleKeyDown(event) {
        //     if (event.key === "@") {
        //         this.showMentionDropdown = true; // Show dropdown when @ is pressed
        //     }
        //     else{
        //         this.showMentionDropdown = false;
        //     }

        // },
        async mentionUser(user) {
            // const words = this.inputText.split(" ");
            // const filteredWords = words.filter((word) => word.startsWith("@"));
            // this.inputText = filteredWords.join(" ");
            const lastMentionIndex = this.inputText.lastIndexOf("@");
            if (lastMentionIndex !== -1) {
                this.inputText =
                    this.inputText.substring(0, lastMentionIndex) +
                    "@" +
                    user.first_name +
                    " " +
                    user.last_name +
                    " ";
            } else {
                this.inputText =
                    "@" + user.first_name + " " + user.last_name + " ";
            }
            // const mentionText = `${user.first_name} `;
            // this.inputText += mentionText; // Append mention to input
            // this.form.mentionUserIds.push(user.id); // Add user id to form.mentionUserIds
            // this.showMentionDropdown = false; // Hide the dropdown after selection
            // console.log('asdasd',this.users)
            setTimeout(() => {
                this.showMentionDropdown = false;
            }, 100);
        },
        async mentionSearchUser(user) {
            // const words = this.inputText.split(" ");
            // const filteredWords = words.filter((word) => word.startsWith("@"));
            // this.inputText = filteredWords.join(" ");
            const lastMentionIndex = this.feedSearch.lastIndexOf("@");
            if (lastMentionIndex !== -1) {
                this.feedSearch =
                    this.feedSearch.substring(0, lastMentionIndex) +
                    "@" +
                    user.first_name +
                    " ";
            } else {
                this.feedSearch =
                    "@" + user.first_name + " " + user.last_name + " ";
            }
            setTimeout(() => {
                this.searchMentionDropdown = false;
            }, 100);
        },
    },
};
</script>
<style scoped lang="scss">
.delete-option {
    color: white;
    cursor: pointer;
}
.tag {
    display: inline-block;
    margin: 2px;
    padding: 4px 8px;
    background-color: #007bff;
    color: #ffffff;
    border-radius: 4px;
}

.text {
    margin: 2px;
}

.post-polls {
    ul {
        margin: 0;
        padding: 0 0 0 15px !important;
        list-style: none;

        li {
            margin-bottom: 5px;
            padding: 5px 9px;
            border-radius: 6px;
            border: 1px solid rgba(208, 213, 221, 0.7);
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;

            p {
                color: white;
                font-size: 10px;
                font-style: normal;
                font-weight: 400;
                line-height: 20px;
                margin: 0;
            }
        }

        li.active {
            background: #f59630;

            p {
                color: #fff;
            }
        }
    }
}

input.add-option-input {
    background-color: transparent;
    color: white;
    border: 1px solid white;
    border-radius: 5px;
    padding: 5px;
    margin-left: 15px;
}

button.add-option:disabled,
button.add-option[disabled] {
    cursor: not-allowed;
    background-color: #cccccc;
    color: #666666;
}

.poll-question {
    color: white;
    font-size: 16px;
    font-weight: 600px;
}
.poll-feed-question {
    color: white;
    font-size: 14px;
}
.poll-date {
    color: white;
    font-size: 14px;
}
.poll-feed-date {
    color: white;
    font-size: 14px;
}
</style>
