<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Infrastructure Settings Details") }}
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
import SelectInput from "@/Components/SelectInput.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        SelectInput,
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
                    text: "Infrastructure Settings",
                    to: "/infrastructure-settings",
                },
                {
                    text: this.$t("Create"),
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
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("eloBusinessSolutions/create", { ...this.form });
                await this.$store.dispatch("eloBusinessSolutions/list");
                this.$router.push("/infrastructure-settings");
            } catch (e) {}
        },

    },
};
</script>
