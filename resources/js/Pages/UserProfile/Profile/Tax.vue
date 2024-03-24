<template>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.financeDepartmentNumber"
                :error="errors.financeDepartmentNumber"
                type="number"
                class=""
                :label="$t('Finance Department Number')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.financeCategory"
                type="number"
                :error="errors.financeCategory"
                class=""
                :label="$t('Finance Category')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.religion"
                :error="errors.religion"
                class=""
                :label="$t('Religion')"
            />
        </div>
    </div>

    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.religionOfPartner"
                :error="errors.religionOfPartner"
                class=""
                :label="$t('Religion of Partner')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.freeAmountChildren"
                type="number"
                :error="errors.freeAmountChildren"
                class=""
                :label="$t('Free Amount Children')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.freeAmountYearly"
                type="number"
                :error="errors.freeAmountYearly"
                class=""
                :label="$t('Free Amount Yearly')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.freeAmountMonthly"
                type="number"
                :error="errors.freeAmountMonthly"
                class=""
                :label="$t('Free Amount Monthly')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.taxLiability"
                :error="errors.taxLiability"
                class=""
                :label="$t('Tax Liability')"
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
