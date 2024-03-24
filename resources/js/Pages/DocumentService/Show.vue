<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div>
            <div class="w-full bg-white rounded-md shadow">
                <div class="flex flex-wrap -mr-6 px-8">
                    <div class="pb-2 pr-6 w-full lg:w-1/3">
                        <label class="form-label font-bold"
                            >{{ $t("ID") }}:</label
                        >
                        <p>{{ this.$route.params.id }}</p>
                    </div>
                    <div class="pb-2 pr-6 w-full lg:w-1/3">
                        <label class="form-label font-bold"
                            >{{ $t("Name") }}:</label
                        >
                        <p>{{ this.name }}</p>
                    </div>
                </div>
            </div>
            <div class="flex mb-1">
                <loading-button
                    @click="generatePdf('pdf')"
                    :loading="isLoading"
                    class="secondary-btn mt-1 mr-1 w-40 place-content-center"
                    >{{ $t("Generate PDF") }}</loading-button
                >
                <loading-button
                    @click="generatePdf('docx')"
                    :loading="isLoading"
                    class="secondary-btn mt-1 mr-1 w-40 place-content-center"
                    >{{ $t("Generate Docx") }}</loading-button
                >
                <loading-button
                    @click="downloadOriginalFile"
                    :loading="isLoading"
                    class="secondary-btn mt-1 mr-1 w-40 place-content-center"
                    >{{ $t("Download Original") }}</loading-button
                >
                <loading-button
                    v-if="$can(`${$route.meta.permission}.edit`)"
                    @click="
                        $router.push(
                            `/document-service/${$route.params.id}/edit`
                        )
                    "
                    :loading="isLoading"
                    class="secondary-btn mt-1 w-40 place-content-center"
                    >{{ $t("Edit Document") }}</loading-button
                >
            </div>
            <div class="grid" style="grid-template-columns: 40% 60%; gap: 2rem">
                <div class="pb-16">
                    <div class="card shadow-md">
                        <img id="document-preview-image" />
                    </div>
                </div>
                <div>
                    <div class="mb-5">
                        <label for="json-input">{{ $t("JSON") }}</label>
                        <input
                            @input="fetchListings"
                            id="json-input"
                            type="checkbox"
                            class="ml-2"
                            v-model="jsonInput"
                        />
                    </div>
                    <div v-if="!jsonInput">
                        <div
                            class="grid gap-2"
                            style="grid-template-columns: 30% 30% 40%"
                        >
                            <label class="font-bold" for="">Key:</label>
                            <label class="font-bold" for="">Value:</label>
                            <div></div>
                        </div>
                        <div
                            v-for="(variable, index) in variables"
                            :key="'variable-' + index"
                            class="grid gap-2 py-2"
                            style="grid-template-columns: 30% 30% 40%"
                        >
                            <TextInput
                                v-model="variable.key"
                                :isReadonly="true"
                            />
                            <TextInput v-model="variable.value" />
                            <div></div>
                        </div>
                    </div>
                    <div class="mt-5" v-else>
                        <div class="flex flex-wrap">
                            <MultiSelectInput
                                class="pb-8 pr-6 w-full lg:w-1/2"
                                @update:model-value="createJson('user', $event)"
                                v-model="form.user"
                                :key="form.user"
                                :options="userProfiles?.data ?? []"
                                :multiple="false"
                                :text-label="$t('User')"
                                label="employee"
                                trackBy="userId"
                                moduleName="userProfile"
                            />
                            <MultiSelectInput
                                class="pb-8 pr-6 w-full lg:w-1/2"
                                @update:model-value="
                                    createJson('invoice', $event)
                                "
                                v-model="form.invoice"
                                :key="form.invoice"
                                :options="invoices?.data ?? []"
                                :multiple="false"
                                :text-label="$t('Invoice')"
                                label="invoiceNumber"
                                trackBy="id"
                                moduleName="invoices"
                            />
                            <MultiSelectInput
                                class="pb-8 pr-6 w-full lg:w-1/2"
                                @update:model-value="
                                    createJson('offer', $event)
                                "
                                v-model="form.offer"
                                :key="form.offer"
                                :options="offers?.data ?? []"
                                :multiple="false"
                                :text-label="$t('Offer')"
                                label="offerNumber"
                                trackBy="id"
                                moduleName="offers"
                            />
                            <MultiSelectInput
                                class="pb-8 pr-6 w-full lg:w-1/2"
                                @update:model-value="
                                    createJson('performanceRecord', $event)
                                "
                                v-model="form.performanceRecord"
                                :key="form.performanceRecord"
                                :options="performanceRecords?.data ?? []"
                                :multiple="false"
                                :text-label="$t('Performance Record')"
                                label="performanceNumber"
                                trackBy="id"
                                moduleName="performanceRecords"
                            />
                        </div>
                        <label class="font-bold" for="">JSON:</label>
                        <MonacoEditor
                            @contentChange="json = $event"
                            :readOnly="false"
                            :codeString="codeString"
                            :height="'1000px'"
                            :language="'json'"
                            class="mt-2"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import MonacoEditor from "../../Components/MonacoEditor.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["isLoading", "showLoader"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("invoices", ["invoices"]),
        ...mapGetters("offers", ["offers"]),
        ...mapGetters("performanceRecords", ["performanceRecords"]),
    },
    components: {
        TextInput,
        MonacoEditor,
        LoadingButton,
        MultiSelectInput,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Document Service",
                    to: "/document-service",
                },
                {
                    text: "Show",
                    active: true,
                },
            ],
            name: "",
            form: {
                user: "",
                invoice: "",
                offer: "",
                performanceRecord: "",
            },
            image: "",
            variables: [],
            codeString: "",
            json: "",
            jsonInput: false,
            user: "",
            performanceRecord: "",
            invoice: "",
            offer: "",
        };
    },
    methods: {
        /**
         * creates json based on the selected user, invoice, offer, performance record and sets it to the editor
         * @param {type} the module name which will be used to fetch the complete resource data using show API
         * @param event the input event
         */
        async createJson(type, event) {
            await this.$nextTick();
            try {
                let response = null;
                if (type === "user") {
                    if (event)
                        response = await this.$store.dispatch(
                            "userProfile/showCompleteEmployeeInfo",
                            this.form.user?.userId
                        );
                    this.user = response?.data ?? "";
                } else if (type === "invoice") {
                    if (event)
                        response = await this.$store.dispatch(
                            "invoices/show",
                            this.form.invoice?.id
                        );
                    this.invoice = response?.data?.pInvoices ?? "";
                } else if (type === "offer") {
                    if (event)
                        response = await this.$store.dispatch(
                            "offers/show",
                            this.form.offer?.id
                        );
                    this.offer = response?.data?.data ?? "";
                } else if (type === "performanceRecord") {
                    if (event)
                        response = await this.$store.dispatch(
                            "performanceRecords/show",
                            {
                                id: this.form.performanceRecord?.id,
                            }
                        );
                    this.performanceRecord = response?.data ?? "";
                }
            } catch (e) {
                console.log(e);
            }
            let json = {};
            try {
                json = JSON.parse(this.json);
            } catch (e) {
            } finally {
                json["user"] = this.user;
                json["invoice"] = this.invoice;
                json["offer"] = this.offer;
                json["performanceRecord"] = this.performanceRecord;
                this.codeString = JSON.stringify(json);
            }
        },
        /**
         * fetch the listing of user profile, invoice, offer and performance record if not already fetched
         */
        async fetchListings() {
            try {
                if (!this.userProfiles?.data?.length)
                    await this.$store.dispatch("userProfile/list");
                if (!this.invoices?.data?.length)
                    await this.$store.dispatch("invoices/list");
                if (!this.offers?.data?.length)
                    await this.$store.dispatch("offers/list");
                if (!this.performanceRecords?.data?.length)
                    await this.$store.dispatch("performanceRecords/list");
            } catch (e) {
                console.log(e);
            }
        },
        // calls the download orignal file API from document service and downloads the file
        async downloadOriginalFile() {
            try {
                this.$store.commit("isLoading", true);
                const response = await this.$store.dispatch(
                    "documentService/downloadOriginalFile",
                    {
                        id: this.$route.params.id,
                        documentType: "docx",
                        filename: this.name,
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * Adds all the variables(key,value) to the payload and makes an API request
         */
        async generatePdf(documentType) {
            let variables = {};
            if (this.jsonInput) {
                try {
                    localStorage.setItem(this.$route.params.id, this.json);
                    variables = JSON.parse(this.json);
                } catch (e) {
                    alert(e);
                    return;
                }
            }
            try {
                const payload = {
                    id: this.$route.params.id,
                    output: documentType,
                };
                if (this.jsonInput) {
                    for (let key in variables) {
                        payload[key] = this.parseJson(variables[key]);
                    }
                } else {
                    this.variables.forEach((variable) => {
                        payload[variable.key] = this.parseJson(variable.value);
                    });
                }
                this.$store.commit("isLoading", true);
                const filename =
                    (this.name.match(/^(.*?)\.docx|^(.*?)\.pdf/)?.[1] ??
                        this.name) +
                    "." +
                    documentType;
                await this.$store.dispatch("documentService/processTemplate", {
                    data: payload,
                    filename: filename,
                    documentType: documentType,
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * Parses the string to JSON and returns it, if the string cannot be parsed to JSON the original value is returned
         * @param {value} the string to be parsed to JSON
         */
        parseJson(value) {
            try {
                return JSON.parse(value);
            } catch (e) {
                return value;
            }
        },
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const documentResponse = await this.$store.dispatch(
                "documentService/show",
                this.$route.params.id
            );
            this.name = documentResponse?.data?.name;
            const response = await this.$store.dispatch(
                "documentService/documentPreviewFiles",
                this.$route.params.id
            );
            this.$store
                .dispatch("documentService/variables", {
                    id: this.$route.params.id,
                })
                .then((res) => {
                    if (res?.data instanceof Array) {
                        this.variables = res.data.map((variable) => {
                            return {
                                key: variable,
                                value: "",
                            };
                        });
                    }
                });
            var reader = new window.FileReader();
            reader.readAsDataURL(response.data);
            reader.onload = function () {
                const imageElement = document.getElementById(
                    "document-preview-image"
                );
                var imageDataUrl = reader.result;
                imageElement.setAttribute("src", imageDataUrl);
            };

            const jsonData = localStorage.getItem(this.$route.params.id);
            if (jsonData) {
                this.jsonInput = true;
                this.codeString = jsonData;
                this.json = jsonData;
                const jsonIntoObject = JSON.parse(this.codeString);
                await this.fetchListings();
                if (jsonIntoObject.user) {
                    this.userProfiles?.data.map((up) => {
                        if (up.id === jsonIntoObject.user.id) {
                            this.form.user = up;
                            return;
                        }
                    });
                }
                if (jsonIntoObject.invoice) {
                    this.invoices?.data.map((invoice) => {
                        if (invoice.id === jsonIntoObject.invoice.id) {
                            this.form.invoice = invoice;
                            return;
                        }
                    });
                }
                if (jsonIntoObject.offer) {
                    this.offers?.data.map((offer) => {
                        if (offer.id === jsonIntoObject.offer.id) {
                            this.form.offer = offer;
                            return;
                        }
                    });
                }
                if (jsonIntoObject.performanceRecord) {
                    this.performanceRecords?.data.map((pf) => {
                        if (pf.id === jsonIntoObject.performanceRecord.id) {
                            this.form.performanceRecord = pf;
                            return;
                        }
                    });
                }
            }
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
};
</script>

<style scoped></style>
