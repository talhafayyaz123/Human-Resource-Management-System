<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill System Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap">
                        <text-input
                            v-model="form.name"
                            :required="true"
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            :label="$t('Name')"
                            :error="errors?.name ?? ''"
                        />
                        <select-input
                            :required="true"
                            v-model="form.subType"
                            :key="form.subType"
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            :label="$t('Sub Type')"
                            :error="errors?.subType ?? ''"
                        >
                            <!-- :error="form.errors.subType" -->
                            <option value="public">
                                {{ $t("Public") }}
                            </option>
                            <option value="private">{{ $t("Private") }}</option>
                        </select-input>

                        <select-input
                            :required="true"
                            :key="form.instanceType"
                            v-model="form.instanceType"
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            :label="$t('Instance Type')"
                            :error="errors?.instanceType ?? ''"
                        >
                            <!-- :error="form.errors.instanceType" -->
                            <option value="development">
                                {{ $t("Development System") }}
                            </option>
                            <option value="test">
                                {{ $t("Test System") }}
                            </option>
                            <option value="productive">
                                {{ $t("Productive System") }}
                            </option>
                        </select-input>
                        <text-input
                            v-model="form.databaseType"
                            :required="true"
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            :label="$t('Database Type')"
                            :error="errors?.databaseType ?? ''"
                        />
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Add Multiple Servers") }}</h3>
                </div>
                <div class="card-body">
                    <div class="flex flex-wrap">
                        <text-input
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            v-model="serverForm.serverIp"
                            :label="$t('IP Address')"
                            :error="errors?.serverIp ?? ''"
                        />
                        <text-input
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            v-model="serverForm.port"
                            :label="$t('Port')"
                            :error="errors?.port ?? ''"
                        />
                        <text-input
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            :label="$t('User Address')"
                            v-model="serverForm.userAddress"
                            :error="errors?.userAddress ?? ''"
                        />
                        <text-input
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            :label="$t('Password for servers')"
                            :type="`password`"
                            v-model="serverForm.password"
                            :error="errors?.password ?? ''"
                        />
                    </div>
                    <div class="w-full pb-8 pr-6 flex flex-row-reverse">
                        <button
                            class="flex items-center btn-green"
                            role="button"
                            @click.prevent="addServers"
                        >
                            {{ $t("Add Server") }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-3">
                <table class="doc-table">
                    <tr class="text-left font-bold">
                        <th class="pb-4 pt-6 px-6">
                            {{ $t("IP Address") }}
                        </th>
                        <th class="pb-4 pt-6 px-6">{{ $t("Port") }}</th>
                        <th class="pb-4 pt-6 px-6">
                            {{ $t("User Address") }}
                        </th>
                        <th class="pb-4 pt-6 px-6">
                            {{ $t("Password for survers") }}
                        </th>
                        <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                    </tr>
                    <tr v-if="serversArray?.length === 0">
                        <td class="px-6 py-4 border-t" colspan="4">
                            {{ $t("No survers found") }}.
                        </td>
                    </tr>
                    <tr
                        v-else
                        v-for="(server, index) in serversArray"
                        :key="'location-' + index"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                    >
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                            >
                                {{ server.serverIp }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4 focus:text-indigo-500"
                            >
                                {{ server.port }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4"
                                tabindex="-1"
                            >
                                {{ server.userAddress }}
                            </a>
                        </td>
                        <td class="border-t">
                            <a
                                href="javascript:void(0)"
                                class="flex items-center px-6 py-4"
                                tabindex="-1"
                                style="text-security: disc"
                            >
                                {{ server.password }}
                            </a>
                        </td>

                        <td class="w-px border-t text-center">
                            <a
                                @click="serversArray.splice(index, 1)"
                                href="#"
                                class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                ><font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                            /></a>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="flex items-center justify-end mt-4">
                <router-link to="/database-cloud" class="primary-btn mr-3">
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
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
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import { mapGetters } from "vuex";

export default {
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Database Cloud",
                    to: "/database-cloud",
                },
                {
                    text: this.$t("Update"),
                    active: true,
                },
            ],
            form: {
                name: "",
                subType: "",
                instanceType: "",
                databaseType: "",
            },
            serverForm: {
                serverIp: "",
                port: "",
                userAddress: "",
                password: "",
            },
            serversArray: [],
        };
    },
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
        PageHeader,
    },
    mounted() {
        // fetch the form data based on the id from route params
        this.fetchData();
    },
    computed: {
        ...mapGetters(["isLoading", "errors"]),
    },
    methods: {
        /**
         * fetches the database cloud data from the show API and populates the 'form' and the 'serversArray'
         */
        async fetchData() {
            try {
                const response = await this.$store.dispatch(
                    "databaseCloud/show",
                    this.$route.params.id
                );
                const modelData = response?.data?.databaseCloud ?? {
                    name: "",
                    subType: "",
                    instanceType: "",
                    databaseType: "",
                };
                this.form = { ...modelData };
                this.serversArray = modelData?.cloudServers ?? [];
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * triggers the database cloud update API
         */
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("databaseCloud/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        cloudServers: this.serversArray,
                    },
                });
                this.$router.push("/database-cloud");
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * adds server details to the serversArray
         */
        addServers() {
            this.$store.commit("errors", {});

            if (!this.serverForm.serverIp) {
                this.$store.commit("errors", "Server IP is required.");
                return;
            }

            if (!this.serverForm.port) {
                this.$store.commit("errors", "Port is required.");
                return;
            }

            if (!this.serverForm.userAddress) {
                this.$store.commit("errors", "User Address is required.");
                return;
            }

            if (!this.serverForm.password) {
                this.$store.commit("errors", "Password is required.");
                return;
            }

            this.serversArray.push(this.serverForm);
            this.serverForm = {
                serverIp: "",
                port: "",
                userAddress: "",
                password: "",
            };
        },
    },
};
</script>

<style scoepd></style>
