<template>
    <div>
        <div class="bg-white rounded-md shadow overflow-x-auto mt-4">
            <table class="w-full whitespace-nowrap mt-4">
                <tr class="text-left font-bold">
                    <th class="pb-4 pt-6 px-6">{{ $t("Date") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Next Uvv") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Document") }}</th>
                </tr>
                <tr
                    v-for="data in uvvData"
                    :key="data.id"
                    class="hover:bg-gray-100 focus-within:bg-gray-100"
                >
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ $dateFormatter(data.date, globalLanguage) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ $dateFormatter(data.nextDate, globalLanguage) }}
                        </a>
                    </td>
                    <td class="border-t">
                        <a
                            href="javascript:void(0)"
                            class="flex items-center px-6 py-4 focus:text-indigo-500"
                        >
                            {{ data.documents?.viewable_name }}
                        </a>
                    </td>
                </tr>
                <tr v-if="uvvData.length === 0">
                    <td class="px-6 py-4 border-t" colspan="4">
                        {{ $t("No Uvv found") }}.
                    </td>
                </tr>
            </table>
        </div>
        <div
            style="margin-top: 80px"
            class="w-full bg-white rounded-md shadow margin-bottom-3rem"
        >
            <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                <date-input
                    v-model="form.date"
                    :error="errors?.date"
                    class="pb-8 pr-6 w-full lg:w-1/3"
                    :label="$t('Date')"
                    @input="addDate"
                />
                <MultiSelectInput
                    :required="true"
                    :showLabels="false"
                    v-model="form.managerId"
                    :key="form.managerId"
                    :options="users"
                    :multiple="false"
                    :textLabel="$t('Manager')"
                    label="first_name"
                    trackBy="id"
                    class="w-1/3"
                    moduleName="auth"
                    :search-param-name="'search_string'"
                    :customLabel="customLabel"
                    :error="errors?.managerId"
                >
                    <template #beforeList>
                        <div
                            class="grid p-2 gap-2"
                            style="grid-template-columns: 25% 25% 50%"
                        >
                            <p class="font-bold">
                                {{ $t("First Name") }}
                            </p>
                            <p class="font-bold">
                                {{ $t("Last Name") }}
                            </p>
                            <p class="font-bold">{{ $t("Email") }}</p>
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
                            style="grid-template-columns: 25% 25% 50%"
                        >
                            <p class="overflow-text-users-table">
                                {{ props.option.first_name }}
                            </p>
                            <p class="overflow-text-users-table">
                                {{ props.option.last_name }}
                            </p>
                            <p class="overflow-text-users-table">
                                {{ props.option.email }}
                            </p>
                        </div>
                    </template>
                </MultiSelectInput>
                <date-input
                    v-model="form.nextUVV"
                    :error="errors?.nextUVV"
                    class="pb-8 pl-6 w-full lg:w-1/3"
                    :label="$t('Date Next UVV')"
                />
                <file-inputs @file-changed="addFiles" :productFiles="form">
                </file-inputs>
            </div>
        </div>
        <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
            <loading-button
                style="cursor: pointer"
                class="secondary-btn"
                @click="$emit('next')"
                >{{ $t("Next") }}
            </loading-button>
        </div>
    </div>
</template>

<script>
import SelectInput from "@/Components/SelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import DateInput from "@/Components/DateInput.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import FileInputs from "@/Components/FileInputs.vue";
export default {
    computed: {
        ...mapGetters("auth", ["user"]),
        ...mapGetters("auth", {
            users: "users",
        }),
        ...mapGetters("intervalSettings", ["intervalSettings"]),
    },
    async mounted() {
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });

        this.form.managerId = this.users.find(
            (user) => user.id == this.user.id
        );
        await this.$store.dispatch("intervalSettings/list");
        var intervalSetting = this.intervalSettings.find(
            (intervalSetting) => intervalSetting.interval_setting_type == "uvv"
        );
        this.months = intervalSetting?.months ?? 0;
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
        uvvData: {
            type: Object,
        },
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        addDate(event) {
            const originalDate = new Date(event.target.value);
            const newDate = new Date(
                originalDate.getFullYear(),
                originalDate.getMonth() + 4,
                originalDate.getDate()
            );
            this.form.nextUVV = newDate.toISOString().slice(0, 10);
        },
        addFiles(data) {
            this.form.files = data;
        },
    },
    components: {
        TextInput,
        SelectInput,
        DateInput,
        MultiSelectInput,
        FileInputs,
    },
    data() {
        return {
            months: 0,
        };
    },
};
</script>

<style></style>
