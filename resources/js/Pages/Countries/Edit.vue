<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <!-- <h1 class="header mb-8 text-3xl font-bold header">
            <router-link class="secondary-color" to="/countries">{{
                $t("Countries")
            }}</router-link>
            <span class="secondary-color font-medium">/</span>
            <span class="text-color">{{ $t("Edit") }}</span>
        </h1> -->
        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Edit Couuntry detail") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
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
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.iso"
                                    :error="errors.iso"
                                    :label="$t('Iso')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.region"
                                    :error="errors.region"
                                    @keypress="limitToAlphabets"
                                    :label="$t('Region')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.subregion"
                                    :error="errors.subregion"
                                    @keypress="limitToAlphabets"
                                    :label="$t('Sub Region')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group flex justify-between">
                                <label class="ml-3" for="isDefaultOnCountry">{{ $t("Is Default") }}</label>
                                <input
                                    v-if="isApiCalled"
                                    class="ml-1"
                                    id="isDefaultOnCountry"
                                    type="checkbox"
                                    v-model="form.isDefault"
                                    :error="errors.isDefault"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link :to="`/countries?page=${$route.query.page}`" class="primary-btn mr-3">
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
import TextInput from "../../Components/TextInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    async mounted() {
        this.$store.commit("showLoader", true);
        const response = await this.$store.dispatch(
            "countries/show",
            this.$route.params.id
        );
        const modelData = response?.data?.data ?? {};
        this.form = {
            iso: modelData.iso,
            name: modelData.name,
            region: modelData.region,
            subregion: modelData.subregion,
            isDefault: modelData.isDefault ? true : "",
        };
        this.isApiCalled = true;
        this.$store.commit("showLoader", false);
    },
    components: {
        LoadingButton,
        SelectInput,
        TextInput,
        MultiSelectInput,
        NumberInput,
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
                    text: "Countries",
                    to: `/countries?page=${this.$route.query.page}`,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            form: {
                name: "",
                iso: "",
                region: "",
                subregion: "",
                isDefault: "",
            },
            isApiCalled: false,
        };
    },
    methods: {
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("countries/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                    },
                });

                await this.$store.dispatch("countries/list");
                this.$router.push("/countries");
            } catch (e) {}
        },
    },
};
</script>

<style scoped>
.table-layout-fixed {
    table-layout: fixed;
}

.disabled {
    color: lightgray;
    cursor: not-allowed;
}
</style>
