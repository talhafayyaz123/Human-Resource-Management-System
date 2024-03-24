<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <trashed-message
            v-if="modelData?.deleted_at"
            class="mb-6"
            @restore="restore"
        >
            {{ $t("This location has been deleted") }}.
        </trashed-message>

        <form novalidate @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update Locations Details") }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.name"
                                    :error="errors.name"
                                    class=""
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.street"
                                    :error="errors.street"
                                    class=""
                                    :label="$t('Street')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.address"
                                    :error="errors.address"
                                    class=""
                                    :label="$t('Address')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.zipCode"
                                    :error="errors.zipCode"
                                    class=""
                                    :label="$t('Zip Code')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.city"
                                    :error="errors.city"
                                    class=""
                                    :label="$t('City')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.state"
                                    :error="errors.state"
                                    class=""
                                    :label="$t('State')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.country"
                                    :error="errors.country"
                                    class=""
                                    :label="$t('Country')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link
                    :to="`/user/locations?page=${this.$route.query.page}`"
                    class="primary-btn mr-3"
                >
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>

                <button
                    v-if="!modelData.deleted_at"
                    class="delete-btn mr-3"
                    tabindex="-1"
                    type="button"
                    @click="destroy"
                >
                    <span class="mr-1">
                        <CustomIcon name="DeleteIcon" />
                    </span>
                    {{ $t("Delete Locations") }}
                </button>
                <loading-button :loading="isLoading" class="secondary-btn">
                    <span class="mr-1">
                        <CustomIcon name="updateIcon" />
                    </span>
                    {{ $t("Update") }}
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import TrashedMessage from "@/Components/TrashedMessage.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
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
        TrashedMessage,
        PageHeader,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Personal Management"),
                    to: "/user/locations",
                },
                {
                    text: this.$t("Locations"),
                    to: `/user/locations?page=${this.$route.query.page}`,
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            modelData: {},
            form: {
                name: "",
                street: "",
                address: "",
                zipCode: "",
                state: "",
                city: "",
                country: "",
            },
        };
    },
    methods: {
        async refresh() {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "userLocations/show",
                    this.$route.params.id
                );
                this.modelData = response?.data?.data ?? {};
                this.form = {
                    name: this.modelData.name,
                    street: this.modelData.street,
                    address: this.modelData.address,
                    zipCode: this.modelData.zipCode,
                    state: this.modelData.state,
                    city: this.modelData.city,
                    country: this.modelData.country,
                };
                document.title = "Edit Location " + this?.form?.name;
            } catch (e) {
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("userLocations/update", {
                    id: this.$route.params.id,
                    data: { ...this.form },
                });
                await this.$store.dispatch("userLocations/list");
                this.$router.push(
                    `/user/locations?page=${this.$route.query.page}`
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
                            "userLocations/destroy",
                            this.$route.params.id
                        );
                        await this.$store.dispatch("userLocations/list");
                        this.$router.push(
                            `/user/locations?page=${this.$route.query.page}`
                        );
                    } catch (e) {}
                }
            });
        },
        restore() {
            if (confirm("Are you sure you want to restore this Locations?")) {
            }
        },
    },
};
</script>
