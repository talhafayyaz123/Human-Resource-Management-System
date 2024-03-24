<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Ticket System Settings ") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-if="isApiCalled && selectedUsers"
                                    v-model="selectedUsers"
                                    :showLabels="false"
                                    @select="
                                        (selectedOption, id) => {
                                            this.form.users = [
                                                ...selectedUsers,
                                                selectedOption,
                                            ];
                                        }
                                    "
                                    :required="true"
                                    :options="users"
                                    :multiple="true"
                                    :textLabel="$t('Users')"
                                    label="first_name"
                                    trackBy="id"
                                    moduleName="auth"
                                    :search-param-name="'search_string'"
                                    :customLabel="customLabel"
                                >
                                    <template #beforeList>
                                        <div
                                            class="grid p-2 gap-2"
                                            style="grid-template-columns: 50% 50%"
                                        >
                                            <p class="font-bold">
                                                {{ $t("First Name") }}
                                            </p>
                                            <p class="font-bold">
                                                {{ $t("Last Name") }}
                                            </p>
                                        </div>
                                        <hr />
                                    </template>
                                    <template #singleLabel="{ props }">
                                        <p>
                                            {{ props.option.first_name }}
                                            {{ props.option.last_name }}
                                        </p>
                                    </template>
                                    <template #option="{ props }">
                                        <div
                                            class="grid"
                                            style="grid-template-columns: 50% 50%"
                                        >
                                            <p class="overflow-text-users-table">
                                                {{ props.option.first_name }}
                                            </p>
                                            <p class="overflow-text-users-table">
                                                {{ props.option.last_name }}
                                            </p>
                                        </div>
                                    </template>
                                </MultiSelectInput>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link
                    to="/ticket-system-settings"
                    class="primary-btn mr-3"
                >
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
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
</template>

<script>
import LoadingButton from "../../Components/LoadingButton.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", {
            users: "users",
        }),
        ...mapGetters("globalNotification", ["globalNotification"]),

        selectedUsers() {
            return this.users.filter((user) =>
                this.globalNotification.includes(user.id)
            );
        },
    },
    components: {
        LoadingButton,
        MultiSelectInput,
        PageHeader,
    },
    async mounted() {
        this.$store.commit("showLoader", true);
        await this.$store.dispatch("auth/list", {
            limit_start: 0,
            limit_count: 25,
            type: "employee",
        });

        await this.$store.dispatch("globalNotification/list");
        this.isApiCalled = true;
        this.$store.commit("showLoader", false);
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Ticket System Settings",
                    to: "/ticket-system-settings",
                },
                {
                    text: this.$t("Create"),
                    active: true,
                },
            ],
            form: {
                users: [],
            },
            isApiCalled: false,
        };
    },
    methods: {
        customLabel(props) {
            return `${props?.first_name ?? ""} ${props?.last_name ?? ""}`;
        },

        async store() {
            try {
                this.$store.commit("isLoading", true);

                const userIds = this.form.users.map((user) => user.id);

                await this.$store.dispatch("globalNotification/create", {
                    userIds,
                });
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>
