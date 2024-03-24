<template>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.accidentInsurance"
                :error="errors.accidentInsurance"
                class=""
                :label="$t('Accident Insurance')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.tariffPoints"
                :error="errors.tariffPoints"
                class=""
                :label="$t('Tariff Points')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.percentageAssociation"
                :error="errors.percentageAssociation"
                class=""
                :label="$t('Percentage Association')"
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
