<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <h6
            class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800"
        ></h6>
        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Asset Delivery Edit") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.model"
                                    :error="errors.model"
                                    :label="$t('Model')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.suppliersId"
                                    :key="form.suppliersId"
                                    :errors="errors.suppliersId"
                                    :options="suppliers?.data ?? []"
                                    :multiple="false"
                                    :textLabel="$t('Suppliers')"
                                    label="supplierName"
                                    :trackBy="'id'"
                                    :moduleName="'suppliers'"
                                    :taggable="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.deliveryNoteNumber"
                                    :error="errors.deliverNoteNumber"
                                    :label="$t('Delivery Note Number')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.invoiceNumber"
                                    :error="errors.invoiceNumber"
                                    :label="$t('Invoice Number')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.quantity"
                                    :error="errors.quantity"
                                    :label="$t('Quantity')"
                                    :isReadonly="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <label class="form-label"
                                    ><span style="color: red">*</span>&nbsp;{{
                                        $t("Delivery Date")
                                    }}:</label
                                >
                                <datepicker
                                    :clearable="false"
                                    :enable-time-picker="false"
                                    auto-apply
                                    :close-on-auto-apply="false"
                                    v-model="form.deliveryDate"
                                    :class="errors.deliveryDate ? 'error' : ''"
                                />
                                <div
                                    v-if="errors?.deliveryDate"
                                    class="form-error"
                                >
                                    {{ errors.deliveryDate }}
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.location"
                                    :key="form.location"
                                    :errors="errors.location"
                                    :options="storages?.data ?? []"
                                    :multiple="false"
                                    :textLabel="$t('Storage Location')"
                                    label="storageLocation"
                                    :trackBy="'id'"
                                    :required="true"
                                    :moduleName="'storageLocation'"
                                    :taggable="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <input
                                    v-model="form.isDeliveryChecked"
                                    type="checkbox"
                                />

                                <label class="ml-3" for="">{{
                                    $t("Delivery Checked")
                                }}</label>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive my-5">
                <table class="w-full doc-table">
                    <tr class="text-left font-bold">
                        <th class="pb-4 pt-6 px-6">
                            {{ $t("Asset Name") }}
                        </th>
                        <th class="pb-4 pt-6 px-6">
                            {{ $t("Model") }}
                        </th>
                        <th class="pb-4 pt-6 px-6">
                            {{ $t("Serial No") }}
                        </th>
                        <th class="pb-4 pt-6 px-6">
                            {{ $t("Status") }}
                        </th>
                        <th class="pb-4 pt-6 px-6">
                            {{ $t("Storage Location") }}
                        </th>
                        <th class="pb-4 pt-6 px-6 text-center">
                            {{ $t("Actions") }}
                        </th>
                    </tr>
                    <tr
                        v-for="(asset, index) in form.assetArticles"
                        :key="'asset-' + index"
                        class="hover:bg-gray-100 focus-within:bg-gray-100"
                    >
                        <td class="pb-4 pt-6 px-6 border-t">
                            {{ asset.assetName }}
                        </td>
                        <td class="pb-4 pt-6 px-6 border-t">
                            {{ asset.model }}
                        </td>
                        <td class="pb-4 pt-6 px-6 border-t">
                            {{ asset.serialNo }}
                        </td>
                        <td class="pb-4 pt-6 px-6 border-t">
                            {{ asset.inventoryStatus }}
                        </td>
                        <td class="pb-4 pt-6 px-6 border-t">
                            <div
                                v-for="(stor, storInd) in storages.data"
                                :key="storInd"
                            >
                                <p v-if="stor.id == asset.storageLocation">
                                    {{ stor.storageLocation }}
                                </p>
                            </div>
                        </td>
                        <td class="pb-4 pt-6 px-6 border-t text-center">
                            <span
                                @click="editAssetArticleCall(asset.id)"
                                role="button"
                                class="mx-2"
                                v-if="$can(`${$route.meta.permission}.edit`)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-pen-to-square"
                                />
                            </span>
                            <span
                                @click="deleteAssetArticle(asset.id)"
                                role="button"
                                v-if="$can(`${$route.meta.permission}.delete`)"
                            >
                                <font-awesome-icon
                                    icon="fa-regular fa-trash-can"
                                />
                            </span>
                        </td>
                    </tr>
                </table>
                <edit-modal
                    :title="$t('Edit Asset Articles')"
                    v-if="toggleEditAssetListModal"
                    @toggleModal="toggleEditAssetListModal = false"
                    :classSize="'modal-md'"
                >
                    <template #body>
                        <div class="grid items-center grid-cols-2 gap-6">
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="editAssetArticle.assetName"
                                        :isReadonly="true"
                                        :error="errors.assetName"
                                        :label="$t('Asset Name')"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <MultiSelectInput
                                        v-model="editAssetArticle.assetListId"
                                        :key="editAssetArticle.assetListId"
                                        :errors="errors.assetListId"
                                        :options="assetLists?.data ?? []"
                                        :multiple="false"
                                        :textLabel="$t('Asset List')"
                                        :customLabel="customLabel"
                                        :trackBy="'id'"
                                        :moduleName="'assetLists'"
                                        :taggable="true"
                                    >
                                        <template #beforeList>
                                            <div
                                                class="grid p-2 gap-2"
                                                style="
                                                    grid-template-columns: 50% 50%;
                                                "
                                            >
                                                <p class="font-bold">
                                                    {{ $t("Name") }}
                                                </p>

                                                <p class="font-bold">
                                                    {{ $t("List Number") }}
                                                </p>
                                            </div>
                                            <hr />
                                        </template>
                                        <template #singleLabel="{ props }">
                                            <div
                                                class="grid gap-2"
                                                style="
                                                    grid-template-columns: 50% 50%;
                                                "
                                            >
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{ props.option.name }}
                                                </p>

                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{
                                                        props.option.assetNumber
                                                    }}
                                                </p>
                                            </div>
                                        </template>
                                        <template #option="{ props }">
                                            <div
                                                class="grid"
                                                style="
                                                    grid-template-columns: 50% 50%;
                                                "
                                            >
                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{ props.option.name }}
                                                </p>

                                                <p
                                                    class="overflow-text-users-table"
                                                >
                                                    {{
                                                        props.option.assetNumber
                                                    }}
                                                </p>
                                            </div>
                                        </template>
                                    </MultiSelectInput>
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <text-input
                                        :required="true"
                                        v-model="editAssetArticle.serialNo"
                                        :error="errors.serialNo"
                                        :label="$t('Serial No')"
                                    />
                                </div>
                            </div>
                            <div class="w-full">
                                <div class="form-group">
                                    <select-input
                                        :required="true"
                                        v-model="
                                            editAssetArticle.inventoryStatus
                                        "
                                        :key="editAssetArticle.inventoryStatus"
                                        :error="errors.inventoryStatus"
                                        label="Status"
                                    >
                                        <option value="stock">
                                            {{ $t("Stock") }}
                                        </option>
                                        <option value="assigned">
                                            {{ $t("Assigned") }}
                                        </option>
                                    </select-input>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template #footer>
                        <button
                            @click="toggleEditAssetListModal = false"
                            type="button"
                            class="primary-btn mr-3"
                        >
                            {{ $t("Cancel") }}
                        </button>
                        <button
                            type="button"
                            class="secondary-btn"
                            @click="updateAssetArticle(editAssetArticle.id)"
                        >
                            {{ $t("Update") }}
                        </button>
                    </template>
                </edit-modal>
            </div>

            <div class="flex items-center justify-end mt-4">
                <router-link to="/asset-lists" class="primary-btn mr-3">
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
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import EditModal from "@/Components/EditModal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("suppliers", ["suppliers"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("userTeams", ["teams"]),
        ...mapGetters("storageLocations", ["storages"]),
        ...mapGetters("assetEmployeeText", ["assetEmployeeText"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("assetList", ["assetLists"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        NumberInput,
        EditModal,
        SelectInput,
        PageHeader,
    },
    async mounted() {
        this.$store.commit("showLoader", true);
        await this.$store.dispatch("suppliers/list");
        await this.$store.dispatch("storageLocations/list");
        await this.$store.dispatch("assetList/list");
        this.refresh();
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Asset Management"),
                    to: "/asset-delivery",
                },
                {
                    text: this.$t("Asset Delivery"),
                    to: "/asset-delivery",
                },
                {
                    text: "Edit",
                    active: true,
                },
            ],
            form: {
                // assetName: "",
                model: "",
                suppliersId: "",
                deliveryNoteNumber: "",
                invoiceNumber: "",
                quantity: 1,
                deliveryDate: "",
                isDeliveryChecked: false,
                location: "",
                assetArticles: [
                    // {
                    //     serialNo: null,
                    //     inventoryStatus: "",
                    // },
                ],
                assetId: "",
                assetListId: "",
            },
            randomNumber: "",

            editAssetArticle: {
                assetName: "",
                assetEmployeeListId: "",
                model: "",
                supplierId: "",
                deliverNoteNumber: "",
                invoiceNumber: "",
                deliveryDate: "",
                deliveryChecked: false,
                storageLocation: "",
                serialNo: "",
                inventoryStatus: "",
                assetId: "",
                assetListId: "",
            },
            assetArticles: [],
            toggleEditAssetListModal: false,
        };
    },
    methods: {
        async refresh() {
            try {
                let response = await this.$store.dispatch(
                    "assetDelivery/show",
                    this.$route.params.id
                );
                document.title =
                    "Edit Asset List " + response?.data?.data?.model;
                const modelData = response?.data?.data ?? {};
                this.form = {
                    model: modelData.model,
                    suppliersId: this.suppliers?.data?.find(
                        (supplier) => supplier.id == modelData.suppliersId
                    ),
                    deliveryNoteNumber: modelData.deliveryNoteNumber,
                    invoiceNumber: modelData.invoiceNumber,
                    quantity: modelData.quantity,
                    deliveryDate: modelData.deliveryDate,
                    isDeliveryChecked:
                        modelData.isDeliveryChecked == 1 ? true : false,
                    location: this.storages?.data?.find(
                        (location) => location.id == modelData.storageLocationId
                    ),
                    assetArticles: modelData.assetArticles,
                    assetId: modelData.assetId,
                };
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("assetDelivery/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        isDeliveryChecked: this.form.isDeliveryChecked
                            ? true
                            : false,
                        storageLocationId: this.form.location.id,
                        suppliersId: this.form.suppliersId.id,
                        userId: this.user.id,
                        // assetListId: this.assetArticle.assetListId["id"] ?? "",
                    },
                });
                await this.$store.dispatch("assetList/list");
                this.$router.push(`/asset-delivery`);
            } catch (e) {}
        },

        editAssetArticleCall(id) {
            let asset = this.form.assetArticles.find((data) => data.id === id);
            this.editAssetArticle = this.cloneDeep(asset);
            this.editAssetArticle.id = id;
            if (asset?.assetListId) {
                this.editAssetArticle.assetListId = this.assetLists?.data?.find(
                    (data) => data.id === asset.assetListId
                );
            }
            this.toggleEditAssetListModal = true;
        },

        async updateAssetArticle(id) {
            await this.$store.dispatch("assetArticle/update", {
                id: id,
                data: {
                    ...this.editAssetArticle,
                    assetListId: this.editAssetArticle.assetListId,
                    assetName: this.editAssetArticle.assetName,
                    serialNo: this.editAssetArticle.serialNo,
                    inventoryStatus: this.editAssetArticle.inventoryStatus,
                    userId: this.user.id,
                    assetListId: this.editAssetArticle?.assetListId["id"] ?? "",
                },
            });
            this.toggleEditAssetListModal = false;
            this.refresh();
        },

        async deleteAssetArticle(id) {
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
                    await this.$store.dispatch("assetArticle/destroy", id);
                    this.refresh();
                }
            });
        },
    },
};
</script>
