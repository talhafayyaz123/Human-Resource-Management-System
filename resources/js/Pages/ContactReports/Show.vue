<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap">
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Subject") }}:</label
                            >
                            <p>{{ form.subject }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Type") }}:</label
                            >
                            <p>{{ form.type }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Priority") }}:</label
                            >
                            <p>{{ form.priority }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Contact Type") }}:</label
                            >
                            <p>{{ form.contactType }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Initiated By") }}:</label
                            >
                            <p>{{ form.initiatedBy }}</p>
                        </div>
                        <div
                            v-if="form.resubmitOn"
                            class="pb-8 pr-6 w-full lg:w-1/4"
                        >
                            <label class="form-label font-bold"
                                >{{ $t("Resubmit On") }}:</label
                            >
                            <p>
                                {{ formatDateLite(new Date(form.resubmitOn)) }}
                            </p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Report Category") }}:</label
                            >
                            <p>
                                {{
                                    this.reportCategory?.find(
                                        (category) => category.id == form.categoryId
                                    )?.name ?? "-"
                                }}
                            </p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Company") }}:</label
                            >
                            <p>
                                {{ this.form.companyId?.companyName }}
                            </p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Created By") }}:</label
                            >
                            <p>
                                {{
                                    (this.form?.createdByEmployee?.first_name ??
                                        "") +
                                    " " +
                                    (this.form?.createdByEmployee?.last_name ?? "")
                                }}
                            </p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Assignee") }}:</label
                            >
                            <p>
                                {{
                                    (this.form?.assignee?.first_name ?? "") +
                                    " " +
                                    (this.form?.assignee?.last_name ?? "")
                                }}
                            </p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Employees") }}:</label
                            >
                            <p>
                                {{
                                    getCommaSeparatedStringFromArray(
                                        this.form.employees
                                    )
                                }}
                            </p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Talked To People") }}:</label
                            >
                            <p>
                                {{
                                    getCommaSeparatedStringFromArray(
                                        this.form.companyEmployees
                                    )
                                }}
                            </p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Text") }}:</label
                            >
                            <p v-html="htmlFromText(this.form.text)"></p>
                        </div>
    
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Contact Report Sources") }}:</label
                            >
                            <div class="tag-container">
                                <span
                                    v-for="source in this.form.contactSources"
                                    :key="source.id"
                                    class="tag"
                                    >{{ source.name }}</span
                                >
                            </div>
                        </div>
    
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Files") }}:</label
                            >
                            <div class="tag-container">
                                <span
                                    v-for="file in this.form.files"
                                    :key="file.id"
                                    class="tag"
                                    >{{ file.viewable_name }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import Quill from "quill";
import PageHeader from "@/Components/Layouts/Page-header.vue";

export default {
    components: {
        PageHeader,
    },
    computed: {
        ...mapGetters("auth", {
            users: "users",
        }),
        ...mapGetters("companies", {
            companies: "companies",
            leads: "leads",
        }),
        ...mapGetters("companyEmployees", {
            employees: "users",
        }),
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Sales",
                    to: "/contact-reports",
                },
                {
                    text: "Contact Reports",
                    to: "/contact-reports?page="+this.$route.query.page,
                },
                {
                    text: this.$t("Show"),
                    active: true,
                },
            ],
            reportCategory: [],
            modelData: {},
            returnPage:'',
            form: {
                subject: "",
                text: "",
                type: "",
                priority: "",
                categoryId: "",
                contactType: "",
                initiatedBy: "",
                companyId: "",
                createdByEmployee: "",
                companyEmployees: [],
                employees: [],
                resubmitOn: "",
                assignee: "",
            },
        };
    },
    methods: {
        /**
         * Converts quill delta into html string, which is used with v-html to display quill editor text
         * @param {text} quill delta to be converted to html
         * @returns {string} html string
         */
        htmlFromText(text) {
            try {
                let article = document.createElement("article");
                let quill = new Quill(article, { readOny: true });
                quill.setContents(JSON.parse(text));
                return quill.root.innerHTML;
            } catch (err) {
                return "";
            }
        },
        /**
         * Creates and returns a comma separated string from an array based on first_name and last_name
         * @param {data} the array that needs to be converted to comma separated string
         * @returns string
         */
        getCommaSeparatedStringFromArray(data) {
            let str = "";
            for (let i = 0; i < data?.length ?? 0; i++) {
                str += `${data[i]?.first_name ?? ""} ${
                    data[i]?.last_name ?? ""
                }${data[i + 1] ? "," : ""}`;
            }
            return str ? str : "-";
        },
    },
    async mounted() {
        if(this.$route.query.page){
             this.returnPage=this.$route.query.page; 
            }
        try {
            this.$store.commit("showLoader", true);
            await this.$store
                .dispatch("reportCategories/list", {
                    perPage: 0,
                    page: 1,
                })
                .then((res) => {
                    this.reportCategory = res?.data?.data ?? [];
                });
            if (!this.companies?.data?.length)
                await this.$store.dispatch("companies/list", {
                    perPage: 25,
                    page: 1,
                });
            if (!this.leads?.data?.length)
                this.$store.dispatch("companies/list", {
                    perPage: 25,
                    page: 1,
                    customerType: "lead",
                });
            if (!this.users?.length)
                await this.$store.dispatch("auth/list", {
                    limit_start: 0,
                    limit_count: 25,
                    type: "employee",
                });
            const response = await this.$store.dispatch(
                "contactReports/show",
                this.$route.params.id
            );
            this.modelData = response?.data?.modelData ?? {};
            this.form = {
                subject: this.modelData.subject,
                text: this.modelData.text,
                type: this.modelData.type,
                priority: this.modelData.priority,
                categoryId: this.modelData.categoryId,
                contactType: this.modelData.contactType,
                initiatedBy: this.modelData.initiatedBy,
                resubmitOn: "",
                companyId: "",
                createdByEmployee:
                    this.users?.find(
                        (user) => user.id == this.modelData.createdByEmployee
                    ) ?? "",
                companyEmployees: [],
                employees:
                    this.users?.filter((user) =>
                        this.modelData?.employees?.some(
                            (employee) => employee == user.id
                        )
                    ) ?? [],
                assignee:
                    this.users?.find(
                        (user) => user.id == this.modelData.assignee
                    ) ?? "",
                contactSources: this.modelData.contactSources,
                files: this.modelData.uploadedFiles,
            };
            if (this.modelData.resubmitOn) {
                this.form.resubmitOn = new Date(this.modelData.resubmitOn);
            }
            this.$nextTick(() => {
                this.form.companyId =
                    this.modelData.type === "lead"
                        ? this.leads?.data?.find(
                              (company) =>
                                  company.id == this.modelData.companyId
                          ) ?? ""
                        : this.companies?.data?.find(
                              (company) =>
                                  company.id == this.modelData.companyId
                          ) ?? "";
            });
        } catch (e) {
            console.log(e);
        }
        finally {
            this.$store.commit("showLoader", false);
        }
    },
};
</script>

<style scoped>
.tag {
    display: inline-block;
    padding: 6px 12px;
    margin-right: 8px;
    margin-bottom: 8px;
    margin-top: 8px;
    border-radius: 16px;
    background-color: #41b883;
    color: #fff;
    font-size: 14px;
    font-weight: 500;
}
</style>
