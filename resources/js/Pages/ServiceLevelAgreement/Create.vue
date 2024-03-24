<template>
    <div>
        <h1 class="header mb-8 text-3xl font-bold">
            <router-link
                class="secondary-color"
                to="/service-level-agreements"
                >{{ $t("Service Level Agreement") }}</router-link
            >
            <span class="secondary-color font-medium">/</span>
            <span class="text-color">{{ $t("Create") }}</span>
        </h1>
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input
                v-model="form.name"
                :error="errors.name"
                class="pb-8 pr-6 w-full lg:w-1/3"
                :label="$t('Name')"
                :required="true"
            />
        </div>
        <div
            class="flex justify-center mb-4 border-b border-gray-200 dark:border-gray-700"
        >
            <div class="flex mr-12">
                <a
                    @click="activeTab = 'infrastructure'"
                    :class="
                        activeTab === 'infrastructure'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("SLA Infrastructure") }}
                </a>
            </div>

            <div class="flex mr-12">
                <a
                    @click="activeTab = 'serviceTimes'"
                    :class="
                        activeTab === 'serviceTimes'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("SLA Service Times") }}
                </a>
            </div>
            <div class="flex">
                <a
                    @click="activeTab = 'slaLevel'"
                    :class="
                        activeTab === 'slaLevel'
                            ? activeClasses
                            : inactiveClasses
                    "
                >
                    {{ $t("SLA Level") }}
                </a>
            </div>
        </div>
        <div id="myTabContent">
            <div
                v-if="activeTab === 'infrastructure'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="product-tab"
            >
                <h6
                    class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800"
                >
                    {{ $t("Customer Portal Access") }}
                </h6>
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <text-input
                        v-model="slaInfrastructure.infrastructureName"
                        :error="errors.infrastructureName"
                        class="pb-8 pr-6 w-full lg:w-1/4"
                        :label="$t('Name')"
                        :required="true"
                    />
                    <div
                        style="text-align: center"
                        class="pb-8 pr-6 w-full lg:w-1/4"
                    >
                        <label for="access">
                            {{ $t("Access") }}
                        </label>
                        <input
                            class="ml-10"
                            id="access"
                            v-model="slaInfrastructure.access"
                            type="checkbox"
                        />
                    </div>
                    <text-input
                        v-model="slaInfrastructure.includedUsers"
                        :error="errors.includedUsers"
                        :required="true"
                        class="pb-4 pr-6 w-full lg:w-1/4"
                        :label="$t('Included Users')"
                        :type="`number`"
                    />
                    <number-input
                        v-model="slaInfrastructure.additionalUser"
                        :required="true"
                        :error="errors.additionalUser"
                        class="pb-4 pr-6 w-full lg:w-1/4"
                        :label="$t('Additional User')"
                    />
                </div>
                <div
                    class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
                >
                    <loading-button
                        @click="saveInfrastructure"
                        class="secondary-btn"
                        >{{ $t("Save") }}
                    </loading-button>
                </div>
                <div class="bg-white rounded-md shadow overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Access") }}</th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Included Users") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">
                                {{ $t("Additional Users") }}
                            </th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                        </tr>
                        <tr
                            v-for="(item, index) in slaInfrastructures"
                            :key="index"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ item?.infrastructureName }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    <span v-if="item?.access == true">Yes</span>
                                    <span v-if="item?.access == false">No</span>
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ item?.includedUsers }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{
                                        $formatter(
                                            item?.additionalUser,
                                            globalLanguage
                                        )
                                    }}
                                </a>
                            </td>
                            <td class="w-px border-t">
                                <button @click="popInfrastructure(index)">
                                    <font-awesome-icon icon="fa-regular fa-trash-can"/>
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div
                v-if="activeTab === 'serviceTimes'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="product-tab"
            >
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <SelectInput
                        :label="'Name'"
                        :error="errors.serviceName"
                        class="pb-8 pr-6 w-full lg:w-1/3"
                        v-model="slaServiceTime.serviceName"
                        :key="slaServiceTime.serviceName"
                    >
                        <option
                            v-for="option in slaInfrastructures"
                            :value="option.infrastructureName"
                            :key="option.infrastructureName"
                        >
                            {{ option.infrastructureName }}
                        </option>
                    </SelectInput>
                </div>
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <MultiSelectInput
                        class="pb-4 pr-6 w-full lg:w-1/3"
                        v-model="slaServiceTime.days"
                        :options="daysOptions"
                        :error="errors.days"
                        :key="slaServiceTime.days"
                        :multiple="true"
                        :required="true"
                        textLabel="Days"
                        label="name"
                        :trackBy="'id'"
                        :moduleName="'days'"
                        :taggable="true"
                    />
                    <text-input
                        :error="errors.from"
                        v-model="slaServiceTime.from"
                        :type="`time`"
                        class="pb-4 pr-6 w-full lg:w-1/3"
                        :required="true"
                        :label="$t('From')"
                    />
                    <text-input
                        :error="errors.to"
                        v-model="slaServiceTime.to"
                        :type="`time`"
                        class="pb-4 pr-6 w-full lg:w-1/3"
                        :required="true"
                        :label="$t('To')"
                    />
                </div>
                <div
                    class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
                >
                    <loading-button @click="saveServiceTime" class="secondary-btn"
                        >{{ $t("Save") }}
                    </loading-button>
                </div>

                <div class="bg-white rounded-md shadow overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Days") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("From") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("To") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                        </tr>
                        <tr
                            v-for="(item, index) in slaServiceTimes"
                            :key="index"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ item?.serviceName }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    <span
                                        class="mr-3 success-badge"
                                        v-for="(day, index) in item?.days"
                                        :key="index"
                                    >
                                        {{ day?.name }}
                                    </span>
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ item?.from }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ item?.to }}
                                </a>
                            </td>
                            <td class="w-px border-t">
                                <button @click="popServiceTime(index)">
                                    <font-awesome-icon icon="fa-regular fa-trash-can"/>
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div
                v-if="activeTab === 'slaLevel'"
                class="p-4"
                id="profile"
                role="tabpanel"
                aria-labelledby="product-tab"
            >
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <SelectInput
                        :label="'Name'"
                        :error="errors.slaName"
                        class="pb-8 pr-6 w-full lg:w-1/3"
                        v-model="slaLevel.slaName"
                        :key="slaLevel.slaName"
                    >
                        <option
                            v-for="option in slaInfrastructures"
                            :value="option.infrastructureName"
                            :key="option.infrastructureName"
                        >
                            {{ option.infrastructureName }}
                        </option>
                    </SelectInput>
                </div>
                <div
                    v-for="(levelDetail, index) in levelDetails"
                    class="flex flex-wrap -mb-8 -mr-6 p-8"
                    :key="index"
                >
                    <SelectInput
                        :label="'Priority'"
                        :error="errors.priority"
                        class="pb-8 pr-6 w-full lg:w-1/3"
                        v-model="levelDetail.priority"
                        :key="levelDetail.priority"
                    >
                        <option
                            v-for="option in priorityOptions"
                            :value="option.id"
                            :key="option.id"
                        >
                            {{ option.name }}
                        </option>
                    </SelectInput>
                    <text-input
                        :error="errors.incident"
                        v-model="levelDetail.incident"
                        :type="`number`"
                        class="pb-4 pr-6 w-full lg:w-1/3"
                        :label="$t('Incident')"
                    />
                    <text-input
                        :error="errors.change"
                        v-model="levelDetail.change"
                        :type="`number`"
                        class="pb-4 pr-6 w-full lg:w-1/4"
                        :label="$t('Change')"
                    />
                    <div
                        role="button"
                        class="float-center"
                        @click="deletelevelDetails(index)"
                    >
                        <span class="fas fa-trash text-danger"></span>
                    </div>
                </div>
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <span
                        @click="addLine"
                        class="ms-2 font-weight-medium text-primary"
                    >
                        <span class="fas fa-plus-circle text-info"></span>
                        {{ $t("Add Line") }}
                    </span>
                </div>
                <span class="error" v-if="errorSlaLevel">{{
                    errorSlaLevel
                }}</span>
                <div
                    class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
                >
                    <loading-button @click="saveSlaLevel" class="secondary-btn"
                        >{{ $t("Save") }}
                    </loading-button>
                </div>
                <div class="bg-white rounded-md shadow overflow-x-auto mt-3">
                    <table class="w-full whitespace-nowrap">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Priority") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Incident") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Change") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Action") }}</th>
                        </tr>
                        <tr
                            v-for="(item, index) in slaLevels"
                            :key="index"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ item?.slaName }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    <span
                                        class="mr-3"
                                        v-for="(level, index) in item?.levels"
                                        :key="index"
                                    >
                                        {{ level?.priority }}
                                    </span>
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    <span
                                        class="mr-3"
                                        v-for="(level, index) in item?.levels"
                                        :key="index"
                                    >
                                        {{ level?.incident }}
                                    </span>
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    <span
                                        class="mr-3"
                                        v-for="(level, index) in item?.levels"
                                        :key="index"
                                    >
                                        {{ level?.change }}
                                    </span>
                                </a>
                            </td>
                            <td class="w-px border-t">
                                <button @click="popSlaLevel(index)">
                                    <font-awesome-icon icon="fa-regular fa-trash-can"/>
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <p class="error" v-if="tabValidation">{{ tabValidation }}</p>
        <div
            class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100"
        >
            <loading-button
                v-if="$can(`${$route.meta.permission}.create`)"
                @click="createServiceLevelAgreement()"
                :loading="isLoading"
                class="secondary-btn"
                >{{ $t("Create Service Level Agreement") }}
            </loading-button>
        </div>
    </div>
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
import NumberInput from "../../Components/NumberInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
        NumberInput,
        SelectInput,
        MultiSelectInput,
    },
    data() {
        return {
            form: {
                name: "",
            },
            daysOptions: [
                {
                    id: "monday",
                    name: "Monday",
                },
                {
                    id: "tuesday",
                    name: "Tuesday",
                },
                {
                    id: "wednesday",
                    name: "Wednesday",
                },
                {
                    id: "thursday",
                    name: "Thursday",
                },
                {
                    id: "friday",
                    name: "Friday",
                },
                {
                    id: "saturday",
                    name: "Saturday",
                },
                {
                    id: "sunday",
                    name: "Sunday",
                },
            ],
            priorityOptions: [
                {
                    id: "0",
                    name: "Urgent",
                },
                {
                    id: "1",
                    name: "High",
                },
                {
                    id: "2",
                    name: "Middle",
                },
            ],
            errorSlaLevel: "",
            slaInfrastructures: [],
            slaInfrastructure: {
                infrastructureName: "",
                additionalUser: 0,
                access: true,
                includedUsers: "",
            },
            slaServiceTimes: [],
            slaServiceTime: {
                serviceName: "",
                days: [],
                from: "",
                to: "",
            },
            slaLevels: [],
            levelDetails: [],
            slaLevel: {
                slaName: "",
            },
            tabValidation: "",
            activeTab: "infrastructure",
            activeClasses:
                "inline-flex items-center justify-center cursor-pointer w-1/2 py-3 font-medium leading-none tracking-wider text-indigo-500 bg-gray-100 border-b-2 border-indigo-500 rounded-t sm:px-6 sm:w-auto sm:justify-start title-font",
            inactiveClasses:
                "inline-flex items-center justify-center cursor-pointer w-1/2 py-3 font-medium leading-none tracking-wider border-b-2 border-gray-200 sm:px-6 sm:w-auto sm:justify-start title-font hover:text-gray-900",
        };
    },
    methods: {
        async createServiceLevelAgreement() {
            this.tabValidation = "";
            if (this.form.name == "") {
                this.$store.commit("errors", {
                    name: "Name is a required field",
                });
                return;
            }
            if (this.slaInfrastructures.length == 0) {
                this.activeTab = "infrastructure";
                this.tabValidation = "Please add SLA Infrastruture";
                return;
            }
            if (this.slaServiceTimes.length == 0) {
                this.activeTab = "serviceTimes";
                this.tabValidation = "Please add SLA Service Time";
                return;
            }
            if (this.slaLevels.length == 0) {
                this.activeTab = "slaLevel";
                this.tabValidation = "Please add SLA Level";
                return;
            }
            const payload = {
                name: this.form.name,
                slaInfrastructure: this.slaInfrastructures,
                slaService: this.slaServiceTimes,
                slaLevel: this.slaLevels,
            };
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("serviceLevelAgreements/create", {
                ...payload,
            });
            await this.$store.dispatch("serviceLevelAgreements/list");
            this.$router.push("/service-level-agreements");
        },

        addLine() {
            this.errorSlaLevel = "";
            this.levelDetails.push({
                priority: "",
                incident: "",
                change: "",
            });
        },
        popInfrastructure(index) {
            this.slaInfrastructures.splice(index, 1);
        },
        popServiceTime(index) {
            this.slaServiceTimes.splice(index, 1);
        },
        popSlaLevel(index) {
            this.slaLevels.splice(index, 1);
        },
        deletelevelDetails(index) {
            this.levelDetails.splice(index, 1);
        },
        validateSlaInfrastructure() {
            if (this.slaInfrastructure.infrastructureName == "") {
                this.$store.commit("errors", {
                    infrastructureName: "Name is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    infrastructureName: "",
                });
            }
            if (this.slaInfrastructure.includedUsers == "") {
                this.$store.commit("errors", {
                    includedUsers: "Included users is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    includedUsers: "",
                });
            }

            return true;
        },
        validateServiceTime() {
            if (this.slaServiceTime.serviceName == "") {
                this.$store.commit("errors", {
                    serviceName: "Name is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    serviceName: "",
                });
            }
            if (this.slaServiceTime.days.length == 0) {
                this.$store.commit("errors", {
                    days: "Days is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    days: "",
                });
            }
            if (this.slaServiceTime.from == "") {
                this.$store.commit("errors", {
                    from: "From is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    from: "",
                });
            }
            if (this.slaServiceTime.to == "") {
                this.$store.commit("errors", {
                    to: "To is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    to: "",
                });
            }

            return true;
        },
        validateSlaLevel() {
            if (this.levelDetails.length == 0) {
                this.errorSlaLevel = "Please add atleast one level";
                return;
            }
            if (this.slaLevel.slaName == "") {
                this.$store.commit("errors", {
                    slaName: "Name is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    slaName: "",
                });
            }

            return true;
        },
        saveInfrastructure() {
            if (this.validateSlaInfrastructure()) {
                this.slaInfrastructures.push(this.slaInfrastructure);
                this.resetInfrastructure();
            }
        },
        saveServiceTime() {
            if (this.validateServiceTime()) {
                this.slaServiceTimes.push(this.slaServiceTime);
                this.resetServiceTime();
            }
        },
        saveSlaLevel() {
            if (this.validateSlaLevel()) {
                const obj = {
                    slaName: this.slaLevel.slaName,
                    levels: this.levelDetails,
                };
                this.slaLevels.push(obj);
                this.resetSlaLevel();
            }
        },
        resetInfrastructure() {
            this.slaInfrastructure = {
                infrastructureName: "",
                additionalUser: 0,
                access: true,
                includedUsers: "",
            };
        },
        resetServiceTime() {
            this.slaServiceTime = {
                serviceName: "",
                days: [],
                from: "",
                to: "",
            };
        },
        resetSlaLevel() {
            this.levelDetails = [];
            this.slaLevel = {
                slaName: "",
            };
        },
    },
};
</script>
<style scoped>
.success-badge {
    background-color: green;
    color: white;
    padding: 4px 8px;
    text-align: center;
    border-radius: 5px;
}
.error {
    color: red;
}
</style>
