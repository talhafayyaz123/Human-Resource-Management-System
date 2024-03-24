<template>
    <div class="grid items-center grid-cols-2 gap-6">
        <div class="w-full">
            <div class="form-group">
                <text-input
                    v-model="form.processNumber"
                    class=""
                    :label="$t('Process Number')"
                    :error="errors['processNumber']"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <text-input
                    v-model="form.title"
                    class=""
                    :label="$t('Title')"
                    :error="errors['title']"
                    :required="true"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <label class="input-label"
                    ><span style="color: red">*</span>&nbsp;{{
                        $t("Date")
                    }}:</label
                >
                <datepicker
                    claas="form-control"
                    :clearable="false"
                    :enable-time-picker="false"
                    auto-apply
                    :close-on-auto-apply="false"
                    v-model="form.date"
                    :class="errors.date ? 'error' : ''"
                />
                <div v-if="errors?.date" class="form-error">
                    {{ errors.date }}
                </div>
            </div>
        </div>
        <div class="w-full"></div>
        <div class="w-full col-span-2">
            <div class="form-group">
                <text-area-input
                    v-model="form.description"
                    class=""
                    :label="$t('Description')"
                    :error="errors['description']"
                    :required="true"
                />
            </div>
        </div>
        <div class="w-full col-span-2">
            <div class="form-group">
                <text-area-input
                    v-model="form.suggestedSolution"
                    class=""
                    :label="$t('Suggested Solution')"
                    :error="errors['suggestedSolution']"
                    :required="true"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    :key="form.affectedGroup"
                    class=""
                    :options="affectedGroups?.data ?? []"
                    v-model="form.affectedGroup"
                    :multiple="false"
                    label="name"
                    :textLabel="$t('Affected Group')"
                    trackBy="id"
                />
            </div>
        </div>
        <div class="col-span-2">
            <file-inputs @file-changed="addFiles" :productFiles="form" />
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import FileInputs from "@/Components/FileInputs.vue";
import { mapGetters } from "vuex";

export default {
    name: "CIPForm",
    props: {
        actionForm: {
            type: Object,
            default: {
                processNumber: "",
                title: "",
                date: new Date(),
                description: "",
                suggestedSolution: "",
                affectedGroup: "",
                files: [],
                userId: "",
            },
        },
    },
    components: {
        TextInput,
        MultiSelectInput,
        TextAreaInput,
        FileInputs,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("affectedGroups", ["affectedGroups"]),
    },
    async mounted() {
        this.syncForm();
        try {
            // fetch the affectedGroups list if not already present in store
            if (!this.affectedGroups?.data?.length) {
                await this.$store.dispatch("affectedGroups/list");
            }
        } catch (e) {
            console.log(e);
        }
    },
    watch: {
        actionForm: {
            handler: function () {
                this.syncForm();
            },
            deep: true,
        },
    },
    data() {
        return {
            form: {
                processNumber: "",
                title: "",
                date: new Date(),
                description: "",
                suggestedSolution: "",
                affectedGroup: "",
                files: [],
                userId: "",
            },
        };
    },
    methods: {
        /**
         * syncs form and actionForm
         */
        syncForm() {
            this.form = {
                processNumber: this.actionForm.processNumber,
                title: this.actionForm.title,
                date: this.actionForm.date,
                description: this.actionForm.description,
                suggestedSolution: this.actionForm.suggestedSolution,
                affectedGroup: this.actionForm.affectedGroup,
                files: [...this.actionForm.files],
                userId: this.actionForm.userId,
            };
        },
        /**
         *
         * @param {files} files array
         */
        addFiles(files) {},
    },
};
</script>

<style scoped></style>
