<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="lg:w-1/2">
            <form @submit.prevent="update">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Update Software Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.name"
                                    :error="errors.name"
                                    :label="$t('Name')"
                                    :required="true"
                                />
                                <!-- :error="form.errors.name" -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <router-link
                        to="/product-softwares"
                        class="primary-btn mr-3"
                    >
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
                            <CustomIcon name="updateIcon" />
                        </span>
                        {{ $t("Update") }}
                    </loading-button>
                </div>
            </form>
        </div>
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
                    text: "Software",
                    to: "/product-softwares",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                name: "",
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
                    "softwares/show",
                    this.$route.params.id
                );
                this.software = response?.data?.software ?? {};
                this.form = {
                    name: this.software.name,
                };
            } catch (e) {}
            finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("softwares/update", {
                id: this.$route.params.id,
                data: { ...this.form },
            });
            await this.$store.dispatch("softwares/list");
            this.$router.push("/product-softwares");
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
                            "softwares/destroy",
                            this.$route.params.id
                        );
                        await this.$store.dispatch("softwares/list");
        
                        this.$router.push("/product-softwares");
                    } catch (e) {}
                }
            });
        },
    },
};
</script>
