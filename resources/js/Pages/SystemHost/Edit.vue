<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update System Host Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.serverIp"
                                    :required="true"
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
                                    v-model="form.username"
                                    :required="true"
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
                                    :key="form.inputType"
                                    v-model="form.password"
                                    :required="true"
                                    :label="$t('Password')"
                                    :type="inputType"
                                    :error="errors?.password ?? ''"
                                    :show-password="showPassword"
                                    @child-event="handleChildEvent"
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
                    v-if="$can(`${$route.meta.permission}.edit`)"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="updateIcon" />
                    </span>
                    {{ $t("Update") }}
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
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                serverIp: "",
                port: "",
                username: "",
                password: "",
                machineName: "",
            },
            showPassword: false,
            inputType: "password"
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "hosts/show",
                    this.$route.params.id
                );
                this.host = response?.data?.host ?? {};
                this.form = {
                    serverIp: this.host.serverIp,
                    port: this.host.port,
                    username: this.host.username,
                    password: this.host.password,
                    machineName: this.host?.machineName,
                };
            } catch (e) {}
            finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("hosts/update", {
                id: this.$route.params.id,
                data: { ...this.form },
            });
            await this.$store.dispatch("hosts/list");
            this.$router.push("/system-hosts");
        },
        async destroy() {
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    try {
                        await this.$store.dispatch(
                            "hosts/destroy",
                            this.$route.params.id
                        );
                        await this.$store.dispatch("hosts/list");

                        this.$router.push("/system-hosts");
                    } catch (e) {}
                }
            });
        },
        handleChildEvent() {
            this.showPassword = !this.showPassword;
            this.inputType = this.showPassword ? "text" : "password";
        },
    },
};
</script>
