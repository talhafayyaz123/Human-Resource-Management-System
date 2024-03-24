<template>
    <div>
        <PageHeader :items="breadcrumbItems" :optionalItems="optionalItems" />

        <form novalidate @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        {{ $t("Fill Department Details") }}
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
                        <div class="w-full" v-if="form.isTopLevel">
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
import { mapGetters } from "vuex";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("userTeams", ["teams"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("userDepartments", ["departments"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        PageHeader,
    },
    remember: "form",
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
                    text: "Create",
                    active: true,
                },
            ],
            form: {
                name: "",
                departmentHeadId: "",
                departmentTeams: [],
                isTopLevel: false,
                employees: [],
                departments: [],
            },
        };
    },
    mounted() {
        this.$store.dispatch("userLocations/list", {
            perPage: 10,
            page: 1,
        });
        this.$store.dispatch("userProfile/list", {
            perPage: 10,
            page: 1,
        });
        this.$store.dispatch("userTeams/list", {
            perPage: 10,
            page: 1,
        });
        this.$store.dispatch("userDepartments/list", {
            perPage: 10,
            page: 1,
        });
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("userDepartments/create", {
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
                });
                await this.$store.dispatch("userDepartments/list");
                this.$router.push("/user/departments");
            } catch (e) {}
        },
    },
};
</script>
