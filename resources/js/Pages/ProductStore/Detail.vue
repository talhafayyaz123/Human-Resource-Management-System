<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="card p-5">
            <div class="pr-detail">
                <div class="pr-card-img">
                    <swiper
                        :style="{
                            '--swiper-navigation-color': '#fff',
                            '--swiper-pagination-color': '#fff',
                        }"
                        :spaceBetween="10"
                        :navigation="true"
                        :thumbs="{ swiper: thumbsSwiper }"
                        :modules="modules"
                        class="mySwiper2"
                    >
                        <swiper-slide
                            v-for="(image, key) in imageFiles?.files"
                            :key="key"
                        >
                            <img
                                :src="image?.base64"
                                style="object-fit: contain"
                            />
                        </swiper-slide>
                    </swiper>
                    <swiper
                        @swiper="setThumbsSwiper"
                        :spaceBetween="10"
                        :slidesPerView="4"
                        :freeMode="true"
                        :watchSlidesProgress="true"
                        :modules="modules"
                        class="mySwiper"
                    >
                        <swiper-slide
                            v-for="(image, key) in imageFiles?.files"
                            :key="key"
                        >
                            <img
                                :src="image?.base64"
                                style="object-fit: contain"
                            />
                        </swiper-slide>
                    </swiper>
                </div>
                <div class="pr-card-content">
                    <div class="">
                        <a href="">
                            <h2>{{ form?.productTitle }}</h2>
                        </a>
                        <h4>Item no. {{ form?.itemNumber }}</h4>
                        <div class="rating">
                            <i :data-star="form?.rattingAvg"></i>
                            <span class="ml-2">{{ form?.totalReviews }}</span>
                        </div>
                        <p class="shor-dec">{{ form?.shortDescription }}</p>
                    </div>
                    <div class="flex items-center">
                        <button class="add-cart-btn mr-4">
                            <font-awesome-icon
                                icon="fa fa-shopping-cart mr-2"
                            />
                            <span>Add To Cart</span>
                        </button>
                        <h3 class="price">
                            {{ $formatter(form?.sellPrice, globalLanguage) }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="full-desc mb-5">
                <h2 class="mt-4 desc-title">Description</h2>
                <p v-html="form?.longDescription"></p>
            </div>
        </div>
        <div class="card my-5">
            <div class="card-header flex justify-between">
                <h3 class="card-title">{{ $t("All Reviews") }}</h3>
                <button class="secondary-btn" @click="toggleReviewModal = true">
                    Add Reviews
                </button>
            </div>
            <div class="card-body">
                <div
                    class=""
                    v-for="review in getReviews?.data ?? []"
                    :key="review?.id"
                >
                    <div class="pr-review my-5">
                        <div class="user flex items-center justify-between">
                            <div class="profile flex items-center">
                                <img
                                    :src="`${review.profileImage}`"
                                    class="w-16 h-16 rounded-full object-cover"
                                />

                                <h3 class="ml-3">{{ review.userName }}</h3>
                            </div>

                            <span
                                class="text-gray-500 text-sm whitespace-nowrap"
                                >Reviewed on
                                <!-- {{ review.createdAt }} -->
                                {{
                                    $dateFormatter(
                                        review.createdAt,
                                        globalLanguage
                                    )
                                }}
                            </span>
                        </div>

                        <div class="rating">
                            <i :data-star="`${review.ratting}`"></i>
                            <h3 class="font-bold ml-2">{{ review.title }}</h3>
                        </div>

                        <!--  <div class="rating my-1">
                            <span class="text-gray-500 text-sm mr-3"
                                >Denomination: 50Design</span
                            >
                            |
                            <span class="font-semibold text-orange-500 ml-3"
                                >Verified Purchase</span
                            >
                        </div> -->

                        <div class="review">
                            <p v-if="!review.isReadMore">
                                {{ review.review.substring(0, 200) + ".." }}
                            </p>
                            <p v-if="review.isReadMore">{{ review.review }}</p>
                            <button
                                @click="review.isReadMore = true"
                                v-if="!review.isReadMore"
                                class="btn btn-primary text-blue-500"
                            >
                                <i
                                    class="fa fa-angle-up mr-2"
                                    aria-hidden="true"
                                ></i
                                >Read more
                            </button>
                            <button
                                @click="review.isReadMore = false"
                                v-if="review.isReadMore"
                                class="btn btn-primary text-blue-500"
                            >
                                <i
                                    class="fa fa-angle-down mr-2"
                                    aria-hidden="true"
                                ></i
                                >Read less
                            </button>
                        </div>
                        <div
                            class="flex my-5 items-center justify-around w-1/4 ml-3"
                        >
                            <span
                                :ref="`help_full_feedback_response_${review.id}`"
                                v-if="!review.isHelpful"
                            >
                                <button
                                    @click="helpfulFeedback(review.id)"
                                    class="add-cart-btn mr-3"
                                >
                                    Helpful
                                </button></span
                            >
                            <span class="text-green-500" v-else
                                >Thank you for your feedback!</span
                            >
                            |
                            <button class="add-cart-btn-report">
                                <span @click="showReportModel(review.id)"
                                    >Report</span
                                >
                            </button>
                        </div>
                    </div>
                    <hr />
                </div>
            </div>
        </div>
    </div>

    <Modal
        :title="$t('Add Review')"
        v-if="toggleReviewModal"
        @toggleModal="toggleReviewModal = false"
        :classSize="'modal-md'"
    >
        <template #body>
            <div class="p-5">
                <div class="grid items-center grid-cols-1 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="review.title"
                                class=""
                                :label="$t('Product Title')"
                            />
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label"
                                ><span></span>{{ $t("Rating") }}:</label
                            >
                            <div class="rate">
                                <input
                                    type="radio"
                                    id="star5"
                                    value="5"
                                    v-model="review.ratting"
                                />
                                <label for="star5" title="text">5 stars</label>
                                <input
                                    type="radio"
                                    id="star4"
                                    v-model="review.ratting"
                                    value="4"
                                />
                                <label for="star4" title="text">4 stars</label>
                                <input
                                    type="radio"
                                    id="star3"
                                    v-model="review.ratting"
                                    value="3"
                                />
                                <label for="star3" title="text">3 stars</label>
                                <input
                                    type="radio"
                                    id="star2"
                                    v-model="review.ratting"
                                    value="2"
                                />
                                <label for="star2" title="text">2 stars</label>
                                <input
                                    type="radio"
                                    id="star1"
                                    v-model="review.ratting"
                                    value="1"
                                />
                                <label for="star1" title="text">1 star</label>
                            </div>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                :required="true"
                                :key="review.userId"
                                v-model="review.userId"
                                :multiple="false"
                                :textLabel="$t('User')"
                                :error="errors.userId"
                                label="first_name"
                                :trackBy="'id'"
                                :options="employees"
                                moduleName="companyEmployees"
                                :search-param-name="'search_string'"
                                :action="'employees'"
                                :countStore="'employeesCount'"
                            >
                                <template #beforeList>
                                    <div
                                        class="grid p-2 gap-2"
                                        style="
                                            grid-template-columns: 25% 25% 50%;
                                        "
                                    >
                                        <p class="font-bold">
                                            {{ $t("First Name") }}
                                        </p>
                                        <p class="font-bold">
                                            {{ $t("Last Name") }}
                                        </p>
                                        <p class="font-bold">
                                            {{ $t("Email") }}
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
                                        style="
                                            grid-template-columns: 25% 25% 50%;
                                        "
                                    >
                                        <p class="overflow-text-users-table">
                                            {{ props.option.first_name }}
                                        </p>
                                        <p class="overflow-text-users-table">
                                            {{ props.option.last_name }}
                                        </p>
                                        <p class="overflow-text-users-table">
                                            {{ props.option.email }}
                                        </p>
                                    </div>
                                </template>
                            </MultiSelectInput>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label"
                                ><span></span>{{ $t("Images") }}:</label
                            >
                            <file-inputs
                                @file-changed="addScriptFiles"
                                :productFiles="scriptFiles"
                            />
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label"
                                ><span></span>{{ $t("Review") }}:</label
                            >

                            <textarea
                                v-model="review.review"
                                rows="5"
                                class="form-control form-textarea"
                            ></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="flex items-center justify-end">
                <loading-button
                    @click="toggleReviewModal = false"
                    class="primary-btn mr-3"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Cancel") }}
                </loading-button>
                <loading-button
                    v-if="$can(`${$route.meta.permission}.create`)"
                    @click="saveComment"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </template>
    </Modal>

    <Modal
        :title="$t('Review Reports')"
        v-if="toggleReviewReportModal"
        @toggleModal="closeReportModel"
        :classSize="'modal-md'"
    >
        <template #body>
            <div class="p-5">
                <div class="grid items-center grid-cols-1 gap-6">
                    <div class="a-row">
                        <p>Optional: Why are you reporting this?</p>
                    </div>
                    <div
                        class="w-full"
                        v-for="(report, index) in reviewReports"
                        :key="'report-' + index"
                    >
                        <div class="form-group">
                            <div class="a-fixed-left-grid a-spacing-top-base">
                                <div
                                    class="a-fixed-left-grid-inner a-grid-vertical-align a-grid-center"
                                >
                                    <div
                                        class="a-fixed-left-grid-col a-col-left"
                                        style="
                                            width: 24px;
                                            margin-left: -24px;
                                            float: left;
                                        "
                                    >
                                        <div class="a-checkbox">
                                            <label
                                                for="cr-report-abusive-review-reason-R2OO0V7OCCCROH-1"
                                            >
                                                <input
                                                    :id="`cr-report-abusive-review-reason-R2OO0V7OCCCROH-1_${index}`"
                                                    type="checkbox"
                                                    v-model="reviewReportIds"
                                                    :value="report.id" />
                                                <i
                                                    class="a-icon a-icon-checkbox"
                                                ></i>
                                                <span
                                                    class="a-label a-checkbox-label"
                                                ></span
                                            ></label>
                                        </div>
                                    </div>
                                    <div
                                        class="a-fixed-left-grid-col a-col-right"
                                        style="padding-left: 0%; float: left"
                                    >
                                        <p
                                            class="a-spacing-micro a-text-left a-size-base"
                                        >
                                            {{ report.title }}
                                        </p>
                                        <p
                                            class="a-size-base a-color-secondary"
                                        >
                                            {{ report.description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="flex items-center justify-end">
                <loading-button
                    @click="closeReportModel"
                    class="primary-btn mr-3"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Cancel") }}
                </loading-button>
                <loading-button
                    @click="saveReviewReport"
                    :loading="isLoading"
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </template>
    </Modal>
</template>
<script>
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";
import TextInput from "@/Components/TextInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import FileInputs from "@/Components/FileInputs.vue";
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import Modal from "@/Components/EditModal.vue";
import Pagination from "laravel-vue-pagination";
// Import Swiper styles
import "swiper/css";

import "swiper/css/free-mode";
import "swiper/css/navigation";
import "swiper/css/thumbs";

// import required modules
import { FreeMode, Navigation, Thumbs } from "swiper/modules";

export default {
    components: {
        PageHeader,
        Swiper,
        SwiperSlide,
        TextInput,
        QuillTextEditor,
        FileInputs,
        MultiSelectInput,
        LoadingButton,
        Modal,
        Pagination,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("storeEntries", ["getReviews"]),
        ...mapGetters("auth", {
            user: "user",
            users: "users",
            employees: "employees",
        }),

        modules() {
            return [FreeMode, Navigation, Thumbs];
        },
    },

    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Product Store",
                    to: "/product-store",
                },
                {
                    text: "Detail",
                    active: true,
                },
            ],
            form: {
                itemNumber: "",
                productTitle: "",
                isVisibleForPartner: false,
                isVisibleForCustomer: false,
                longDescription: "",
                shortDescription: "",
                sellPrice: "",
                products: [],
                authorId: "",
                rattingAvg: "",
                totalReviews: "",
            },
            imageFiles: {
                files: [],
            },
            scriptFilesConvertedToBase64: [],
            scriptFiles: {
                files: [],
            },
            thumbsSwiper: null,
            filesConvertedToBase64: "",
            review: {
                productStoreId: "",
                title: "",
                ratting: 0,
                userId: "",
                review: "",
                images: [],
            },
            reviewReports: [],
            toggleReviewModal: false,
            toggleReviewReportModal: false,
            reviewReportIds: [],
            reportViewId: "",
        };
    },
    async mounted() {
        await this.$store.dispatch("storeEntries/getReviews", {
            productStoreId: this.$route.params.id,
        });

        await this.$store.dispatch("auth/employees", {
            limit_start: 0,
            limit_count: 25,
        });

        const reportResponse = await this.$store.dispatch(
            "storeEntries/reports"
        );
        const responseDataReport =
            reportResponse?.data?.reviewReport?.data ?? {};
        this.reviewReports = responseDataReport;

        // fetch the companies list if empty. Needed for company and tenant dropdown
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "storeEntries/show",
                this.$route.params.id
            );
            const responseData = response?.data?.data ?? {};
            this.assignValues(responseData);
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
    methods: {
        showReportModel(id) {
            this.reportViewId = id;
            this.toggleReviewReportModal = true;
        },
        closeReportModel() {
            this.reportViewId = "";
            (this.reviewReportIds = []), (this.toggleReviewReportModal = false);
        },
        showMore(value) {
            value = true;
        },
        showLess(value) {
            value = false;
        },
        async changePageOrSearch(page = 1) {
            await this.$store.dispatch("storeEntries/getReviews", {
                productStoreId: this.$route.params.id,
                perPage: page,
            });
        },
        async helpfulFeedback(reviewId) {
            await this.$store.dispatch(
                "storeEntries/saveHelpfulFeedback",
                reviewId
            );
            this.$refs[
                "help_full_feedback_response_" + reviewId
            ][0].textContent = "Thank you for your feedback";
        },
        async saveReviewReport() {
            if (this.reviewReportIds.length > 0) {
                const payLoad = {
                    reviewId: this.reportViewId,
                    reviewReportIds: this.reviewReportIds,
                };
                await this.$store.dispatch(
                    "storeEntries/saveProductReviewReport",
                    payLoad
                );
                this.closeReportModel();
            }
        },
        async saveComment() {
            try {
                this.$store.commit("isLoading", true);
                this.review.userId = this.review.userId.id;
                this.review.images = this.scriptFilesConvertedToBase64;
                this.review.productStoreId = this.$route.params.id;
                await this.$store.dispatch(
                    "storeEntries/createReview",
                    this.review
                );
                this.review = {
                    title: "",
                    ratting: 0,
                    userId: "",
                    review: "",
                    images: [],
                };
                this.scriptFilesConvertedToBase64 = [];
                this.scriptFiles = {
                    files: [],
                };
                await this.$store.dispatch("storeEntries/getReviews", {
                    productStoreId: this.$route.params.id,
                });
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * converts the files to base64 and saves then in 'scriptFilesConvertedToBase64'
         * @param {files} selected files, which need to be converted to base64
         */
        addScriptFiles(files) {
            this.scriptFilesConvertedToBase64 = [];
            files.forEach((file) => {
                // only convert to base64 if the file is of type Blob
                if (file instanceof Blob) {
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => {
                        const requiredData = reader.result.split(",");
                        this.scriptFilesConvertedToBase64.push({
                            name: file.name,
                            base64: requiredData[1],
                            size: file.size,
                        });
                    };
                    reader.onerror = (error) => {
                        console.log(error);
                    };
                } else {
                    this.scriptFilesConvertedToBase64.push({
                        ...file,
                    });
                }
            });
        },
        setThumbsSwiper(swiper) {
            this.thumbsSwiper = swiper;
        },

        assignValues(response) {
            this.form.productTitle = response?.productTitle;
            this.form.shortDescription = response?.shortDescription;
            this.form.itemNumber = response?.itemNumber;
            this.form.longDescription = response?.longDescription;
            this.form.products = response?.products;
            this.form.isVisibleForCustomer = response?.isVisibleToCustomer;
            this.form.isVisibleForPartner = response?.isVisibleToPartner;
            this.form.authorId = response?.author;
            this.form.sellPrice = response?.sellPrice;
            this.form.rattingAvg = response?.rattingAvg;
            this.form.totalReviews = response?.totalReviews;
            const productImages = response?.productImages;

            if (productImages) {
                const requiredImages = productImages.map((prodImage) => {
                    console.log(prodImage);
                    //this.imageFilesConvertedToBase64 = prodImage.url
                    return {
                        name: prodImage.viewableName,
                        base64: prodImage.url,
                        size: prodImage.size,
                    };
                });
                this.imageFiles.files = requiredImages;
                //this.imageFilesConvertedToBase64 =  requiredImages[0]?.base64;
            }
        },
    },
};
</script>
<style scoped>
.rate {
    float: left;
    height: 46px;
    padding: 0 10px;
}
.rate:not(:checked) > input {
    position: absolute;
    top: -9999px;
}
.rate:not(:checked) > label {
    float: right;
    width: 1em;
    overflow: hidden;
    white-space: nowrap;
    cursor: pointer;
    font-size: 30px;
    color: #ccc;
}
.rate:not(:checked) > label:before {
    content: "★ ";
}
.rate > input:checked ~ label {
    color: #ffc700;
}
.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #deb217;
}
.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
}

.swiper {
    width: 100%;
    height: 100%;
}

.swiper-slide {
    text-align: center;
    font-size: 18px;
    background: #fff;

    /* Center slide text vertically */
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.swiper {
    width: 100%;
    height: 300px;
    margin-left: auto;
    margin-right: auto;
}

.swiper-slide {
    background-size: cover;
    background-position: center;
}

.mySwiper2 {
    height: 80%;
    width: 100%;
}

.mySwiper {
    height: 20%;
    box-sizing: border-box;
    padding: 10px 0;
}

.mySwiper .swiper-slide {
    width: 25%;
    height: 100%;
    opacity: 0.4;
}

.mySwiper .swiper-slide-thumb-active {
    opacity: 1;
}

.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
