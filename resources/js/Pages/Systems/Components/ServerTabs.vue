<template>
    <div>
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Add Multiple Servers") }}</h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                class=""
                                v-model="form.serverIp"
                                :label="$t('IP Address')"
                                :error="errors?.serverIp ?? ''"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                class=""
                                v-model="form.port"
                                :label="$t('Port')"
                                :error="errors?.port ?? ''"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                class=""
                                :label="$t('User Address')"
                                v-model="form.userAddress"
                                :error="errors?.userAddress ?? ''"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                class=""
                                :label="$t('Password for servers')"
                                :type="`password`"
                                v-model="form.password"
                                :error="errors?.password ?? ''"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <div v-if="isSystemCloud" class="checkbox-group">
                                <input
                                    type="checkbox"
                                    class="checkbox-input"
                                    v-model="form.isMaster"
                                    id="master"
                                />
                                <label for="master" class="checkbox-label">
                                    <span class="">{{ $t("Is Master") }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
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
                    <th class="pb-4 pt-6 px-6">{{ $t("IP Address") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("Port") }}</th>
                    <th class="pb-4 pt-6 px-6">{{ $t("User Address") }}</th>
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
                            ><font-awesome-icon icon="fa-regular fa-trash-can"
                        /></a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
export default {
    components: { TextInput, LoadingButton },
    data() {
        return {
            form: {
                serverIp: "",
                port: "",
                userAddress: "",
                password: "",
                isMaster: "",
            },
            serversArray: this.cloudServers ?? [],
            errors: {},
        };
    },
    props: { cloudServers: Array, isSystemCloud: false },
    watch: {
        serversArray: {
            handler: function (val, oldVal) {
                this.$emit("serverValueChanged", val);
            },
            deep: true,
        },
        cloudServers: {
            immediate: true,
            handler: function (newValue) {
                if (newValue) {
                    this.serversArray = newValue;
                }
            },
        },
    },
    methods: {
        addServers() {
            this.errors = {};

            if (!this.form.serverIp) {
                this.errors.serverIp = "Server IP is required.";
            }

            if (!this.form.port) {
                this.errors.port = "Port is required.";
            }

            if (!this.form.userAddress) {
                this.errors.userAddress = "User Address is required.";
            }

            if (!this.form.password) {
                this.errors.password = "Password is required.";
            }
            if (Object.keys(this.errors).length === 0) {
                this.serversArray.push(this.form);
                this.form = {
                    serverIp: "",
                    port: "",
                    userAddress: "",
                    password: "",
                };
            }
        },
    },
};
</script>

<style></style>
