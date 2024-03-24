<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Software Version Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.name"
                                    :error="errors.name"
                                    :required="true"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :error="errors.type"
                                    v-model="form.type"
                                    :required="true"
                                    :label="$t('Type')"
                                >
                                    <option
                                        v-for="(option, index) in options"
                                        :key="index"
                                        :value="option.value"
                                    >
                                        {{ option.displayValue }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.softwareId"
                                    :error="errors.softwareId"
                                    :required="true"
                                    :label="$t('Product Software')"
                                >
                                    <option
                                        v-for="software in softwares.data"
                                        :key="'software-' + software.id"
                                        :value="software.id"
                                    >
                                        {{ software.name }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/versions" class="primary-btn mr-3">
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
import SelectInput from "../../Components/SelectInput.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("softwares", ["softwares"]),
    },
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
        PageHeader,
    },
    async mounted() {
        try {
            await this.$store.dispatch("softwares/list");
        } catch (e) {}
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Software Version",
                    to: "/versions",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            form: {
                name: "",
                type: "",
                softwareId: null,
            },
            options: [
                {
                    value: "lts",
                    displayValue: "LTS (Long Term Support)",
                },
                {
                    value: "fr",
                    displayValue: "FR (Feature Release)",
                },
                {
                    value: "hf",
                    displayValue: "HF (Hot Fix)",
                },
                {
                    value: "mr",
                    displayValue: "MR (Master Release)",
                },
            ],
        };
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("versions/create", { ...this.form });
                await this.$store.dispatch("versions/list");
                this.$router.push("/versions");
            } catch (e) {}
        },
    },
};
</script>
