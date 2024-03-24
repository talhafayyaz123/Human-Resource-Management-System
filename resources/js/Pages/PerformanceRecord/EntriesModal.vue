<template>
    <Modal
        :title="$t(actionType)"
        v-if="toggleModal"
        @toggleModal="$emit('cancel', true)"
        width="50%"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6 px-5">
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            class=""
                            :error="errors.userId"
                            :key="form.userId"
                            v-model="form.userId"
                            :options="users ?? []"
                            :multiple="false"
                            :text-label="$t('Person/Reference')"
                            :required="true"
                            :customLabel="customLabel"
                            trackBy="id"
                            moduleName="auth"
                            :search-param-name="'search_string'"
                            :query="{
                                limit_start: 0,
                                limit_count: 100,
                                type: 'employee',
                            }"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <NumberInput
                            :label="$t('Quantity')"
                            :required="true"
                            class=""
                            :roundNearQuarter="true"
                            :forceLeftBound="true"
                            :showPrefix="false"
                            :min="0"
                            v-model="form.quantity"
                            :error="errors.time"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <SelectInput
                            :label="$t('Goodwill')"
                            class=""
                            v-model="form.isGoodwill"
                            :error="errors.isGoodwill"
                            :required="true"
                        >
                            <option :value="1">Yes</option>
                            <option :value="0">No</option>
                        </SelectInput>
                    </div>
                </div>
                <div v-if="form.isNewEntry && form.projectId" class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :required="true"
                            :options="projects?.data ?? []"
                            :multiple="false"
                            :text-label="$t('Projects')"
                            label="name"
                            trackBy="id"
                            :isDisabled="true"
                            moduleName="projects"
                            v-model="form.projectId"
                            :key="form.projectId"
                            :query="{
                                status: [
                                    'new',
                                    'in-work',
                                    'in-review',
                                    'blocked',
                                ],
                                companyId: this.company?.id,
                            }"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <DateInput
                            class=""
                            v-model="form.date"
                            :required="true"
                            :label="$t('Created Date')"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <TextAreaInput
                            :label="$t('Description')"
                            :required="true"
                            class=""
                            v-model="form.description"
                            :error="errors.description"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <TextAreaInput
                            :label="$t('Internal Note')"
                            :required="false"
                            class=""
                            v-model="form.internalNote"
                        />
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <loading-button
                :loading="isLoading"
                style="cursor: pointer"
                class="secondary-btn"
                @click="actionType === 'Add' ? add() : update()"
                >{{ $t(actionType) }}</loading-button
            >
            <button
                @click="$emit('cancel', true)"
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
import TextAreaInput from "@/Components/TextareaInput.vue";
import SelectInput from "@/Components/SelectInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import DateInput from "@/Components/DateInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";

export default {
    emits: ["refresh", "cancel", "addEntry", "updateEntry"],
    computed: {
        ...mapGetters("auth", ["user", "users"]),
        ...mapGetters(["isLoading", "errors"]),
        ...mapGetters("projects", ["projects"]),
    },
    components: {
        TextAreaInput,
        SelectInput,
        MultiSelectInput,
        NumberInput,
        DateInput,
        LoadingButton,
        Modal,
    },
    data() {
        return {
            shouldShow: true,
            form: {
                date: "",
                description: "",
                internalNote: "",
                isGoodwill: "",
                time: "",
                userId: "",
                projectId: "",
                isNewEntry: false,
            },
        };
    },
    watch: {
        async record(val) {
            this.shouldShow = false;
            this.form = { ...val };
            // this.form.userId = this.users?.find((user) => {
            //     return user.id == val.userId?.id;
            // });
            await this.$nextTick();
            this.shouldShow = true;

            if (this.form.isNewEntry) {
                await this.$store.dispatch("projects/list", {
                    companyId: this.company?.id ?? "",
                    status: ["new", "in-work", "in-review", "blocked"],
                });
                this.form.projectId =
                    this.projects?.data?.find((project) => {
                        return project.id === this.form.projectId;
                    }) ?? "";
            }
        },
    },
    props: {
        isCreate: {
            type: Boolean,
            default: false,
        },
        actionType: {
            type: String,
            default: "Add",
        },
        toggleModal: {
            type: Boolean,
            default: false,
        },
        record: {
            type: Object,
            default: () => ({}),
        },
        company: {
            type: Object,
            default: () => ({}),
        },
    },
    methods: {
        /**
         * returns a custom label value for the selected option in multiselect component
         * @param {props} the properties of the options
         */
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        /**
         * adds new entry to performance record time tracker
         */
        async add() {
            try {
                if (this.isCreate) {
                    this.$emit("addEntry", {
                        ...this.form,
                        time: this.form.quantity,
                        date: this.form.date,
                        userId: this.form.userId,
                        userProfile: this.form.userId,
                    });
                    this.$emit("cancel", true);
                    return;
                }
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("performanceRecords/addEntries", {
                    id: this.$route.query.id,
                    data: {
                        ...this.form,
                        time: this.form.quantity,
                        date: this.form.date,
                        userId: this.form.userId,
                        editedUserId: this.user?.id,
                    },
                });
                this.$emit("cancel", true);
                this.$emit("refresh", true);
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * updates the selected performance record time tracker entry
         */
        async update() {
            try {
                if (this.isCreate) {
                    this.$emit("updateEntry", {
                        ...this.form,
                        time: this.form.quantity,
                        date: this.form.date,
                        userId: this.form.userId,
                        userProfile: this.form.userId,
                    });
                    this.$emit("cancel", true);
                    return;
                }
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("performanceRecords/editEntries", {
                    id: this.form.id,
                    data: {
                        ...this.form,
                        time: this.form.quantity,
                        date: this.form.date,
                        userId: this.form.userId,
                        editedUserId: this.user?.id,
                        projectId: this.form.projectId?.id ?? null,
                    },
                });
                this.$emit("cancel", true);
                this.$emit("refresh", true);
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

<style></style>
