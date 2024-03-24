<template>
    <div v-show="showLayout">
        <div id="dropdown" />
        <div class="sidebar-responsive-overlay" @click="toggleMenu"></div>
        <div class="main-layout">
            <div class="left-sidebar">
                <main-menu v-if="isPublic" />
            </div>
            <div class="content-page">
                <!--========================================================-->
                <!--========================================================-->
                <img
                    v-if="!showLoader"
                    src="@/assets/images/union-bg.svg"
                    alt="watermark"
                    class="union-bg"
                />
                <!--========================================================-->
                <!--========================================================-->
                <div class="top-navbar">
                    <div class="topnav-left">
                        <div class="responsive-bar" @click="toggleMenu">
                            <Icon name="menuBarIcon" />
                        </div>
                        <div class="search">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <icon name="searchIcon" />
                                    </span>
                                </div>
                                <input
                                    type="search"
                                    v-model="searchQueryGlobally"
                                    class="form-control"
                                    :placeholder="$t('Find Something')"
                                    autocomplete="off"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="topnav-right">
                        <ul class="top-menu">
                            <li class="top-menu-items">
                                <div class="dark-mode" title="Dark Mode Coming Soon">
                                    <span v-if="isDarkMode"
                                        ><icon name="moonIcon"
                                    /></span>
                                    <span v-else><icon name="sunIcon" /></span>
                                </div>
                            </li>
                            <li class="top-menu-items expand-ico" @click="initFullScreen">
                                <span v-if="fullScreen" title="Minimize Screen"><CustomIcon name="miniIcon" /></span>
                                <span v-else title="Maximize Screen"><CustomIcon name="expandIcon" /></span>
                            </li>
                            <li class="top-menu-items" title="Language">
                                <language-selector />
                            </li>
                            <li
                                class="top-menu-items cursor-pointer " title="Global Setting"
                                @click="toggleSetting()"
                            >
                                <icon name="settingIcon" />
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
                                                        (user?.first_name ??
                                                            "") +
                                                            " " +
                                                            (user?.last_name ??
                                                                "")
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
                                                        (user?.profile_image ??
                                                            '') +
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
                                                @click="
                                                    $router.push('/profile')
                                                "
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
                <div class="global-setting" :class="{ show: showSetting }">
                    <GlobalSetting @close-setting="closeSetting" />
                </div>
                <!--========================================================-->
                <!--========================================================-->
                <div
                    :class="`${
                        showLoader ? 'flex justify-center items-center' : ''
                    }`"
                    scroll-region
                >
                    <flash-messages />
                    <div v-if="showLoader" class="loader" id="loader">
                        <img src="@/assets/images/doc.gif" alt="" />
                    </div>
                    <div
                        v-if="showLayout"
                        :class="[showLoader ? 'hidden' : '']"
                    >
                        <router-view />
                        <right-bar />
                    </div>
                </div>
                <!--========================================================-->
                <!--========================================================-->
            </div>
        </div>
    </div>
</template>

<script>
import Icon from "../Components/Icon.vue";
import LanguageSelector from "../Components/LanguageSelector.vue";
import PingLogo from "../Components/PinLogo.vue";
import Dropdown from "../Components/Dropdown.vue";
import MainMenu from "../Components/MainMenu.vue";
import FlashMessages from "../Components/FlashMessages.vue";
import router from "../router";
import { mapGetters } from "vuex";
import { loadLanguageAsync } from "laravel-vue-i18n";
import pusherMixin from "@/Mixins/pusherMixin.js";
import useDebouncedRef from "../composables/useDebouncedRef";
import emitter from "../utils/eventBus";
import GlobalSetting from "../Components/Layouts/GlobalSetting.vue";
import RightBar from "../Components/Layouts/RightBar/RightBar.vue";

export default {
    mixins: [pusherMixin],
    components: {
        FlashMessages,
        Dropdown,
        Icon,
        PingLogo,
        MainMenu,
        LanguageSelector,
        GlobalSetting,
        RightBar,
    },
    data() {
        return {
            fullscreen: false,
            shouldShow: false,
            public: this.$route.meta.public,
            showLayout: false, // shows the layout when the auth/show API in the beforeRouteEnter guard is successful
            searchQueryGlobally: useDebouncedRef("", 800),
            showSetting: false,
            isSidebarCondensed: false,
            isDarkMode: false,
            fullScreen: false,
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
    watch: {
        // fullscreen(val) {
        //     if (val) {
        //         this.shouldShow = true;
        //         setTimeout(() => {
        //             this.shouldShow = false;
        //         }, 4000);
        //     }
        // },
        async searchQueryGlobally(val) {
            this.$emitter.emit("searchQueryGlobally", val);
        },
        isSidebarCondensed(newVal) {
            document.body.classList.toggle("sidebar-condensed", newVal);
        },
    },
    updated() {
        /**
         * the mouseover/mousemove/mouseleave event registered in the mounted hook are lost when the page changes
         * that's why we need to add new events on updated hook
         */
        try {
            /**
             * remove previously registered mouseover/mousemove events
             */
            document
                .querySelector(".header")
                .removeEventListener("mouseover", this.showExitScreenCard);
            document
                .querySelector(".header")
                .removeEventListener("mousemove", this.showExitScreenCard);
            /**
             * add mouseover/mousemove events
             */
            document
                .querySelector(".header")
                ?.addEventListener("mouseover", this.showExitScreenCard);
            document
                .querySelector(".header")
                ?.addEventListener("mousemove", this.showExitScreenCard);
        } catch (e) {}
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
            this.fullScreen = !this.fullScreen;
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
};
</script>

<style scoped lang="scss">
.menu-dropdown label {
    color: white;
}

.menu-dropdown::-webkit-scrollbar {
    width: 4px;
    height: 4px;
}

.menu-dropdown::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
}

.menu-dropdown::-webkit-scrollbar-thumb {
    background-color: #818cf8;
    outline: 1px solid slategrey;
    border-radius: 5px;
}

/* Accordion styles */
.tabs {
    overflow: hidden;
}

.tab {
    width: 100%;
    color: #b6bfbf;
    overflow: hidden;

    &-label {
        display: flex;
        justify-content: space-between;
        cursor: pointer;

        /* Icon */
        &:hover {
        }

        &::after {
            content: "\276F";
            text-align: center;
            transition: all 0.35s;
            color: #b6bfbf;
            position: absolute;
            right: 10px;
            margin-top: 15px;
        }
    }

    &-content {
        display: none;
        max-height: 0;
        color: #b6bfbf;
        transition: all 0.35s;
    }

    &-close {
        display: flex;
        justify-content: flex-end;
        font-size: 0.75em;
        cursor: pointer;

        &:hover {
        }
    }
}

.styles-configurator-tab-label::after {
    margin-top: 0px !important;
}

// :checked
input:checked {
    + .tab-label {
        &::after {
            transform: rotate(90deg);
            transform-origin: center;
        }
    }

    ~ .tab-content {
        display: block;
        max-height: 100vh;
    }
}

.inner-accordion-label::after {
    transform: rotate(90deg);
    transform-origin: center;
}

input[class="tab-checkbox"] {
    position: absolute;
    opacity: 0;
    z-index: -1;
}

.inner-accordion {
    display: none;
}
#loader {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    img {
        width: 90px;
    }
}
.union-bg {
    position: fixed;
    left: 57%;
    top: 65%;
    transform: translate(-50%, -50%);
    width: 25%;
    z-index: -1;
}
@keyframes prixClipFix {
    0% {
        clip-path: polygon(50% 50%, 0 0, 0 0, 0 0, 0 0, 0 0);
    }

    25% {
        clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 0, 100% 0, 100% 0);
    }

    50% {
        clip-path: polygon(
            50% 50%,
            0 0,
            100% 0,
            100% 100%,
            100% 100%,
            100% 100%
        );
    }

    75% {
        clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 0 100%, 0 100%);
    }

    100% {
        clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 0 100%, 0 0);
    }
}

// .loader {
//     width: 90px;
//     height: 90px;
//     border-radius: 50%;
//     position: relative;
//     animation: rotate 1s linear infinite;
// }

// .loader::before {
//     content: "";
//     box-sizing: border-box;
//     position: absolute;
//     inset: 0px;
//     border-radius: 50%;
//     border: 8px solid #2957a4;
//     animation: prixClipFix 2s linear infinite;
// }

// @keyframes rotate {
//     100% {
//         transform: rotate(360deg);
//     }
// }

// @keyframes prixClipFix {
//     0% {
//         clip-path: polygon(50% 50%, 0 0, 0 0, 0 0, 0 0, 0 0);
//     }

//     25% {
//         clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 0, 100% 0, 100% 0);
//     }

//     50% {
//         clip-path: polygon(50% 50%,
//                 0 0,
//                 100% 0,
//                 100% 100%,
//                 100% 100%,
//                 100% 100%);
//     }

//     75% {
//         clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 0 100%, 0 100%);
//     }

//     100% {
//         clip-path: polygon(50% 50%, 0 0, 100% 0, 100% 100%, 0 100%, 0 0);
//     }
// }
</style>
