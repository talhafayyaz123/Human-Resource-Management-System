<template>
    <div class="event-pipeline">
        <!--=========================================-->
        <!--=========================================-->
        <div class="event-pipeline-header">
            <div class="flex items-center">
                <router-link to="/" class="home-icon mr-3">
                    <CustomIcon name="homeIcon" />
                </router-link>
                <h2>{{$t("Customer Flow")}}</h2>
            </div>
            <div class="right-header">
                <ul class="top-menu">
                    <li class="top-menu-items top-menu-profile top-menu-log">
                        <button class="production-btn">Production</button>
                    </li>
                    <li class="top-menu-items top-menu-profile top-menu-log">
                        <CustomIcon name="logIcon" />
                    </li>
                    <li class="top-menu-items cursor-pointer">
                        <CustomIcon name="settingIcon" />
                    </li>
                    <li class="top-menu-items top-menu-profile">
                        <div class="divider"></div>
                    </li>
                    <li class="top-menu-items">
                        <div class="dark-mode">
                            <span v-if="isDarkMode"
                                ><CustomIcon name="moonIcon"
                            /></span>
                            <span v-else><CustomIcon name="sunIcon" /></span>
                        </div>
                    </li>

                    <li class="top-menu-items">
                        <language-selector />
                    </li>
                    <li class="top-menu-items" @click="initFullScreen">
                        <CustomIcon name="expandIcon" />
                    </li>
                    <li
                        class="top-menu-items cursor-pointer"
                        @click="toggleSetting()"
                    >
                        <CustomIcon name="settingIcon" />
                    </li>
                    <li class="top-menu-items top-menu-profile">
                        <div class="divider"></div>
                    </li>
                    <li class="top-menu-items top-menu-profile">
                        <dropdown class="" placement="bottom-end">
                            <template #default>
                                <div class="flex items-center">
                                    <h4
                                        v-if="
                                            user?.first_name &&
                                            !user?.profile_image
                                        "
                                    >
                                        {{
                                            getInitials(
                                                (user?.first_name ?? "") +
                                                    " " +
                                                    (user?.last_name ?? "")
                                            )
                                        }}
                                    </h4>
                                    <h4>
                                        {{ user?.first_name }}&nbsp;{{
                                            user?.last_name
                                        }}
                                    </h4>
                                    <div
                                        class="profile-img"
                                        :style="{
                                            backgroundImage:
                                                'url(' +
                                                (user?.profile_image ?? '') +
                                                ')',
                                        }"
                                    ></div>
                                </div>
                            </template>
                            <template #dropdown>
                                <div
                                    class="py-2 text-sm bg-white rounded shadow-xl"
                                >
                                    <div
                                        class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                        as="button"
                                        @click="$router.push('/profile')"
                                    >
                                        {{ $t("Profile") }}
                                    </div>
                                    <hr />
                                    <div
                                        class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                        as="button"
                                        @click="logout"
                                    >
                                        {{ $t("Logout") }}
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </li>
                </ul>
            </div>
        </div>
        <!--=========================================-->
        <!--=========================================-->
        <!-- <div class="event-pipeline-rightbar">
            <div class="event-pipeline-search">
                <input type="text" placeholder="Search" />
                <div class="icon"></div>
            </div>
            <div class="event-pipeline-accord">
                <div class="accordians">
                    <div class="accordion-item bg-yellow">
                        <div @click="toggleItem(1)" class="accordion-header">
                            <h3>Recommended</h3>
                            <CustomIcon name="downIcon" />
                        </div>
                        <div v-if="isOpen(1)" class="accordion-content">
                            <button class="event-pip-btn mb-2">
                                <CustomIcon name="mailEvIcon" />
                                <span>Send Email</span>
                            </button>
                            <button class="event-pip-btn">
                                <CustomIcon name="mailEvIcon" />
                                <span>Send Internal Email Notification</span>
                            </button>
                        </div>
                    </div>
                    <div class="accordion-item bg-blue">
                        <div @click="toggleItem(2)" class="accordion-header">
                            <h3>Delay</h3>
                            <CustomIcon name="downIcon" />
                        </div>
                        <div v-if="isOpen(2)" class="accordion-content">
                            <button class="event-pip-btn mb-2">
                                <CustomIcon name="mailEvIcon" />
                                <span>Delay For A Set Amount Of Time</span>
                            </button>
                            <button class="event-pip-btn">
                                <CustomIcon name="mailEvIcon" />
                                <span>Delay until a day or time</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!--=========================================-->
        <!--=========================================-->
        <div class="event-pipeline-content">
            <Designer />
        </div>
    </div>
</template>

<script>
import LanguageSelector from "@/Components/LanguageSelector.vue";
import PingLogo from "@/Components/PinLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import MainMenu from "@/Components/MainMenu.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
import router from "@/router";
import { mapGetters } from "vuex";
import { loadLanguageAsync } from "laravel-vue-i18n";
import pusherMixin from "@/Mixins/pusherMixin.js";
import useDebouncedRef from "@/composables/useDebouncedRef";
import GlobalSetting from "@/Components/Layouts/GlobalSetting.vue";
import RightBar from "@/Components/Layouts/RightBar/RightBar.vue";
import Designer from "./Components/Designer.vue";

export default {
    mixins: [pusherMixin],
    components: {
        FlashMessages,
        Dropdown,
        PingLogo,
        MainMenu,
        LanguageSelector,
        GlobalSetting,
        RightBar,
        Designer,
    },
    data() {
        return {
            openSections: [],
            fullscreen: false,
            shouldShow: false,
            public: this.$route.meta.public,
            showLayout: false, // shows the layout when the auth/show API in the beforeRouteEnter guard is successful
            searchQueryGlobally: useDebouncedRef("", 800),
            showSetting: false,
            isSidebarCondensed: false,
            isDarkMode: false,
        };
    },
    computed: {
        ...mapGetters("auth", ["user", "authenticated"]),
        ...mapGetters("userProfile", ["authUserProfile"]),
        ...mapGetters(["isPublic", "showLoader"]),
        canViewAdministrator() {
            return (
                this.$can("user.list") ||
                this.$can("role.list") ||
                this.$can("permission.list")
            );
        },
        canViewProduct() {
            return (
                this.$can("product-group.list") ||
                this.$can("elo-version.list") ||
                this.$can("product-category.list") ||
                this.$can("product-unit.list") ||
                this.$can("product-software.list") ||
                this.$can("product-period.list")
            );
        },
        canViewSales() {
            return this.$can("report-category.list");
        },
        canViewDocumentService() {
            return (
                this.$can("template.list") ||
                this.$can("data-source.list") ||
                this.$can("storage-method.list")
            );
        },
        canViewSystem() {
            return (
                this.$can("system-host.list") ||
                this.$can("data-source.list") ||
                this.$can("storage-method.list")
            );
        },
        canViewPersonalManagement() {
            return this.$can("expiry-month.list");
        },
        canViewGlobalSettings() {
            return this.$can("document-assignment.list");
        },
        canViewTermsOfPayment() {
            return this.$can("terms-of-payment.list");
        },
        canViewTravelExpenseSettings() {
            return this.$can("travel-expense-request-type.list");
        },
        canViewDefaultCoverLetterText() {
            return this.$can("default-cover-letter-text.list");
        },
    },
    async mounted() {
        if (this.$route.query.language) {
            const languages = ["de", "en"];
            const selectedLanguage = languages.includes(
                this.$route.query.language
            )
                ? this.$route.query.language
                : "en";
            loadLanguageAsync(selectedLanguage);
            localStorage.setItem("language", selectedLanguage);
            this.$store.commit("language", selectedLanguage);
        }
        this.$store.commit(
            "language",
            localStorage.getItem("language") ?? "en"
        );
        document
            .querySelector(".header")
            ?.addEventListener("mousemove", (e) => {
                this.shouldShow = true;
            });
        document
            .querySelector(".header")
            ?.addEventListener("mouseover", (e) => {
                this.shouldShow = true;
            });
        document
            .querySelector(".header")
            ?.addEventListener("mouseleave", (e) => {
                setTimeout(() => {
                    this.shouldShow = false;
                }, 4000);
            });
        this.$store.dispatch("tickets/openTicketsCount");
    },

    props: {
        auth: Object,
    },
    methods: {
        toggleItem(index) {
            const indexOfSection = this.openSections.indexOf(index);
            if (indexOfSection === -1) {
                this.openSections.push(index);
            } else {
                this.openSections.splice(indexOfSection, 1);
            }
        },
        isOpen(index) {
            return this.openSections.includes(index);
        },
        toggleMode() {
            this.isDarkMode = !this.isDarkMode;
            if (this.isDarkMode) {
                document.body.classList.add("is-dark-mode");
            } else {
                document.body.classList.remove("is-dark-mode");
            }
        },
        toggleSetting() {
            this.showSetting = !this.showSetting;
        },
        toggleMenu() {
            this.isSidebarCondensed = !this.isSidebarCondensed;
        },
        closeSetting() {
            this.showSetting = false;
        },
        initFullScreen() {
            document.body.classList.toggle("fullscreen-enable");
            if (
                !document.fullscreenElement &&
                /* alternative standard method */
                !document.mozFullScreenElement &&
                !document.webkitFullscreenElement
            ) {
                // current working methods
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(
                        Element.ALLOW_KEYBOARD_INPUT
                    );
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
            }
        },
        showExitScreenCard(e) {
            this.shouldShow = true;
            setTimeout(() => {
                this.shouldShow = false;
            }, 4000);
        },
        getInitials(name) {
            const tokens = name?.split(" ");
            if (tokens)
                return `${tokens?.[0]?.[0] ?? ""}${
                    tokens?.[1]?.[0] ?? ""
                }`.toUpperCase();
            else return "";
        },
        isUrl(url) {
            return url === this.$route.path.substr(1);
        },
        async logout() {
            localStorage.clear();
            await axios.get("/api/auth/logout");
            localStorage.setItem("user", "{}");
            localStorage.setItem("token", "");
            localStorage.setItem("refresh_token", "");
            localStorage.setItem("authenticated", false);
            localStorage.setItem("userProfileImage", "");
            this.$store.commit("permissions/permissions", []);
            this.$store.commit("auth/set_user", {});
            this.$store.commit("auth/userPermissions", []);
            this.$store.commit("taskBoards/boards", []);
            this.$store.commit("taskBoards/selectedBoard", null);
            this.$store.commit("taskBoards/assignees", []);
            this.$store.commit("taskStatuses/statuses", []);
            this.$store.commit("taskStatuses/assignees", []);
            this.$store.commit("vacationRequest/vacationRequests", {
                data: [],
                links: [],
            });
            router.push({ name: "Login" });
        },
    },
    beforeRouteEnter(to, from, next) {
        document.body.classList.add("full-layout");
        next((vm) => {
            vm.$store.commit(
                "isPublic",
                !vm.public || vm.$store.getters["auth/authenticated"]
            );
            if (!vm.$store.getters["isPublic"]) return;
            vm.$store
                .dispatch("auth/show", {
                    id: localStorage.getItem("user_id"),
                })
                .then(async (res) => {
                    if (!vm.$store.getters["pusher"]) vm.initializePusher();
                    vm.$store.commit("auth/set_user", res?.data ?? {});
                    const userPermissions = [];
                    const permissions =
                        vm.$store.getters["permissions/permissionsGlobal"];
                    const scopes =
                        JSON.parse(localStorage.getItem("scopes")) ?? {};
                    for (let scope in scopes) {
                        let scopePermissions = scopes[scope]?.permissions ?? [];
                        scopePermissions.forEach((permission) => {
                            const foundPermission = permissions?.find(
                                (perm) =>
                                    perm.value == permission &&
                                    perm.scope === scope
                            );
                            if (foundPermission)
                                userPermissions.push(foundPermission.title);
                        });
                    }
                    vm.$store.commit("auth/userPermissions", userPermissions);
                    vm.$nextTick(() => {
                        vm.$emitter.emit(
                            "collapse",
                            !!localStorage.getItem("collapsed")
                        );
                    });
                    vm.showLayout = true; // show the layout when the API was successfull
                    if (!vm.authUserProfile && vm.user.id) {
                        vm.$store
                            .dispatch(
                                "userProfile/showCompleteEmployeeInfo",
                                vm.user.id
                            )
                            .then((response) => {
                                vm.$store.commit(
                                    "userProfile/authUserProfile",
                                    response?.data ?? null
                                );
                            })
                            .catch((e) => console.log(e));
                    }
                })
                .catch((e) => {
                    console.log(e);
                    // redirect to the login page when the API is unsuccessfull
                    vm.$router.push({ name: "Login" });
                });
        });
    },

    beforeRouteLeave(to, from, next) {
        document.body.classList.remove("full-layout");
        next();
    },
};
</script>
