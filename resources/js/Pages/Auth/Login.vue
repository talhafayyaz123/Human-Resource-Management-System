<template>
    <div class="auth-wrapper">
        <div class="language-btn">
            <language-selector />
        </div>

        <div class="auth-left">
            <div class="overlay"></div>
            <img class="auth-img" src="@/assets/images/auth-hero.png" alt=""/>
            <img class="auth-bg" src="@/assets/images/auth-bg.png" alt=""/>
        </div>
        <div class="auth-right">
            <div class="w-100">
                <img src="@/assets/images/auth-logo.svg" alt="">
                <h2>{{$t("Login")}}</h2>
                <h4 class="mb-5">{{$t("Please fill your information below")}}</h4>
                <flash-messages class="mb-5" />
                <form @submit.prevent="login">
                    <div class="form-group mb-5">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <img src="@/assets/images/email.svg" alt="">
                                </span>
                            </div>
                            <text-input v-model="form.mail" :error="errors['email']" :label="$t('Email')" :placeholder="$t('Email')"
                                type="email" autofocus autocapitalize="off" />
                        </div>
                    </div>

                    <div class="form-group mb-5">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <img src="@/assets/images/lock.svg" alt="">
                                </span>
                            </div>
                            <text-input v-model="form.password" :error="errors['password']" :label="$t('Password')" :key="form.inputType" :placeholder="$t('Password')"
                                :type="inputType" :show-password="showPassword"  @child-event="handleChildEvent" />
                        </div>
                    </div>

<!--                    <div class="flex items-center justify-between mb-5">-->
<!--                        <div class="switch">-->
<!--                            <div class="switch-label">{{$t("Remember Me")}}</div>-->
<!--                            <input type="checkbox" id="switch" checked v-model="form.remember" />-->
<!--                            <label for="switch"></label>-->
<!--                        </div>-->
<!--                        &lt;!&ndash; <router-link to="/account/forgot-password" class="forget-pass">Forgot your password?</router-link> &ndash;&gt;-->
<!--                    </div>-->

                    <div class="flex items-center justify-center">
                        <loading-button @click="login" :loading="isLoading" class="btn btn-primary">
                            <span class="mr-2">{{ $t("Login") }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="21" viewBox="0 0 20 21" fill="none">
                                <path
                                    d="M13.2549 10.3404L8.00693 5.09415C7.77665 4.86445 7.40357 4.86445 7.17271 5.09415C6.94243 5.32384 6.94243 5.69693 7.17271 5.92662L12.0044 10.7566L7.17329 15.5865C6.94301 15.8162 6.94301 16.1893 7.17329 16.4196C7.40357 16.6493 7.77723 16.6493 8.00751 16.4196L13.2555 11.1734C13.4823 10.9461 13.4823 10.5671 13.2549 10.3404Z"
                                    fill="white" stroke="white" stroke-width="0.5" />
                            </svg>
                        </loading-button>
                    </div>
                    <hr class="mt-5 mb-2">
                </form>
            </div>
            <div class="social-links mt-5">
                <ul>
                    <li>
                        <a href="https://www.facebook.com" target="_blank" ><img src="@/assets/images/facebook.png" alt=""></a>
                    </li>
                    <li>
                        <a href="https://www.instagram.com" target="_blank"><img src="@/assets/images/instagram.png" alt=""></a>
                    </li>
                    <li>
                        <a href="https://www.twitter.com" target="_blank"><img src="@/assets/images/twitter.png" alt=""></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import Logo from "../../Components/Logo.vue";
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import FlashMessages from "../../Components/FlashMessages.vue";
import LanguageSelector from "../../Components/LanguageSelector.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        FlashMessages,
        LoadingButton,
        Logo,
        TextInput,
        LanguageSelector,
    },
    data() {
        return {
            form: {
                mail: "",
                password: "",
                remember: false,
            },
            showPassword: false,
            inputType: "password"
        };
    },
    methods: {
        async login() {
            try {
                this.$store.commit("isLoading", true);
                let response = await this.$store.dispatch(
                    "auth/login",
                    this.form
                );
                const userId = response?.data?.token_info?.user_id;
                if (userId) {
                    this.$store.dispatch("auth/showProfilePic", userId);
                }
                localStorage.setItem(
                    "user",
                    JSON.stringify(response.data.user) ?? "{}"
                );
                localStorage.setItem("authenticated", true);
                this.$router.push({ name: "Main" });
            } catch (err) {
                console.log(err);
            }
        },
        handleChildEvent() {
            this.showPassword = !this.showPassword;
            this.inputType = this.showPassword ? "text" : "password";
        },
    },
};
</script>
