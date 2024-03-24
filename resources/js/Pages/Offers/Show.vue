<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Offer Detail") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap">
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{
                                    $t(
                                        `${
                                            module === "offer"
                                                ? "Offer"
                                                : "Offer Confirmation"
                                        } Number`
                                    )
                                }}:</label
                            >
                            <p>{{ offer.offerNumber }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Terms") }}:</label
                            >
                            <p>
                                {{
                                    termsOfPayment?.data?.find(
                                        (terms) => terms.id == offer.term
                                    )?.offerText ?? "-"
                                }}
                            </p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Receiver Type") }}:</label
                            >
                            <p>{{ offer.receiverType }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Project") }}:</label
                            >
                            <p>
                                {{
                                    projects?.data?.find(
                                        (project) => project.id == offer.project
                                    )?.name ?? "-"
                                }}
                            </p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Offer Type") }}:</label
                            >
                            <p>{{ offer.type }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t("Created By") }}:</label
                            >
                            <p>{{ createdBy }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <SaleOffer v-if="offer?.id" :key="offer.id" :offer="offer" />
        </form>
    </div>
</template>

<script>
import SaleOffer from "../../Components/Offer.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    props: {
        module: {
            type: String,
            default: "offer",
        },
    },
    computed: {
        ...mapGetters("termsOfPayment", {
            termsOfPayment: "termsOfPayment",
        }),
        ...mapGetters("projects", ["projects"]),
        ...mapGetters("auth", {
            users: "users",
        }),
    },
    components: {
        SaleOffer,
        PageHeader,
    },
    data() {
        return {
            offer: {},
            createdBy: "",
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Sales",
                    to: "/offers",
                },
                {
                    text: "Offer",
                    to: "/offers?page="+this.$route.query.page,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
        };
    },
    async mounted() {
       
        try {
            this.$store.commit("showLoader", true);
            if (!this.termsOfPayment?.data?.length)
                await this.$store.dispatch("termsOfPayment/list", {
                    perPage: 25,
                    page: 1,
                });
            if (!this.projects?.data?.length)
                await this.$store.dispatch("projects/list", {
                    perPage: 25,
                    page: 1,
                });
            const response = await this.$store.dispatch(
                "offers/show",
                this.$route.params.id
            );
            await this.$store.dispatch("auth/list", {
                limit_start: 0,
                limit_count: 25,
                type: "employee",
            });
            this.offer = response?.data?.data ?? {};
            const createdBy =
                this.users?.find((user) => user.id == this.offer.createdBy) ??
                "";
            if (createdBy)
                this.createdBy =
                    createdBy["first_name"] + " " + createdBy["last_name"];
        } catch (e) {
            console.log(e);
        }
        finally {
            this.$store.commit("showLoader", false);
        }
    },
};
</script>

<style></style>
