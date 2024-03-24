<template>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.birthName"
                :error="errors.birthName"
                class=""
                :label="$t('Birth Name')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <date-input
                :required="true"
                v-model="profileForm.dateOfBirth"
                :error="errors.dateOfBirth"
                class=""
                :label="$t('Date of birth')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <select-input
                :required="true"
                v-if="isLoaded"
                v-model="profileForm.countryOfBirth"
                :error="errors.countryOfBirth"
                class=""
                :label="$t('Country Of Birth')"
            >
                <option
                    v-for="country in countriesData"
                    :value="country.name"
                    :key="country.id"
                >
                    {{ country.name }}
                </option>
            </select-input>
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                :required="true"
                v-model="profileForm.placeOfBirth"
                :error="errors.placeOfBirth"
                class=""
                :label="$t('Place Of Birth')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <select-input
                :required="true"
                v-if="isLoaded"
                v-model="profileForm.gender"
                :error="errors.gender"
                class=""
                :label="$t('Gender')"
            >
                <option value="male">{{ $t("Male") }}</option>
                <option value="female">{{ $t("Female") }}</option>
                <option value="other">{{ $t("Other") }}</option>
            </select-input>
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                :required="true"
                v-model="profileForm.nationality"
                :error="errors.nationality"
                class=""
                :label="$t('Nationality')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <MultiSelectInput
                v-model="profileForm.highestSchoolDegree"
                :key="profileForm.highestSchoolDegree"
                :options="highestSchoolDegrees.data"
                :multiple="false"
                :textLabel="$t('Highest School Degree')"
                label="name"
                trackBy="id"
                :error="errors.highestSchoolDegree"
                moduleName="highestSchoolDegrees"
                class=""
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <MultiSelectInput
                v-model="profileForm.highestEducationLevel"
                :key="profileForm.highestEducationLevel"
                :options="highestEducationLevels.data"
                :multiple="false"
                :textLabel="$t('Highest Education Level')"
                label="name"
                trackBy="id"
                :error="errors.highestEducationLevel"
                moduleName="highestEducationLevels"
                class=""
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <select-input
                :required="true"
                :key="profileForm.maritalStatus"
                v-model="profileForm.maritalStatus"
                :error="errors.maritalStatus"
                class=""
                :label="$t('Marital Status')"
            >
                <option value="married">{{ $t("Married") }}</option>
                <option value="single">{{ $t("Single") }}</option>
                <option value="divorced">{{ $t("Divorced") }}</option>
                <option value="widowed">{{ $t("Widowed") }}</option>
            </select-input>
        </div>
    </div>

    <!-- <text-input
        :required="true"
        v-model="profileForm.countryOfBirth"
        :error="errors.countryOfBirth"
        class=""
        :label="$t('Country Of Birth')"
    /> -->

    <!--    <text-input
        :required="true"
        v-model="profileForm.placeOfBirth"
        :error="errors.placeOfBirth"
        class=""
        :label="$t('Place Of Birth')"
    />-->
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import DateInput from "@/Components/DateInput.vue";
import { mapGetters } from "vuex";

export default {
    inject: ["isLoaded"],
    components: {
        SelectInput,
        TextInput,
        DateInput,
        MultiSelectInput,
    },
    props: ["profileForm", "errors"],
    data() {
        return {
            countriesData: [],
        };
    },
    computed: {
        ...mapGetters("countries", ["countries"]),
        ...mapGetters("highestSchoolDegrees", ["highestSchoolDegrees"]),
        ...mapGetters("highestEducationLevels", ["highestEducationLevels"]),
    },
    async mounted() {
        await this.$store.dispatch("countries/list");
        if (!this.highestSchoolDegrees?.data?.length)
            await this.$store.dispatch("highestSchoolDegrees/list");
        if (!this.highestEducationLevels?.data?.length)
            await this.$store.dispatch("highestEducationLevels/list");
        this.countriesData = this.countries?.data;
    },
};
</script>
