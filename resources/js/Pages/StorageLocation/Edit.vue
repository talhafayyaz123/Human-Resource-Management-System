<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div class="lg:w-1/2">
            <form @submit.prevent="update">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            {{ $t("Update Storage Locations Details") }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.storageLocation"
                                    :error="errors.storageLocation"
                                    :label="$t('Storage Location')"
                                    :required="true"
                                />
                                <!-- :error="form.errors.name" -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <router-link
                        :to="`/storage-locations?page=${returnPage}`"
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
            returnPage:'',
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Storage Location",
                    to: "/storage-locations?page="+this.$route.query.page,
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                storageLocation: "",
            },
        };
    },
    mounted() {
        this.refresh();
    },
    methods: {
        async refresh() {
            if(this.$route.query.page){
                this.returnPage=this.$route.query.page; 
            }
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "storageLocations/show",
                    this.$route.params.id
                );
                let storage = response?.data?.storage ?? {};
                this.form = {
                    storageLocation: storage.storageLocation,
                };
            } catch (e) {}
            finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            this.$store.commit("isLoading", true);
            await this.$store.dispatch("storageLocations/update", {
                id: this.$route.params.id,
                data: { ...this.form },
            });
            await this.$store.dispatch("storageLocations/list");
            this.$router.push("/storage-locations?page="+this.returnPage);
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
                            "storageLocations/destroy",
                            this.$route.params.id
                        );
                        await this.$store.dispatch("storageLocations/list");
        
                        this.$router.push("/storage-locations?page="+this.returnPage);
                    } catch (e) {}
                }
            });
        },
    },
};
</script>
