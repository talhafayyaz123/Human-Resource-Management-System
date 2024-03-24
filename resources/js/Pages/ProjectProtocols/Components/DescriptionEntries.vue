<template>
    <div class="table-responsive">
        
        <table class="doc-table">
            <thead>
                <tr>
                    <th
                        class=""
                    >
                        {{ $t("POS") }}
                    </th>
                    <th
                        class=""
                    >
                        {{ $t("Description") }}
                    </th>
                    <th
                        class=""
                    >
                        {{ $t("Expiry Date") }}
                    </th>
                    <th
                        class=""
                    >
                        {{ $t("Ownership") }}
                    </th>
                    <th
                        class=""
                    >
                        {{ $t("Action") }}
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="(entry, index) in entries.data"
                    :key="'entry-' + index"
                    :tabindex="index"
                    class="focus:outline-none h-16 border border-gray-100 rounded"
                >
                    <td class="">
                        <div class="">
                            {{ index + 1 }}
                        </div>
                    </td>
                    <td class="">
                        <div class="">
                            {{ entry.description }}
                        </div>
                    </td>
                    <td class="">
                        <div class="">
                            {{
                                $dateFormatter(entry.expiryDate, globalLanguage)
                            }}
                        </div>
                    </td>
                    <td class="">
                        <div class="">
                            {{
                                (entry.ownership?.first_name ?? "") +
                                " " +
                                (entry.ownership?.last_name ?? "")
                            }}
                        </div>
                    </td>
                    <td class="">
                        <div class="">
                            <font-awesome-icon
                                @click="editEntry(entry)"
                                class="cursor-pointer"
                                icon="fa-regular fa-pen-to-square"
                            />
                            <font-awesome-icon
                                @click="deleteEntry(entry.id)"
                                class="cursor-pointer ml-2"
                                icon="fa-regular fa-trash-can"
                            />
                        </div>
                    </td>
                </tr>
                <tr v-if="(entries.data?.length ?? 0) === 0">
                    <td class="" colspan="4">
                        {{ $t("No Entries added") }}.
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="margin-top: 3rem" class="flex justify-center">
            <pagination
                :limit="10"
                align="center"
                :data="entries ?? []"
                @pagination-change-page="changePageOrSearch"
            >
            </pagination>
        </div>
        <button
            @click.prevent="toggleModal = !toggleModal"
            class="docsHeroLayoutColor px-3 py-2 rounded my-5"
        >
            {{ $t("Add Entry") }} +
        </button>
    </div>
    <Modal
        :title="$t(`${action}`)"
        v-if="toggleModal"
        @toggleModal="toggleModal = $event"
        width="50%"
    >
        <template #body>
            <div class="ml-6 mr-6 border mb-3 p-3" style="height: 350px">
                <div class="flex flex-wrap -mb-8 -mr-6 p-8">
                    <text-input
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        @input="errors.description = false"
                        v-model="form.description"
                        :required="true"
                        :label="$t('Description')"
                    />
                    <p v-if="errors.description" class="text-red-500">
                        {{ $t("Description is Required") }}
                    </p>
                    <date-input
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        @input="errors.expiryDate = false"
                        v-model="form.expiryDate"
                        :required="true"
                        :label="$t('Expiry Date')"
                        :max="
                            new Date().toLocaleDateString('fr-ca', {
                                timeZone: 'Europe/Berlin',
                            })
                        "
                    />
                    <p v-if="errors.expiryDate" class="text-red-500">
                        {{ $t("Expiry Date is Required") }}
                    </p>
                    <MultiSelectInput
                        class="pb-8 pr-6 w-full lg:w-1/2"
                        @update:modelValue="errors.ownership = false"
                        :key="form.ownership"
                        v-model="form.ownership"
                        :options="users ?? []"
                        :required="true"
                        :multiple="false"
                        :text-label="$t('Ownership')"
                        :customLabel="customLabel"
                        trackBy="id"
                        moduleName="auth"
                    />
                    <p v-if="errors.ownership" class="text-red-500">
                        {{ $t("Ownership is Required") }}
                    </p>
                </div>
            </div>
        </template>
        <template #footer>
            <button
                @click="addEntry"
                type="button"
                class="secondary-btn"
            >
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
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import DateInput from "@/Components/DateInput.vue";
import Pagination from "laravel-vue-pagination";

import { mapGetters } from "vuex";

export default {
    name: "DescriptionEntries",
    props: {
        id: {
            type: Number,
            default: null,
        },
    },
    components: {
        MultiSelectInput,
        DateInput,
        TextInput,
        Modal,
        Pagination,
    },
    computed: {
        ...mapGetters("auth", ["users"]),
    },
    data() {
        return {
            entries: {
                data: [],
                links: [],
            },
            action: "Add",
            toggleModal: false,
            form: {
                description: "",
                expiryDate: "",
                ownership: "",
            },
            errors: {
                description: false,
                expiryDate: false,
                ownership: false,
            },
        };
    },
    mounted() {
        this.getData();
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            const response = await this.$store.dispatch(
                "projectProtocolEntries/list",
                {
                    id: this.id ?? this.$route.params.id,
                    queryParams: {
                        page: page,
                    },
                }
            );
            this.entries = response?.data?.data ?? {
                data: [],
                links: [],
            };
            this.entries.data = this.entries.data.map((entry) => {
                return {
                    ...entry,
                    ownership: this.users.find(
                        (user) => user.id == entry.ownership
                    ),
                };
            });
        },
        /**
         * fetch the entries listing
         */
        async getData() {
            try {
                const response = await this.$store.dispatch(
                    "projectProtocolEntries/list",
                    {
                        id: this.id ?? this.$route.params.id,
                    }
                );
                this.entries = response?.data?.data ?? { data: [], links: [] };
                this.entries.data = this.entries.data.map((entry) => {
                    return {
                        ...entry,
                        ownership: this.users.find(
                            (user) => user.id == entry.ownership
                        ),
                    };
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * returns a custom label value for the selected option in multiselect component
         * @param {props} the properties of the options
         */
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        editEntry(entry) {
            this.action = "Edit";
            this.form = { ...entry };
            this.toggleModal = true;
        },
        async deleteEntry(id) {
            try {
                await this.$store.dispatch(
                    "projectProtocolEntries/destroy",
                    id
                );
                this.getData();
            } catch (e) {
                console.log(e);
            }
        },
        async addEntry() {
            if (
                !this.form.description ||
                !this.form.expiryDate ||
                !this.form.ownership
            ) {
                this.errors = {
                    description: !this.form.description,
                    expiryDate: !this.form.expiryDate,
                    ownership: !this.form.ownership,
                };
                return;
            }
            try {
                if (this.action.toLowerCase() === "edit") {
                    await this.$store.dispatch(
                        "projectProtocolEntries/update",
                        {
                            id: this.form.id,
                            data: {
                                ...this.form,
                                ownership: this.form.ownership?.id,
                                projectProtocolId:
                                    this.id ?? this.$route.params.id,
                            },
                        }
                    );
                } else {
                    await this.$store.dispatch(
                        "projectProtocolEntries/create",
                        {
                            ...this.form,
                            ownership: this.form.ownership?.id,
                            projectProtocolId: this.id ?? this.$route.params.id,
                        }
                    );
                }
                this.getData();
            } catch (e) {
                console.log(e);
            } finally {
                this.form = {
                    description: "",
                    expiryDate: "",
                    ownership: "",
                };
            }
            this.toggleModal = false;
        },
    },
    watch: {
        toggleModal(val) {
            if (!val) {
                this.action = "Add";
                this.form = {
                    description: "",
                    expiryDate: "",
                    ownership: "",
                };
            }
        },
    },
};
</script>
