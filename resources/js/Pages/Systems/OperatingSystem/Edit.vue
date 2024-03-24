<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update Operating System Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.name"
                                    :error="errors.name"
                                    :label="$t('Name')"
                                    :required="true"
                                />
                                <!-- :error="form.errors.name" -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/employees" class="primary-btn mr-3">
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
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
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
                    text: "Operating System",
                    to: "/operating-system",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                name: "",
            },
            operatingSystem: {},
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
                    "operatingSystem/show",
                    this.$route.params.id
                );
                this.operatingSystem = response?.data?.operatingSystem ?? {};
                console.log(this.operatingSystem);
                this.form = {
                    name: this.operatingSystem.name,
                };
            } catch (e) {}
            finally {
            this.$store.commit("showLoader", false);
            }
        },
        async update() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("operatingSystem/update", {
                id: this.$route.params.id,
                data: { ...this.form },
            });
            await this.$store.dispatch("operatingSystem/list");
            this.$router.push("/operating-system");
        },
    },
};
</script>
