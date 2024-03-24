<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill Ticket Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.title"
                                    :required="true"
                                    :error="errors.title"
                                    class=""
                                    :label="$t('Title')"
                                />
                            </div>
                        </div>
                         <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.area"
                                    class=""
                                    :label="$t('Area')"
                                    :error="errors?.area ?? ''"
                                >
                                    <option value="customer">
                                        {{ $t("Customer") }}
                                    </option>
                                    <option value="partner">
                                        {{ $t("Partners") }}
                                    </option>
                                    <option value="product">
                                        {{ $t("Products") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-if="form.area === 'customer'"
                                    v-model="form.companyId"
                                    @update:model-value="getAms"
                                    :required="true"
                                    :options="companies.data"
                                    :error="errors?.companyId ?? ''"
                                    :multiple="false"
                                    :textLabel="$t('Customer')"
                                    label="companyName"
                                    trackBy="id"
                                    moduleName="companies"
                                />
                                <MultiSelectInput
                                    v-if="form.area === 'partner'"
                                    v-model="form.partnerId"
                                    :required="true"
                                    :options="partners.data"
                                    :error="errors?.companyId ?? ''"
                                    :multiple="false"
                                    :textLabel="$t('Partner')"
                                    label="companyName"
                                    trackBy="id"
                                    moduleName="partners"
                                />
                                <MultiSelectInput
                                    v-if="form.area === 'product'"
                                    v-model="form.productId"
                                    :key="form.productId"
                                    :options="products.data"
                                    class=""
                                    :multiple="false"
                                    :textLabel="$t('Products')"
                                    label="name"
                                    trackBy="id"
                                    moduleName="products"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    @update:model-value="setSoftware"
                                    v-model="form.amsId"
                                    :key="form.amsId"
                                    :options="ams"
                                    class=""
                                    :multiple="false"
                                    :textLabel="$t('Accounting')"
                                    :isDisabled="ams.length == 1"
                                    label="amsNumber"
                                    trackBy="id"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    v-model="form.softwareId"
                                    :key="form.softwareId"
                                    :options="softwares.data"
                                    :multiple="false"
                                    :textLabel="$t('Software')"
                                    label="name"
                                    :trackBy="'id'"
                                    :moduleName="'softwares'"
                                    :taggable="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.type"
                                    class=""
                                    :label="$t('Type')"
                                    :error="errors?.type ?? ''"
                                >
                                    <option value="incident">
                                        {{ $t("Incident") }}
                                    </option>
                                    <option value="change">
                                        {{ $t("Change") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.priority"
                                    :required="true"
                                    class=""
                                    :label="$t('Priority')"
                                    :error="errors?.priority ?? ''"
                                >
                                    <option value="low">
                                        1 {{ $t("low") }}
                                    </option>
                                    <option value="normal">
                                        2 {{ $t("normal") }}
                                    </option>
                                    <option value="high">
                                        3 {{ $t("high") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.state"
                                    :required="true"
                                    class=""
                                    :label="$t('Status')"
                                    :error="errors?.state ?? ''"
                                >
                                    <option value="new">{{ $t("New") }}</option>
                                    <option value="open">
                                        {{ $t("Open") }}
                                    </option>
                                    <option value="waiting-for-response">
                                        {{ $t("Waiting For Response") }}
                                    </option>
                                    <option value="pending">
                                        {{ $t("Pending") }}
                                    </option>
                                    <option value="resolved">
                                        {{ $t("Resolved") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    @open="getEmployeesListing"
                                    v-model="form.assignee"
                                    :key="form.assignee"
                                    :options="employees"
                                    :multiple="false"
                                    :text-label="$t('Assignee')"
                                    :error="errors.assignee"
                                    label="first_name"
                                    trackBy="id"
                                    moduleName="auth"
                                    :search-param-name="'search_string'"
                                >
                                    <template #beforeList>
                                        <div
                                            class="grid p-2 gap-2"
                                            style="
                                                grid-template-columns: 50% 50%;
                                            "
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
                                            {{
                                                props.option.first_name
                                            }}&nbsp;{{ props.option.last_name }}
                                        </p>
                                    </template>
                                    <template #option="{ props }">
                                        <div
                                            class="grid"
                                            style="
                                                grid-template-columns: 50% 50%;
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
                                        </div>
                                    </template>
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    @open="getUsersListing"
                                    v-model="form.reporter"
                                    :options="users"
                                    :multiple="false"
                                    :key="form.reporter"
                                    :text-label="$t('Reporter')"
                                    label="first_name"
                                    trackBy="id"
                                    moduleName="auth"
                                    :query="{ type: null }"
                                    :search-param-name="'search_string'"
                                >
                                    <template #beforeList>
                                        <div
                                            class="grid p-2 gap-2"
                                            style="
                                                grid-template-columns: 50% 50%;
                                            "
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
                                            {{
                                                props.option.first_name
                                            }}&nbsp;{{ props.option.last_name }}
                                        </p>
                                    </template>
                                    <template #option="{ props }">
                                        <div
                                            class="grid"
                                            style="
                                                grid-template-columns: 50% 50%;
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
                                        </div>
                                    </template>
                                </MultiSelectInput>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.visibility"
                                    :required="true"
                                    class=""
                                    :label="$t('Visibility')"
                                    :error="errors?.visibility ?? ''"
                                >
                                    <option value="internal">
                                        {{ $t("Internal Only") }}
                                    </option>
                                    <option value="public">
                                        {{ $t("Public") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    :required="true"
                                    v-model="form.contactType"
                                    class=""
                                    :label="$t('Contact Type')"
                                    :error="errors?.contactType ?? ''"
                                >
                                    <option value="mail">
                                        {{ $t("Mail") }}
                                    </option>
                                    <option value="phone">
                                        {{ $t("Phone") }}
                                    </option>
                                    <option value="direct">
                                        {{ $t("Direct Contact") }}
                                    </option>
                                    <option value="customer-portal">
                                        {{ $t("Customer Portal") }}
                                    </option>
                                    <option value="partner-portal">
                                        {{ $t("Partner Portal") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full col-span-2">
                            <div class="form-group">
                                <QuillTextEditor
                                    class=""
                                    :content="form.text"
                                    :content-type="'html'"
                                    :required="true"
                                    :error="errors.text"
                                    @delta="form.text = $event"
                                    :label="$t('Text')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/tickets" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="secondary-btn"
                >
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
import LoadingButton from "../../Components/LoadingButton.vue";
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import TextAreaInput from "../../Components/TextareaInput.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import QuillTextEditor from "../../Components/QuillTextEditor.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        QuillTextEditor,
        MultiSelectInput,
        TextInput,
        SelectInput,
        TextAreaInput,
        LoadingButton,
        PageHeader,
    },
    computed: {
        ...mapGetters(["errors", "isLoading", "pusher"]),
        ...mapGetters("auth", ["user", "users", "employees"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("products", ["products"]),
        ...mapGetters("softwares", ["softwares"]),
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Partner Management"),
                    to: "/partner-management/tickets",
                },
                {
                    text: this.$t("Tickets"),
                    to: "/partner-management/tickets",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            text: "",
            ams: [],
            form: {
                title: "",
                area: "partner",
                state: "new",
                priority: "",
                companyId: "",
                partnerId: "",
                productId: "",
                reporter: "",
                contactType: "",
                assignee: "",
                text: "",
                time: "",
                visibility: "",
                type: "",
                softwareId: "",
                amsId: "",
            },
        };
    },
    async mounted() {
        try {
            this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
            this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
                customerType: "partner",
            });
            this.$store.dispatch("products/list", {
                perPage: 25,
                page: 1,
            });
            await this.$store.dispatch("softwares/list");
            this.form.assignee =
                this.employees.find((user) => user.id == this.user.id) ?? "";
            this.form.reporter =
                this.employees.find((user) => user.id == this.user.id) ?? "";
            await this.$nextTick();
        } catch (e) {
            console.log(e);
        }
    },
    methods: {
        /**
         * gets employees listing on dropdown open
         */
        async getEmployeesListing() {
            try {
                await this.$store.dispatch("auth/employees", {
                    limit_start: 0,
                    limit_count: 25,
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * gets users listing on dropdown open
         */
        async getUsersListing() {
            try {
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                });
            } catch (e) {
                console.log(e);
            }
        },
        async setSoftware() {
            await this.$nextTick();
            this.form.softwareId = this.form.amsId?.attachment?.software ?? "";
        },
        async getAms() {
            await this.$nextTick();
            try {
                const response = await this.$store.dispatch(
                    "ams/getByCustomerAndAttachment",
                    this.form.companyId?.id
                );
                this.ams = response?.data?.data ?? [];
                this.form.amsId = this.ams.length == 1 ? this.ams[0] : "";
                this.setSoftware();
            } catch (e) {
                console.log(e);
            }
        },
        watch: {
        form : {
                deep: true,
                handler(newValue) {
                if (newValue.area == "product") {
                    this.form.companyId = "";
                    this.form.partnerId= "";
                } 
                else if (newValue.area == "customer") {
                    this.form.productId = "";
                    this.form.partnerId= "";
                } 
                else if (newValue.area == "partner") {
                    this.form.companyId = "";
                    this.form.productId = "";
                } 
            },
        },
    },
        async store() {
            try {
                this.$store.commit("isLoading", true);
                const response = await this.$store.dispatch("partnerTickets/create", {
                    ...this.form,
                    companyId:
                        this.form.area === "customer"
                            ? this.form.companyId?.id
                            : this.form.area === "partner" ? this.form.partnerId?.id : '',
                    ...(this.form.productId && {  productId: this.form.productId?.id }),
                    assignee: this.form.assignee?.id,
                    userId: this.form.reporter?.id,
                    userType: "employee",
                    softwareId: this.form.softwareId?.id,
                    amsId: this.form.amsId?.id,
                });
                await this.$store.dispatch("partnerTickets/list");
                this.$store.dispatch("partnerTickets/openTicketsCount");
                this.$router.push("/partner-management/tickets");
                try {
                    await this.$store.dispatch("ticketComments/sendMail", {
                        id: response?.data?.ticketCommentId,
                        data: {
                            reporter:
                                this.form.reporter?.email ?? this.user?.email,
                            assignee: this.form.assignee?.email,
                            reporterName: this.form?.reporter
                                ? this.form.reporter?.first_name +
                                  " " +
                                  this.form.reporter?.last_name
                                : this.user?.first_name +
                                  " " +
                                  this.user?.last_name,
                            assigneeName: this.form?.assignee
                                ? this.form?.assignee?.first_name +
                                  " " +
                                  this.form?.assignee?.last_name
                                : "",
                            isReporterEmployee:
                                (this.form.reporter?.types?.["employee"] ??
                                    0) == 1,
                            isAssigneeEmployee:
                                (this.form.assignee?.types?.["employee"] ??
                                    0) == 1,
                        },
                    });
                } catch (e) {
                    console.log(e);
                }
                this.pusher.sendMessage(
                    JSON.stringify({
                        ticket: response?.data?.ticket ?? null,
                        action: "CommentCreated",
                        userName:
                            this.user?.first_name + " " + this.user?.last_name,
                    }),
                    response?.data?.ticketUsers ?? [],
                    null
                );
            } catch (e) {}
        },
    },
};
</script>

<style scoped>
:deep(.ql-container) {
    height: auto;
}
</style>
