<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <trashed-message
            v-if="modelData?.deleted_at"
            class="mb-6"
            @restore="restore"
        >
            {{ $t("This department has been deleted") }}.
        </trashed-message>

        <form novalidate @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Update Departments Details") }}
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
                            <div class="checkbox-group">
                                <input
                                    id="top-level"
                                    class="checkbox-input"
                                    v-model="form.isTopLevel"
                                    type="checkbox"
                                />
                                <label for="top-level" class="checkbox-label"
                                    >{{ $t("Is Top Level") }}:</label
                                >
                            </div>
                        </div>
                        <div class="w-full" v-if="!form.isTopLevel">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.departmentHeadId"
                                    :key="form.departmentHeadId"
                                    :options="userProfiles?.data"
                                    class=""
                                    :multiple="false"
                                    :textLabel="$t('Department Head')"
                                    label="employee"
                                    trackBy="id"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>
                        <div
                            class="w-full"
                            v-if="form.isTopLevel && isApiCalled"
                        >
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    v-model="form.departments"
                                    :options="departments?.data ?? []"
                                    :multiple="true"
                                    :text-label="$t('Departments')"
                                    label="name"
                                    trackBy="id"
                                    moduleName="departments"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.departmentTeams"
                                    :key="form.departmentTeams"
                                    :options="teams?.data"
                                    class=""
                                    :multiple="true"
                                    :textLabel="$t('Department Teams')"
                                    label="name"
                                    trackBy="id"
                                    moduleName="teams"
                                />
                            </div>
                        </div>
                        <div class="w-full" v-if="form.isTopLevel">
                            <div class="form-group">
                                <MultiSelectInput
                                    class=""
                                    v-model="form.employees"
                                    :key="form.employees"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="true"
                                    :text-label="$t('Employees')"
                                    label="employee"
                                    trackBy="userId"
                                    moduleName="userProfile"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/user/departments" class="primary-btn mr-3">
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
                    {{ $t("Delete Departments") }}
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
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    async mounted() {
        this.$store.commit("showLoader", true);
        try {
            await this.$store.dispatch("userLocations/list", {
                perPage: 10,
                page: 1,
            });
            await this.$store.dispatch("userProfile/list", {
                perPage: 10,
                page: 1,
            });
            await this.$store.dispatch("userTeams/list", {
                perPage: 10,
                page: 1,
            });
        } catch (e) {
        } finally {
        }
        this.refresh();
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("userLocations", ["locations"]),
        ...mapGetters("userTeams", ["teams"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("userDepartments", ["departments"]),
    },
    components: {
        LoadingButton,
        TextInput,
        TrashedMessage,
        MultiSelectInput,
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
                    to: "/user/departments",
                },
                {
                    text: this.$t("Departments"),
                    to: "/user/departments",
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            isLoaded: false,
            modelData: {},
            isApiCalled: false,
            form: {
                name: "",
                departmentHeadId: "",
                departmentTeams: [],
                isTopLevel: false,
                employees: [],
                departments: "",
            },
        };
    },
    methods: {
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "userDepartments/show",
                    this.$route.params.id
                );
                this.modelData = response?.data?.data ?? {};
                document.title = "Edit Department " + this?.modelData?.name;
                this.form = {
                    name: this.modelData.name,
                    departmentHeadId: this.userProfiles?.data?.find(
                        (user) => user.id == this.modelData.departmentHeadId
                    ),
                    departmentTeams: this.modelData.departmentTeams?.map(
                        (departmentTeam) => {
                            return {
                                ...(this.teams?.data?.find(
                                    (team) => team.id == departmentTeam?.id
                                ) ?? {}),
                            };
                        }
                    ),
                    departments: this.modelData.departments,
                    isTopLevel: this.modelData.isTopLevel,
                    employees: this.modelData.employees,
                };
                this.$nextTick(() => {
                    this.show = true; //necessary because the multiselect dropdown does not work when the page loads, so we have to add a little delay with nextTick
                });
                await this.$store.dispatch("userDepartments/list");
                this.isApiCalled = true;
            } catch (e) {
            } finally {
                this.isLoaded = true;
                 this.$store.commit("showLoader", false);
            }
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("userDepartments/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        departmentHeadId: this.form.isTopLevel
                            ? null
                            : this.form?.departmentHeadId?.userId,
                        departmentTeams: this.form?.departmentTeams.map(
                            (item) => item.id
                        ),
                        employees: this.form?.employees.map(
                            (employee) => employee.id
                        ),
                    },
                });
                await this.$store.dispatch("userDepartments/list");
                this.$router.push("/user/departments");
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
                            "userDepartments/destroy",
                            this.$route.params.id
                        );
                        await this.$store.dispatch("userDepartments/list");
                        this.$router.push("/user/departments");
                    } catch (e) {}
                }
            });
        },
        restore() {
            if (confirm("Are you sure you want to restore this Departments?")) {
            }
        },
    },
};
</script>
