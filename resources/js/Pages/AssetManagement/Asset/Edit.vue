<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Fill Assets Details") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group flex justify-center">
                                <input
                                    class=""
                                    @change="processFile"
                                    type="file"
                                    ref="fileInput"
                                    style="display: none"
                                />
                                <div
                                    @mouseover="hovering = true"
                                    @mouseleave="hovering = false"
                                    :style="{
                                        backgroundImage:
                                            'url(data:image/png;base64,' +
                                            (form.assetImage?.base64 ?? '') +
                                            ')',
                                    }"
                                    class="flex justify-center items-center mb-5 mt-5 relative"
                                    style="
                                        background-position: center center;
                                        background-repeat: no-repeat;
                                        background-size: cover;
                                        height: 180px;
                                        width: 350px;
                                        background-color: #2957a4;
                                        color: white;
                                        overflow: hidden;
                                    "
                                >
                                    <p
                                        v-if="!form.assetImage?.base64"
                                        style="font-size: 1.5rem"
                                    >
                                        {{ getInitials(form?.assetName ?? "") }}
                                    </p>
                                    <div
                                        v-if="hovering"
                                        @click="this.$refs.fileInput.click()"
                                        style="
                                            display: flex;
                                            justify-content: center;
                                            align-items: center;
                                            width: 100%;
                                            height: 35px;
                                            background-color: rgba(
                                                255,
                                                255,
                                                255,
                                                0.2
                                            );
                                            position: absolute;
                                            bottom: 0;
                                            cursor: pointer;
                                        "
                                    >
                                        <font-awesome-icon
                                            style="
                                                font-size: 1.2rem;
                                                color: rgba(255, 255, 255, 0.5);
                                            "
                                            icon="fa-solid fa-camera"
                                        ></font-awesome-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-area-input
                                    v-model="form.assetDescription"
                                    :error="errors.assetDescription"
                                    :label="$t('Description')"
                                    :required="true"
                                    class="pb-8"
                                />
                            </div>
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.manufacturer"
                                    :error="errors.manufacturer"
                                    class="pb-8"
                                    :label="$t('Manufacturer')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.assetName"
                                    :error="errors.assetName"
                                    :label="$t('Asset Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-if="form.supplierId"
                                    v-model="form.supplierId"
                                    :errors="errors.supplierId"
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
                                    v-model="form.assetType"
                                    :error="errors.assetType"
                                    :label="$t('Asset Type')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <number-input
                                    v-model="form.priceNetto"
                                    :error="errors.priceNetto"
                                    :label="$t('Price Netto')"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="grid items-center grid-cols-3 gap-6 my-5">
                        <div class="w-full">
                            <div class="form-group">
                                <input
                                    v-model="form.storeArticle"
                                    type="checkbox"
                                />
                                <label class="ml-3" for="">{{
                                    $t("Store Article")
                                }}</label>
                                <div
                                    v-if="errors.storeArticle"
                                    class="form-error"
                                >
                                    {{ $t(errors.storeArticle) ?? "" }}
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <input
                                    v-model="form.activeAsset"
                                    type="checkbox"
                                />
                                <label class="ml-3" for="">{{
                                    $t("Active Asset")
                                }}</label>
                                <div
                                    v-if="errors.activeAsset"
                                    class="form-error"
                                >
                                    {{ $t(errors.activeAsset) ?? "" }}
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <input
                                    v-model="form.archivedAsset"
                                    type="checkbox"
                                />
                                <label class="ml-3" for="">{{
                                    $t("Archived Asset")
                                }}</label>
                                <div
                                    v-if="errors.archivedAsset"
                                    class="form-error"
                                >
                                    {{ $t(errors.archivedAsset) ?? "" }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4 mr-12">
                <router-link
                    :to="`/assets?page=${this.$route.query.page}`"
                    class="primary-btn me-3"
                >
                    <span class="mr-1">
                        <CustomIcon name="cancelIcon" />
                    </span>
                    <span>{{ $t("Cancel") }}</span>
                </router-link>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    :loading="isLoading"
                    class="btn-green"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </form>
        <edit-modal
            :title="$t('Add Delivery')"
            v-if="toggleAssetListModal"
            @toggleModal="toggleAssetListModal = false"
            :classSize="'modal-md'"
        >
            <template #body>
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="assetArticle.assetName"
                                :isReadonly="true"
                                :error="errors.assetName"
                                class=""
                                :label="$t('Asset Name')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="assetArticle.model"
                                :error="errors.model"
                                class=""
                                :label="$t('Model')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                class=""
                                v-model="assetArticle.suppliersId"
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
                                v-model="assetArticle.deliveryNoteNumber"
                                :error="errors.deliverNoteNumber"
                                class=""
                                :label="$t('Delivery Note Number')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="assetArticle.invoiceNumber"
                                :error="errors.invoiceNumber"
                                class=""
                                :label="$t('Invoice Number')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="assetArticle.quantity"
                                :error="errors.quantity"
                                class=""
                                :label="$t('Quantity')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <div class="">
                                <label class="input-label"
                                    ><span style="color: red">*</span>&nbsp;{{
                                        $t("Delivery Date")
                                    }}:</label
                                >
                                <datepicker
                                    :clearable="false"
                                    :enable-time-picker="false"
                                    auto-apply
                                    :close-on-auto-apply="false"
                                    v-model="assetArticle.deliveryDate"
                                    :class="errors.deliveryDate ? 'error' : ''"
                                    class="form-control"
                                />
                                <div
                                    v-if="errors?.deliveryDate"
                                    class="form-error"
                                >
                                    {{ errors.deliveryDate }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                class=""
                                v-model="assetArticle.location"
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
                        <div class="checkbox-group">
                            <input
                                class="checkbox-input"
                                v-model="assetArticle.isDeliveryChecked"
                                type="checkbox"
                                id="isDeliveryChecked"
                            />

                            <label
                                class="checkbox-label"
                                for="isDeliveryChecked"
                                >{{ $t("Delivery Checked") }}</label
                            >
                        </div>
                    </div>
                </div>
                <div
                    v-if="
                        assetArticle.assetArticles &&
                        assetArticle.assetArticles.length > 0
                    "
                    class="border-t-4 mt-6"
                >
                    <div
                        v-for="(assetArt, index) in assetArticle.assetArticles"
                        :key="'asset-' + index"
                        class="grid items-center grid-cols-2 gap-6"
                    >
                        <div class="w-full">
                            <div class="form-group">
                                <div class="">
                                    <div class="mt-6">
                                        <text-input
                                            :required="true"
                                            @input="
                                                addSerialNumber($event, index)
                                            "
                                            v-model="assetArt.serialNo"
                                            class=""
                                            :label="$t('Serial No')"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="">
                                    <div class="mt-6">
                                        <select-input
                                            :required="true"
                                            @change="
                                                addInventoryStatus(
                                                    $event,
                                                    index
                                                )
                                            "
                                            v-model="assetArt.inventoryStatus"
                                            :key="assetArt.inventoryStatus"
                                            label="Status"
                                            class="lg:w-full"
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
                        </div>
                    </div>
                </div>
            </template>
            <template #footer>
                <button
                    @click="toggleAssetListModal = false"
                    type="button"
                    class="primary-btn mr-3"
                >
                    {{ $t("Cancel") }}
                </button>
                <button
                    type="button"
                    class="secondary-btn"
                    @click="saveAssetDelivery"
                >
                    {{ $t("Create") }}
                </button>
            </template>
        </edit-modal>
        <div class="card my-3">
            <div class="card-header">
                <h3 class="card-title"></h3>
            </div>
            <div class="card-body">
                <div class="w-full flex">
                    <div class="flex justify-around" style="width: 80%">
                        <div class="text-center bg-blue-200 p-4 rounded-md">
                            <div class="text-xl font-bold">
                                {{ existingAssets }}
                            </div>
                            <div>{{ $t("Existing Asset") }}</div>
                        </div>
                        <div class="text-center bg-green-200 p-4 rounded-md">
                            <div class="text-xl font-bold">
                                {{ inStock }}
                            </div>
                            <div>{{ $t("In Stock") }}</div>
                        </div>
                        <div class="text-center bg-yellow-200 p-4 rounded-md">
                            <div class="text-xl font-bold">
                                {{ assignedAssets }}
                            </div>
                            <div>{{ $t("Assigned Asset") }}</div>
                        </div>
                    </div>
                    <div
                        class="flex justify-end items-center"
                        style="width: 20%"
                    >
                        <!-- Updated this line -->
                        <button
                            @click="addAssetArticle"
                            class="btn-green"
                            style="height: 40px"
                        >
                            <span class="hidden md:inline"
                                >&nbsp;{{ $t("Add Delivery") }}</span
                            >
                        </button>
                    </div>
                </div>
                <br />
                <br />
                <div class="table-responsive">
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
                            v-for="(asset, index) in assetArticles"
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
                                    v-if="
                                        $can(`${$route.meta.permission}.edit`)
                                    "
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-pen-to-square"
                                    />
                                </span>
                                <span
                                    @click="deleteAssetArticle(asset.id)"
                                    role="button"
                                    v-if="
                                        $can(`${$route.meta.permission}.delete`)
                                    "
                                >
                                    <font-awesome-icon
                                        icon="fa-regular fa-trash-can"
                                    />
                                </span>
                                <router-link
                                    v-if="
                                        $can(`${$route.meta.permission}.show`)
                                    "
                                    class="pl-2"
                                    :to="`/asset-article/${asset.id}`"
                                >
                                    <font-awesome-icon icon="fa-solid fa-eye" />
                                </router-link>
                            </td>
                        </tr>
                    </table>
                    <edit-modal
                        :title="$t('Edit Asset Articles')"
                        v-if="toggleEditAssetListModal"
                        @toggleModal="toggleEditAssetListModal = false"
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
                                            class=""
                                            :label="$t('Asset Name')"
                                        />
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="form-group">
                                        <MultiSelectInput
                                            class="w-full pr-4 lg:w-full"
                                            v-model="
                                                editAssetArticle.assetListId
                                            "
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
                                                            props.option
                                                                .assetNumber
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
                                                            props.option
                                                                .assetNumber
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
                                            class=""
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
                                            :key="
                                                editAssetArticle.inventoryStatus
                                            "
                                            :error="errors.inventoryStatus"
                                            label="Status"
                                            class="w-full pr-4 lg:w-full"
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
                                type="button"
                                class="secondary-btn"
                                @click="updateAssetArticle(editAssetArticle.id)"
                            >
                                {{ $t("Update") }}
                            </button>
                            <button
                                @click="toggleEditAssetListModal = false"
                                type="button"
                                class="primary-btn mr-3"
                            >
                                {{ $t("Cancel") }}
                            </button>
                        </template>
                    </edit-modal>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="assets-list-btn">
        <ul>
            <li>
                <div class="cursor-pointer" @click="toggleFeeds()">
                    <img src="@/assets/images/feeds.png" alt="" />
                </div>
            </li>
            <li>
                <div class="cursor-pointer" @click="toggleDocPilot()">
                    <img src="@/assets/images/doc-pilot.png" alt="" />
                </div>
            </li>
            <li>
                <div
                    class="cursor-pointer"
                    @click="historyToggle = !historyToggle"
                >
                    <img src="@/assets/images/history.png" alt="" />
                </div>
            </li>
        </ul>
    </div>
    <History
        v-if="historyToggle"
        @closehistory="handleCloseHistory"
        ref="edit-history"
        :id="$route.params.id"
        :moduleName="'Asset'"
        :displayName="'Asset'"
    />

    <div class="feeds-setting" :class="{ show: objectFeed }">
        <ObjectFeeds @closefeeds="handleCloseFeeds" />
    </div>
    <div class="docPilot-setting" :class="{ show: docPilot }">
        <DocPilots @closeDocPilot="handleCloseDocPilot" />
    </div> -->
</template>

<script>
import LoadingButton from "@/Components/LoadingButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { mapGetters } from "vuex";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import EditModal from "@/Components/EditModal.vue";
import SelectInput from "@/Components/SelectInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";

import ObjectFeeds from "./components/ObjectFeeds.vue";
import DocPilots from "./components/DocPilot.vue";
import History from "./components/History.vue";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("suppliers", ["suppliers"]),
        ...mapGetters("assetList", ["assetLists"]),
        ...mapGetters("auth", ["user"]),
        ...mapGetters("storageLocations", ["storages"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        NumberInput,
        EditModal,
        SelectInput,
        ObjectFeeds,
        DocPilots,
        History,
        PageHeader,
        TextAreaInput,
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
                    to: "/assets",
                },
                {
                    text: this.$t("Assets"),
                    to: `/assets?page=${this.$route.query.page}`,
                },
                {
                    text: this.$t("Edit"),
                    active: true,
                },
            ],
            form: {
                assetName: "",
                assetType: "",
                storeArticle: "",
                priceNetto: "",
                supplierId: "",
                assetDescription: "",
                manufacturer: "",
                activeAsset: false,
                archivedAsset: false,
                assetImage: {},
            },
            existingAssets: 0,
            inStock: 0,
            assignedAssets: 0,
            hovering: false,
            assetArticle: {
                assetName: "",
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
            toggleAssetListModal: false,
            toggleEditAssetListModal: false,
            assetArticles: [],
            // objectFeed: false,
            // docPilot: false,
            // historyToggle: false,
        };
    },

    watch: {
        "assetArticle.quantity"(quan) {
            this.assetArticle.assetArticles = [];
            for (let i = 0; i < quan; i++) {
                this.assetArticle.assetArticles[i] = {
                    serialNo: "",
                    inventoryStatus: "",
                };
            }
        },
        toggleAssetListModal(newVal) {
            if (newVal) {
                document.body.classList.add("modal-layout");
            } else {
                document.body.classList.remove("modal-layout");
            }
        },
    },
    async mounted() {
        this.$store.commit("showLoader", true);
        await this.$store.dispatch("suppliers/list");
        await this.$store.dispatch("assetList/list");
        await this.$store.dispatch("storageLocations/list");
        this.refresh();
    },
    methods: {
        addSerialNumber(e, index) {
            this.assetArticle.assetArticles[index].serialNo = e.target.value;
        },
        addInventoryStatus(e, index) {
            this.assetArticle.assetArticles[index].inventoryStatus =
                e.target.value;
        },
        // toggleFeeds() {
        //     this.objectFeed = !this.objectFeed;
        // },
        // toggleDocPilot() {
        //     this.docPilot = !this.docPilot;
        // },
        // handleCloseFeeds() {
        //     this.objectFeed = false;
        // },
        // handleCloseDocPilot() {
        //     this.docPilot = false;
        // },
        // handleCloseHistory() {
        //     this.historyToggle = false;
        // },
        async refresh() {
            try {
                const response = await this.$store.dispatch(
                    "assets/show",
                    this.$route.params.id
                );
                const modelData = response?.data?.data ?? {};
                document.title = "Edit Asset " + modelData?.assetNumber;
                this.existingAssets = modelData?.existingAssets || 0;
                this.inStock = modelData?.inStock || 0;
                this.assignedAssets = modelData?.assignedAssets || 0;
                this.form = {
                    assetName: modelData.assetName,
                    moduleHistory: modelData.moduleHistory,
                    assetType: modelData.assetType,
                    storeArticle: modelData.storeArticle,
                    priceNetto: modelData.priceNetto,
                    supplierId: this.suppliers?.data?.find(
                        (supplier) => supplier.id == modelData.supplierId
                    ),
                    assetImage: modelData.assetImage,
                    assetDescription: modelData?.description,
                    manufacturer: modelData?.manufacturer,
                    activeAsset: modelData?.activeAsset === 1 ? true : false,
                    archivedAsset:
                        modelData?.archivedAsset === 1 ? true : false,
                };
                this.assetArticles = modelData.assetArticles;
                this.assetArticle.assetName = this.form.assetName;
                this.assetArticle.assetId = this.$route.params.id;
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
        },
        getInitials(name) {
            const tokens = name?.split(" ");
            if (tokens)
                return `${tokens?.[0]?.[0] ?? ""}${
                    tokens?.[1]?.[0] ?? ""
                }`.toUpperCase();
            else return "";
        },
        processFile(event) {
            let base64Content = "";
            let file = event.target.files[0];
            var reader = new FileReader();
            reader.onload = (event) => {
                const fileName = file.name;
                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                const fileExtension = fileName
                    .substring(fileName.lastIndexOf(".") + 1)
                    .toLowerCase();
                const dataUri = event.target.result;
                const parts = dataUri.split(",");
                if (parts.length === 2) {
                    base64Content = parts[1];
                    file = {
                        name: fileName,
                        type: fileExtension,
                        size: `${fileSizeMB} MB`,
                        base64: base64Content,
                    };
                    this.form.assetImage = file;
                }
            };
            reader.readAsDataURL(file);
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("assets/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        supplierId: this.form.supplierId["id"] ?? "",
                    },
                });
                await this.$store.dispatch("assets/list");
                this.$router.push(`/assets?page=${this.$route.query.page}`);
            } catch (e) {}
        },
        editAssetArticleCall(id) {
            let asset = this.assetArticles.find((data) => data.id === id);
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
                    // ...this.editAssetArticle,
                    // assetListId : this.editAssetArticle.assetListId,
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
        async saveAssetDelivery() {
            await this.$store.dispatch("assetDelivery/create", {
                ...this.assetArticle,
                isDeliveryChecked: this.assetArticle.isDeliveryChecked
                    ? true
                    : false,
                storageLocationId: this.assetArticle.location.id,
                suppliersId: this.assetArticle.suppliersId?.id,
                userId: this.user.id,
                assetListId: this.assetArticle.assetListId["id"] ?? "",
            });
            this.toggleAssetListModal = false;
            this.refresh();
        },
        customLabel(props) {
            return `${props?.name}`;
        },
        async deleteAssetArticle(id) {
            await this.$store.dispatch("assetArticle/destroy", id);
            this.refresh();
        },
        addAssetArticle() {
            this.assetArticle = {
                assetName: this.form.assetName,
                suppliersId: this.form.supplierId,
                model: "",
                deliveryNoteNumber: "",
                invoiceNumber: "",
                quantity: 0,
                deliveryDate: "",
                isDeliveryChecked: "",
                location: "",
                assetArticles: [
                    // {
                    //     serialNo: "",
                    //     inventoryStatus: "",
                    // },
                ],
                assetId: this.$route.params.id,
                assetListId: "",
            };
            this.toggleAssetListModal = true;
        },
        closeModal() {
            this.toggleAssetListModal = false;
        },
    },
};
</script>
