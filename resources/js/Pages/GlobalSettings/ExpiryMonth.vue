<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="lg:w-1/2">
            <form novalidate @submit.prevent="save">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Save Expiry Month") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    type="number"
                                    min="1"
                                    max="12"
                                    v-model="expiryMonth"
                                    :error="errors.expiryMonth"
                                    :label="$t('Expiry Month')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="flex items-center justify-end mt-4"
                    v-if="$can(`${$route.meta.permission}.create`)"
                >
                    <loading-button :loading="isLoading" class="secondary-btn">
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
import TextInput from "../../Components/TextInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import { mapGetters } from "vuex";

export default {
    mounted() {
        this.refresh();
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
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
                    text: "Expiry Month",
                    active: true,
                },
            ],
            expiryMonth: "",
        };
    },
    methods: {
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "globalSettings/list"
                );
                this.expiryMonth = response?.data?.expiryMonth ?? 0;
            } catch (e) {
                console.log(e);
            }
        },
        async save() {
            if (this.expiryMonth < 0 || this.expiryMonth > 12) {
                this.$store.commit("errors", {
                    expiryMonth:
                        "Expiry month must be less 12 and greater than 0",
                });
                return;
            }
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("globalSettings/save", {
                    expiryMonth: this.expiryMonth,
                });
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
