<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill Project Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <input
                                    id="internal-project"
                                    type="checkbox"
                                    v-model="form.internalProject"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                />
                                <label
                                    class="tab-label styles-configurator-tab-label ml-4"
                                    for="internal-project"
                                >
                                    {{ $t("Internal Project") }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="grid items-center grid-cols-3 gap-6 my-5">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.name"
                                    :error="errors.name"
                                    :label="$t('Project Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.status"
                                    :error="errors.status"
                                    :label="$t('Status')"
                                >
                                    <option value="new">{{ $t("New") }}</option>
                                    <option value="done">
                                        {{ $t("Done") }}
                                    </option>
                                    <option value="in-work">
                                        {{ $t("In Work") }}
                                    </option>
                                    <option value="in-review">
                                        {{ $t("In Review") }}
                                    </option>
                                    <option value="blocked">
                                        {{ $t("Blocked") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    @update:model-value="orderConfirmationValue"
                                    :required="true"
                                    :textLabel="$t('Customer')"
                                    v-model="form.companyId"
                                    :options="companies.data"
                                    :multiple="false"
                                    label="companyName"
                                    trackBy="id"
                                    moduleName="companies"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.assignedUsers"
                                    :key="form.assignedUsers"
                                    :error="errors['assignedUsers']"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="true"
                                    :text-label="$t('Assigned Users')"
                                    label="employee"
                                    trackBy="userId"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.orderConfirmationId"
                                    @update:model-value="setEstimatedTime"
                                    :key="form.orderConfirmationId"
                                    :error="errors['orderConfirmationId']"
                                    :options="orderConfirmations ?? []"
                                    :multiple="true"
                                    :text-label="$t('Order Confirmation')"
                                    label="saleOfferNumber"
                                    trackBy="id"
                                    moduleName="OrderConfirmation"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="grid items-center grid-cols-1 gap-6 my-5">
                        <div class="w-full">
                            <div class="form-group">
                                <QuillTextEditor
                                    :content="form.description"
                                    :error="errors.description"
                                    @delta="form.description = $event"
                                    :label="$t('Description')"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="grid items-center grid-cols-3 gap-6 my-5">
                        <div class="w-full">
                            <div class="form-group">
                                <DateInput
                                    v-model="form.plannedStartDate"
                                    :error="errors.plannedStartDate"
                                    :label="$t('Planned Start Date')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <DateInput
                                    v-model="form.plannedFinishedDate"
                                    :error="errors.plannedFinishedDate"
                                    :label="$t('Planned Finished Date')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <DateInput
                                    v-model="form.actualStartDate"
                                    :error="errors.actualStartDate"
                                    :label="$t('Actual Start Date')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <DateInput
                                    v-model="form.actualFinishedDate"
                                    :error="errors.actualFinishedDate"
                                    :label="$t('Actual Finished Date')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <DateInput
                                    v-model="form.earliestStartDate"
                                    :error="errors.earliestStartDate"
                                    :label="$t('Earliest Start Date')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <DateInput
                                    v-model="form.expectedFinishedDate"
                                    :error="errors.expectedFinishedDate"
                                    :label="$t('Expected Finished Date')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    :showLabels="false"
                                    :required="true"
                                    :textLabel="$t('Project Manager')"
                                    v-model="form.employeeId"
                                    :options="users"
                                    :multiple="false"
                                    label="first_name"
                                    trackBy="id"
                                    moduleName="auth"
                                    :search-param-name="'search_string'"
                                >
                                    <template #beforeList>
                                        <div
                                            class="grid p-2 gap-2"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
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
                                        {{ props.option.first_name }}
                                        {{ props.option.last_name }}
                                    </template>
                                    <template #option="{ props }">
                                        <div
                                            class="grid gap-2"
                                            style="
                                                grid-template-columns: 25% 25% 50%;
                                            "
                                        >
                                            <p
                                                class="overflow-text-users-table"
                                            >
                                                {{ props.option.first_name }}
                                            </p>
                                            <p
                                                class="overflow-text-users-table"
                                            >
                                                {{ props.option.last_name }}
                                            </p>
                                            <p
                                                class="overflow-text-users-table"
                                            >
                                                {{ props.option.email }}
                                            </p>
                                        </div>
                                    </template>
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.estimatedTime"
                                    :error="errors.estimatedTime"
                                    :label="$t('Estimated Time (hrs)')"
                                    type="number"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.neededTime"
                                    :error="errors.neededTime"
                                    :isReadonly="true"
                                    :label="$t('Needed Time (hrs)')"
                                    type="number"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.neededTimeWithGoodwill"
                                    :error="errors.neededTimeWithGoodwill"
                                    :isReadonly="true"
                                    :label="
                                        $t('Needed Time Incl. Goodwill (hrs)')
                                    "
                                    type="number"
                                />
                            </div>
                        </div>

                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.externalOrderNumber"
                                    :error="errors.externalOrderNumber"
                                    :label="$t('External Order Number')"
                                    type="text"
                                />
                            </div>
                        </div>


                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link
                    to="/project-management/projects"
                    class="primary-btn mr-3"
                >
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button :loading="isLoading" class="secondary-btn">
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import SelectInput from "@/Components/SelectInput.vue";
import DateInput from "@/Components/DateInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", {
            users: "users",
        }),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("companies", {
            companies: "companies",
        }),
    },
    components: {
        LoadingButton,
        TextInput,
        QuillTextEditor,
        SelectInput,
        DateInput,
        MultiSelectInput,
        PageHeader,
    },
   /*  created(){
        this.$emitter.on("updateDescription", (value) => {
           alert('555')
        });
    }, */
    async mounted() {
       
        this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });
        this.$store.dispatch("companies/list", {
            perPage: 25,
            page: 1,
        });
        if (!this.userProfiles?.data?.length) {
            this.$store.dispatch("userProfile/list");
        }
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Project Management"),
                    to: "/project-management/projects",
                },
                {
                    text: this.$t("Projects"),
                    to: "/project-management/projects",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            form: {
                projectId: "fnkwfn",
                internalProject: false,
                name: "",
                description: "",
                status: "new",
                plannedStartDate: "",
                actualStartDate: "",
                earliestStartDate: "",
                actualFinishedDate: "",
                expectedFinishedDate: "",
                plannedFinishedDate: "",
                companyId: "",
                employeeId: "",
                estimatedTime: "",
                neededTime: "",
                neededTimeWithGoodwill: "",
                comments: "",
                assignedUsers: [],
                orderConfirmationId: "",
                externalOrderNumber:''
            },
            orderConfirmations: [
                {
                    id: null,
                    saleOfferNumber: "Not Assigned",
                },
            ],
        };
    },
    methods: {
        /**
         * sets the estimated time to the quantity of selected offer confirmation
         */
        async setEstimatedTime() {
            await this.$nextTick();

            if (Array.isArray(this.form.orderConfirmationId)) {
                // If orderConfirmationId is an array, sum the quantities
                this.form.estimatedTime = this.form.orderConfirmationId.reduce(
                    (sum, confirmation) =>
                        parseInt(sum) + parseInt(confirmation.quantity ?? 0),
                    0
                );
            } else {
                // If orderConfirmationId is not an array, use its quantity directly
                this.form.estimatedTime = parseInt(
                    this.form.orderConfirmationId?.quantity ?? 0
                );
            }
        },
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("projects/create", {
                    ...this.form,
                    employeeId: this.form.employeeId?.id,
                    companyId: this.form.companyId?.id,
                    assignedUsers: this.form.assignedUsers.map(
                        (user) => user.id
                    ),
                    orderConfirmationIds: Array.isArray(
                        this.form.orderConfirmationId
                    )
                        ? this.form.orderConfirmationId
                              ?.map((order) => order?.id ?? null)
                              ?.filter(Boolean) ?? []
                        : [],
                });
                await this.$store.dispatch("projects/list");
                this.$router.push("/project-management/projects");
            } catch (e) {}
        },
        async orderConfirmationValue(event) {
            this.orderConfirmations = [
                {
                    id: null,
                    saleOfferNumber: "Not Assigned",
                },
            ];
            const response = await this.$store.dispatch(
                "projects/offerConfirmationWithProjects",
                event.id
            );
            let responseData = response?.data?.data;

            // Use the spread operator to merge arrays
            this.orderConfirmations = [
                ...this.orderConfirmations,
                ...responseData,
            ];
        },
    },
};
</script>
