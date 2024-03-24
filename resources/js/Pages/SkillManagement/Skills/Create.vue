<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill Skill Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.name"
                                    :required="true"
                                    :error="errors.name"
                                    class=""
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full col-span-2">
                            <div class="form-group">
                                <QuillTextEditor
                                    class=""
                                    :content="form.description"
                                    :required="true"
                                    :error="errors.description"
                                    @delta="form.description = $event"
                                    label="Description"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.level"
                                    :error="errors.level"
                                    :required="true"
                                    :label="$t('Skill Level')"
                                >
                                    <option
                                        v-for="skillLevel in skillLevel?.data"
                                        :key="'skillLevel-' + skillLevel.id"
                                        :value="skillLevel.id"
                                    >
                                        {{ skillLevel.name }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="checkbox-group">
                                <input
                                    type="checkbox"
                                    id="terms-checkbox"
                                    v-model="form.is_required"
                                    class="checkbox-input"
                                />
                                <label
                                    for="terms-checkbox"
                                    class="checkbox-label"
                                >
                                    <span class="ml-1">{{
                                        $t("Is Required")
                                    }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap"></div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/skill" class="primary-btn mr-3">
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
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        QuillTextEditor,
        MultiSelectInput,
        TextInput,
        SelectInput,
        TextAreaInput,
        LoadingButton,
        PageHeader,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("skills", ["skills"]),
        ...mapGetters("skillLevels", ["skillLevel"]),
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Skills",
                    to: "/skill",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            form: {
                name: "",
                description: "",
                is_required: "",
                level: "",
            },
        };
    },
    async mounted() {
        try {
            await this.$store.dispatch("skillLevels/list");
        } catch (e) {}
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("skills/create", {
                    ...this.form,
                });
                await this.$store.dispatch("skills/list");
                this.$router.push("/skill");
            } catch (e) {}
        },
    },
};
</script>

<style scoped>
:deep(.ql-container) {
    height: auto;
}
</style>
