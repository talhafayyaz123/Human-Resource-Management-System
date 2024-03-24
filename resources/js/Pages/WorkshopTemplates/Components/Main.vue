<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="flex items-center justify-end mb-3">
            <button
                @click="exportTemplate"
                v-if="id"
                class="secondary-btn gap-2"
            >
                <CustomIcon name="ExportIcon" />
                {{ $t("Export") }}
            </button>
            <button
                v-if="!id && $route.name === 'WorkshopTemplatesCreate'"
                @click="$refs['import-input']?.click()"
                class="secondary-btn gap-2"
            >
                {{ $t("Import") }}
            </button>
            <input
                @input="importTemplate"
                ref="import-input"
                type="file"
                class="hidden"
            />
        </div>

        <div class="">
            <WorkshopTemplateForm :actionForm="form" @save="save" />
        </div>
        <FileUpload :files="form?.files ?? []" :id="id" v-if="id" />

        <TemplateEditor
            ref="templateEditor"
            v-if="id"
            :cardsFromAPI="form?.cards ?? []"
            :workshopTemplateId="id"
        />
    </div>
</template>

<script>
import TemplateEditor from "./TemplateEditor.vue";
import WorkshopTemplateForm from "./Form.vue";
import FileUpload from "./FileUpload.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    name: "Main",
    components: {
        TemplateEditor,
        WorkshopTemplateForm,
        FileUpload,
        LoadingButton,
        PageHeader,
    },
    computed: {
        ...mapGetters(["isLoading"]),
    },
    props: {
        action: {
            type: String,
            required: true,
        },
        // used to sync the form from Edit and Create page
        actionForm: {
            type: Object,
            default: () => ({
                templateName: "",
                templateVersion: "",
                consultant: "",
                assignedProducts: [],
            }),
        },
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Consulting"),
                    to: "/workshop-templates",
                },
                {
                    text: this.$t("Workshop Templates"),
                    to: "/workshop-templates?page=" + this.$route.query.page,
                },
                {
                    text:
                        this.$route.name === "WorkshopTemplatesCreate"
                            ? "Create"
                            : "Edit",
                    active: true,
                },
            ],
            id: "", // the id of the created workshop template, will be used for workshopTemplate updation and widget creation purposes
            form: {
                templateName: "",
                templateVersion: "",
                consultant: "",
                assignedProducts: [],
            },
        };
    },
    watch: {
        // sync form and actionForm on change, will be triggered from Edit
        actionForm() {
            this.syncFormAndActionForm();
            // sync the id for update
            this.id = this.form.id;
        },
    },
    mounted() {
        this.syncFormAndActionForm();
    },
    methods: {
        /**
         * imports the file input from the file input
         * @param {event} the file input event
         */
        importTemplate(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.readAsText(file, "UTF-8");
            reader.onload = async (event) => {
                try {
                    // read the content of the file as text
                    const json = JSON.parse(event.target.result);
                    // set only the form fields required to create a template
                    this.form = {
                        templateName: json?.templateName ?? "",
                        templateVersion: json?.templateVersion ?? "",
                        consultant: json?.consultant ?? "",
                        assignedProducts: json?.assignedProducts ?? [],
                    };
                    // create the template
                    await this.save(this.form);
                    // update the id of the form to the newly created template id
                    this.form = { ...json, id: this.id };
                    // add a little delay because the cards are being retrieved in the save method using ref
                    await this.$nextTick();
                    // update the template with cards and all the widgets
                    await this.save(this.form);
                } catch (e) {
                    console.log(e);
                }
            };
            reader.onerror = function (error) {
                console.log(error);
            };
        },
        /**
         * downloads a JSON file containing template data
         * this file will be used to import the template
         */
        exportTemplate() {
            let fileContents = JSON.stringify(this.form);

            let fileName = `${this.form.templateName}.json`;
            let blob = new Blob([fileContents], {
                type: "text/plain",
            });
            let url = URL.createObjectURL(blob);
            let link = document.createElement("a");
            link.href = url;
            link.download = fileName;
            link.click();
            URL.revokeObjectURL(url);
        },
        /**
         * sync the form with actionForm
         */
        syncFormAndActionForm() {
            this.form = this.actionForm;
        },
        /**
         * hits the workshopTemplates/create API or workshopTemplates/update API depending on if 'id' is set or not
         * saves the 'id' from the API response
         * @param {formData} received when the save event is triggered. Contains the form data entered by the user. Has the same
         * properties as the 'form' in the 'data' above
         */
        save(formData) {
            return new Promise(async (resolve, reject) => {
                try {
                    this.$store.commit("isLoading", true);
                    this.$emitter.emit("resetConfigurationInputs", true); // emits 'resetConfigurationInputs' event which resets the value, options, records, fields configuration of the widgets in the widgetMixin
                    const payload = {
                        ...formData,
                        consultantId: formData.consultant?.id,
                        assignedProducts: formData.assignedProducts.map(
                            (product) => product.id
                        ),
                        cards: this.$refs.templateEditor?.cards ?? [],
                    };
                    const response = await this.$store.dispatch(
                        `workshopTemplates/${this.id ? "update" : "create"}`,
                        this.id ? { id: this.id, data: payload } : payload
                    );
                    this.id = response?.data?.id ?? this.id;
                    resolve();
                } catch (e) {
                    console.log(e);
                    reject(e);
                }
            });
        },
    },
};
</script>

<style scoped>
.color-blue {
    color: #2957a4;
}
.bg-blue {
    background-color: #2957a4;
}

th,
td {
    border: 1px solid black;
}
</style>
