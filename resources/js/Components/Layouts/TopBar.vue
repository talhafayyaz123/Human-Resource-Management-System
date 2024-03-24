<template>
    <div class="top-navbar">
        <div class="topnav-left">
            <div class="search">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <icon name="searchIcon" />
                        </span>
                    </div>
                    <input type="search" v-model="searchQueryGlobally" class="form-control" placeholder="Find Something" autocomplete="off" />
                </div>
            </div>
        </div>
        <div class="topnav-right">
            <ul class="top-menu">
                <li class="top-menu-items">
                    <font-awesome-icon @click="fullscreen = !fullscreen" color="#818cf8"
                        class="menuTextColor cursor-pointer" icon="expand" />
                </li>
                <li class="top-menu-items">
                    <language-selector />
                </li>
                <li class="top-menu-items cursor-pointer">
                    <icon name="settingIcon" />
                </li>
                <li class="top-menu-items top-menu-profile">
                    <div class="divider"></div>
                </li>
                <li class="top-menu-items top-menu-profile">
                    <dropdown class="mt-1" placement="bottom-end">
                        <template #default>
                            <div class="flex items-center">
                                <h4 v-if="user?.first_name && !user?.profile_image">
                                    {{ getInitials((user?.first_name ?? "") + " " + (user?.last_name ?? ""))
                                    }}
                                </h4>
                                <h4>{{ user?.first_name }}&nbsp;{{ user?.last_name }}</h4>
                                <div class="profile-img"
                                    :style="{ backgroundImage: 'url(' + (user?.profile_image ?? '') + ')', }">
                                </div>
                            </div>
                        </template>
                        <template #dropdown>
                            <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                                <div class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                    as="button" @click="$router.push('/profile')">
                                    {{ $t("Profile") }}
                                </div>
                                <hr />
                                <div class="block cursor-pointer px-8 py-2 w-full text-left hover:text-white hover:bg-indigo-500"
                                    as="button" @click="logout">
                                    {{ $t("Logout") }}
                                </div>
                            </div>
                        </template>
                    </dropdown>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>
import Icon from "../Icon.vue";
import LanguageSelector from "../LanguageSelector.vue";
import useDebouncedRef from "../../composables/useDebouncedRef";
import Dropdown from "../Dropdown.vue";

export default {
    components: {
        Icon,
        LanguageSelector,
        Dropdown
    },
    data() {
        return {
            searchQueryGlobally: useDebouncedRef("", 800),
        };
    },
    watch: {
        async searchQueryGlobally(val) {
            this.$emitter.emit("searchQueryGlobally", val);
        },
    },

    methods: {
        /**
         * Full screen
         */
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
        getInitials(name) {
            const tokens = name?.split(" ");
            if (tokens)
                return `${tokens?.[0]?.[0] ?? ""}${tokens?.[1]?.[0] ?? ""
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
            this.$store.commit("vacationRequest/vacationRequests", {
                data: [],
                links: [],
            });
            router.push({ name: "Login" });
        },
    },
};
</script>
