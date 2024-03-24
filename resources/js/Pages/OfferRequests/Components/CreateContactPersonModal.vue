<template>
    <Modal :title="$t('Add Employee')" v-if="toggleEmployeeModal">
        <template #body>
            <div class="px-8 py-8">
                <div class="w-full my-5">
                    <div class="form-group">
                        <MultiSelectInput
                            @update:model-value="employeeSelected"
                            :showLabels="false"
                            v-model="employeeFormTemp"
                            :key="employeeFormTemp"
                            :options="users"
                            :multiple="false"
                            :textLabel="$t('Select An Existing Employee')"
                            label="first_name"
                            trackBy="id"
                            moduleName="auth"
                            :search-param-name="'search_string'"
                            :customLabel="customLabel"
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
                                    <p class="font-bold">
                                        {{ $t("Email") }}
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
                    </div>
                </div>
                <div class="grid items-center grid-cols-4 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeTempData.title"
                                :label="$t('Title')"
                                placeholder=" "
                                :floatingLabel="true"
                                :error="errors.title"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeTempData.first_name"
                                :label="$t('First Name')"
                                placeholder=" "
                                :floatingLabel="true"
                                :error="errors.first_name"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeTempData.last_name"
                                :label="$t('Last Name')"
                                placeholder=" "
                                :floatingLabel="true"
                                :error="errors.last_name"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :isReadonly="typeof employeeTempData?.id === 'string'"
                                :required="true"
                                v-model="employeeTempData.email"
                                :label="$t('Email')"
                                placeholder=" "
                                :floatingLabel="true"
                                :error="errors.email"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeTempData.mobile"
                                :label="$t('Mobile')"
                                placeholder=" "
                                :floatingLabel="true"
                                :error="errors.mobile"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeTempData.phone"
                                :label="$t('Phone Number')"
                                placeholder=" "
                                :floatingLabel="true"
                                :error="errors.phone"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeTempData.fax"
                                :label="$t('Fax')"
                                placeholder=" "
                                :floatingLabel="true"
                                :error="errors.fax"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeTempData.position"
                                :label="$t('Position')"
                                placeholder=" "
                                :floatingLabel="true"
                                :error="errors.position"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="employeeTempData.department"
                                :label="$t('Department')"
                                placeholder=" "
                                :floatingLabel="true"
                                :error="errors.department"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                v-model="employeeTempData.location_id"
                                :floatingLabel="true"
                                :error="errors.location_id"
                                :label="$t('Location')"
                            >
                                <option
                                    v-for="location in locations?.data ?? []"
                                    :key="'location' + location.id"
                                    :value="location.id"
                                >
                                    {{ location.addressFirst }},
                                    {{ location.addressSecond }}, {{ location.city }},
                                    {{ location.state }}, {{ location.zip }},
                                    {{ location.countryName }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <loading-button
                :loading="isLoading"
                type="button"
                class="secondary-btn"
                @click="saveEmployee"
            >
                {{ $t("Save") }}
            </loading-button>
            <button
                @click="onCancelEmployeeEdit"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import SelectInput from "@/Components/SelectInput.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["toggleEmployeeModal", "userAdded"],
    props: {
        toggleEmployeeModal: {
            type: Boolean,
            default: false,
        },
        company: {
            type: Object,
            default: null,
        },
    },
    computed: {
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("auth", ["users"]),
    },
    components: {
        Modal,
        MultiSelectInput,
        TextInput,
        LoadingButton,
        SelectInput,
    },
    data() {
        return {
            employeeFormTemp: null,

            locations: {
                data: [],
                links: [],
            },
            employeeTempData: {}, //used for v-model in employee edit modal
        };
    },
    mounted() {
        // fetch company locations on mount
        this.fetchLocations();
    },
    methods: {
        /**
         * triggered when employee is selected from employee dropdown in employees tab
         * copies the data from 'employeeFormTemp' to 'employeeForm'
         * if employee dropdown is cleared then resets the 'employeeForm'
         */
        async employeeSelected() {
            await this.$nextTick();
            if (this.employeeFormTemp)
                this.employeeTempData = { ...this.employeeFormTemp };
            else {
                this.employeeTempData = {};
            }
        },
        /**
         * fetches company locations based on company ID
         */
        async fetchLocations() {
            try {
                const response = await this.$store.dispatch(
                    "companies/locationsList",
                    this.company.id
                );
                this.locations.data = response?.data?.locations ?? [];
            } catch (e) {}
        },
        /**
         * modifies the shown label in the dropdown to format it using first_name and last_name
         * @param {props} object containing first_name and last_name
         */
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        /**
         * sends an elo request based on 'moduleAction'
         * @param {moduleAction}
         */
        async sendEloRequest(moduleAction) {
            return new Promise(async (resolve, reject) => {
                try {
                    //Define logic to retrieve elo configuration
                    let content = {
                        ...this.company,
                        moduleAction: moduleAction,
                    };
                    content.employeeData = { ...this.employeeFormTemp };
                    await this.$store.dispatch(
                        "globalSettings/sendEloApiRequest",
                        {
                            content: content,
                        }
                    );
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },
        /**
         * triggers the auth 'create' API
         */
        async addEmployee() {
            try {
                this.$store.commit("isLoading", true);
                let response = null;
                if (this.employeeFormTemp?.id) {
                    response = await this.$store.dispatch("auth/update", {
                        id: this.employeeTempData.id,
                        title: this.employeeTempData.title,
                        mobile: this.employeeTempData.mobile,
                        phone: this.employeeTempData.phone,
                        fax: this.employeeTempData.fax,
                        partner: this.employeeTempData.partner,
                        position: this.employeeTempData.position,
                        department: this.employeeTempData.department,
                        location_id: this.employeeTempData.location_id,
                        types: [
                            ...Object.keys(this.employeeTempData.types).filter(
                                (type) => this.employeeTempData.types[type] == 1
                            ),
                            "customer",
                        ],
                        set_company_id: this.company.id,
                    });
                } else {
                    response = await this.$store.dispatch("auth/create", {
                        ...this.employeeTempData,
                        types: ["customer"],
                        mail: this.employeeTempData.email,
                        set_company_id: this.company.id,
                    });
                }
                // emit userAdded to auto select newly added user
                this.$emit("userAdded", response?.data);
                await this.sendEloRequest("createCustomerEmployee");
            } catch (e) {
                console.log(e);
            } finally {
                this.employeeTempData = {};
                this.$emit("toggleEmployeeModal", false);
                this.employeeFormTemp = null;
            }
        },
        /**
         * sets the employeeForm and runs the 'addEmployee' function
         */
        async saveEmployee() {
            try {
                this.employeeForm = { ...this.employeeTempData };
                this.addEmployee();
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * hides the modal and resets the 'employeeTempData' state
         */
        onCancelEmployeeEdit() {
            this.$emit("toggleEmployeeModal", false);
            this.employeeTempData = {};
            this.employeeFormTemp = null;
        },
    },
};
</script>

<style scoped></style>
