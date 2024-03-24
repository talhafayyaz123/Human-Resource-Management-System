<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="save">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Save Elo Configuration") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.username"
                                    :error="errors.name"
                                    :required="true"
                                    :label="$t('Username')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <CustomIcon name="lckIcon" />
                                            </span>
                                        </div>
                                <text-input
                                    :key="inputType"
                                    v-model="form.password"
                                    :required="true"
                                    :error="errors['password']"
                                    :label="$t('Password')"
                                    :type="inputType"
                                    :show-password="showPassword"
                                    @child-event="handleChildEvent"

                                />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.eloUrl"
                                    :required="true"
                                    :error="errors['eloUrl']"
                                    :label="$t('ELO URL')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/dashboard" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    mounted() {
        this.refresh();
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Elo Configuration",
                    active: true,
                },
            ],
            form: {
                username: "",
                password: "",
                eloUrl: "",
            },
            showPassword: false,
            inputType: "password"
        };
    },
    methods: {
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "globalSettings/eloConfigurationList"
                );
                this.form.username = response?.data?.eloConfigUsername ?? "";
                this.form.password = response?.data?.eloConfigPassword ?? "";
                this.form.eloUrl = response?.data?.eloConfigUrl ?? "";
            } catch (e) {
                console.log(e);
            }
        },
        async save() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(
                    "globalSettings/saveEloConfiguration",
                    {
                        eloConfigUsername: this.form.username,
                        eloConfigPassword: this.form.password,
                        eloConfigUrl: this.form.eloUrl,
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },
        handleChildEvent() {
            this.showPassword = !this.showPassword;
            this.inputType = this.showPassword ? "text" : "password";
        },
    },
};
</script>

<style scoped>
label {
    font-weight: bold;
}
</style>
