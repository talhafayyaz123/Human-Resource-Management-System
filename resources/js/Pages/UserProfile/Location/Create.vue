<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form novalidate @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Location Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.name"
                                    :error="errors.name"
                                    class=""
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.street"
                                    :error="errors.street"
                                    class=""
                                    :label="$t('Street')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.address"
                                    :error="errors.address"
                                    class=""
                                    :label="$t('Address')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.zipCode"
                                    :error="errors.zipCode"
                                    class=""
                                    :label="$t('Zip Code')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.city"
                                    :error="errors.city"
                                    class=""
                                    :label="$t('City')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.state"
                                    :error="errors.state"
                                    class=""
                                    :label="$t('State')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.country"
                                    :error="errors.country"
                                    class=""
                                    :label="$t('Country')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/user/locations" class="primary-btn mr-3">
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
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
        PageHeader,
    },
    remember: "form",
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Management"),
                    to: "/user/locations",
                },
                {
                    text: this.$t("Locations"),
                    to: "/user/locations",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            form: {
                name: "",
                street: "",
                address: "",
                zipCode: "",
                state: "",
                city: "",
                country: "",
            },
        };
    },
    mounted() {},
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("userLocations/create", {
                    ...this.form,
                });
                await this.$store.dispatch("userLocations/list");
                this.$router.push("/user/locations");
            } catch (e) {}
        },
    },
};
</script>
