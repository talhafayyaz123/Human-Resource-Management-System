<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill System Host Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.serverIp"
                                    :label="$t('Server Ip/Host')"
                                    :error="errors?.serverIp ?? ''"
                                />
                                <!-- :error="form.errors.serverIp" -->
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.port"
                                    :required="true"
                                    :label="$t('Server Port')"
                                    :error="errors?.port ?? ''"
                                />
                                <!-- :error="form.errors.serverPort" -->
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.username"
                                    :label="$t('Username')"
                                    :error="errors?.username ?? ''"
                                />
                                <!-- :error="form.errors.username" -->
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
                                    :required="true"
                                    v-model="form.password"
                                    :label="$t('Password')"
                                    :type="inputType"
                                    :show-password="showPassword"
                                    @child-event="handleChildEvent"
                                    :error="errors?.password ?? ''"
                                />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.machineName"
                                    :label="$t('Virtual Machine Name')"
                                    type="text"
                                    :error="errors?.machineName ?? ''"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/system-hosts" class="primary-btn mr-3">
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
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
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
                    text: "System Hosts",
                    to: "/system-hosts",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            form: {
                serverIp: "",
                serverPort: "",
                username: "",
                password: "",
                machineName: "",
            },
            showPassword: false,
            inputType: "password"

        };
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("hosts/create", { ...this.form });
                await this.$store.dispatch("hosts/list");
                this.$router.push("/system-hosts");
            } catch (e) {}
        },
        handleChildEvent() {
            this.showPassword = !this.showPassword;
            this.inputType = this.showPassword ? "text" : "password";
        },
    },
};
</script>
