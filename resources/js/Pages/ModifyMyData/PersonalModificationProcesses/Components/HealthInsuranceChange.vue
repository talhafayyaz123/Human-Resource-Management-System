<template>
    <div class="px-8 pb-6">
        <p>
            <span class="italic">{{ $t("Dear") }}</span
            >&nbsp;{{ authUserProfile?.jobData?.jobTitle ?? "" }}&nbsp;<span
                class="font-bold"
                >{{
                    (user?.first_name ?? "") + " " + (user?.last_name ?? "")
                }}</span
            >
        </p>
        <p class="italic mt-5">
            {{
                $t(
                    "you are using this process to change your health insurance provider in your personnel file"
                )
            }}.
        </p>
        <p class="italic mt-5">
            {{
                $t(
                    "Please provide a reason for changing your health insurance"
                )
            }}:
        </p>
        <div class="flex flex-wrap mt-2">
            <div class="pr-6">
                <input
                    :disabled="fromChangeRequest"
                    name="reason"
                    v-model="reason"
                    id="change-to-private-health-insurance"
                    type="radio"
                    class="mr-1"
                    value="change-to-private-health-insurance"
                />
                <label for="change-to-private-health-insurance">{{
                    $t("Change to private health insurance")
                }}</label>
            </div>
            <div class="pr-6">
                <input
                    :disabled="fromChangeRequest"
                    name="reason"
                    v-model="reason"
                    id="change-to-another-statutory-health-insurance"
                    type="radio"
                    class="mr-1"
                    value="change-to-another-statutory-health-insurance"
                />
                <label for="change-to-another-statutory-health-insurance">{{
                    $t("Change to another statutory health insurance")
                }}</label>
            </div>
        </div>
        <p class="text-red-500" v-if="errors['reason']">
            {{ errors["reason"] }}
        </p>
        <p class="italic mt-5">
            {{
                $t("Below you can see your currently stored health insurance")
            }}:
        </p>
        <div class="flex flex-wrap">
            <TextInput
                class="pr-6"
                :isReadonly="true"
                :model-value="authUserProfile?.healthInsurance ?? ''"
                :label="$t('Health Insurance')"
            ></TextInput>
        </div>
        <p class="italic mt-5">
            {{
                $t(
                    "Please enter your new health isurance data and check this entry carefully"
                )
            }}:
        </p>
        <div class="flex flex-wrap">
            <TextInput
                :isReadonly="fromChangeRequest"
                class="pr-6"
                v-model="form.healthInsurance"
                :label="$t('Health Insurance')"
            ></TextInput>
        </div>
        <p class="text-red-500" v-if="errors['changeRequestData']">
            {{ errors["changeRequestData"] }}
        </p>
        <div v-if="!fromChangeRequest" class="flex flex-wrap mt-5">
            <div class="pr-6">
                <input
                    v-model="termsAgreed"
                    :checked="termsAgreed"
                    id="confirmation"
                    type="checkbox"
                    class="mr-1"
                />
                <label for="confirmation">{{
                    $t("I have carefully checked the data and confirm it.")
                }}</label>
            </div>
        </div>
    </div>
</template>

<script>
import mainMixin from "../Mixins/mainMixin";

export default {
    mixins: [mainMixin],
    data() {
        return {
            form: {
                healthInsurance: "",
            },
            reason: "",
        };
    },
};
</script>

<style scoped></style>
