<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill System Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap">
                        <text-input
                            v-model="form.name"
                            :required="true"
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            :label="$t('Name')"
                            :error="errors?.name ?? ''"
                        />
                        <div class="form-group">
                            <select-input
                                :required="true"
                                v-model="form.subType"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                                :label="$t('Sub Type')"
                                :error="errors?.subType ?? ''"
                            >
                                <!-- :error="form.errors.subType" -->
                                <option value="public">
                                    {{ $t("Public") }}
                                </option>
                                <option value="private">{{ $t("Private") }}</option>
                            </select-input>
                        </div>
                        <div class="form-group">
                            <select-input
                                :required="true"
                                v-model="form.instanceType"
                                class="pb-8 pr-6 w-full lg:w-1/2"
                                :label="$t('Instance Type')"
                                :error="errors?.instanceType ?? ''"
                            >
                                <!-- :error="form.errors.instanceType" -->
                                <option value="development">
                                    {{ $t("Development System") }}
                                </option>
                                <option value="test">
                                    {{ $t("Test System") }}
                                </option>
                                <option value="productive">
                                    {{ $t("Productive System") }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                </div>
            </div>
            <server-tabs
                @serverValueChanged="serverValueChanged"
                :isSystemCloud="true"
            ></server-tabs>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/system-cloud" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button :loading="isLoading" class="secondary-btn">
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
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import ServerTabs from "../../Systems/Components/ServerTabs.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

import { mapGetters } from "vuex";
export default {
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        ServerTabs,
        PageHeader,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Systems"),
                    to: "/system-cloud",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            customers: [],
            form: {
                type: "cloud",
                name: "",
                instanceType: "",
                subType: "",
                cloudServers: [],
            },
        };
    },
    methods: {
        serverValueChanged(val) {
            this.form.cloudServers = val;
        },
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("systemCloud/create", {
                    ...this.form,
                });
                await this.$store.dispatch("systemCloud/list");
                this.$router.push("/system-cloud");
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

<style></style>
