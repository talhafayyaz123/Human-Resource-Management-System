<template>
    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-2">
                <h3 class="card-title">{{ $t("Configuration") }}</h3>
                <CustomIcon name="iIocn" />
            </div>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-3 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <TextInput
                            v-model="form.templateName"
                            :label="$t('Template Name')"
                            :required="true"
                            :error="errors['templateName']"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <TextInput
                            v-model="form.templateVersion"
                            :label="$t('Template Version')"
                            :required="true"
                            :error="errors['templateVersion']"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-model="form.consultant"
                            :key="form.consultant"
                            :isDisabled="true"
                            :options="userProfiles?.data ?? []"
                            :multiple="false"
                            :text-label="$t('Consultant')"
                            label="employee"
                            trackBy="userId"
                            moduleName="userProfile"
                        />
                    </div>
                </div>
                <div
                    class="w-full"
                    v-if="$route.name === 'WorkshopTemplatesEdit'"
                >
                    <div class="form-group">
                        <label class="input-label"
                            >{{ $t("Created At") }}:</label
                        >
                        <datepicker
                            class="form-control"
                            :disabled="true"
                            :clearable="false"
                            :enable-time-picker="false"
                            auto-apply
                            :close-on-auto-apply="false"
                            v-model="form.createdAt"
                            :class="errors.createdAt ? 'error' : ''"
                        />
                        <div v-if="errors?.createdAt" class="form-error">
                            {{ errors.createdAt }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <button @click="openProductsModal" class="secondary-btn">
                {{ $t("Select Products") }}
            </button>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <div class="form-control">
                            <p v-if="!form.assignedProducts?.length">
                                {{ $t("No Products Selected") }}
                            </p>
                            <ul>
                                <li
                                    class="flex justify-between"
                                    v-for="(
                                        product, index
                                    ) in form.assignedProducts"
                                    :key="'product-' + product.id"
                                >
                                    <p>{{ product.name }}</p>
                                    <font-awesome-icon
                                        @click="removeProduct(index)"
                                        class="cursor-pointer text-black self-center"
                                        icon="fa-solid fa-xmark"
                                    />
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            v-model="form.status"
                            :key="form.status"
                            :options="[$t('draft'), $t('stable')]"
                            :multiple="false"
                            :text-label="$t('Status')"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex items-center justify-end mt-3">
        <loading-button
            @click="$emit('save', form)"
            :loading="isLoading"
            class="secondary-btn gap-2"
        >
        <CustomIcon name="saveIcon" />
            {{ $t("Save & Proceed") }}
        </loading-button>
    </div>

    <!-- 
        select products modal
     -->
    <select-product-modal
        v-if="productToggle"
        @selected="addProducts"
        @cancelled="productToggle = false"
        :selectedItems="form.assignedProducts"
        :products="products"
    ></select-product-modal>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import SelectProductModal from "@/Components/SelectProductModal.vue";
import { mapGetters } from "vuex";

export default {
    name: "WorkshopTemplatesForm",
    emits: ["save"],
    props: {
        // the actionForm is the form containing the properties of the form
        actionForm: {
            type: Object,
            default: () => ({}),
        },
    },
    components: {
        TextInput,
        MultiSelectInput,
        LoadingButton,
        SelectProductModal,
    },
    data() {
        return {
            form: {},
            productToggle: false,
            products: [],
        };
    },
    methods: {
        // sync the form to the actionForm prop
        syncFormData() {
            this.form = { ...this.actionForm };
            if (this.$route.name === "WorkshopTemplatesCreate")
                this.form.consultant = this.authUserProfile;
        },
        /**
         * opens the SelectProductModal by toggling productToggle
         * calls the products list API and sets the 'products' to feed the SelectProductModal
         */
        async openProductsModal() {
            try {
                const response = await this.$store.dispatch(
                    "products/productsWithQuantity",
                    {
                        type: "software",
                    }
                );
                // sets the products from the API response
                this.products = response?.data?.products ?? {
                    data: [],
                    links: [],
                };
            } catch (e) {
                console.log(e);
            } finally {
                this.productToggle = true;
            }
        },
        /**
         * adds the selected products received from the SelectProductModal to the 'assignedProducts'
         * @param {products} the selected products
         */
        addProducts(products) {
            this.form.assignedProducts = [...products];
            this.productToggle = false;
        },
        /**
         * removes the product in the index from assignedProducts
         * @param {index} index of the product to be removed from assignedProducts
         */
        removeProduct(index) {
            this.form.assignedProducts = this.form.assignedProducts.splice(
                index,
                1
            );
        },
    },
    watch: {
        // watch for change in the actionForm and then sync with form
        actionForm() {
            this.syncFormData();
        },
    },
    async mounted() {
        // sync the form and actionForm on mount
        this.syncFormData();
        // fetch user profiles for consultant dropdown
        try {
            if (!this.userProfiles?.data?.length)
                await this.$store.dispatch("userProfile/list");
            // set the currently logged in user as the consultant on the create page only
        } catch (e) {
            console.log(e);
        }
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("userProfile", ["userProfiles", "authUserProfile"]),
        ...mapGetters("auth", ["user"]),
    },
};
</script>

<style scoped>
.selected-products {
    min-height: 50px;
    border: 1px solid lightgrey;
    border-radius: 5px;
    width: 100%;
}
</style>
