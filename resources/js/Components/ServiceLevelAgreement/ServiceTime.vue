<template>

    <div class="card">
        <div class="card-header">
          <h3 class="card-title"></h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-5 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="slaServiceTime.serviceName"
                            :error="errors.serviceName"
                            :label="$t('Name')"
                            :required="true"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-model="slaServiceTime.days"
                            :options="daysOptions"
                            :error="errors.days"
                            :key="slaServiceTime.days"
                            :multiple="true"
                            :required="true"
                            :textLabel="$t('Days')"
                            label="name"
                            :trackBy="'id'"
                            :moduleName="'days'"
                            :taggable="true"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :error="errors.from"
                            v-model="slaServiceTime.from"
                            :type="`time`"
                            :required="true"
                            :label="$t('From')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :error="errors.to"
                            v-model="slaServiceTime.to"
                            :type="`time`"
                            :required="true"
                            :label="$t('To')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <number-input
                            v-model="slaServiceTime.factor"
                            :required="true"
                            :showPrefix="false"
                            :error="errors.factor"
                            :label="$t('Factor')"
                        />
                    </div>
                </div>
            </div>
        </div>
      </div>
      
      <div class="flex items-center justify-end mt-4">
        <loading-button v-if="$can(`sla-levels.create`)" @click="saveServiceTime" :loading="isLoading" class="secondary-btn">
          <span class="mr-1">
            <CustomIcon name="saveIcon" />
          </span>
          {{ $t("Save and Proceed") }}
        </loading-button>
      </div>

    <div
        v-if="$can(`sla-levels.list`)"
        class="table-responsive my-3"
    >
        <table class="w-full doc-table">
            <tr class="text-left font-bold">
                <th @click="sort('name')" class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("Name") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'name'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th @click="sort('days')" class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("Days") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'days'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th @click="sort('from')" class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("From") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'from'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th @click="sort('to')" class="pb-4 pt-6 px-6 cursor-pointer">
                    {{ $t("To") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'to'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
                <th
                    @click="sort('factor')"
                    class="pb-4 pt-6 px-6 cursor-pointer"
                >
                    {{ $t("Service Hour") }}
                    <font-awesome-icon
                        v-if="form.sortBy === 'factor'"
                        class="cursor-pointer ml-2"
                        :icon="`fa-solid fa-sort-${
                            form.sortOrder === 'asc' ? 'up' : 'down'
                        }`"
                    />
                </th>
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
                        {{ item?.name }}
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
                <td class="border-t">
                    <a
                        href="javascript:void(0)"
                        class="flex items-center px-6 py-4 focus:text-indigo-500"
                    >
                        {{
                            $formatter(
                                item?.factor,
                                globalLanguage,
                                "EUR",
                                true
                            )
                        }}
                    </a>
                </td>
                <td class="w-px border-t">
                    <button
                        v-if="$can(`sla-levels.edit`)"
                        @click="showServiceTime(item?.id)"
                        class="mx-2"
                    >
                        <font-awesome-icon icon="fa-regular fa-pen-to-square" />
                    </button>
                    <button
                        v-if="$can(`sla-levels.delete`)"
                        @click="destroy(item?.id)"
                    >
                        <font-awesome-icon icon="fa-regular fa-trash-can" />
                    </button>
                </td>
            </tr>
        </table>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="slaServiceTimes"
                @pagination-change-page="changePageOrSearch"
            ></pagination>
            <br />
            <br />
        </div>
    </div>
    <Modal
        :title="$t('Edit Service Time')"
        v-if="toggleModal"
        @toggleModal="toggleModal = $event"
        width="50%"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6 p-5">
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="editslaServiceTime.name"
                            :error="errors.editserviceName"
                            :label="$t('Name')"
                            :required="true"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-model="editslaServiceTime.days"
                            :options="daysOptions"
                            :error="errors.editdays"
                            :key="editslaServiceTime.days"
                            :multiple="true"
                            :required="true"
                            :textLabel="$t('Days')"
                            label="name"
                            :trackBy="'id'"
                            :moduleName="'days'"
                            :taggable="true"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :error="errors.editfrom"
                            v-model="editslaServiceTime.from"
                            :type="`time`"
                            :required="true"
                            :label="$t('From')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            :error="errors.to"
                            v-model="editslaServiceTime.to"
                            :type="`time`"
                            :required="true"
                            :label="$t('To')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <number-input
                            v-model="editslaServiceTime.factor"
                            :required="true"
                            :showPrefix="false"
                            :error="errors.editfactor"
                            :label="$t('Factor')"
                        />
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <button
                @click="editServiceTime()"
                type="button"
                class="secondary-btn"
            >
                {{ $t("Update") }}
            </button>
            <button
                @click="toggleModal = false"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { mapGetters } from "vuex";

import Pagination from "laravel-vue-pagination";
import Modal from "@/Components/EditModal.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),

        ...mapGetters("slaServiceTimes", ["slaServiceTimes"]),
    },
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
        MultiSelectInput,
        NumberInput,
        Modal,
        Pagination,
    },
    mounted() {
        this.refresh();
    },

    data() {
        return {
            page: 1,
            form: {
                search: "",
                sortBy: "",
                sortOrder: "",
                perPage: 100,
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
            toggleModal: false,
            slaServiceTime: {
                serviceName: "",
                days: [],
                from: "",
                to: "",
                factor: 0,
            },
            editslaServiceTime: [],
        };
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        await this.$store.dispatch(
                            "slaServiceTimes/list",
                            this.pickBy(this.form)
                        );
                    } catch (e) {}
                }, 150)();
            },
        },
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;

            await this.$store.dispatch("slaServiceTimes/list", {
                page: this.page,
            });
        },
        async editServiceTime() {
            if (this.editValidateServiceTime()) {
                const obj = {
                    serviceName: this.editslaServiceTime.name,
                    days: this.editslaServiceTime.days,
                    to: this.editslaServiceTime.to,
                    from: this.editslaServiceTime.from,
                    factor: this.editslaServiceTime.factor,
                };
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("slaServiceTimes/update", {
                    id: this.editslaServiceTime?.id,
                    data: obj,
                });
                this.toggleModal = false;
                this.$store.commit("flashMessage", {
                    show: true,
                    message: "SLA Service Time updated successfully",
                    type: "success",
                });

                this.refresh();
                this.editslaServiceTime = [];
            }
        },
        async showServiceTime(id) {
            this.$store.commit("errors", {
                editserviceName: "",
            });
            this.$store.commit("errors", {
                editdays: "",
            });
            this.$store.commit("errors", {
                editfrom: "",
            });
            this.$store.commit("errors", {
                editto: "",
            });
            this.editslaServiceTime = [];
            const response = await this.$store.dispatch(
                "slaServiceTimes/show",
                id
            );
            this.editslaServiceTime = response?.data?.data;
            this.toggleModal = true;
        },
        async refresh() {
            await this.$store.dispatch(
                "slaServiceTimes/list",
                this.pickBy(this.form)
            );
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
            if (this.slaServiceTime.factor == "") {
                this.$store.commit("errors", {
                    factor: "Factor is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    factor: "",
                });
            }

            return true;
        },

        editValidateServiceTime() {
            if (this.editslaServiceTime.name == "") {
                this.$store.commit("errors", {
                    editserviceName: "Name is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    editserviceName: "",
                });
            }
            if (this.editslaServiceTime.days.length == 0) {
                this.$store.commit("errors", {
                    editdays: "Days is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    editdays: "",
                });
            }
            if (this.editslaServiceTime.from == "") {
                this.$store.commit("errors", {
                    editfrom: "From is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    editfrom: "",
                });
            }
            if (this.editslaServiceTime.to == "") {
                this.$store.commit("errors", {
                    editto: "To is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    editto: "",
                });
            }
            if (this.editslaServiceTime.factor == "") {
                this.$store.commit("errors", {
                    editfactor: "Factor is a required field",
                });
                return;
            } else {
                this.$store.commit("errors", {
                    editfactor: "",
                });
            }

            return true;
        },

        async destroy(id) {
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    try {
                        await this.$store.dispatch("slaServiceTimes/destroy", id);
                        this.$store.commit("flashMessage", {
                            show: true,
                            message: "SLA Service Time deleted successfully",
                            type: "success",
                        });
                        this.refresh();
                    } catch (e) {}
                }
            });
            
        },
        async saveServiceTime() {
            if (this.validateServiceTime()) {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("slaServiceTimes/create", {
                    ...this.slaServiceTime,
                });
                this.$store.commit("flashMessage", {
                    show: true,
                    message: "SLA Service Time created successfully",
                    type: "success",
                });
                this.refresh();
                this.resetServiceTime();
            }
        },

        resetServiceTime() {
            this.slaServiceTime = {
                serviceName: "",
                days: [],
                from: "",
                to: "",
                factor: 0,
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
