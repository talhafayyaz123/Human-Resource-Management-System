<template>
    <div class="object-feeds">
        <div class="close-overlay" @click="closefeeds()"></div>
        <div class="feeds">
            <div class="close-feeds" @click="closefeeds()">
                <img src="@/assets/images/feeds.svg" alt="" />
            </div>
            <div class="feeds-border"></div>
            <div class="feeds-header">
                <div class="search">
                    <icon name="searchIcon" />
                </div>
                <h2>Feeds</h2>
                <div class="notifications">
                    <icon name="notificIcon" />
                </div>
            </div>
            <div class="feeds-border"></div>
            <div class="feeds-body">
                <div class="write-tweet">
                    <div class="head-tweet flex items-center">
                        <div
                            class="profile-img"
                            :style="{
                                backgroundImage:
                                    'url(' + (user.profile_image ?? '') + ')',
                            }"
                        ></div>
                        <!-- <img src="@/assets/images/avatar-1.png" alt=""> -->
                        <input
                            type="text"
                            placeholder="Type..."
                            @input="handleInput"
                            @keydown="handleKeyDown"
                            v-model="inputText"
                        />
                    </div>
                    <div>
                        <div
                            v-for="tag in this.form.tags"
                            :key="tag"
                            class="tag"
                        >
                            {{ tag }}
                        </div>
                        <div class="post-polls">
                            <ul>
                                <template
                                    v-for="voteAnswer in [
                                        ...this.form.voteAnswers,
                                    ]"
                                >
                                    <li>
                                        <p class="">{{ voteAnswer }}</p>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                    <input
                        id="hidden-input"
                        type="file"
                        ref="file"
                        @change="addFiles"
                        name="files[]"
                        multiple
                        class="hidden"
                    />
                    <div class="bottom-tweet">
                        <ul>
                            <li>
                                <span
                                    class="cursor-pointer"
                                    @click="toggleEmoji()"
                                    id="button"
                                    type="button"
                                >
                                    <icon name="emojiIcon" />
                                </span>
                                <EmojiPicker
                                    class="absolute"
                                    v-if="isToggleEmoji"
                                    :native="true"
                                    @select="onSelectEmoji"
                                />
                            </li>
                            <li>
                                <span
                                    @click="$refs.file.click()"
                                    id="button"
                                    type="button"
                                >
                                    <icon name="gallaryIcon" />
                                </span>
                            </li>
                            <li class="relative" @click="addVoteAnswers()">
                                <icon name="callenIcon" />
                            </li>
                        </ul>
                        <div class="cursor-pointer" @click="createFeed()">
                            <icon name="sendIcon" />
                        </div>
                        <div
                            v-if="showMentionDropdown"
                            class="mention-dropdown"
                            style="margin-top: 110px"
                        >
                            <div
                                v-for="user in mentionedUsers"
                                :key="user.id"
                                @click="mentionUser(user)"
                            >
                                {{ user.first_name }} {{ user.last_name }}
                            </div>
                        </div>
                    </div>
                </div>

                <OwnObjectStream :ownStream="feeds" />
            </div>
        </div>
    </div>
</template>
<script>
import Icon from "@/Components/Icon.vue";
import OwnObjectStream from "@/Components/MyFeed/OwnObjectStream.vue";
import EmojiPicker from "vue3-emoji-picker";
import "vue3-emoji-picker/css";
import { mapGetters } from "vuex";

export default {
    computed: {
        textWithoutTags() {
            return this.inputText.replace(/#(\w+)/g, "").replace(/@(\w+)/g, "");
        },
        ...mapGetters("auth", ["user"]),
    },
    watch: {
        textWithoutTags(newValue) {
            this.form.text = newValue;
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
                this.mentionedUsers = this.users; // Example data
                this.showMentionDropdown = true; // Show dropdown when there are mentions
            } else {
                // this.form.mentionUserIds = [];
                this.mentionedUsers = [];
                this.showMentionDropdown = false; // Hide dropdown when there are no mentions
            }
        },
    },
    components: {
        OwnObjectStream,
        Icon,
        EmojiPicker,
    },
    async created() {
        const users = await this.$store.dispatch("assets/listEmployees");
        this.users = users.data.data;
    },
    async mounted() {
        this.getModuleFeed();
        this.form.isVote = 0;
        this.form.voteAnswers = [];
    },
    methods: {
        onSelectEmoji(emoji) {
            console.log(emoji.i);
            this.inputText = this.inputText + emoji.i;
        },
        toggleEmoji() {
            this.isToggleEmoji = !this.isToggleEmoji;
        },
        addVoteAnswers() {
            this.form.isVote = 1;
            this.form.voteAnswers.push(this.inputText);
            this.form.text = "";
            this.inputText = "";
        },
        addFiles(event) {
            const files = event.target.files;
            files.forEach((file) => {
                this.processFile(file);
            });
        },
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
                    // let file = {
                    //     name: fileName,
                    //     type: fileExtension,
                    //     size: `${fileSizeMB} MB`,
                    //     base64: base64Content,
                    // };
                    console.log("file", file);
                    this.form.image = {
                        base64: base64Content,
                        name: fileName,
                        size: `${fileSizeMB} MB`,
                    };
                }
            };
            reader.readAsDataURL(file);
        },
        closefeeds() {
            this.$emit("closefeeds");
        },
        async createFeed() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("assets/createFeed", {
                ...this.form,
            });
            this.getModuleFeed();
            this.form.text = "";
            this.form.tags = [];
            this.form.mentionUserIds = [];
            this.inputText = "";
            this.tags = [];
            this.form.isVote = 0;
            this.form.voteAnswers = [];
        },
        async getModuleFeed() {
            const feedData = await this.$store.dispatch(
                "assets/getModuleFeed",
                {
                    moduleId: 2,
                    moduleName: "SaleOffer",
                }
            );
            this.feeds = feedData.data.data;
        },
        handleInput() {
            this.showMentionDropdown = false; // Hide the dropdown on input
        },
        handleKeyDown(event) {
            if (event.key === "@") {
                this.showMentionDropdown = true; // Show dropdown when @ is pressed
            }
        },
        async mentionUser(user) {
            const words = this.inputText.split(" ");
            const filteredWords = words.filter((word) => !word.startsWith("@"));
            this.inputText = filteredWords.join(" ");
            console.log(user.id);

            const mentionText = `${user.first_name} ` + `${user.last_name} `;
            this.inputText += mentionText; // Append mention to input
            this.form.mentionUserIds.push(user.id); // Add user id to form.mentionUserIds
            // this.showMentionDropdown = false; // Hide the dropdown after selection
            // console.log('asdasd',this.users)
            setTimeout(() => {
                this.showMentionDropdown = false;
            }, 100);
        },
    },
    data() {
        return {
            assetImages: [],
            feeds: "",
            users: "",
            isToggleEmoji: false,
            inputText: "",
            tags: [],
            mentionedUsers: [
                { id: 1, name: "User1" },
                { id: 2, name: "User2" },
            ],
            showMentionDropdown: false,
            form: {
                text: "",
                isVote: 0,
                moduleId: 2,
                modelName: "SaleOffer",
                tags: [],
                mentionUserIds: [],
                voteAnswers: [],
                image: "",
            },
        };
    },
};
</script>
<style scoped>
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

.mention-dropdown {
    position: absolute;
    background-color: #f9f9f9;

    max-height: 150px;
    overflow-y: auto;
}

.mention-dropdown div {
    padding: 4px 8px;
    cursor: pointer;
}

.mention-dropdown div:hover {
    background-color: #ddd;
}

.post-polls {
    ul {
        margin: 0;
        padding: 0;
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
</style>
