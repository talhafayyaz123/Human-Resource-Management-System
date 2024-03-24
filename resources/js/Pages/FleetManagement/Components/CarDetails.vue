<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Car Details</h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.licenceNumber"
                                :error="errors?.licenceNumber"
                                class=""
                                :label="$t('Licence Number')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                v-if="form.isApiCalled"
                                v-model="form.driver"
                                :showLabels="false"
                                :options="users"
                                class=""
                                :multiple="false"
                                :textLabel="$t('Driver')"
                                label="first_name"
                                trackBy="id"
                                moduleName="auth"
                                :search-param-name="'search_string'"
                                :customLabel="customLabel"
                                :error="errors.driverId"
                            >
                                <template #beforeList>
                                    <div
                                        class="grid p-2 gap-2"
                                        style="grid-template-columns: 50% 50%"
                                    >
                                        <p class="font-bold">
                                            {{ $t("First Name") }}
                                        </p>
                                        <p class="font-bold">
                                            {{ $t("Last Name") }}
                                        </p>
                                    </div>
                                    <hr />
                                </template>
                                <template #singleLabel="{ props }">
                                    <p>
                                        {{ props.option.first_name }}
                                        {{ props.option.last_name }}
                                    </p>
                                </template>
                                <template #option="{ props }">
                                    <div
                                        class="grid"
                                        style="grid-template-columns: 50% 50%"
                                    >
                                        <p class="overflow-text-users-table">
                                            {{ props.option.first_name }}
                                        </p>
                                        <p class="overflow-text-users-table">
                                            {{ props.option.last_name }}
                                        </p>
                                    </div>
                                </template>
                            </MultiSelectInput>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.vim"
                                :error="errors?.vim"
                                class=""
                                :label="$t('VIM')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                :required="true"
                                v-if="form.isApiCalled"
                                v-model="form.fuelType"
                                class=""
                                :label="$t('Fuel Type')"
                                :error="errors?.fuelType ?? ''"
                            >
                                <!-- :error="form.errors.subType" -->
                                <option value="diesel">
                                    {{ $t("Diesel") }}
                                </option>
                                <option value="electronic">
                                    {{ $t("Electronic") }}
                                </option>
                                <option value="gasoline">
                                    {{ $t("Gasoline") }}
                                </option>
                            </select-input>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                v-if="form.isApiCalled"
                                :required="true"
                                v-model="form.carType"
                                class=""
                                :label="$t('Car Type')"
                                :error="errors?.carType ?? ''"
                            >
                                <option value="rent_car">
                                    {{ $t("Rent Car") }}
                                </option>
                                <option value="leasing_car">
                                    {{ $t("Leasing Car") }}
                                </option>
                                <option value="pool_car">
                                    {{ $t("Pool Car") }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="false"
                                v-model="form.assetNumber"
                                :error="errors?.assetNumber"
                                class=""
                                :label="$t('Asset Number')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.color"
                                :error="errors?.color"
                                class=""
                                :label="$t('Color')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.kilowatt"
                                :error="errors?.kilowatt"
                                class=""
                                :label="'kW/PS'"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.brand"
                                :error="errors?.brand"
                                class=""
                                :label="$t('Brand')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.modelKey"
                                :error="errors?.modelKey"
                                class=""
                                :label="$t('Model Key')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.model"
                                :error="errors?.model"
                                class=""
                                :label="$t('Model')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.modelDetail"
                                :error="errors?.modelDetail"
                                class=""
                                :label="$t('Model Detail')"
                            />
                        </div>
                    </div>
                    <div class="w-full col-span-2">
                        <div class="form-group">
                            <QuillTextEditor
                                v-if="form.isApiCalled"
                                class=""
                                :content="form.extraEquipment"
                                :required="true"
                                :error="errors.extraEquipment"
                                @delta="form.extraEquipment = $event"
                                :label="$t('Extra Equipment')"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Service Information") }}</h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.totalMileage"
                                :error="errors?.totalMileage"
                                class=""
                                :label="$t('Total Mileage')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <date-input
                                v-model="form.updatedAt"
                                :error="errors?.updatedAt"
                                class=""
                                :label="$t('Updated at')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                v-if="form.isApiCalled"
                                :required="true"
                                v-model="form.status"
                                class=""
                                :label="$t('Status')"
                                :error="errors?.status ?? ''"
                            >
                                <option value="ready">
                                    {{ $t("Ready") }}
                                </option>
                                <option value="out_of_service">
                                    {{ $t("Out of Service") }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end mt-4">
            <router-link to="/fleet-cars" class="primary-btn me-3">
                <span class="mr-1">
                    <CustomIcon name="cancelIcon" />
                </span>
                <span>{{ $t("Cancel") }}</span>
            </router-link>
            <loading-button @click="$emit('next')" class="secondary-btn">
                <span class="mr-1">
                    <CustomIcon name="saveIcon" />
                </span>
                {{ $t("Save and Proceed") }}
            </loading-button>
        </div>
    </div>
</template>

<script>
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import DateInput from "@/Components/DateInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
export default {
    computed: {
        ...mapGetters("auth", {
            users: "users",
        }),
    },
    props: {
        form: {
            type: Object,
            required: true,
        },
        errors: {
            type: Object,
            default: null,
        },
        isEdit: {
            type: Boolean,
            default: false,
        },
    },
    components: {
        TextInput,
        SelectInput,
        MultiSelectInput,
        QuillTextEditor,
        DateInput,
        LoadingButton,
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
    },
};
</script>

<style></style>
