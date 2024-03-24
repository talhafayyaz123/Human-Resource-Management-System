<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update Infrastructure Settings Details") }}
                    </h3>
                </div>
                      <div class="card-body">
                        <div class="grid items-center grid-cols-2 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="form.name"
                                        :label="$t('Name')"
                                        :error="errors?.name ?? ''"
                                    />
                                    <!-- :error="form.errors.serverIp" -->
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        v-model="form.installName"
                                        :required="true"
                                        :label="$t('install Name')"
                                        :error="errors?.installName ?? ''"
                                    />
                                    <!-- :error="form.errors.serverPort" -->
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <select-input
                                        v-model="form.type"
                                        :error="errors.type"
                                        :key="form.type"
                                        :required="true"
                                        :label="$t('Type')"
                                    >
                                        <option value="">
                                            {{ $t("--Select--") }}
                                        </option>
                                        <option value="elo-cloud-business-solution">
                                            {{ $t("ELO Cloud Business Solution") }}
                                        </option>
                                        <option value="elo-cloud-module">
                                            {{ $t("ELO Cloud Module") }}
                                        </option>
                                        <option value="elo-cloud-database">
                                            {{ $t("ELO Cloud Database") }}
                                        </option>
                                        <option value="elo-cloud-repository">
                                            {{ $t("ELO Cloud Repository") }}
                                        </option>
                                    </select-input>
                                </div>

                            </div>
                        </div>
                    </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/infrastructure-settings" class="primary-btn mr-3">
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
import SelectInput from "@/Components/SelectInput.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
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
                    text: "Infrastructure Settings",
                    to: "/infrastructure-settings",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                name: "",
                installName: "",
                type:""
            },

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
                    "eloBusinessSolutions/show",
                    this.$route.params.id
                );
                console.log( response?.data?.data)
                this.host = response?.data?.data ?? {};
                this.form = {
                    name: this.host.name,
                    installName: this.host.installName,
                    type: this.host.type,

                };
            } catch (e) {}
            finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("eloBusinessSolutions/update", {
                id: this.$route.params.id,
                data: { ...this.form },
            });
            await this.$store.dispatch("eloBusinessSolutions/list");
            this.$router.push("/infrastructure-settings");
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
