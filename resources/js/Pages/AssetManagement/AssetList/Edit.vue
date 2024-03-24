<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <h6
            class="text-xl font-normal leading-normal mt-0 mb-2 text-cyan-800"
        ></h6>
        <form @submit.prevent="update">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $t("Asset List Edit") }}</h3>
                </div>
                <div class="card-body">
                    <div class="grid items-center grid-cols-2 gap-6">
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-if="form.employeeId"
                                    v-model="form.employeeId"
                                    :errors="errors.employeeId"
                                    :options="userProfiles?.data ?? []"
                                    :multiple="false"
                                    :textLabel="$t('Employees')"
                                    :customLabel="customLabel"
                                    :trackBy="'id'"
                                    :moduleName="'userProfile'"
                                    :required="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :isReadonly="true"
                                    :required="true"
                                    v-model="form.personalNumber"
                                    :error="errors.personalNumber"
                                    :label="$t('Personal Number')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :isReadonly="true"
                                    :required="true"
                                    v-model="form.name"
                                    :error="errors.name"
                                    :label="$t('Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <MultiSelectInput
                                    v-model="form.teamId"
                                    :key="randomNumber"
                                    v-if="form.teamId"
                                    :errors="errors.teamId"
                                    :options="teams?.data ?? []"
                                    :multiple="true"
                                    :textLabel="$t('Team')"
                                    label="name"
                                    :trackBy="'id'"
                                    :moduleName="'teams'"
                                    :taggable="true"
                                    :required="true"
                                    :isDisabled="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    :isReadonly="true"
                                    :required="true"
                                    v-model="form.location"
                                    :key="form.location"
                                    :error="errors.location"
                                    :label="$t('Location')"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap">
                        <!--   <MultiSelectInput
                            class="pb-8 pr-6 w-full lg:w-1/2"
                            v-model="form.employeeText"
                            :key="form.employeeText"
                            v-if="form.teamId"
                            :errors="errors.employeeText"
                            :options="assetEmployeeText?.data ?? []"
                            :multiple="true"
                            :textLabel="$t('Employee Text')"
                            label="assetEmployeeText"
                            :trackBy="'id'"
                            :moduleName="'employeeText'"
                            :taggable="true"
                            :isReadonly="true"
                        /> -->
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4 mr-12">
                <router-link
                    :to="`/asset-lists?page=${this.$route.query.page}`"
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
                    class="secondary-btn"
                >
                    <span class="mr-1">
                        <CustomIcon name="saveIcon" />
                    </span>
                    {{ $t("Save and Proceed") }}
                </loading-button>
            </div>
        </form>
        <div class="card asset-list mt-5 mx-16">
            <div class="flex">
                <div class="asset-profile">
                    <div class="user">
                        <img
                            :src="
                                !!userProfile?.profile_image
                                    ? userProfile?.profile_image
                                    : defaultImg
                            "
                            alt="img"
                        />
                    </div>
                    <h3>
                        {{
                            this.employeeDetail.firstName +
                            " " +
                            this.employeeDetail.lastName
                        }}
                    </h3>
                </div>
                <div class="asset-details">
                    <ul>
                        <li>
                            <h3>{{ $t("Personalnummer") }}:</h3>
                            <p>
                                {{
                                    this.employeeDetail?.personalNumber ?? " - "
                                }}
                            </p>
                        </li>
                        <li>
                            <h3>{{ $t("Business Unit") }}:</h3>
                            <p>
                                {{ this.employeeDetail?.departments ?? " - " }}
                            </p>
                        </li>
                        <li>
                            <h3>{{ $t("Department") }}:</h3>
                            <p>
                                {{ this.employeeDetail?.departments ?? " - " }}
                            </p>
                        </li>
                        <li>
                            <h3>{{ $t("Supervisor") }}:</h3>
                            <!-- not coming from backend -->
                            <p>
                                {{ this.employeeDetail?.supervisor ?? " - " }}
                            </p>
                        </li>
                        <li>
                            <h3>{{ $t("Location") }}:</h3>
                            <p>{{ this.employeeDetail?.location ?? " - " }}</p>
                        </li>
                        <li>
                            <h3>{{ $t("Date") }}:</h3>
                            <p>{{ this.employeeDetail?.joinDate ?? " - " }}</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="asset-desc">
                <p key="index">
                    <QuillTextEditor
                        :isReadonly="true"
                        class="pb-8"
                        :content="form?.employeeText"
                        @delta="updateEmployeeText($event)"
                        height="auto"
                    />
                </p>
            </div>
            <div
                class="asset-listing"
                v-if="form.assetArticles && form.assetArticles.length > 0"
            >
                <h3>Assets</h3>
                <div class="asset-card-list gap-5 grid-cols-1 lg:grid-cols-3">
                    <div
                        class=""
                        v-for="(asset, index) in form.assetArticles"
                        :key="'asset-' + index"
                    >
                        <div class="asset-card" :key="'asset-' + index">
                            <div class="asset-card-img">
                                <div
                                    v-if="asset.assetImage.base64"
                                    :style="{
                                        backgroundImage:
                                            'url(data:image/png;base64,' +
                                            (asset.assetImage.base64 ?? '') +
                                            ')',
                                    }"
                                    class="flex justify-center items-center mb-5 mt-5 relative image-style"
                                    style="
                                        background-color: #2957a4;
                                        color: white;
                                    "
                                ></div>

                                <div
                                    v-else
                                    v-bind:style="{
                                        backgroundImage:
                                            'url(' + imgPlaceholder + ')',
                                    }"
                                    class="flex justify-center items-center mb-5 mt-5 relative image-style"
                                ></div>
                            </div>

                            <div class="asset-card-content">
                                <h4>{{ asset.assetName }}</h4>
                                <p>
                                    {{ $t("Serial number") }} :
                                    {{ asset.serialNo }}
                                </p>
                                <p>{{ $t("Model") }} : {{ asset.model }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
import profilePic from "@/assets/images/user.png";
import prodImgPlaceholder from "@/assets/images/imagePlaceholder.png";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";

export default {
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("suppliers", ["suppliers"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("userTeams", ["teams"]),
        ...mapGetters("assetEmployeeText", ["assetEmployeeText"]),
    },
    components: {
        LoadingButton,
        TextInput,
        MultiSelectInput,
        NumberInput,
        EditModal,
        SelectInput,
        PageHeader,
        QuillTextEditor,
    },
    async mounted() {
        this.$store.commit("showLoader", true);
        await this.$store.dispatch("assetEmployeeText/list");
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
                    to: "/asset-lists",
                },
                {
                    text: this.$t("Asset Lists"),
                    to: `/asset-lists?page=${this.$route.query.page}`,
                },
                {
                    text: this.$t("Edit"),
                    active: true,
                }
            ],
            defaultImg: profilePic,
            imgPlaceholder: prodImgPlaceholder,
            form: {
                personalNumber: "",
                name: "",
                assetType: "",
                employeeId: "",
                location: "",
                teamId: "",
                assetArticles: [],
                employeeText: "",
            },
            userProfile: "",
            editAssetArticle: [],
            employeeDetail: "",
            employeeDescriptionText: "",

            randomNumber: "",
            toggleEditAssetListModal: false,
        };
    },
    watch: {
        "form.employeeId": async function (newEmployeeId, oldEmployeeId) {
            let response = await this.$store.dispatch(
                "assetList/getEmployee",
                newEmployeeId["id"]
            );
            response = response?.data?.employee;

            this.form.name = response.name;
            this.form.personalNumber = response?.personalNumber;
            if (response.location?.street && response.location?.state)
                this.form.location =
                    response.location?.street ??
                    +" " + response.location?.state;
            else {
                this.form.location = "";
            }
            this.form.teamId = response.teams;
            this.randomNumber = Math.random() * 100;

            await this.$nextTick();
        },
    },
    methods: {
        updateEmployeeText(event){  
            this.form.employeeText=event;
        },
        async refresh() {
            await this.$store.dispatch("userProfile/list");
            await this.$store.dispatch("userTeams/list");
            let response = await this.$store.dispatch(
                "assetList/show",
                this.$route.params.id
            );
            document.title =
                "Edit Asset List " + response?.data?.data?.assetNumber;
            let employee = this.userProfiles?.data?.find((userProfile) => {
                return userProfile.id == response?.data?.data?.employeeId;
            });
            this.form.employeeId = employee;
            this.employeeDetail = employee;
            // pass form.employeeId id to get the image
            const getUserByIdRes = await this.$store.dispatch("auth/show", {
                id: this.form.employeeId.userId,
            });
            this.$store.commit("showLoader", false);
            this.userProfile = getUserByIdRes?.data;
            this.form.assetArticles = response?.data?.data?.assetArticles;
            /*let requiredText = response?.data?.data?.employeeTexts.map(
                (txt) => {
                    return {
                        id: txt.id,
                        assetEmployeeText: txt.text,
                    };
                }
            );
            this.employeeDescriptionText = requiredText;
             */
            this.form.employeeText = response?.data?.data?.employeeText;
        },
        async update() {
            try {
                this.$store.commit("isLoading", true);
                await this.$store.dispatch("assetList/update", {
                    id: this.$route.params.id,
                    data: {
                        ...this.form,
                        employeeId: this.form.employeeId["id"] ?? "",
                    },
                });
                await this.$store.dispatch("assetList/list");
                this.$router.push(
                    `/asset-lists?page=${this.$route.query.page}`
                );
            } catch (e) {}
        },
        customLabel(props) {
            return `${props?.firstName ?? ""} ${props?.lastName ?? ""}`;
        },
        getInitials(name) {
            const tokens = name?.split(" ");
            if (tokens)
                return `${tokens?.[0]?.[0] ?? ""}${
                    tokens?.[1]?.[0] ?? ""
                }`.toUpperCase();
            else return "";
        },
    },
};
</script>

<style scoped>
.image-style {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 180px;
    width: 350px;
    overflow: hidden;
}
:deep(.ql-toolbar) {
    display: none !important;
}
:deep(.ql-container) {
    border: none !important;
}
:deep(.ql-tooltip) {
    display: none !important;
}
</style>
