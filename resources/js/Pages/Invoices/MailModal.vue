<template>
    <Modal
        title="Send Mail"
        v-if="toggleModal"
        @toggleModal="$emit('toggleModal', false)"
        width="50%"
    >
        <template #body>
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full col-span-2">
                    <div class="form-group">
                        <div
                            v-if="!fromOpenPost"
                            class="flex items-center pb-8 pr-6 w-full lg:w-1/3"
                        >
                            <input
                                v-model="mergePdf"
                                :checked="mergePdf"
                                class="mr-2"
                                type="checkbox"
                                id="merge-pdf"
                            />
                            <label for="merge-pdf">{{
                                $t("Merge PDFs")
                            }}</label>
                        </div>
                    </div>
                </div>
                <!-- show company employees dropdown if mail is being sent from offer or offer confirmation -->
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-if="isOffer || isOrder"
                            :customLabel="customLabel"
                            v-model="form.to"
                            :key="form.to"
                            :textLabel="$t('To')"
                            :required="true"
                            :options="employees"
                            :multiple="true"
                            trackBy="id"
                            moduleName="companyEmployees"
                            :error="errors.to"
                        />
                        <TextInput
                            :required="true"
                            :label="$t('To')"
                            v-model="form.to"
                            :error="errors['to']"
                        />
                    </div>
                </div>
                <div class="w-full col-span-2">
                    <div class="pb-2 pr-6 w-full">
                        <label>{{ $t("Data") }}:</label>
                        <MonacoEditor
                            @contentChange="form.data = $event"
                            :codeString="companyJson"
                            :language="'json'"
                            class="mt-2"
                            height="300px"
                        />
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <loading-button
                :loading="isLoading || processing"
                style="cursor: pointer"
                class="secondary-btn mx-2"
                @click="generateEmail"
            >
                {{ processing ? $t("Processing") : $t("Send Mail") }}
            </loading-button>
            <button
                @click="$emit('toggleModal', false)"
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
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MonacoEditor from "@/Components/MonacoEditor.vue";
import { mapGetters } from "vuex";

export default {
    name: "MailModal",
    emits: ["toggleModal", "refresh"],
    props: {
        toggleModal: {
            type: Boolean,
            default: false,
        },
        // invoice filename and base64
        invoice: {
            type: Object,
        },
        // performance record filename and base64
        performanceRecord: {
            type: Object,
            default: () => ({}),
        },
        // merged invoice and performance record filename and base64
        mergedPdf: {
            type: Object,
        },
        // invoice details
        invoiceData: {
            type: Object,
        },
        // invoice mail template assignment details
        invoiceTemplate: {
            type: Object,
        },
        // processing details
        processing: {
            type: Boolean,
        },
        // the modal is in open posts module
        fromOpenPost: {
            type: Boolean,
            default: false,
        },
        // mail modal was used in offer module
        isOffer: {
            type: Boolean,
            default: false,
        },
        // mail modal was used in order module
        isOrder: {
            type: Boolean,
            default: false,
        },
    },
    components: {
        MultiSelectInput,
        Modal,
        TextInput,
        MonacoEditor,
        LoadingButton,
    },
    data() {
        return {
            mergePdf: false,
            form: {
                to: "",
                data: "",
            },
            companyJson: "", // company details as json
        };
    },
    mounted() {
        // set the 'to' to the invoice email
        this.form.to = this.invoiceData?.invoiceEmail;
        this.mergePdf =
            this.invoiceData?.companyDetails?.mergePdfsOnDefault == 1;
        // if mail is being sent from offer or offer confirmation then fetch the company employees
        if (
            (this.isOffer || this.isOrder) &&
            this.invoiceData?.companyDetails?.id
        ) {
            this.fetchCompanyEmployees(this.invoiceData?.companyDetails?.id);
        }
        try {
            // convert the companyJson to stringified json to use in the monaco editor
            this.companyJson = JSON.stringify(this.invoiceData);
        } catch (e) {
            console.log(e);
        }
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companyEmployees", {
            employees: "users",
        }),
    },
    methods: {
        /**
         * formats the option label in multiselect input
         * @param {props} the options received from the multiselect input
         * @returns the formated label
         */
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },
        /**
         * fetches the company employees listing based on id
         * @param {id} company id
         */
        async fetchCompanyEmployees(id) {
            try {
                await this.$store.dispatch("companyEmployees/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "customer",
                    company_id: id,
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * tries to parse JSON string and returns parsed JSON if successfull else returns an empty object
         * @param {data} JSON string to be parsed
         */
        getParsedJson(data) {
            try {
                return JSON.parse(data);
            } catch (e) {
                return {};
            }
        },
        /**
         * hits the mailTemplates send mail API
         */
        async generateEmail() {
            // check for 'to' validation
            if (!this.form.to) {
                this.$store.commit("errors", {
                    to: this.$t("To is a required field"),
                });
                return;
            }
            try {
                this.$store.commit("isLoading", true);
                let files = []; // contains invoice, performance record names and base64
                if (this.mergePdf) {
                    files.push({
                        name: this.mergedPdf.name,
                        base64_content: this.mergedPdf.base64,
                    });
                } else {
                    // if invoice is prop is set then push the relevant info to files
                    if (this.invoice.name) {
                        files.push({
                            name: this.invoice.name,
                            base64_content: this.invoice.base64,
                        });
                    }
                    // if performanceRecord is prop is set then push the relevant info to files
                    if (this.performanceRecord.name) {
                        files.push({
                            name: this.performanceRecord.name,
                            base64_content: this.performanceRecord.base64,
                        });
                    }
                }
                let to = [];
                // if mail is being sent from offer or offer confirmation and 'to' is an array
                // then map the emails
                if (
                    (this.isOffer || this.isOrder) &&
                    this.form.to instanceof Array
                ) {
                    to = this.form.to.map((employee) => employee.email);
                } else {
                    to = this.form.to ? [this.form.to] : [];
                }
                // create the payload for sending mail
                const payload = {
                    data: this.getParsedJson(this.form.data),
                    id: this.invoiceTemplate?.mailTemplateId,
                    mails: to,
                    files: files,
                    from: this.invoiceTemplate?.senderMail,
                    cc: this.invoiceTemplate?.cc
                        ? [this.invoiceTemplate?.cc]
                        : [],
                    bcc: this.invoiceTemplate?.bcc
                        ? [this.invoiceTemplate?.bcc]
                        : [],
                };
                // send the mail
                await this.$store.dispatch(
                    "mailTemplates/sendMailTemplate",
                    payload
                );
                if (!this.fromOpenPost) {
                    // update invoice status to 'sent'
                    await this.$store.dispatch("invoices/updateStatus", {
                        id: this.invoiceData?.id,
                        data: {
                            status: "sent",
                        },
                    });
                } else if (this.fromOpenPost) {
                    await this.$store.dispatch(
                        "openposts/updateEmailReminder",
                        this.invoiceData?.id
                    );
                    this.$emit("openPostReminder");
                } else if (this.isOffer) {
                    // update offer status to 'versendet'
                    await this.$store.dispatch("offers/updateStatus", {
                        id: this.invoiceData?.id,
                        data: {
                            status: "versendet",
                        },
                    });
                } else if (this.isOrder) {
                    // update order status to 'sent'
                    await this.$store.dispatch(
                        "orderConfirmation/updateStatus",
                        {
                            id: this.invoiceData?.id,
                            data: {
                                status: "sent",
                            },
                        }
                    );
                }
                // refresh the invoices listing
                this.$emit("refresh", true);

                // toggle the modal
                this.$emit("toggleModal", false);
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
