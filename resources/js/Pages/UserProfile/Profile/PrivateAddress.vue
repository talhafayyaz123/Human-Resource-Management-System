<template>
    <div class="w-full">
        <div class="form-group">
            <text-input
                :required="true"
                v-model="profileForm.street"
                :error="errors.street"
                class=""
                :label="$t('Street')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.address"
                :error="errors.address"
                class=""
                :label="$t('Address')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.zipCode"
                :error="errors.zipCode"
                class=""
                :label="$t('Zip Code')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.city"
                :error="errors.city"
                class=""
                :label="$t('City')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                :required="true"
                v-model="profileForm.state"
                :error="errors.state"
                class=""
                :label="$t('State')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <select-input
                v-if="isApiCalled"
                :required="true"
                v-model="profileForm.country"
                :error="errors.country"
                class=""
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
    </div>

    <!-- <text-input
        :required="true"
        v-model="profileForm.country"
        :error="errors.country"
        class=""
        :label="$t('Country')"
    /> -->
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { mapGetters } from "vuex";
export default {
    inject: ["existingUser"],
    components: {
        TextInput,
        SelectInput,
    },
    computed: {
        ...mapGetters("countries", ["countries"]),
    },
    props: ["profileForm", "errors"],
    data() {
        return {
            countriesData: [],
            isApiCalled: false,
        };
    },
    async mounted() {
        await this.$store.dispatch("countries/list");
        this.countriesData = this.countries?.data;
        this.isApiCalled = true;
    },
};
</script>
