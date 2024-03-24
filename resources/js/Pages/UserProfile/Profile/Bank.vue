<template>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.accountOwner"
                :error="errors.accountOwner"
                class=""
                :label="$t('Account Owner')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.iban"
                :error="errors.iban"
                class=""
                :label="$t('IBAN')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.bic"
                :error="errors.bic"
                class=""
                :label="$t('BIC')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.bankName"
                :error="errors.bankName"
                class=""
                :label="$t('Bank Name')"
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
