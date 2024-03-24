<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update Payment Period Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.name"
                                    :error="errors.name"
                                    :required="true"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.billingCycle"
                                    :error="errors.billingCycle"
                                    type="number"
                                    min="0"
                                    :label="$t('Billing Cycle (Days)')"
                                />
                                <!-- :error="form.errors.name" -->
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <label class="inline-flex items-center mb-3">
                                    <input
                                        type="checkbox"
                                        class="form-checkbox h-5 w-5 text-gray-600"
                                        v-model="form.isDefault"
                                    />
                                    <span class="ml-2 text-gray-700">{{
                                        $t("Is Default")
                                    }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/product-periods" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.edit`)"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Update") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
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
                    text: "Payment Period",
                    to: "/product-periods",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                name: "",
                billingCycle: 0,
                isDefault: false,
            },
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "periods/show",
                    this.$route.params.id
                );
                this.period = response?.data?.period ?? {};
                this.form = {
                    name: this.period.name,
                    billingCycle: this.period.billingCycle,
                    isDefault: this.period.isDefault,
                };
            } catch (e) {}
            finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("periods/update", {
                id: this.$route.params.id,
                data: { ...this.form },
            });
            await this.$store.dispatch("periods/list");
            this.$router.push("/product-periods");
        },
        async destroy() {
            this.$swal({
                title: this.$t("Do you want to delete this record?"),
                text: this.$t("You can't revert your action"),
                type: "warning",
                showCancelButton: true,
                confirmButtonText: this.$t("Yes delete it!"),
                cancelButtonText: this.$t("No"),
                showCloseButton: true,
                showLoaderOnConfirm: true,
            }).then(async (result) => {
                if (result.isConfirmed === true) {
                    try {
                        await this.$store.dispatch(
                            "periods/destroy",
                            this.$route.params.id
                        );
                        await this.$store.dispatch("periods/list");
        
                        this.$router.push("/product-periods");
                    } catch (e) {}
                }
            });
        },
    },
};
</script>
