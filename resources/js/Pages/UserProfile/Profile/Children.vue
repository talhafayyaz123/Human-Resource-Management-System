<template>
    <div class="table-responsive">
        <table class="doc-table">
            <thead>
                <tr>
                    <th class="">
                        {{ $t("Name") }}
                    </th>
                    <th class="">
                        {{ $t("Gender") }}
                    </th>
                    <th class="">
                        {{ $t("Date of birth") }}
                    </th>
                    <th class="">
                        {{ $t("Action") }}
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="(child, index) in profileForm.childrenData"
                    :key="'child-' + index"
                    :tabindex="index"
                    class="focus:outline-none h-16 border border-gray-100 rounded"
                >
                    <td class="">
                        <div class="">
                            {{ child.name }}
                        </div>
                    </td>
                    <td class="">
                        <div class="">
                            {{ $t(child.gender) }}
                        </div>
                    </td>
                    <td class="">
                        <div class="">
                            {{ child.dateOfBirth }}
                        </div>
                    </td>
                    <td class="">
                        <div class="">
                            <font-awesome-icon
                                @click="editChild(index)"
                                class="cursor-pointer"
                                icon="fa-regular fa-pen-to-square"
                            />
                            <font-awesome-icon
                                @click="deleteChild(index)"
                                class="cursor-pointer ml-2"
                                icon="fa-regular fa-trash-can"
                            />
                        </div>
                    </td>
                </tr>
                <tr v-if="(profileForm.childrenData?.length ?? 0) === 0">
                    <td class="" colspan="4">{{ $t("No Children added") }}.</td>
                </tr>
            </tbody>
        </table>
    </div>
    <button
        @click.prevent="toggleModal = !toggleModal"
        class="secondary-btn mt-3"
    >
        {{ $t("Add Child") }} +
    </button>
    <Modal
        :title="$t(`${action} Child Data`)"
        v-if="toggleModal"
        @toggleModal="toggleModal = $event"
        width="50%"
    >
        <template #body>
            <div class="grid grid-cols-2 gap-6 ml-6 mr-6">
                <div class="form-group">
                    <text-input
                        @input="errors.name = false"
                        v-model="childDataTemp.name"
                        :label="$t('Name')"
                    />
                    <p v-if="errors.name" class="text-red-500">
                        Name is Required
                    </p>
                </div>
                <div class="form-group">
                    <select-input
                        @input="errors.gender = false"
                        v-model="childDataTemp.gender"
                        :label="$t('Gender')"
                    >
                        <option value="male">{{ $t("Male") }}</option>
                        <option value="female">{{ $t("Female") }}</option>
                        <option value="other">{{ $t("Other") }}</option>
                    </select-input>

                    <p v-if="errors.gender" class="text-red-500">
                        Gender is Required
                    </p>
                </div>

                <div class="form-group">
                    <date-input
                        @input="errors.dateOfBirth = false"
                        v-model="childDataTemp.dateOfBirth"
                        :label="$t('Date of Birth')"
                        :max="
                            new Date().toLocaleDateString('fr-ca', {
                                timeZone: 'Europe/Berlin',
                            })
                        "
                    />
                    <p v-if="errors.dateOfBirth" class="text-red-500">
                        Date of birth is Required
                    </p>
                </div>
            </div>
        </template>
        <template #footer>
            <button @click="addChildren" type="button" class="secondary-btn">
                {{ action }}
            </button>
            <button
                @click="toggleModal = false"
                type="button"
                class="primary-btn mr-3"
            >
                Cancel
            </button>
        </template>
    </Modal>
</template>

<script>
import Modal from "@/Components/EditModal.vue";
import TextInput from "@/Components/TextInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import DateInput from "@/Components/DateInput.vue";

export default {
    components: {
        SelectInput,
        DateInput,
        TextInput,
        Modal,
    },
    props: ["profileForm"],
    data() {
        return {
            action: "Add",
            selectedChildIndex: -1,
            toggleModal: false,
            childDataTemp: {
                name: "",
                gender: "",
                dateOfBirth: "",
            },
            errors: {
                name: false,
                gender: false,
                dateOfBirth: false,
            },
        };
    },
    methods: {
        editChild(index) {
            this.selectedChildIndex = index;
            this.action = "Edit";
            this.childDataTemp = { ...this.profileForm.childrenData[index] };
            this.toggleModal = true;
        },
        deleteChild(index) {
            this.profileForm.childrenData.splice(index, 1);
        },
        addChildren() {
            if (
                !this.childDataTemp.name.length ||
                !this.childDataTemp.gender.length ||
                !this.childDataTemp.dateOfBirth.length
            ) {
                this.errors = {
                    name: !this.childDataTemp.name.length,
                    gender: !this.childDataTemp.gender.length,
                    dateOfBirth: !this.childDataTemp.dateOfBirth.length,
                };
                return;
            }
            if (this.action.toLowerCase() === "edit") {
                this.profileForm.childrenData[this.selectedChildIndex] = {
                    ...this.childDataTemp,
                };
            } else {
                console.log(this.profileForm);
                this.profileForm.childrenData.push({ ...this.childDataTemp });
            }
            this.toggleModal = false;
        },
    },
    watch: {
        toggleModal(val) {
            if (!val) {
                this.action = "Add";
                this.selectedChildIndex = -1;
                this.childDataTemp = {
                    name: "",
                    gender: "",
                    dateOfBirth: "",
                };
            }
        },
    },
};
</script>
