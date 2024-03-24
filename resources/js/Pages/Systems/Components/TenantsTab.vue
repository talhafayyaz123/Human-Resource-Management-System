<template>
    <div>
        <div class="card my-5">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Tenant") }}</h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                :required="true"
                                :key="tenant.systemUser"
                                :textLabel="$t('Customer')"
                                v-model="tenant.systemUser"
                                :error="errors['tenant.systemUser']"
                                :options="customers"
                                :multiple="false"
                                label="companyName"
                                trackBy="id"
                                moduleName="companies"
                            >
                                <template #beforeList>
                                    <div
                                        class="grid p-2 gap-2"
                                        style="grid-template-columns: 60% 25%"
                                    >
                                        <p class="font-bold">
                                            {{ $t("Name") }}
                                        </p>
                                        <p class="font-bold">
                                            {{ $t("Type") }}
                                        </p>
                                    </div>
                                    <hr />
                                </template>
                                <template #option="{ props }">
                                    <div
                                        class="grid gap-2"
                                        style="grid-template-columns: 60% 25%"
                                    >
                                        <p class="overflow-text-users-table">
                                            {{ props.option.companyName }}
                                        </p>
                                        <p
                                            style="text-transform: capitalize"
                                            class="overflow-text-users-table"
                                        >
                                            {{ props.option.customerType }}
                                        </p>
                                    </div>
                                </template>
                            </MultiSelectInput>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                :required="true"
                                :textLabel="$t('Cloud System')"
                                v-model="tenant.systemCloud"
                                :options="cloudSystems"
                                :key="tenant.systemCloud"
                                :multiple="false"
                                label="systemName"
                                moduleName="systemCloud"
                                :error="errors['tenant.systemCloud']"
                                trackBy="id"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="tenant.tenantName"
                                :label="$t('Tenant Name')"
                                :error="errors['tenant.tenantName'] ?? ''"
                                :placeholder="
                                    $t(
                                        'Tenant Name must only contain letters, dashes, and underscores'
                                    )
                                "
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="tenant.url"
                                :label="$t('Url')"
                                :error="errors['tenant.url'] ?? ''"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <!-- Database Cloud multi-select -->
                            <MultiSelectInput
                                v-model="tenant.databaseCloud"
                                :options="databaseCloudOptions"
                                :multiple="true"
                                :textLabel="$t('Database')"
                                tag-placeholder="Add this as new tag"
                                label="name"
                                trackBy="id"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <!-- Distributed Filesystem multi-select -->
                            <MultiSelectInput
                                v-model="tenant.distributedFileSystem"
                                :options="distributedFileSystemOptions"
                                :multiple="true"
                                :textLabel="$t('Filesystem')"
                                label="name"
                                trackBy="id"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card my-5">
            <div class="card-header flex justify-between">
                <h3 class="card-title">{{ $t("Add Repositories for Tenant") }}</h3>
                <button
                    class="flex items-center btn-green"
                    role="button"
                    @click.prevent="addRepository"
                >
                    {{ $t("Add Repository") }}
                </button>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-1 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                v-model="form.name"
                                :required="true"
                                :label="$t('Name')"
                                :error="repositoriesErrors?.name ?? ''"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <textarea-input
                                v-model="form.text"
                                :required="true"
                                :label="$t('Text')"
                                :error="repositoriesErrors?.text ?? ''"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card my-5">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Repositories for Tenant") }}</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="w-full doc-table">
                        <tr class="text-left font-bold">
                            <th class="pb-4 pt-6 px-6">{{ $t("Name") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Text") }}</th>
                            <th class="pb-4 pt-6 px-6">{{ $t("Actions") }}</th>
                        </tr>
                        <tr v-if="tenant.repositoriesArray?.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">
                                {{ $t("No repositories found") }}.
                            </td>
                        </tr>
                        <tr
                            v-else
                            v-for="(repository, index) in tenant.repositoriesArray"
                            :key="'tenant-' + index"
                            class="hover:bg-gray-100 focus-within:bg-gray-100"
                        >
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ repository.name }}
                                </a>
                            </td>
                            <td class="border-t">
                                <a
                                    href="javascript:void(0)"
                                    class="flex items-center px-6 py-4 focus:text-indigo-500"
                                >
                                    {{ repository.text }}
                                </a>
                            </td>
                            <td class="w-px border-t text-center">
                                <a
                                    @click="tenant.repositoriesArray.splice(index, 1)"
                                    href="#"
                                    class="font-medium text-red-600 dark:text-red-500 hover:underline"
                                    ><font-awesome-icon icon="fa-regular fa-trash-can"
                                /></a>
                            </td>
                        </tr>
                    </table>
                    <div
                        v-if="errors['tenant.repositoriesArray'] ?? false"
                        class="form-error p-2"
                    >
                        {{ errors["tenant.repositoriesArray"] ?? "" }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import TextInput from "@/Components/TextInput.vue";
export default {
    components: { MultiSelectInput, TextInput, TextareaInput },
    props: {
        customers: Array,
        distributedFileSystemOptions: Array,
        databaseCloudOptions: Array,
        errors: Object,
        propTenant: { type: Object, default: {} },
        cloudSystems: Array,
    },
    // async mounted(){
    //         //Get distributed filesystem data
    //         const filesystemResponse =  await this.$store.dispatch("distributedFilesystem/list");
    //         this.distributedFileSystemOptions = filesystemResponse?.data?.data ?? [];

    //         //Get cloud database data
    //         const cloudResponse =  await this.$store.dispatch("databaseCloud/list");
    //         this.databaseCloudOptions = cloudResponse?.data?.data ?? [];
    // },
    data() {
        return {
            // distributedFileSystemOptions: [],
            // databaseCloudOptions: [],
            tenant:
                Object.keys(this.propTenant).length === 0
                    ? {
                          tenantName: "",
                          repositoriesArray: [],
                          systemUser: [],
                          systemCloud: "",
                          url: "",
                          databaseCloud: [],
                          distributedFileSystem: [],
                      }
                    : this.propTenant,
            form: {
                name: "",
                text: "",
            },
            repositoriesErrors: {},
        };
    },
    computed: {
        systemUser() {
            return this.tenant.systemUser;
        },
    },
    watch: {
        tenant: {
            handler: function (val, oldVal) {
                this.$emit("tenantValueChanged", val);
            },
            deep: true,
        },
        systemUser: {
            handler: function (val, oldVal) {
                this.tenant.tenantName = val?.companyName.replace(/ /g, "_");
            },
            deep: true,
        },
    },
    methods: {
        addRepository() {
            const nameRegex = /^[A-Za-z\-_]+$/;
            this.repositoriesErrors = {};

            if (!this.form.name) {
                this.repositoriesErrors.name = "Name is required.";
            } else if (!nameRegex.test(this.form.name)) {
                this.repositoriesErrors.name =
                    "Name must only contain letters, dashes, and underscores.";
            }

            if (!this.form.text) {
                this.repositoriesErrors.text = "Text is required.";
            }
            if (Object.keys(this.repositoriesErrors).length === 0) {
                this.tenant.repositoriesArray.push(this.form);
                this.form = {
                    name: "",
                    text: "",
                };
            }
        },
    },
};
</script>

<style></style>
