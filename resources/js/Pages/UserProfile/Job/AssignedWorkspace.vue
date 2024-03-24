<template>
    <div class="grid items-center grid-cols-2 gap-6">
        <div class="w-full">
            <div class="form-group">
                <MultiSelectInput
                    v-if="isLoaded"
                    class=""
                    :required="true"
                    :show-labels="false"
                    v-model="jobForm.locationId"
                    :options="locations.data"
                    :multiple="false"
                    :textLabel="$t('Location')"
                    label="address"
                    trackBy="id"
                    moduleName="locations"
                    :showLoadMoreText="true"
                    :error="errors.locationId"
                >
                    <template #beforeList>
                        <div
                            class="grid p-2 gap-2"
                            style="
                                grid-template-columns: 16.66% 16.66% 16.66% 16.66% 16.66% 16.66%;
                            "
                        >
                            <p class="font-bold">{{ $t("name") }}</p>
                            <p class="font-bold">{{ $t("Street") }}</p>
                            <p class="font-bold">{{ $t("City") }}</p>
                            <p class="font-bold">{{ $t("ZIP") }}</p>
                            <p class="font-bold">{{ $t("State") }}</p>
                            <p class="font-bold">{{ $t("Country") }}</p>
                        </div>
                        <hr />
                    </template>
                    <template #singleLabel="{ props }">
                        {{ props.option.name }}
                    </template>
                    <template #option="{ props }">
                        <div
                            class="grid gap-2"
                            style="
                                grid-template-columns: 16.66% 16.66% 16.66% 16.66% 16.66% 16.66%;
                            "
                        >
                            <p
                                class="overflow-text-users-table location-length"
                            >
                                {{ props.option.name }}
                            </p>
                            <p
                                class="overflow-text-users-table location-length"
                            >
                                {{ props.option.street }}
                            </p>
                            <p
                                class="overflow-text-users-table location-length"
                            >
                                {{ props.option.city }}
                            </p>
                            <p
                                class="overflow-text-users-table location-length"
                            >
                                {{ props.option.zipCode }}
                            </p>
                            <p
                                class="overflow-text-users-table location-length"
                            >
                                {{ props.option.state }}
                            </p>
                            <p
                                class="overflow-text-users-table location-length"
                            >
                                {{ props.option.country }}
                            </p>
                        </div>
                    </template>
                </MultiSelectInput>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
export default {
    inject: ["isLoaded"],
    computed: {
        ...mapGetters("userLocations", ["locations"]),
    },
    components: {
        TextInput,
        MultiSelectInput,
    },
    mounted() {
        this.$store.dispatch("userLocations/list", {
            perPage: 10,
            page: 1,
        });
    },
    props: ["jobForm", "errors"],
    data() {
        return {};
    },
};
</script>
