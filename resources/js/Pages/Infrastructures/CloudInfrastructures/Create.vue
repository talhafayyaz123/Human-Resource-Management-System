<template>
    <div>
        <PageHeader
            :items="breadcrumbItems"
            :optionalItems="optionalItems"
        />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill System Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.name"
                                    :required="true"
                                    :label="$t('Name')"
                                    :error="errors?.name ?? ''"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.subType"
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
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.instanceType"
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
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link
                    to="/infrastructures/cloud-infrastructures"
                    class="primary-btn mr-3"
                >
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
import Modal from "@/Components/EditModal.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";

import { mapGetters } from "vuex";
export default {
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        PageHeader,
        Modal,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    data() {
        return {
            moduleType: 'add',
            toggleModal: false,
            selectedId: '',
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Infrastructure"),
                    to: "/infrastructures/cloud-infrastructures",
                },
                {
                    text: this.$t("Systems"),
                    to: "/infrastructures/cloud-infrastructures",
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
            serversArray: [],
            server: {
                serverIp: "",
                port: "",
                userAddress: "",
                password: "",
                isMaster: "",
            },
        };
    },
    watch: {
        serversArray: {
            handler: function (val, oldVal) {
                this.form.cloudServers = val;
            },
            deep: true,
        },
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("systemCloud/create", {
                    ...this.form,
                });
                await this.$store.dispatch("systemCloud/list");
                this.$router.push("/infrastructures/cloud-infrastructures");
            } catch (e) {
                console.log(e);
            }
        }
    },
};
</script>

<style></style>
