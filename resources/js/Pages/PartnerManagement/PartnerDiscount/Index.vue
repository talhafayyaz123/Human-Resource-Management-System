<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="lg:w-1/2">
            <form @submit.prevent="store">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Fill Partner Discount") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="discountRate"
                                    :error="errors?.discountRate"
                                    type="number"
                                    step="any"
                                    :label="$t('Partner Discount in %')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
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
    </div>
</template>

<script>
import LoadingButton from "../../../Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import TextareaInput from "../../../Components/TextareaInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import TextInput from "@/Components/TextInput.vue";;

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },

    components: {
        TextInput,
        TextareaInput,
        LoadingButton,
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
                    text: "Partner Discount",
                    to: "/partner-management/discount",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            discountRate: "",
        };
    },
    mounted() {
        this.refresh();
    },

    methods: {
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "globalSettings/getPartnerDiscount"
                );
                this.discountRate = response?.data?.partnerDiscountRate ?? "";
            } catch (e) {
                console.log(e);
            }
        },
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch(
                    "globalSettings/savePartnerDiscount",
                    {
                        discountRate: this.discountRate,
                    }
                );
            } catch (e) {}
        },
    },
};
</script>
