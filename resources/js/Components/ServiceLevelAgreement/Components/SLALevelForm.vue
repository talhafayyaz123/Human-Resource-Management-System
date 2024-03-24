<template>
    <div class="card">
        <div class="card-header">
          <h3 class="card-title"></h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-4 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="slaForm.name"
                            :error="errors.name"
                            :label="$t('Name')"
                            :required="true"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <number-input
                            :error="errors.factor"
                            v-model="slaForm.factor"
                            :required="true"
                            :showPrefix="false"
                            :label="$t('Factor')"
                        />
                    </div>
                </div>
            </div>
            <div class="grid items-center grid-cols-4 gap-6 my-5">
                <div class="self-center">
                    <p>{{ $t("Priority: Urgent") }}</p>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :error="errors.reactionTimeUrgent"
                            :required="true"
                            :min="0"
                            @keypress="limitToPositiveNumbers"
                            v-model="slaForm.reactionTimeUrgent"
                            :type="`number`"
                            :label="$t('Reaction Time')"
                        />
                    </div>
                </div>
            </div>
            <div class="grid items-center grid-cols-4 gap-6 my-5">
                <div class="self-center">
                    <p>{{ $t("Priority: High") }}</p>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :error="errors.reactionTimeHigh"
                            :required="true"
                            :min="0"
                            @keypress="limitToPositiveNumbers"
                            v-model="slaForm.reactionTimeHigh"
                            :type="`number`"
                            :label="$t('Reaction Time')"
                        />
                    </div>
                </div>
            </div>
            <div class="grid items-center grid-cols-4 gap-6 my-5">
                <div class="self-center">
                    <p>{{ $t("Priority: Low") }}</p>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :error="errors.reactionTimeLow"
                            :required="true"
                            :min="0"
                            @keypress="limitToPositiveNumbers"
                            v-model="slaForm.reactionTimeLow"
                            :type="`number`"
                            :label="$t('Reaction Time')"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex items-center justify-end mt-4">
        <loading-button v-if="$can(`${$route.meta.permission}.create`)" @click="$emit('save', slaForm)" :loading="isLoading" class="secondary-btn">
          <span class="mr-1">
            <CustomIcon name="saveIcon" />
          </span>
          {{ $t("Save and Proceed") }}
        </loading-button>
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    props: {
        form: {
            type: Object,
            default: () => ({}),
        },
    },
    watch: {
        form: {
            handler: function () {
                this.slaForm = { ...this.form };
            },
            deep: true,
        },
    },
    mounted() {
        this.slaForm = { ...this.form };
    },
    data() {
        return {
            slaForm: {},
        };
    },
    components: {
        LoadingButton,
        TextInput,
        NumberInput,
        SelectInput,
    },
};
</script>
