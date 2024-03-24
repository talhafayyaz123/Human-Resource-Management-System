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
                    "with this process you will change your bank details in your personnel file."
                )
            }}
        </p>
        <p class="italic mt-1">
            {{
                $t(
                    "Below you will see your current bank account information on file"
                )
            }}:
        </p>
        <div class="flex flex-wrap mt-2">
            <text-input
                :model-value="authUserProfile?.accountOwner"
                :isReadonly="true"
                class="pb-8 pr-6 w-full lg:w-1/4"
                :label="$t('Account Owner')"
            />

            <text-input
                :model-value="authUserProfile?.iban"
                :isReadonly="true"
                class="pb-8 pr-6 w-full lg:w-1/4"
                :label="$t('iban')"
            />

            <text-input
                :model-value="authUserProfile?.bic"
                :isReadonly="true"
                class="pb-8 pr-6 w-full lg:w-1/4"
                :label="$t('bic')"
            />

            <text-input
                :model-value="authUserProfile?.bankName"
                :isReadonly="true"
                class="pb-8 pr-6 w-full lg:w-1/4"
                :label="$t('Bank Name')"
            />
        </div>
        <p class="italic">
            {{
                $t(
                    "Please enter your new bank account information and review it carefully"
                )
            }}:
        </p>
        <div class="flex flex-wrap mt-2">
            <text-input
                :isReadonly="fromChangeRequest"
                v-model="form.accountOwner"
                class="pb-8 pr-6 w-full lg:w-1/4"
                :label="$t('Account Owner')"
            />

            <text-input
                :isReadonly="fromChangeRequest"
                v-model="form.iban"
                class="pb-8 pr-6 w-full lg:w-1/4"
                :label="$t('iban')"
            />

            <text-input
                :isReadonly="fromChangeRequest"
                v-model="form.bic"
                class="pb-8 pr-6 w-full lg:w-1/4"
                :label="$t('bic')"
            />

            <text-input
                :isReadonly="fromChangeRequest"
                v-model="form.bankName"
                class="pb-8 pr-6 w-full lg:w-1/4"
                :label="$t('Bank Name')"
            />
        </div>
        <p class="text-red-500" v-if="errors['changeRequestData']">
            {{ errors["changeRequestData"] }}
        </p>
        <div v-if="!fromChangeRequest" class="flex flex-wrap">
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
                accountOwner: "",
                iban: "",
                bic: "",
                bankName: "",
            },
            reason: "",
        };
    },
};
</script>

<style scoped></style>
