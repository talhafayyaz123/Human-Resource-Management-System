<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <trashed-message
            v-if="modelData?.deleted_at"
            class="mb-6"
            @restore="restore"
        >
            {{ $t("This record has been deleted") }}.
        </trashed-message>

        <form novalidate @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update Invoice Type Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.name"
                                    :error="errors?.name"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link
                    :to="`/travel-expenses/invoice-types?page=${this.$route.query.page}`"
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
import TrashedMessage from "@/Components/TrashedMessage.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    async mounted() {
        this.refresh();
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
    },
    components: {
        LoadingButton,
        TextInput,
        TrashedMessage,
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
                    text: "Invoice Types",
                    to: `/travel-expenses/invoice-types?page=${this.$route.query.page}`,
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            isLoaded: false,
            modelData: {},
            form: {
                name: "",
            },
        };
    },
    methods: {
        filterOptions(options, selectedValues) {
            return options.filter(
                (option) => !selectedValues.includes(option.value)
            );
        },
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "travelExpenseInvoiceType/show",
                    this.$route.params.id
                );
                this.modelData = response?.data?.data ?? {};
                this.form = {
                    name: this.modelData.name,
                };
            } catch (e) {
            } finally {
                this.isLoaded = true;
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("travelExpenseInvoiceType/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                    },
                });
                await this.$store.dispatch("travelExpenseInvoiceType/list");
                this.$router.push(
                    `/travel-expenses/invoice-types?page=${this.$route.query.page}`
                );
            } catch (e) {}
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
                            "travelExpenseInvoiceType/destroy",
                            this.$route.params.id
                        );
                        await this.$store.dispatch(
                            "travelExpenseInvoiceType/list"
                        );
                        this.$router.push(
                            `/travel-expenses/invoice-types?page=${this.$route.query.page}`
                        );
                    } catch (e) {}
                }
            });
        },
    },
};
</script>
