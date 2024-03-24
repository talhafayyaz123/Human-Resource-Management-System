<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form>
            <div class="card my-5">
                <div class="card-header">
                    <h3 class="card-title"></h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap">
                        <div class="pb-8 pr-6 w-full lg:w-1/4">
                            <label class="form-label font-bold"
                                >{{ $t(`Offer Request Number`) }}:</label
                            >
                            <p>{{ offer.offerRequestNumber }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4 capitalize">
                            <label class="form-label font-bold"
                                >{{ $t("Receiver Type") }}:</label
                            >
                            <p>{{ offer.receiverType }}</p>
                        </div>
                        <div class="pb-8 pr-6 w-full lg:w-1/4 capitalize">
                            <label class="form-label font-bold"
                                >{{ $t("Status") }}:</label
                            >
                            <p>{{ offer.requestStatus ?? "-" }}</p>
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
    computed: {
        ...mapGetters("auth", {
            users: "users",
        }),
    },
    components: {
        SaleOffer,
        PageHeader
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
                    to: "/offer-requests",
                },
                {
                    text: "Offer Requests",
                    to: "/offer-requests?page="+this.$route.query.page,
                },
                {
                    text: "Show",
                    active: true,
                },
            ],
        };
    },
    async mounted() {
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "offerRequests/show",
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
