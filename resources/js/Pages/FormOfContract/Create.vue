<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="lg:w-1/2">
            <form @submit.prevent="store">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Fill Form Of Contract Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.name"
                                    :error="errors.name"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <router-link
                        to="/form-of-contract"
                        class="primary-btn mr-3"
                    >
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
                    text: "Form Of Contract",
                    to: "/form-of-contract",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            form: {
                name: "",
            },
        };
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("formOfContract/create", {
                    ...this.form,
                });
                await this.$store.dispatch("formOfContract/list");
                this.$router.push("/form-of-contract");
            } catch (e) {}
        },
    },
};
</script>
