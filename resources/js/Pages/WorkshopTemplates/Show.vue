<template>
    <div>
        <PageHeader :items="breadcrumbItems"  />

        <div class="w-full bg-white rounded-md shadow">
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <MultiSelectInput
                    v-model="customer"
                    :required="true"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :textLabel="$t('Customer')"
                    :placeholder="$t('Select Customer')"
                    :options="companies.data"
                    :multiple="false"
                    label="companyName"
                    trackBy="id"
                    moduleName="companies"
                    :error="errors.customer"
                />
                <MultiSelectInput
                    v-model="system"
                    :required="true"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :textLabel="$t('System')"
                    :placeholder="$t('Select System')"
                    :options="premiseSystems.data"
                    :multiple="false"
                    label="systemName"
                    trackBy="id"
                    moduleName="systems"
                    :query="{ type: 'premise' }"
                    :error="errors.system"
                />
            </div>
            <div class="pb-5 pl-8">
                <loading-button
                    @click="save()"
                    :loading="isLoading"
                    class="docsHeroColorBlue px-3 py-2 rounded"
                >
                    Save
                </loading-button>
            </div>
        </div>

        <TemplateEditor
            ref="templateEditor"
            v-if="id"
            :cardsFromAPI="form?.cards ?? []"
            :workshopTemplateId="id"
        />
    </div>
</template>

<script>
import TemplateEditor from "./Components/TemplateEditor.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    name: "WorkshopTemplatesCreate",
    components: {
        TemplateEditor,
        MultiSelectInput,
        LoadingButton,
        PageHeader
    },
    computed: {
        ...mapGetters("workshopTemplates", ["inputConfigurations"]),
        ...mapGetters("companies", ["companies"]),
        ...mapGetters("systems", ["premiseSystems"]),
        ...mapGetters(["isLoading", "errors"]),
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Workshop Templates",
                    to: "/workshop-templates?page="+this.$route.query.page,
                },
                {
                    text: "Customer",
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
            customer: "",
            system: "",
        };
    },
    async mounted() {
        // toggle 'showPreview' to true in the show page to remove all the actions
        this.$store.commit("showLoader", true);
        this.$store.commit("workshopTemplates/showPreview", true);
        // clear out the selectedWidget
        this.$store.commit("workshopTemplates/selectedWidget", null);
        try {
            if (!this.companies?.data?.length)
                await this.$store.dispatch("companies/list", {
                    perPage: 25,
                    page: 1,
                });
            if (!this.premiseSystems?.data?.length)
                await this.$store.dispatch("systems/list", {
                    perPage: 25,
                    page: 1,
                    type: "premise",
                });
            const response = await this.$store.dispatch(
                "workshopTemplates/show",
                this.$route.params.id
            );
            this.form = response?.data?.data ?? this.form;
            this.id = this.form.id;
        } catch (e) {
            console.log(e);
        }
        finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        /**
         * flattens the 'inputConfigurations' object and returns an object containing the inner most object key/value pairs
         */
        flattenObject() {
            let objectArray = Object.values(
                this.inputConfigurations["widgets"]
            );
            let flattenedObject = {};
            objectArray.forEach((obj) => {
                flattenedObject = { ...flattenedObject, ...obj };
            });
            // map group name and value to the flattenedObject
            for (let key in this.inputConfigurations["groups"]) {
                flattenedObject[key] = this.inputConfigurations["groups"][key];
            }
            return flattenedObject;
        },
        /**
         * runs the exportFile API on the files in the form and downloads the relevant JSON
         */
        async save() {
            try {
                // iterate over files
                for (let i = 0; i < this.form.files.length; i++) {
                    // run the exportFile API based on the storage_name for the given file
                    this.$store.commit("isLoading", true);
                    const response = await this.$store.dispatch(
                        "workshopTemplates/exportFile",
                        {
                            filename: this.form.files[i]?.storage_name,
                            data: JSON.stringify(this.flattenObject()), //send the data to be mapped as JSON string
                        }
                    );
                    // download the JSON file based on the response
                    let fileContents = JSON.stringify(
                        response?.data?.data ?? {}
                    );
                    let fileName = response?.data?.file ?? "index.json";
                    let blob = new Blob([fileContents], {
                        type: "application/json",
                    });
                    let url = URL.createObjectURL(blob);
                    let link = document.createElement("a");
                    link.href = url;
                    link.download = fileName;
                    link.click();
                    URL.revokeObjectURL(url);
                }
            } catch (e) {
                console.log(e);
            }
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
