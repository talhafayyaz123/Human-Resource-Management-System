<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Fill Request Details") }}</h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-3 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.title"
                                :error="errors.title"
                                class=""
                                :label="$t('Product Title')"
                            />
                        </div>
                    </div>
                    <div v-if="$route.params.id" class="w-full">
                        <div class="form-group">
                            <text-input
                                :disabled="true"
                                :required="true"
                                v-model="form.itemNumber"
                                :error="errors.itemNumber"
                                class=""
                                :label="$t('Request Number')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <SelectInput
                                v-model="form.status"
                                :key="form.status"
                                :label="$t('Status')"
                                :disabled="$route.name == 'requestsCreate'"
                                :required="true"
                                class=""
                            >
                                <option value="Draft">{{ $t("Draft") }}</option>
                                <option value="Pending">
                                    {{ $t("Pending") }}
                                </option>
                                <option value="Accepted">
                                    {{ $t("Accepted") }}
                                </option>
                                <option value="Declined">
                                    {{ $t("Declined") }}
                                </option>
                            </SelectInput>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                class=""
                                v-model="form.partner"
                                :textLabel="$t('Partner')"
                                :error="errors['partner']"
                                :key="form.partner"
                                :options="partners?.data"
                                :required="true"
                                :multiple="false"
                                label="companyName"
                                trackBy="id"
                                moduleName="companies"
                                :query="{ customerType: 'partner' }"
                                :countStore="'partnerCount'"
                            />
                        </div>
                    </div>
                    <div class="w-full col-span-3">
                        <div class="form-group">
                            <QuillTextEditor
                                class=""
                                :required="true"
                                :content="form.shortDescription"
                                :content-type="'html'"
                                :error="errors.shortDescription"
                                :label="$t('Short Description')"
                                @delta="form.shortDescription = $event"
                            />
                        </div>
                    </div>

                    <div>
                        <div class="form-group w-full">
                            <text-input
                                :required="true"
                                v-model="form.sellPrice"
                                :error="errors.sellPrice"
                                :type="`number`"
                                class=""
                                :label="$t('UVP Sale Price')"
                            />
                        </div>
                    </div>
                    <div>
                        <div class="form-group w-full">
                            <text-input
                                :required="true"
                                v-model="form.discount"
                                :error="errors.discount"
                                :type="`number`"
                                class=""
                                :min="0"
                                :label="$t('Discount (%)')"
                            />
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.partnerPrice"
                                :error="errors.partnerPrice"
                                :type="`number`"
                                class=""
                                :label="$t('Partner Price')"
                            />
                        </div>
                    </div>
                    <div>
                        <div class="form-group w-full">
                            <MultiSelectInput
                                class=""
                                v-model="form.product"
                                :textLabel="$t('Service Product')"
                                :error="errors.productId"
                                :key="form.product"
                                :options="products?.data"
                                :required="true"
                                :multiple="false"
                                label="name"
                                trackBy="id"
                                moduleName="productsWithQuantity"
                                :countStore="'productsCount'"
                            />
                        </div>
                    </div>
                    <div>
                        <div class="form-group">
                            <text-input
                                :required="true"
                                v-model="form.serviceHours"
                                :error="errors.serviceHours"
                                :type="`number`"
                                class=""
                                :label="$t('Service Hours')"
                            />
                        </div>
                    </div>
                </div>
                <div class="grid items-center grid-cols-2 gap-6 mt-5">

                    <div class="w-full">
                        <div class="form-group">
                            <QuillTextEditor
                                class=""
                                :required="true"
                                :content="form.serviceDescription"
                                :content-type="'html'"
                                :error="errors.serviceDescription"
                                @delta="form.serviceDescription = $event"
                                :label="$t('Service Description')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <QuillTextEditor
                                class=""
                                :required="true"
                                :content="form.delimitation"
                                :content-type="'html'"
                                :error="errors.delimitation"
                                @delta="form.delimitation = $event"
                                :label="$t('Delimitation')"
                            />
                        </div>
                    </div>
                    <div class="w-full col-span-2">
                        <div class="form-group">
                            <QuillTextEditor
                                class=""
                                :content="form.description"
                                :content-type="'html'"
                                :error="errors.description"
                                @delta="form.description = $event"
                                :label="$t('Product Description')"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <label class="form-label"
                                ><span></span>{{ $t("Scripts") }}:</label
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
                                ><span></span>&nbsp;{{
                                    $t("Product Images")
                                }}:</label
                            >
                            <file-inputs
                                @file-changed="addImageFiles"
                                :productFiles="imageFiles"
                            />
                        </div>
                    </div>

                    <div class="w-full col-span-2">
                        <div class="form-group">
                            <QuillTextEditor
                                class=""
                                :required="true"
                                :content="form.approverNotes"
                                :content-type="'html'"
                                :error="errors.approverNotes"
                                @delta="form.approverNotes = $event"
                                :label="$t('Notes for Approver')"
                            />
                        </div>
                    </div>
                    <div class="w-full col-span-2">
                        <div class="form-group">
                            <label class="form-label"
                                ><span></span>{{ $t("Documentation") }}:</label
                            >
                            <file-inputs
                                @file-changed="addDocumentedFiles"
                                :productFiles="documentedFiles"
                            />
                        </div>
                    </div>
                </div>
                <div
                    class="grid items-center grid-cols-2 gap-6 mt-5"
                    v-if="recordId"
                >
                    <div class="w-full">
                        <div class="form-group">
                            <select-input
                                v-model="requestForm.visibility"
                                :key="requestForm.visibility"
                                :required="true"
                                class=""
                                :label="$t('Visibility')"
                                :error="errors?.visibility ?? ''"
                            >
                                <option value="internal">
                                    {{ $t("Internal Only") }}
                                </option>
                                <option value="public">
                                    {{ $t("Public") }}
                                </option>
                            </select-input>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <number-input
                                :roundNearQuarter="true"
                                :forceLeftBound="true"
                                :showPrefix="false"
                                :canBeNull="true"
                                v-model="requestForm.time"
                                class=""
                                :required="true"
                                :label="$t('Accounted Time')"
                                :error="errors.time ?? ''"
                            />
                        </div>
                    </div>
                    <div class="w-full col-span-3">
                        <loading-button
                            :loading="isLoading"
                            style="cursor: pointer"
                            class="secondary-btn"
                            @click="requestsCommentsHandler"
                            >{{ $t("Create Comments") }}</loading-button
                        >
                    </div>
                    <div class="w-full col-span-3">
                        <div class="form-group">
                            <QuillTextEditor
                                class="w-full"
                                :content="requestForm.text"
                                :content-type="'html'"
                                :required="true"
                                :error="errors.text"
                                @delta="requestForm.text = $event"
                            />
                        </div>
                    </div>
                    <div class="w-full col-span-3">
                        <div class="form-group">
                            <label class="form-label"
                                ><span></span>{{ $t("Comments Files") }}:</label
                            >
                            <file-inputs
                                @file-changed="addCommentsFiles"
                                :productFiles="commentFiles"
                            />
                        </div>
                    </div>
                    <div class="w-full col-span-3">
                        <div class="form-group">
                            <div
                                v-if="comments.data && comments.data.length > 0"
                                style="background-color: #fff; overflow: auto"
                                class="mt-4 p-2 chat"
                            >
                                <div
                                    :class="[
                                        'chat_box',
                                        'comment',
                                        user?.id !== comment.userId
                                            ? 'chat_box_right'
                                            : '',
                                    ]"
                                    v-for="comment in comments?.data ?? []"
                                    :key="'comment-' + comment.id"
                                >
                                    <div class="chat-box-left">
                                        <div class="chat-user">
                                            <div class="flex items-center">
                                                <div
                                                    class="chat-user-img"
                                                    :style="{
                                                        backgroundImage:
                                                            'url(' +
                                                            (userProfilePictures?.[
                                                                comment.userId
                                                            ]?.profile_image ??
                                                                '') +
                                                            ')',
                                                    }"
                                                ></div>
                                                <div class="chat-user-name">
                                                    <h3>
                                                        {{
                                                            getInitials(
                                                                comment.userName ||
                                                                    usernames[
                                                                        comment
                                                                            .userId
                                                                    ]
                                                            )
                                                        }}
                                                    </h3>
                                                </div>
                                                <div class="created-user">
                                                    <p>
                                                        @{{
                                                            comment.userName ||
                                                            usernames[
                                                                comment.userId
                                                            ]
                                                        }}
                                                    </p>
                                                </div>
                                                <div
                                                    v-if="
                                                        comment?.visibility ===
                                                        'internal'
                                                    "
                                                    class="internal-tag"
                                                >
                                                    <p>{{ $t("Internal") }}</p>
                                                </div>
                                            </div>
                                            <!-- ticket.isArchived == 0 && -->
                                            <div
                                                v-if="
                                                    user?.id ===
                                                        comment.userId &&
                                                    comment.isArchived == 0 &&
                                                    comment.isAllowedDelete
                                                "
                                                class="dropdown"
                                            >
                                                <font-awesome-icon
                                                    icon="fa-solid fa-ellipsis-vertical"
                                                    class="hidden cursor-pointer mt-5 mr-2"
                                                ></font-awesome-icon>
                                                <div class="dropdown-content">
                                                    <p
                                                        @click="
                                                            openEditModal(
                                                                comment
                                                            )
                                                        "
                                                        class="pt-2 pl-2 pb-2 cursor-pointer dropdown-action"
                                                    >
                                                        {{ $t("Edit") }}
                                                    </p>
                                                    <hr />
                                                    <p
                                                        @click="
                                                            openDeleteModal(
                                                                comment.id
                                                            )
                                                        "
                                                        class="pt-2 pl-2 pb-2 cursor-pointer dropdown-action"
                                                    >
                                                        {{ $t("Delete") }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="chat-body">
                                            <div class="talktext">
                                                <div
                                                    class="ql-editor"
                                                    v-html="comment.text"
                                                ></div>
                                            </div>

                                            <div class="upload-gallary mt-5">
                                                <div
                                                    v-for="(
                                                        file, index
                                                    ) in comment.attachment"
                                                    :key="index"
                                                    class="upload-box"
                                                    :class="[
                                                        index > 0 ? '' : '',
                                                    ]"
                                                >
                                                    <div
                                                        class="upload-content flex items-center"
                                                    >
                                                        <font-awesome-icon
                                                            class="cursor-pointer mr-2"
                                                            icon="fa-solid fa-cloud-arrow-up"
                                                            @click="
                                                                downloadAttachment(
                                                                    file.id,
                                                                    file.viewable_name
                                                                )
                                                            "
                                                        />
                                                        <span>{{
                                                            file.viewable_name
                                                        }}</span>
                                                    </div>
                                                    <div
                                                        class="chat-user-img"
                                                        :style="{
                                                            backgroundImage:
                                                                'url(' +
                                                                (userProfilePictures?.[
                                                                    comment
                                                                        .userId
                                                                ]
                                                                    ?.profile_image ??
                                                                    '') +
                                                                ')',
                                                        }"
                                                    ></div>
                                                </div>
                                            </div>
                                            <div class="chat-meta">
                                                <p>
                                                    <span v-if="comment.time">
                                                        {{
                                                            $t(
                                                                "Accounted Time"
                                                            )
                                                        }}:
                                                        {{ comment.time }}
                                                    </span>
                                                    <span v-else>
                                                        {{
                                                            $t(
                                                                "Accounted Time"
                                                            )
                                                        }}:
                                                        {{ $t("Not Set") }}
                                                    </span>

                                                    {{
                                                        comment.isArchived == 1
                                                            ? " - " +
                                                              $t("Is Deleted")
                                                            : ""
                                                    }}
                                                </p>
                                                <p>
                                                    {{
                                                        getTimeString(
                                                            comment.createdAt
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat-box-right">
                                        <div class="chat-user">
                                            <div
                                                class="flex items-center justify-end w-full"
                                            >
                                                <div
                                                    v-if="
                                                        comment?.visibility ===
                                                        'internal'
                                                    "
                                                    class="internal-tag"
                                                >
                                                    <p>{{ $t("Internal") }}</p>
                                                </div>
                                                <div class="created-user">
                                                    <p>
                                                        @{{
                                                            comment.userName ||
                                                            usernames[
                                                                comment.userId
                                                            ]
                                                        }}
                                                    </p>
                                                </div>
                                                <div class="chat-user-name">
                                                    <h3>
                                                        {{
                                                            getInitials(
                                                                comment.userName ||
                                                                    usernames[
                                                                        comment
                                                                            .userId
                                                                    ]
                                                            )
                                                        }}
                                                    </h3>
                                                </div>
                                                <div
                                                    class="chat-user-img"
                                                    :style="{
                                                        backgroundImage:
                                                            'url(' +
                                                            (userProfilePictures?.[
                                                                comment.userId
                                                            ] ?? '') +
                                                            ')',
                                                    }"
                                                ></div>
                                            </div>
                                        </div>
                                        <div class="chat-body">
                                            <div class="talktext">
                                                <div
                                                    class="ql-editor"
                                                    v-html="comment.text"
                                                ></div>
                                            </div>

                                            <div class="upload-gallary mt-5">
                                                <div
                                                    v-for="(
                                                        file, index
                                                    ) in comment.attachment"
                                                    :key="index"
                                                    class="upload-box"
                                                    :class="[
                                                        index > 0 ? '' : '',
                                                    ]"
                                                >
                                                    <div
                                                        class="upload-content flex items-center"
                                                    >
                                                        <font-awesome-icon
                                                            class="cursor-pointer mr-2"
                                                            icon="fa-solid fa-cloud-arrow-up"
                                                            @click="
                                                                downloadAttachment(
                                                                    file.id,
                                                                    file.viewable_name
                                                                )
                                                            "
                                                        />
                                                        <span>{{
                                                            file.viewable_name
                                                        }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="chat-meta">
                                                <p>
                                                    <span v-if="comment.time">
                                                        {{
                                                            $t(
                                                                "Accounted Time"
                                                            )
                                                        }}:
                                                        {{ comment.time }}
                                                    </span>
                                                    <span v-else>
                                                        {{
                                                            $t(
                                                                "Accounted Time"
                                                            )
                                                        }}:
                                                        {{ $t("Not Set") }}
                                                    </span>

                                                    {{
                                                        comment.isArchived == 1
                                                            ? " - " +
                                                              $t("Is Deleted")
                                                            : ""
                                                    }}
                                                </p>
                                                <p>
                                                    {{
                                                        getTimeString(
                                                            comment.createdAt
                                                        )
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p
                                    ref="load"
                                    v-show="perPage < comments?.total"
                                    class="text-center mt-2"
                                >
                                    {{ $t("Loading more") }}....
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <router-link to="/requests" class="primary-btn mr-3">
                <span class="mr-1">
                    <CustomIcon name="cancelIcon" />
                </span>
                <span>{{ $t("Cancel") }}</span>
            </router-link>
            <loading-button
                v-if="$can(`${$route.meta.permission}.create`)"
                @click="store"
                :loading="isLoading"
                class="secondary-btn"
            >
                <span class="mr-1">
                    <CustomIcon name="saveIcon" />
                </span>
                {{ $t("Save and Proceed") }}
            </loading-button>
        </div>
    </div>

    <Modal
        title="Delete Comment"
        v-if="toggleDeleteModal"
        @toggleModal="toggleDeleteModal = $event"
        width="50%"
    >
        <template #body>
            <div class="flex ml-6 mr-6 border mb-3 p-3">
                <p>{{ $t("Are you sure you want to delete this comment?") }}</p>
            </div>
        </template>
        <template #footer>
            <button
                @click="deleteCommentHandler"
                type="button"
                class="secondary-btn"
            >
                {{ $t("Delete") }}
            </button>
            <button
                @click="toggleDeleteModal = false"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
    <Modal
        title="Edit Comment"
        v-if="toggleEditModal"
        @toggleModal="toggleEditModal = $event"
        width="50%"
    >
        <template #body>
            <div class="ml-6 mr-6 border mb-3 p-3">
                <QuillTextEditor
                    class="lg:w-full mt-2"
                    :content="requestForm.text"
                    :content-type="'html'"
                    :required="true"
                    :error="errors.text"
                    @delta="requestForm.text = $event"
                    :label="$t('Text')"
                />
                <number-input
                    :roundNearQuarter="true"
                    :forceLeftBound="true"
                    :showPrefix="false"
                    v-model="requestForm.time"
                    class="lg:w-full"
                    :required="true"
                    :canBeNull="true"
                    :label="$t('Accounted Time')"
                    :error="errors.time ?? ''"
                />
                <select-input
                    v-model="requestForm.visibility"
                    class="lg:w-full"
                    :label="$t('Visibility')"
                    :error="errors?.visibility ?? ''"
                    :required="true"
                >
                    <option value="internal">
                        {{ $t("Internal Only") }}
                    </option>
                    <option value="public">{{ $t("Public") }}</option>
                </select-input>
            </div>
        </template>
        <template #footer>
            <button
                @click="updateCommentHandler"
                type="button"
                class="secondary-btn"
            >
                {{ $t("Update") }}
            </button>
            <button
                @click="toggleEditModal = false"
                type="button"
                class="primary-btn mr-3"
            >
                {{ $t("Cancel") }}
            </button>
        </template>
    </Modal>
    <ImageModal
        v-if="toggleImageModal"
        @toggleModal="
            toggleImageModal = $event;
            imageSrc = '';
        "
        :src="imageSrc"
    />
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import NumberInput from "@/Components/NumberInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import FileInputs from "@/Components/FileInputs.vue";
import SelectInput from "@/Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";
import userProfilePicturesMixin from "@/Mixins/userProfilePicturesMixin.js";
import Modal from "@/Components/EditModal.vue";
import ImageModal from "@/Components/ImageModal.vue";

import { mapGetters } from "vuex";

export default {
    mixins: [userProfilePicturesMixin],

    computed: {
        ...mapGetters(["errors", "isLoading", "pusher"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("auth", [
            "user",
            "userPermissions",
            "users",
            "userProfilePictures",
        ]),
    },
    components: {
        LoadingButton,
        TextInput,
        NumberInput,
        MultiSelectInput,
        ImageModal,
        Modal,
        QuillTextEditor,
        SelectInput,
        TextAreaInput,
        FileInputs,
    },
    watch: {
        "requestForm.time"(val) {
            this.errors.time = "";
        },
        "requestForm.text"(val) {
            this.errors.text = "";
        },
        "form.partner"(val) {
            if (val != null && val != undefined) {
                this.$store
                    .dispatch("products/productsWithQuantity", {
                        partnerId: this.form.partner?.id ?? "", // filter products by the default price list of this company
                        perPage: 25,
                    })
                    .then((response) => {
                        console.log(response?.data?.products);
                        this.products = response?.data?.products ?? {
                            data: [],
                            links: [],
                        };
                    })
                    // eslint-disable-next-line no-console
                    .catch(() =>
                        console.warn("Vue Cal: Missing drag & drop module.")
                    );
            }
        },
    },
    props: {
        recordId: {
            type: Number,
            required: false,
        },
    },
    data() {
        return {
            requestForm: {
                text: "",
                time: "",
                productRequestStoreId: this.$route.params.id,
                visibility: "",
            },
            comments: {
                data: [],
            },
            commentFiles: {
                files: [],
            },
            products: [],
            commentFilesConvertedToBase64: [],
            perPage: 10,
            request: {},
            requestId: "",
            shouldShow: false,
            isReadonly: false,
            usernames: [],
            ams: [],

            imageSrc: "",
            toggleImageModal: false,

            toggleEditModal: false,
            toggleDeleteModal: false,
            selectedCommentId: "",

            form: {
                itemNumber: "",
                title: "",
                description: "",
                shortDescription: "",
                sellPrice: "",
                authorId: "",
                status: "",
                approverNotes: "",
                partnerPrice: "",
                discount: "",
                partner: "",
                product: "",
                serviceHours: "",
                serviceDescription: "",
                delimitation: "",
            },
            scriptFiles: {
                files: [],
            },
            scriptFilesConvertedToBase64: [],
            documentedFiles: {
                files: [],
            },
            documentedFilesConvertedToBase64: [],
            imageFiles: {
                files: [],
            },
            imageFilesConvertedToBase64: [],
        };
    },

    async mounted() {
        await this.$store.dispatch("companies/list", {
            perPage: 25,
            page: 1,
            customerType: "partner",
        });
        this.form.authorId = this.user.tenant_id;
        if (this.$route.name == "requestsCreate") {
            this.form.status = "Draft";
        }
        if (this.$route.params.id) {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "requests/show",
                    this.$route.params.id
                );
                const {
                    title,
                    description,
                    shortDescription,
                    sellPrice,
                    authorId,
                    partnerPrice,
                    discount,
                    status,
                    serviceDescription,
                    partner,
                    product,
                    serviceHours,
                    delimitation,
                    documentedImages,
                    approverNotes,
                    scripts,
                    productImages,
                    itemNumber,
                    // assignee, // this is missing
                } = response?.data?.data;
                this.form = {
                    id: this.$route.params.id,
                    title: title,
                    description: description,
                    shortDescription: shortDescription,
                    sellPrice: sellPrice,
                    authorId: authorId,
                    status: status,
                    partner: partner,
                    discount: discount,
                    partnerPrice: partnerPrice,
                    approverNotes: approverNotes,
                    itemNumber: itemNumber,
                    delimitation: delimitation,
                    product: product,
                    serviceHours: serviceHours,
                    serviceDescription: serviceDescription,
                    // assignee: assignee,
                };

                document.title = "Edit Request " + this.form?.title;
                this.request = response?.data?.data ?? {};
                this.requestId = response?.data?.data?.id ?? "";
                await this.fetchRequestComments();
                this.getUsernames();
                if (this.$refs.load) this.observer?.observe(this.$refs.load);

                if (scripts) {
                    const requiredScripts = scripts.map((script) => {
                        return {
                            name: script.viewableName,
                            base64: script.url.split(",")[1],
                            size: script.size,
                        };
                    });
                    this.scriptFiles.files = requiredScripts;
                    this.scriptFilesConvertedToBase64 = requiredScripts;
                }

                if (documentedImages) {
                    const requiredDocumentations = documentedImages.map(
                        (documentation) => {
                            return {
                                name: documentation.viewableName,
                                base64: documentation.url.split(",")[1],
                                size: documentation.size,
                            };
                        }
                    );
                    this.documentedFiles.files = requiredDocumentations;
                    this.documentedFilesConvertedToBase64 =
                        requiredDocumentations;
                }

                if (productImages) {
                    const requiredImages = productImages.map((prodImage) => {
                        return {
                            name: prodImage.viewableName,
                            base64: prodImage.url.split(",")[1],
                            size: prodImage.size,
                        };
                    });
                    this.imageFiles.files = requiredImages;
                    this.imageFilesConvertedToBase64 = requiredImages;
                }
            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
            // finally {
            //     // to set values in  assigne and reported, need these to send ecaim after create a comment
            //     // not needed this feature for now
            //     if (this.request?.userId) {
            //         try {
            //             const response = await this.$store.dispatch(
            //                 "auth/show",
            //                 {
            //                     id: this.request?.userId,
            //                 }
            //             );
            //             this.form.reporter = response?.data ?? "";
            //         } catch (e) {
            //             console.log(e);
            //         }
            //     }

            //     if (!!this.form.assignee) {
            //         const response = await this.$store.dispatch("auth/show", {
            //             id: this.form.assignee,
            //         });
            //         this.form.assignee = response?.data ?? "";
            //     }
            // }
        }
    },
    methods: {
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
        addDocumentedFiles(files) {
            this.documentedFilesConvertedToBase64 = [];
            files.forEach((file) => {
                // only convert to base64 if the file is of type Blob
                if (file instanceof Blob) {
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => {
                        const requiredData = reader.result.split(",");
                        this.documentedFilesConvertedToBase64.push({
                            name: file.name,
                            base64: requiredData[1],
                            size: file.size,
                        });
                    };
                    reader.onerror = (error) => {
                        console.log(error);
                    };
                } else {
                    this.documentedFilesConvertedToBase64.push({
                        ...file,
                    });
                }
            });
        },

        addImageFiles(files) {
            this.imageFilesConvertedToBase64 = [];
            files.forEach((file) => {
                // only convert to base64 if the file is of type Blob
                if (file instanceof Blob) {
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => {
                        const requiredData = reader.result.split(",");
                        this.imageFilesConvertedToBase64.push({
                            name: file.name,
                            base64: requiredData[1],
                            size: file.size,
                        });
                    };
                    reader.onerror = (error) => {
                        console.log(error);
                    };
                } else {
                    this.imageFilesConvertedToBase64.push({
                        ...file,
                    });
                }
            });
        },

        addCommentsFiles(files) {
            this.commentFilesConvertedToBase64 = [];
            files.forEach((file) => {
                // only convert to base64 if the file is of type Blob
                if (file instanceof Blob) {
                    let reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = () => {
                        const requiredData = reader.result.split(",");
                        this.commentFilesConvertedToBase64.push({
                            name: file.name,
                            base64: requiredData[1],
                            size: file.size,
                        });
                    };
                    reader.onerror = (error) => {
                        console.log(error);
                    };
                } else {
                    this.commentFilesConvertedToBase64.push({
                        ...file,
                    });
                }
            });
        },
        /**
         * hits the licenses create API
         */
        async store() {
            try {
                this.$store.commit("isLoading", true);
                if (this.$route.params.id) {
                    await this.$store.dispatch("requests/update", {
                        id: this.$route.params.id,
                        data: {
                            isShowOnAdmin: true,
                            ...(this.form.itemNumber && {
                                itemNumber: this.form.itemNumber,
                            }),
                            ...(this.form.title && { title: this.form.title }),
                            ...(this.form.description && {
                                description: this.form.description,
                            }),
                            ...(this.form.shortDescription && {
                                shortDescription: this.form.shortDescription,
                            }),
                            ...(this.form.sellPrice && {
                                sellPrice: this.form.sellPrice,
                            }),
                            ...(this.form.discount && {
                                discount: this.form.discount,
                            }),
                            ...(this.form.partnerPrice && {
                                partnerPrice: this.form.partnerPrice,
                            }),
                            ...(this.form.partner && {
                                partnerId: this.form.partner?.id,
                            }),
                            ...(this.form.product && {
                                productId: this.form.product?.id,
                            }),
                            ...(this.form.serviceHours && {
                                serviceHours: this.form.serviceHours,
                            }),
                            ...(this.form.serviceDescription && {
                                serviceDescription:
                                    this.form.serviceDescription,
                            }),
                            ...(this.form.delimitation && {
                                delimitation: this.form.delimitation,
                            }),
                            ...(this.form.authorId && {
                                authorId: this.form.authorId,
                            }),
                            ...(this.form.status && {
                                status: this.form.status,
                            }),
                            ...(this.form.approverNotes && {
                                approverNotes: this.form.approverNotes,
                            }),
                            ...(this.scriptFilesConvertedToBase64 &&
                                this.scriptFilesConvertedToBase64.length >
                                    0 && {
                                    scripts: this.scriptFilesConvertedToBase64,
                                }),
                            ...(this.imageFilesConvertedToBase64 &&
                                this.imageFilesConvertedToBase64.length > 0 && {
                                    productImages:
                                        this.imageFilesConvertedToBase64,
                                }),
                            ...(this.documentedFilesConvertedToBase64 &&
                                this.documentedFilesConvertedToBase64.length >
                                    0 && {
                                    documentedImages:
                                        this.documentedFilesConvertedToBase64,
                                }),
                        },
                    });
                } else {
                    await this.$store.dispatch("requests/create", {
                        isShowOnAdmin: true,
                        ...(this.form.itemNumber && {
                            itemNumber: this.form.itemNumber,
                        }),
                        ...(this.form.title && { title: this.form.title }),
                        ...(this.form.description && {
                            description: this.form.description,
                        }),
                        ...(this.form.shortDescription && {
                            shortDescription: this.form.shortDescription,
                        }),
                        ...(this.form.sellPrice && {
                            sellPrice: this.form.sellPrice,
                        }),
                        ...(this.form.partner && {
                            partnerId: this.form.partner?.id,
                        }),
                        ...(this.form.product && {
                            productId: this.form.product?.id,
                        }),
                        ...(this.form.serviceHours && {
                            serviceHours: this.form.serviceHours,
                        }),
                        ...(this.form.serviceDescription && {
                            serviceDescription: this.form.serviceDescription,
                        }),
                        ...(this.form.delimitation && {
                            delimitation: this.form.delimitation,
                        }),
                        ...(this.form.discount && {
                            discount: this.form.discount,
                        }),
                        ...(this.form.partnerPrice && {
                            partnerPrice: this.form.partnerPrice,
                        }),
                        ...(this.form.authorId && {
                            authorId: this.form.authorId,
                        }),
                        ...(this.form.status && { status: this.form.status }),
                        ...(this.form.approverNotes && {
                            approverNotes: this.form.approverNotes,
                        }),
                        ...(this.scriptFilesConvertedToBase64 &&
                            this.scriptFilesConvertedToBase64.length > 0 && {
                                scripts: this.scriptFilesConvertedToBase64,
                            }),
                        ...(this.imageFilesConvertedToBase64 &&
                            this.imageFilesConvertedToBase64.length > 0 && {
                                productImages: this.imageFilesConvertedToBase64,
                            }),
                        ...(this.documentedFilesConvertedToBase64 &&
                            this.documentedFilesConvertedToBase64.length >
                                0 && {
                                documentedImages:
                                    this.documentedFilesConvertedToBase64,
                            }),
                    });
                }
                this.$router.push("/requests");
            } catch (e) {
                console.log(e);
            }
        },

        async deleteCommentHandler() {
            try {
                await this.$store.dispatch(
                    "requestComments/destroy",
                    this.selectedCommentId
                );
                await this.fetchRequestComments();
                this.toggleDeleteModal = false;
                this.selectedCommentId = "";
            } catch (e) {}
        },

        async updateCommentHandler() {
            try {
                this.$store.commit("isLoading", true);
                const requestCommentResponse = await this.$store.dispatch(
                    "requestComments/update",
                    {
                        id: this.selectedCommentId,
                        data: {
                            ...this.requestForm,
                            productRequestStoreId: this.requestId,
                            userId: this.user.id,
                            userType: "employee",
                        },
                    }
                );
                this.toggleEditModal = false;
                this.pusher.sendMessage(
                    JSON.stringify({
                        ticket: requestCommentResponse?.data?.ticket ?? null,
                        action: "CommentUpdated",
                        userName:
                            this.user?.first_name + " " + this.user?.last_name,
                    }),
                    requestCommentResponse?.data?.ticketUsers ?? [],
                    null
                );
                this.shouldShow = false;
                await this.fetchRequestComments();
                this.selectedCommentId = "";
                this.ticketForm = {
                    time: "",
                    text: "",
                    ticketId: this.ticketId,
                };
            } catch (e) {
            } finally {
                this.shouldShow = true;
            }
        },

        openEditModal(comment) {
            this.selectedCommentId = comment.id;
            this.requestForm.text = comment.text;
            this.requestForm.time = comment.time;
            this.requestForm.productRequestStoreId = this.requestId;
            this.requestForm.visibility = comment.visibility ?? "";
            this.toggleEditModal = true;
        },
        openDeleteModal(id) {
            this.selectedCommentId = id;
            this.toggleDeleteModal = true;
        },

        getInitials(name) {
            const tokens = name?.split(" ");
            if (tokens)
                return `${tokens?.[0]?.[0] ?? ""}${
                    tokens?.[1]?.[0] ?? ""
                }`.toUpperCase();
            else return "";
        },
        getTimeString(time) {
            return (
                this.$dateFormatter(time?.substr(0, 10), this.globalLanguage) +
                " " +
                time?.substr(11, 8)
            );
        },
        async downloadAttachment(id, viewableName) {
            try {
                // check if the file is an image
                const imageFileRegex = /\.(jpg|jpeg|png|gif|bmp|svg)$/i;
                if (imageFileRegex.test(viewableName)) {
                    const response = await this.$store.dispatch(
                        "requests/downloadRequestAttachmentBase64",
                        {
                            id: id,
                            queryParams: { name: viewableName },
                        }
                    );
                    console.log("res", response);
                    console.log("res", response?.data);
                    var reader = new FileReader();
                    reader.readAsDataURL(response?.data);
                    reader.onerror = (err) => {
                        console.log(err);
                    };
                    reader.onloadend = () => {
                        //convert to base64 and set to imageSrc and toggle the image modal to view the image
                        this.imageSrc = reader.result;
                        this.toggleImageModal = true;
                    };
                } else {
                    this.$store.dispatch("requests/downloadRequestAttachment", {
                        id: id,
                        queryParams: { name: viewableName },
                    });
                }
            } catch (e) {
                console.log(e);
            }
        },

        getUsernames() {
            let username = [];
            this.comments?.data.forEach(async (comment) => {
                if (!username.includes(comment.userId)) {
                    username.push(comment.userId);
                    let response = {};
                    if (comment.userId && comment.userId !== "n.a.") {
                        response = await this.$store.dispatch("auth/show", {
                            id: comment.userId,
                        });
                    }
                    if (
                        !response?.data?.first_name &&
                        !response?.data?.last_name
                    ) {
                        this.usernames[comment.userId] = "User not found";
                    } else
                        this.usernames[comment.userId] =
                            (response?.data?.first_name ?? "") +
                            " " +
                            (response?.data?.last_name ?? "");
                }
            });
        },

        /**
         * fetches ticket comments listing
         */
        fetchRequestComments() {
            return new Promise(async (resolve, reject) => {
                try {
                    const responseComments = await this.$store.dispatch(
                        "requestComments/list",
                        {
                            // id: this.ticketId,
                            // id: this.$route.params.id,
                            id: this.requestId,
                            queryParams: {
                                perPage: this.perPage,
                            },
                        }
                    );
                    this.comments = responseComments?.data?.data ?? [];
                    this.getUserProfilePictures(
                        this.comments?.data ?? [],
                        "userId"
                    );
                    resolve();
                } catch (e) {
                    reject(e);
                }
            });
        },

        async requestsCommentsHandler() {
            try {
                this.$store.commit("isLoading", true);
                const requestCommentResponse = await this.$store.dispatch(
                    "requestComments/create",
                    {
                        ...this.requestForm,
                        // ticketId: this.$route.params.id,
                        productRequestStoreId: this.requestId,
                        userId: this.user.id,
                        attachment: this.commentFilesConvertedToBase64,
                        userType: "employee",
                    }
                );
                // this.commentFiles = [];
                this.commentFilesConvertedToBase64 = [];
                await this.fetchRequestComments();
                this.getUsernames();
                const response = await this.$store.dispatch(
                    "requests/show",
                    this.$route.params.id
                );
                await this.$nextTick();
                this.request = response?.data?.tickets ?? {};
                this.shouldShow = false;
                this.requestForm = {
                    text: "",
                    time: "",
                    productRequestStoreId: this.requestId,
                };

                // try {
                //     await this.$store.dispatch("requestComments/sendMail", {
                //         id: requestCommentResponse?.data?.id,
                //         data: {
                //             reporter: this.form?.reporter?.email,
                //             assignee: this.form?.assignee?.email,
                //         },
                //     });
                // } catch (e) {
                //     console.log(e);
                // }
                this.pusher.sendMessage(
                    JSON.stringify({
                        ticket: requestCommentResponse?.data?.ticket ?? null,
                        action: "CommentCreated",
                        userName:
                            this.user?.first_name + " " + this.user?.last_name,
                    }),
                    requestCommentResponse?.data?.ticketUsers ?? [],
                    null
                );
            } catch (err) {
            } finally {
                this.shouldShow = true;
            }
        },
    },
};
</script>

<style scoped>
.ql-container.ql-snow {
    padding-bottom: 45px;
}

.dropdown-action:hover {
    background-color: #2957a4;
    color: white;
}

.dropdown {
    position: relative;
    display: inline-block;
    margin-top: -10px;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
    right: 0;
}

.dropdown:hover .dropdown-content {
    display: block;
}

.comment {
    position: relative;
}

.comment:hover svg {
    display: block !important;
}

.chat_box {
    border-bottom: 1px solid #e7eaed;
    padding: 15px 0px;
}

.chat::-webkit-scrollbar {
    width: 5px;
    height: 3px;
}

.chat::-webkit-scrollbar-track {
    box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
}

.chat::-webkit-scrollbar-thumb {
    background-color: darkgrey;
    outline: 1px solid slategrey;
    border-radius: 5px;
}

.talk-bubble {
    margin: 20px;
    display: inline-block;
    position: relative;
    /* width: 200px; */
    height: auto;
    background-color: white;
}

.round {
    border-radius: 30px;
    -webkit-border-radius: 30px;
    -moz-border-radius: 30px;
}

.tri-right.border.left-top:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -40px;
    right: auto;
    top: -8px;
    bottom: auto;
    border: 32px solid;
    border-color: #666 transparent transparent transparent;
}

.tri-right.left-top:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -22px;
    right: auto;
    top: 0px;
    bottom: auto;
    border: 22px solid;
    border-color: white transparent transparent transparent;
}

/* Right triangle, left side slightly down */
.tri-right.border.left-in:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -22px;
    right: auto;
    top: 30px;
    bottom: auto;
    border: 20px solid;
    border-color: #666 #666 transparent transparent;
}

.tri-right.left-in:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -12px;
    right: auto;
    top: 22px;
    bottom: auto;
    border: 12px solid;
    border-color: white white transparent transparent;
}

/*Right triangle, placed bottom left side slightly in*/
.tri-right.border.btm-left:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: -8px;
    right: auto;
    top: auto;
    bottom: -40px;
    border: 32px solid;
    border-color: transparent transparent transparent #666;
}

.tri-right.btm-left:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: 0px;
    right: auto;
    top: auto;
    bottom: -20px;
    border: 22px solid;
    border-color: transparent transparent transparent white;
}

/*Right triangle, placed bottom left side slightly in*/
.tri-right.border.btm-left-in:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: 30px;
    right: auto;
    top: auto;
    bottom: -40px;
    border: 20px solid;
    border-color: #666 transparent transparent #666;
}

.tri-right.btm-left-in:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: 38px;
    right: auto;
    top: auto;
    bottom: -20px;
    border: 12px solid;
    border-color: white transparent transparent white;
}

/*Right triangle, placed bottom right side slightly in*/
.tri-right.border.btm-right-in:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: 30px;
    bottom: -40px;
    border: 20px solid;
    border-color: #666 #666 transparent transparent;
}

.tri-right.btm-right-in:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: 38px;
    bottom: -20px;
    border: 12px solid;
    border-color: white white transparent transparent;
}

/*
	left: -8px;
  right: auto;
  top: auto;
	bottom: -40px;
	border: 32px solid;
	border-color: transparent transparent transparent #666;
	left: 0px;
  right: auto;
  top: auto;
	bottom: -20px;
	border: 22px solid;
	border-color: transparent transparent transparent white;

/*Right triangle, placed bottom right side slightly in*/
.tri-right.border.btm-right:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -8px;
    bottom: -40px;
    border: 20px solid;
    border-color: #666 #666 transparent transparent;
}

.tri-right.btm-right:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: 0px;
    bottom: -20px;
    border: 12px solid;
    border-color: white white transparent transparent;
}

/* Right triangle, right side slightly down*/
.tri-right.border.right-in:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -40px;
    top: 30px;
    bottom: auto;
    border: 20px solid;
    border-color: #666 transparent transparent #666;
}

.tri-right.right-in:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -12px;
    top: 22px;
    bottom: auto;
    border: 12px solid;
    border-color: white transparent transparent white;
}

/* Right triangle placed top right flush. */
.tri-right.border.right-top:before {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -40px;
    top: -8px;
    bottom: auto;
    border: 32px solid;
    border-color: #666 transparent transparent transparent;
}

.tri-right.right-top:after {
    content: " ";
    position: absolute;
    width: 0;
    height: 0;
    left: auto;
    right: -20px;
    top: 0px;
    bottom: auto;
    border: 20px solid;
    border-color: white transparent transparent transparent;
}

/* talk bubble contents */

.is-archived {
    text-decoration: line-through;
}

:deep(.multiselect__single) {
    font-size: inherit;
}

:deep(.multiselect__tags) {
    min-height: 20px;
}
</style>
