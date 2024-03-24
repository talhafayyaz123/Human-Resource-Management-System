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
                    "this process will allow you to change your address in your personnel file"
                )
            }}.
        </p>
        <p class="italic mt-5">
            {{ $t("Below you will see your currently stored address data") }}:
        </p>
        <div class="flex flex-wrap mt-2">
            <text-input
                :required="true"
                :model-value="authUserProfile?.street"
                :error="errors.street"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('Street')"
            />
            <text-input
                :model-value="authUserProfile?.address"
                :error="errors.address"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('Address')"
            />
            <text-input
                :model-value="authUserProfile?.zipCode"
                :error="errors.zipCode"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('Zip Code')"
            />

            <text-input
                :model-value="authUserProfile?.city"
                :error="errors.city"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('City')"
            />

            <text-input
                :required="true"
                :model-value="authUserProfile?.state"
                :error="errors.state"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('State')"
            />

            <select-input
                :required="true"
                :model-value="authUserProfile?.country"
                :key="authUserProfile?.country"
                :error="errors.country"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('Country')"
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
        <p class="italic">
            {{
                $t(
                    "Please enter your new address information and review it carefully"
                )
            }}:
        </p>
        <div class="flex flex-wrap mt-2">
            <text-input
                :isReadonly="fromChangeRequest"
                v-model="form.street"
                :required="true"
                :error="errors.street"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('Street')"
            />
            <text-input
                :isReadonly="fromChangeRequest"
                v-model="form.address"
                :error="errors.address"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('Address')"
            />
            <text-input
                :isReadonly="fromChangeRequest"
                v-model="form.zipCode"
                :error="errors.zipCode"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('Zip Code')"
            />

            <text-input
                :isReadonly="fromChangeRequest"
                v-model="form.city"
                :error="errors.city"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('City')"
            />

            <text-input
                :isReadonly="fromChangeRequest"
                v-model="form.state"
                :required="true"
                :error="errors.state"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('State')"
            />

            <select-input
                :isReadOnly="fromChangeRequest"
                v-model="form.country"
                :required="true"
                :error="errors.country"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('Country')"
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
import SelectInput from "@/Components/SelectInput.vue";
import { mapGetters } from "vuex";

export default {
    mixins: [mainMixin],
    components: {
        SelectInput,
    },
    data() {
        return {
            countriesData: [],
            form: {
                street: "",
                address: "",
                zipCode: "",
                city: "",
                state: "",
                country: "",
            },
            reason: "",
        };
    },
    computed: {
        ...mapGetters("countries", ["countries"]),
    },
    async mounted() {
        await this.$store.dispatch("countries/list");
        this.countriesData = this.countries?.data;
    },
};
</script>

<style scoped></style>
