<template>
    <div :key="key">
        <div class="page-header">
            <div class="page-title">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 align-items-center">
                        <li class="breadcrumb-item">
                            <router-link to="/dashboard">{{
                                $t("Admin Portal")
                            }}</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="cursor-pointer">{{
                                $t("My Feeds")
                            }}</span>
                        </li>
                    </ol>
                </div>
            </div>
            <div class="other-items d-flex justify-center">
                <div class="search mr-0" style="margin: 0;">
                    <div class="form-group m-0" style="margin: 0;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <icon name="search" class="mr-1" />
                                </span>
                            </div>
                            <input
                                v-model="searchGlobal"
                                type="search"
                                :placeholder="$t('Search here')"
                                class="form-control"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <!---========--->
            <!--        <div class="mr-5">-->
            <!--          <button class="filterBtn"-->
            <!--                  style="width: 40px; padding:0;display: flex;align-items: center;justify-content: center;">-->
            <!--            <icon name="settingIcon" class="w-full"/>-->
            <!--          </button>-->
            <!--        </div>-->
            <!---========--->
            <!--        <div class="right-side">-->
            <!--          <div class="user-img">-->
            <!--            <img src="@/assets/images/avatar-1.png" alt="">-->
            <!--          </div>-->
            <!--        </div>-->
            <!---========--->
        </div>
        <div class="feeds">
            <div class="feeds-row mb-5">
                <div class="feeds-col">
                    <div
                        class="feeds-card"
                        style="background: rgba(245, 150, 48, 0.15)"
                    >
                        <div
                            @click="togglefeedsItem(1)"
                            class="feeds-card-header flex items-center justify-between cursor-pointer"
                        >
                            <h3>New Feeds Since last Login</h3>
                            <CustomIcon name="downIcon" />
                        </div>
                        <div class="feeds-card-content" v-if="isfeedsOpen(1)">
                            <div class="feeds-log">
                                <icon name="feedIcon" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="feeds-col">
                    <div
                        class="feeds-card"
                        style="background: rgba(41, 87, 164, 0.15)"
                    >
                        <div
                            @click="togglefeedsItem(1)"
                            class="feeds-card-header flex items-center justify-between cursor-pointer"
                        >
                            <h3>Count of My Task</h3>
                            <CustomIcon name="downIcon" />
                        </div>
                        <div class="feeds-card-content" v-if="isfeedsOpen(1)">
                            <div class="count-task">
                            <div class="chart">
                                <icon name="infoIcon" />
                            </div>
                            <div class="data">
                                <h1>12,924</h1>
                                <div class="flex items-center justify-between">
                                    <h4>In Work:</h4>
                                    <p>10</p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <h4>New:</h4>
                                    <p>10</p>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="feeds-col">
                    <div
                        class="feeds-card"
                        style="background: rgba(191, 0, 0, 0.15)"
                    >
                    <div
                            @click="togglefeedsItem(1)"
                            class="feeds-card-header flex items-center justify-between cursor-pointer"
                        >
                            <h3>Count of Escalating Tasks</h3>
                            <CustomIcon name="downIcon" />
                        </div>
                        <div class="feeds-card-content" v-if="isfeedsOpen(1)">
                            <div class="count-escalating">
                            <h1>12,924</h1>
                        </div>
                        <div class="progress-bar"></div>
                        </div>
                    </div>
                </div>
                <div class="feeds-col">
                    <div
                        class="feeds-card"
                        style="background: rgba(41, 164, 61, 0.15)"
                    >
                    <div
                            @click="togglefeedsItem(1)"
                            class="feeds-card-header flex items-center justify-between cursor-pointer"
                        >
                            <h3>Current Hashtags</h3>
                            <CustomIcon name="downIcon" />
                        </div>
                        <div class="feeds-card-content" v-if="isfeedsOpen(1)">
                            <div class="hastags">
                            <div class="">
                                <h4
                                    v-if="!!topTags"
                                    v-for="topTageName in topTags"
                                >
                                    {{ topTageName?.name }}
                                </h4>
                            </div>
                            <div class="">
                                <h1>Total<br />{{ totalTags }}</h1>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="feeds-row mb-5">
                <div class="feeds-col" style="height: auto">
                    <div class="feeds-tab">
                        <h4>Own Objects Stream ({{ totalOwn }})</h4>
                        <div class="action-btn">
                            <icon name="bartaskIcon" class="cursor-pointer" />
                        </div>
                    </div>
                </div>
                <div class="feeds-col" style="height: auto">
                    <div class="feeds-tab mention-tab">
                        <h4>Mentioned Stream ({{ totalMentions }})</h4>
                        <div class="action-btn">
                            <icon name="bartaskIcon" class="cursor-pointer" />
                        </div>
                    </div>
                </div>
                <div class="feeds-col" style="height: auto">
                    <div class="feeds-tab sub-tab">
                        <h4>Subscribed Stream ({{ totalSubscribed }})</h4>
                        <div class="action-btn">
                            <icon name="bartaskIcon" class="cursor-pointer" />
                        </div>
                    </div>
                </div>
                <div class="feeds-col" style="height: auto">
                    <div class="feeds-tab hash-tab">
                        <h4>Hashtag Stream ({{ totalTags ?? 0 }})</h4>
                        <div class="action-btn">
                            <icon name="bartaskIcon" class="cursor-pointer" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="feeds-row">
                <OwnObjectStream
                    @getFeeds="refresh()"
                    :ownStream="ownStream"
                    :users="users"
                    ref="column-ownStream"
                    class="column-with-scroll"
                    @scrollend="handleScroll('ownStream')"
                />
                <MentionedStream
                    @getFeeds="refresh()"
                    :mentionedStream="mentionedStream"
                    :users="users"
                    ref="column-mentionedStream"
                    class="column-with-scroll"
                    @scrollend="handleScroll('mentionedStream')"
                />
                <SubscribedStream
                    @getFeeds="refresh()"
                    :mentionedStream="subscribedStream"
                    :users="users"
                    ref="column-subscribedStream"
                    class="column-with-scroll"
                    @scrollend="handleScroll('subscribedStream')"
                />
                <TagsStream
                    @getFeeds="refresh()"
                    :tagStream="tagStream"
                    ref="column-tagsStream"
                    :users="users"
                    class="column-with-scroll"
                    @scrollend="handleScroll('tagsStream')"
                />
            </div>
        </div>
    </div>
</template>

<script>
import Icon from "../../Components/Icon.vue";
import OwnObjectStream from "../../Components/MyFeed/OwnObjectStream.vue";
import { mapGetters } from "vuex";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin";
import MentionedStream from "../../Components/MyFeed/MentionedStream.vue";
import SubscribedStream from "../../Components/MyFeed/SubscribedStream.vue";
import TagsStream from "../../Components/MyFeed/TagsStream.vue";
import { debounce } from "@/utils/debounce";
import myFeeds from "@/store/MyFeeds";
export default {
    mixins: [userProfilePicturesMixin],
    components: {
        MentionedStream,
        Icon,
        OwnObjectStream,
        SubscribedStream,
        TagsStream,
    },
    computed: {
        ...mapGetters("auth", ["userProfilePictures"]),
    },
    data() {
        return {
            openSections: [],
            topTags: "",
            totalTags: 0,
            totalMentions: 0,
            totalSubscribed: 0,
            totalOwn: 0,
            searchGlobal: "",
            ownStream: [],
            tagStream: [],
            mentionedStream: [],
            subscribedStream: [],
            ownStreamPage: 1,
            tagStreamPage: 1,
            subscribedPage: 1,
            mentionStreamPage: 1,
            ownStreamTotalPages: 0,
            mentionedStreamTotalPages: 0,
            subscribedStreamTotalPages: 0,
            tagStreamTotalPages: 0,
            users: [],
        };
    },
    watch: {
        searchGlobal(...val) {
            this.debouncedFetch(...val);
        },
    },
    unmounted() {
        // clear all emitters
        this.$emitter.all.clear();
    },
    async created() {
        const users = await this.$store.dispatch("assets/listEmployees");
        this.users = users.data.data;
        this.debouncedFetch = debounce(async (newValue, oldValue) => {
            await this.refresh();
        }, 500);
    },
    async mounted() {
        this.$emitter.on("getFeeds", () => {
            this.refresh();
        });
        await this.refresh();
    },
    methods: {
        togglefeedsItem(index) {
            const indexOfSection = this.openSections.indexOf(index);
            if (indexOfSection === -1) {
                this.openSections.push(index);
            } else {
                this.openSections.splice(indexOfSection, 1);
            }
        },
        isfeedsOpen(index) {
            return this.openSections.includes(index);
        },
        async refresh() {
            await this.fetchOwnStream();
            await this.fetchMentionedStream();
            await this.fetchSubscribedStream();
            await this.fetchTagStream();
        },

        async fetchOwnStream() {
            this.ownStreamPage = 1;

            const ownStream = await this.$store.dispatch(
                "myFeeds/ownStreamData",
                {
                    page: 1,
                    search: this.searchGlobal,
                }
            );
            this.ownStream = ownStream.data.data;
            this.totalOwn = ownStream?.data?.total;
            this.getUserProfilePictures(this.ownStream ?? [], "userId");
            this.ownStreamTotalPages = Math.ceil(
                ownStream.data.total / ownStream.data.per_page
            );
        },
        async fetchMentionedStream() {
            this.mentionStreamPage = 1;
            const mentionStream = await this.$store.dispatch(
                "myFeeds/mentionedStream",
                {
                    page: 1,
                    search: this.searchGlobal,
                }
            );
            this.mentionedStream = mentionStream.data.data;
            this.totalMentions = mentionStream?.data?.total;

            this.getUserProfilePictures(this.mentionedStream ?? [], "userId");
            this.mentionedStreamTotalPages = Math.ceil(
                mentionStream.data.total / mentionStream.data.per_page
            );
        },

        async fetchSubscribedStream() {
            this.subscribedPage = 1;
            const subscribeStream = await this.$store.dispatch(
                "myFeeds/subscribedStream",
                {
                    page: 1,
                    search: this.searchGlobal,
                }
            );
            this.subscribedStream = subscribeStream.data.data;
            this.totalSubscribed = subscribeStream?.data?.total;
            this.getUserProfilePictures(this.subscribedStream ?? [], "userId");
            this.subscribedStreamTotalPages = Math.ceil(
                subscribeStream.data.total / subscribeStream.data.per_page
            );
        },

        async fetchTagStream() {
            this.tagStreamPage = 1;
            const tagStream = await this.$store.dispatch("myFeeds/tagStream", {
                page: 1,
                search: this.searchGlobal,
            });
            this.tagStream = tagStream.data?.tagsFeed?.data;
            this.totalTags = tagStream.data?.totalTagsCount;
            this.topTags = tagStream.data?.topTags;
            this.getUserProfilePictures(
                this.tagStream.flatMap((item) => item.feeds),
                "userId"
            );
            this.tagStreamTotalPages = Math.ceil(
                tagStream.data?.tagsFeed?.total /
                    tagStream.data?.tagsFeed?.per_page
            );
        },

        async handleScroll(columnType) {
            const columnElement = this.$refs[`column-` + columnType];
            // Check if the scroll is at the bottom of the column
            if (columnElement.scrollTop === columnElement.scrollHeight) {
                // Trigger API call for pagination
                if (
                    columnType === "ownStream" &&
                    this.ownStreamPage < this.ownStreamTotalPages
                ) {
                    this.ownStreamPage = this.ownStreamPage + 1;
                    const ownStream = await this.$store.dispatch(
                        "myFeeds/ownStreamData",
                        {
                            page: this.ownStreamPage,
                            search: this.searchGlobal,
                        }
                    );
                    if (ownStream.data.data.length > 0) {
                        ownStream.data.data.forEach((data, index) => {
                            this.ownStream.push(data);
                        });
                    }
                } else if (
                    columnType === "tagStream" &&
                    this.tagStreamPage < this.tagStreamTotalPages
                ) {
                    this.tagStreamPage = this.tagStreamPage + 1;
                    const tagStream = await this.$store.dispatch(
                        "myFeeds/tagStream",
                        {
                            page: this.tagStreamPage,
                            search: this.searchGlobal,
                        }
                    );
                    if (tagStream.data.data.length > 0) {
                        tagStream.data.data.forEach((data, index) => {
                            this.tagStream.push(data);
                        });
                    }
                } else if (
                    columnType === "mentionedStream" &&
                    this.mentionStreamPage < this.mentionedStreamTotalPages
                ) {
                    this.mentionStreamPage = this.mentionStreamPage + 1;
                    const mentionStream = await this.$store.dispatch(
                        "myFeeds/mentionedStream",
                        {
                            page: this.mentionStreamPage,
                            search: this.searchGlobal,
                        }
                    );
                    if (mentionStream.data.data.length > 0) {
                        mentionStream.data.data.forEach((data, index) => {
                            this.mentionedStream.push(data);
                        });
                    }
                } else if (
                    columnType === "subscribedStream" &&
                    this.subscribedPage < this.subscribedStreamTotalPages
                ) {
                    this.subscribedPage = this.subscribedPage + 1;
                    const subscribedStream = await this.$store.dispatch(
                        "myFeeds/subscribedStream",
                        {
                            page: this.subscribedPage,
                            search: this.searchGlobal,
                        }
                    );
                    if (subscribedStream.data.data.length > 0) {
                        subscribedStream.data.data.forEach((data, index) => {
                            this.subscribedStream.push(data);
                        });
                    }
                }
            }
        },
        isAtBottom() {
            const windowHeight = window.innerHeight;
            const scrollY = window.scrollY || window.pageYOffset;
            const bodyHeight = document.body.offsetHeight;

            return windowHeight + scrollY >= bodyHeight;
        },
    },
};
</script>
<style scoped>
.column-with-scroll {
    overflow-y: auto; /* Add scroll to individual column */
    max-height: 100vh; /* Set a maximum height for the column */
}
</style>
