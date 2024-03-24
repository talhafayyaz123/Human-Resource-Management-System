<template>
    <div class="grid items-center grid-cols-2 gap-6">
        <div class="w-full">
            <div class="form-group">
                <!-- Time spent on the entry -->
                <number-input
                    :roundNearQuarter="true"
                    :forceLeftBound="true"
                    :showPrefix="false"
                    v-model="accountedTime"
                    :min="0"
                    
                    :required="true"
                    :label="$t('Accounted Time')"
                    :error="$t(modalErrors.accountedTime)"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <!-- Which "company" does the current record belong to -->
                <MultiSelectInput
                    :required="true"
                    v-if="$can('company.list')"
                    
                    :textLabel="$t('Customer')"
                    v-model="company"
                    :options="companyOptions"
                    :multiple="false"
                    label="companyName"
                    trackBy="id"
                    moduleName="companies"
                    :key="company?.id"
                    :error="$t(modalErrors.company)"
                    @update:model-value="getProjects"
                    @asyncSearch="companyOptions = companies?.data"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    :required="true"
                    :options="projects?.data ?? []"
                    
                    :multiple="false"
                    :text-label="$t('Projects')"
                    label="name"
                    v-if="$can('project.list')"
                    trackBy="id"
                    moduleName="projects"
                    v-model="project"
                    :key="project"
                    :error="$t(modalErrors.projectId)"
                    :query="{
                        status: ['new', 'in-work', 'in-review', 'blocked'],
                    }"
                />
            </div>
        </div>
        <div class="w-full col-span-2">
            <div class="form-group">
                <!-- Description of the given type -->

                <text-area-input
                    :required="true"
                    v-model="description"
                    :rows="3"
                    :error="$t(modalErrors.description)"
                    
                    :label="$t('Description')"
                />
            </div>
        </div>
        <div class="w-full col-span-2">
            <div class="form-group">
                <text-area-input
                    :required="false"
                    v-model="internalNote"
                    :rows="3"
                    
                    :label="$t('Internal Note')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="checkbox-group">
                <!-- Is the time tracking for free? -->
                    <input
                        id="goodwill"
                        v-model="isGoodwill"
                        :type="`checkbox`"
                        class="checkbox-input"
                    />
                    <label class="checkbox-label" :for="'goodwill'">
                        {{ $t("Is Goodwill") }}?
                    </label>
            </div>
        </div>
    </div>
</template>

<script>
import NumberInput from "@/Components/NumberInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    props: [
        "modalErrors",
        "actionType",
        "filterCompany",
        "timeTrackerCompanyEditData",
    ],
    components: {
        NumberInput,
        TextAreaInput,
        MultiSelectInput,
    },
    computed: {
        ...mapGetters("companies", ["companies"]),
        ...mapGetters("projects", ["projects"]),
    },
    data() {
        return {
            project: "",
            company: "",
            companyOptions: [],
            accountedTime: "0.00",
            description: "",
            internalNote: "",
            isGoodwill: false,
        };
    },
    mounted() {
        this.getCompanies();
        if (this.actionType === "edit") {
            this.getProjects(this.timeTrackerCompanyEditData.company);
            this.description = this.timeTrackerCompanyEditData.description;
            this.internalNote = this.timeTrackerCompanyEditData.internalNote;
            this.accountedTime = this.timeTrackerCompanyEditData.time;
            this.isGoodwill =
                this.timeTrackerCompanyEditData.isGoodwill == 1 ? true : false;
            this.company = this.timeTrackerCompanyEditData.company;
            this.project =
                this.timeTrackerCompanyEditData.additionalInfo.project;
        }
    },
    methods: {
        /**
         * get projects listing based on company
         * @param {companyId} company id
         */
        async getProjects(companyId) {
            try {
                await this.$store.dispatch("projects/list", {
                    companyId: companyId?.id ?? "",
                    status: ["new", "in-work", "in-review", "blocked"],
                });
            } catch (e) {
                console.log(e);
            }
        },
        async getCompanies() {
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
            if (this.filterCompany.id !== "all") {
                this.companyOptions = this.companies?.data.filter(
                    (item) => item.id === this.filterCompany.id
                );
                this.company = this.filterCompany;
            } else {
                this.companyOptions = this.companies?.data;
            }
        },
    },
};
</script>
