// Located in resources/js/Shared/LanguageSelector.vue
<template>
    <div class="language">
        <div class="cursor-pointer" @click="isDisplay = !isDisplay">
            <icon :name="selectedIcon" />
        </div>
        <div
            v-if="isDisplay"
            style="position: absolute; background: white"
            class="select-box"
        >
            <div class="nav-wrapper">
                <div class="sl-nav">
                    <ul>
                        <li>
                            <ul>
                                <li
                                    v-for="(option, key) in options"
                                    :key="key"
                                    class="flex justify-between items-center cursor-pointer p-2"
                                >
                                    <div
                                        class="flex justify-between items-center cursor-pointer p-2"
                                        @click="selectedValue(option)"
                                    >
                                        <icon
                                            class="cursor-pointer w-5 h-5"
                                            :name="option.icon"
                                        />
                                        <span class="translation-style">{{
                                            option.name
                                        }}</span>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Icon from "./Icon.vue";
import { loadLanguageAsync } from "laravel-vue-i18n";
export default {
    components: {
        Icon,
    },

    data() {
        return {
            isDisplay: false,
            options: [
                { icon: "en", name: "English" },
                { icon: "de", name: "Deutsch" },
            ],
            selectedIcon: localStorage.getItem("language") || "en",
        };
    },

    methods: {
        selectedValue(option) {
            this.selectedIcon = option.icon;
            this.isDisplay = !this.isDisplay;
            loadLanguageAsync(this.selectedIcon);
            localStorage.setItem("language", this.selectedIcon);
            this.$store.commit("language", this.selectedIcon);
            /*   this.$store.dispatch("language/store", {
                locale: this.selectedIcon,
            }); */
        },
    },
};
</script>
<style scoped>
@import url(https://fonts.googleapis.com/css?family=Raleway);
.translation-style {
    padding: 0.5rem;
    font-family: "Raleway", sans-serif;
    color: black;
    font-size: 20px;
}

.translation-style:hover {
    color: #146c78;
}
</style>
