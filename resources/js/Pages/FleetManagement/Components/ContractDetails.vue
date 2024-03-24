<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"></h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.contactNumber"
                                :error="errors?.contactNumber"
                                class=""
                                :label="$t('Contact number')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <number-input
                                v-model="form.leasingRate"
                                class=""
                                :label="$t('Leasing Rate')"
                                type="number"
                                placeholder=" "
                                :error="errors.leasingRate"
                                :required="true"
                                :floatingLabel="true"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <date-input
                                v-model="form.deliveryDate"
                                :required="true"
                                :error="errors?.deliveryDate"
                                class=""
                                :label="$t('Delivery date')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <date-input
                                v-model="form.leasingStartDate"
                                :required="true"
                                :error="errors?.leasingStartDate"
                                class=""
                                :label="$t('Leasing start date')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.contractPeriodMonths"
                                :error="errors?.contractPeriodMonths"
                                class=""
                                :label="$t('Contact period months')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <date-input
                                v-model="form.leasingEndDate"
                                :required="true"
                                :error="errors?.leasingEndDate"
                                class=""
                                :label="$t('Leasing end date')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.miles"
                                :error="errors?.miles"
                                class=""
                                :label="`Miles/Year`"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="doc-table">
                <thead>
                    <tr>
                        <th class=""></th>
                        <th class="">
                            {{ $t("Per additional kilometer") }}
                        </th>
                        <th class="">
                            {{ $t("Free extra kilometers") }}
                        </th>
                        <th class="">
                            {{ $t("Per less kilometers") }}
                        </th>
                        <th class="">
                            {{ $t("Free less kilometers") }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ $t("Leasing (vehicle transfer)") }}
                            </a>
                        </td>
                        <td class="">
                            <div class="form-group">
                                <number-input
                                    v-model="
                                        form.leasing.perAdditionalKilometers
                                    "
                                    :error="
                                        errors[
                                            'leasing.perAdditionalKilometers'
                                        ]
                                    "
                                    :showPrefix="false"
                                    class="lg:w-100"
                                    type="number"
                                    placeholder=" "
                                    :floatingLabel="true"
                                />
                            </div>
                        </td>
                        <td class="">
                            <div class="form-group">
                                <text-input
                                    v-model="form.leasing.freeExtraKilometers"
                                    :error="
                                        errors['leasing.freeExtraKilometers']
                                    "
                                    class="lg:w-100"
                                    :floatingLabel="true"
                                />
                            </div>
                        </td>
                        <td class="">
                            <div class="form-group">
                                <number-input
                                    v-model="form.leasing.perLessKilometers"
                                    :error="errors['leasing.perLessKilometers']"
                                    :showPrefix="false"
                                    class="lg:w-100"
                                    type="number"
                                    placeholder=" "
                                    :floatingLabel="true"
                                />
                            </div>
                        </td>
                        <td class="">
                            <div class="form-group">
                                <text-input
                                    v-model="form.leasing.freeLessKilometers"
                                    :error="
                                        errors['leasing.freeLessKilometers']
                                    "
                                    style=""
                                    :floatingLabel="true"
                                    class="lg:w-100"
                                />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ $t("Damage protection") }}
                            </a>
                        </td>
                        <td class="">
                            <a href="javascript:void(0)" class="">
                                <div class="form-group">
                                    <number-input
                                        v-model="
                                            form.damageProtection
                                                .perAdditionalKilometers
                                        "
                                        :showPrefix="false"
                                        class="lg:w-100"
                                        type="number"
                                        placeholder=" "
                                        :error="
                                            errors[
                                                'damageProtection.perAdditionalKilometers'
                                            ]
                                        "
                                        :floatingLabel="true"
                                    />
                                </div>
                            </a>
                        </td>
                        <td class="">
                            <a href="javascript:void(0)" class="">
                                <div class="form-group">
                                    <text-input
                                        v-model="
                                            form.damageProtection
                                                .freeExtraKilometers
                                        "
                                        :error="
                                            errors[
                                                'damageProtection.freeExtraKilometers'
                                            ]
                                        "
                                        class="lg:w-100"
                                        :floatingLabel="true"
                                    />
                                </div>
                            </a>
                        </td>
                        <td class="">
                            <a href="javascript:void(0)" class="">
                                <div class="form-group">
                                    <number-input
                                        v-model="
                                            form.damageProtection
                                                .perLessKilometers
                                        "
                                        :showPrefix="false"
                                        :error="
                                            errors[
                                                'damageProtection.perLessKilometers'
                                            ]
                                        "
                                        class="lg:w-100"
                                        type="number"
                                        placeholder=" "
                                        :floatingLabel="true"
                                    />
                                </div>
                            </a>
                        </td>
                        <td class="">
                            <a href="javascript:void(0)" class="">
                                <div class="form-group">
                                    <text-input
                                        v-model="
                                            form.damageProtection
                                                .freeLessKilometers
                                        "
                                        :error="
                                            errors[
                                                'damageProtection.freeLessKilometers'
                                            ]
                                        "
                                        style=""
                                        :floatingLabel="true"
                                        class="lg:w-100"
                                    />
                                </div>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center focus:text-indigo-500"
                            >
                                {{ $t("Maintenance and protection") }}
                            </a>
                        </td>
                        <td class="">
                            <a href="javascript:void(0)" class="">
                                <div class="form-group">
                                    <number-input
                                        v-model="
                                            form.maintenanceAndAbstraction
                                                .perAdditionalKilometers
                                        "
                                        :showPrefix="false"
                                        :error="
                                            errors[
                                                'maintenanceAndAbstraction.perAdditionalKilometers'
                                            ]
                                        "
                                        class="lg:w-100"
                                        type="number"
                                        placeholder=" "
                                        :floatingLabel="true"
                                    />
                                </div>
                            </a>
                        </td>
                        <td class="">
                            <a href="javascript:void(0)" class="">
                                <div class="form-group">
                                    <text-input
                                        v-model="
                                            form.maintenanceAndAbstraction
                                                .freeExtraKilometers
                                        "
                                        :error="
                                            errors[
                                                'maintenanceAndAbstraction.freeExtraKilometers'
                                            ]
                                        "
                                        class="lg:w-100"
                                        :floatingLabel="true"
                                    />
                                </div>
                            </a>
                        </td>
                        <td class="">
                            <a href="javascript:void(0)" class="">
                                <div class="form-group">
                                    <number-input
                                        v-model="
                                            form.maintenanceAndAbstraction
                                                .perLessKilometers
                                        "
                                        :showPrefix="false"
                                        :error="
                                            errors[
                                                'maintenanceAndAbstraction.perLessKilometers'
                                            ]
                                        "
                                        class="lg:w-100"
                                        type="number"
                                        placeholder=" "
                                        :floatingLabel="true"
                                    />
                                </div>
                            </a>
                        </td>
                        <td class="">
                            <a href="javascript:void(0)" class="">
                                <div class="form-group">
                                    <text-input
                                        v-model="
                                            form.maintenanceAndAbstraction
                                                .freeLessKilometers
                                        "
                                        :error="
                                            errors[
                                                'maintenanceAndAbstraction.freeLessKilometers'
                                            ]
                                        "
                                        style=""
                                        :floatingLabel="true"
                                        class="lg:w-100"
                                    />
                                </div>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-end mt-4">
            <loading-button
            @click="$emit('next')"
                class="secondary-btn"
            >
                <span class="mr-1">
                    <CustomIcon name="nextIcon" />
                </span>
                {{ $t("Next") }}
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
import NumberInput from "@/Components/NumberInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
export default {
    props: {
        form: {
            type: Object,
            required: true,
        },
        errors: {
            type: Object,
            default: null,
        },
    },
    components: {
        TextInput,
        SelectInput,
        MultiSelectInput,
        QuillTextEditor,
        DateInput,
        NumberInput,
        LoadingButton,
    },
};
</script>

<style></style>
