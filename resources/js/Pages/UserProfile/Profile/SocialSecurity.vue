<template>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.socialSecurityNumber"
                :error="errors.socialSecurityNumber"
                class=""
                :label="$t('Social Security Number')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.previousHealthInsurance"
                :error="errors.previousHealthInsurance"
                class=""
                :label="$t('Previous Health Insurance')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.healthInsurance"
                :error="errors.healthInsurance"
                class=""
                :label="$t('Health Insurance')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.groupPeople"
                :error="errors.groupPeople"
                type="number"
                class=""
                :label="$t('Group of People')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.contributionGroup"
                type="number"
                :error="errors.contributionGroup"
                class=""
                :label="$t('Contribution Group')"
            />
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
export default {
    components: {
        TextInput,
    },
    props: ["profileForm", "errors"],
    data() {
        return {};
    },
};
</script>
