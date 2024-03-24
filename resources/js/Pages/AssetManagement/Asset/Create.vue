<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="store">
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
                                    class="pb-8"
                                    :label="$t('Description')"
                                    :required="true"
                                />
                            </div>
                            <div class="form-group">
                                <text-input
                                    :required="true"
                                    v-model="form.manufacturer"
                                    :error="errors.manufacturer"
                                    class="pb-8 lg:pb-0"
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

            <div class="flex items-center justify-end mt-4">
                <router-link to="/assets" class="primary-btn me-3">
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
import PageHeader from "@/Components/Layouts/Page-header.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("suppliers", ["suppliers"]),
    },
    components: {
        LoadingButton,
        TextInput,
        TextAreaInput,
        MultiSelectInput,
        NumberInput,
        PageHeader,
    },
    async mounted() {
        await this.$store.dispatch("suppliers/list");
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
                    to: "/assets",
                },
                {
                    text: "Create",
                    active: true,
                }
            ],
            form: {
                assetName: "",
                assetType: "",
                storeArticle: false,
                priceNetto: "",
                assetDescription: "",
                manufacturer: "",
                activeAsset: false,
                archivedAsset: false,
                supplierId: "",
                assetImage: {},
            },
            hovering: false,
        };
    },
    methods: {
        async store() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("assets/create", {
                    ...this.form,
                    supplierId: this.form.supplierId["id"] ?? "",
                    assetImages: this.assetImages,
                });
                await this.$store.dispatch("assets/list");
                this.$router.push("/assets");
            } catch (e) {}
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
    },
};
</script>
