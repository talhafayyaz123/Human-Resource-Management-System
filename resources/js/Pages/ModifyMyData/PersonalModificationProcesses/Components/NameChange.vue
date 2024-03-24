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
            {{ $t("please indicate a reason for civil name change") }}:
        </p>
        <div class="flex flex-wrap mt-2">
            <div class="pr-6">
                <input
                    :disabled="fromChangeRequest"
                    v-model="reason"
                    name="reason"
                    id="change-after-marriage"
                    type="radio"
                    class="mr-1"
                    value="change-after-marriage"
                />
                <label for="change-after-marriage">{{
                    $t("Change after marriage")
                }}</label>
            </div>
            <div class="pr-6">
                <input
                    :disabled="fromChangeRequest"
                    v-model="reason"
                    name="reason"
                    id="change-to-birth-name"
                    type="radio"
                    class="mr-1"
                    value="change-to-birth-name"
                />
                <label for="change-to-birth-name">{{
                    $t("Change to birth name after divorce")
                }}</label>
            </div>
        </div>
        <p class="text-red-500" v-if="errors['reason']">
            {{ errors["reason"] }}
        </p>
        <p class="italic mt-5">
            {{ $t("Name currently on file in your personnel file") }}:
        </p>
        <div class="flex flex-wrap">
            <TextInput
                class="pr-6"
                :isReadonly="true"
                :required="true"
                :model-value="user?.first_name ?? ''"
                :label="$t('First Name')"
            ></TextInput>
            <TextInput
                :model-value="user?.last_name ?? ''"
                :isReadonly="true"
                :required="true"
                class="pr-6"
                :label="$t('Last Name')"
            ></TextInput>
        </div>
        <p class="italic mt-5">{{ $t("Please enter your new last name") }}:</p>
        <div class="flex flex-wrap">
            <TextInput
                class="pr-6"
                :required="true"
                :isReadonly="true"
                :model-value="user?.first_name ?? ''"
                :label="$t('First Name')"
            ></TextInput>
            <TextInput
                class="pr-6"
                :required="true"
                :label="$t('Last Name')"
                v-model="form.lastName"
                :isReadonly="fromChangeRequest"
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
                lastName: "",
            },
            reason: "",
        };
    },
};
</script>

<style scoped></style>
