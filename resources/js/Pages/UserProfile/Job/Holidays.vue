<template>
    <div class="grid items-center grid-cols-2 gap-6">
        <div class="w-full">
            <div class="form-group">
                <text-input
                    type="number"
                    :required="true"
                    v-model="jobForm.totalAnnualLeaves"
                    @update:model-value="totalAnnualLeavesChanged"
                    :error="errors.totalAnnualLeaves"
                    class=""
                    :label="$t('Total Annual Leaves')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <text-input
                    type="number"
                    v-model="jobForm.additionalLeaves"
                    @update:model-value="totalAnnualLeavesChanged"
                    :error="errors.additionalLeaves"
                    class=""
                    :label="
                        $t('Additional Leaves for Year ') +
                        new Date().getFullYear()
                    "
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <text-input
                    type="number"
                    v-model="jobForm.previousYearRemainingLeaves"
                    class=""
                    :isReadonly="true"
                    :label="$t('Previous Year Leaves')"
                />
            </div>
        </div>
        <div class="w-full">
            <div class="form-group">
                <text-input
                    type="number"
                    v-model="totalLeaves"
                    :isReadonly="true"
                    class=""
                    :tooltipMessage="
                        $t(
                            `Sum of 'Total Annual Leaves', 'Additional leaves' and unexpired 'Remaining Leaves' of last year.`
                        )
                    "
                    :label="$t('Total Leaves')"
                />
            </div>
        </div>
    </div>

    <!--    <text-input-->
    <!--        type="number"-->
    <!--        min="1900"-->
    <!--        :max="new Date().getFullYear()"-->
    <!--        v-model="jobForm.remainingLeavesYear"-->
    <!--        :error="errors.remainingLeavesYear"-->
    <!--        class="pb-8 pr-6 w-full lg:w-1/3"-->
    <!--        :label="$t('Remaining Leaves Year')"-->
    <!--    />-->
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";

export default {
    components: {
        TextInput,
        MultiSelectInput,
    },
    props: ["jobForm", "errors"],
    data() {
        return {};
    },
    computed: {
        totalLeaves() {
            return (
                Number(this.jobForm.previousYearRemainingLeaves) +
                Number(this.jobForm.totalAnnualLeaves) +
                Number(this.jobForm.additionalLeaves)
            );
        },
    },
    methods: {
        async totalAnnualLeavesChanged() {
            await this.$nextTick();
            this.totalLeaves =
                Number(this.jobForm.previousYearRemainingLeaves) +
                Number(this.jobForm.totalAnnualLeaves) +
                Number(this.jobForm.additionalLeaves);
        },
    },
};
</script>

<style scoped>
:deep(.multiselect__tag) {
    text-transform: capitalize;
}
</style>
