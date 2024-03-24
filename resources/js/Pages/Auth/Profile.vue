<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <form @submit.prevent="update">
            <div class="card">
                <div class="card-body">
                    <div class="grid items-start grid-cols-4 gap-6">
                        <div class="w-full col-span-2 flex">
                            <div class="profile-upload-image">
                                <input
                                    @change="handleFileChange"
                                    type="file"
                                    ref="fileInput"
                                    style="display: none"
                                />
                                <div
                                    @mouseover="hovering = true"
                                    @mouseleave="hovering = false"
                                    :style="{
                                        backgroundImage:
                                            'url(' +
                                            (user?.profile_image ?? '') +
                                            ')',
                                    }"
                                    class="profile-img"
                                    style="
                                        background-position: center center;
                                        background-repeat: no-repeat;
                                        background-size: cover;
                                        background-color: #2957a4;
                                        color: white;
                                        overflow: hidden;
                                    "
                                >
                                    <p
                                        v-if="
                                            user?.first_name &&
                                            !user?.profile_image
                                        "
                                        style="font-size: 1.5rem"
                                    >
                                        {{
                                            getInitials(
                                                (user?.first_name ?? "") +
                                                    " " +
                                                    (user?.last_name ?? "")
                                            )
                                        }}
                                    </p>
                                    <div
                                        class="upload-btn"
                                        v-if="hovering"
                                        @click="this.$refs.fileInput.click()"
                                    >
                                        <CustomIcon name="camimageIcon" />
                                    </div>
                                </div>
                            </div>
                            <div class="m-5">
                                <p class="font-bold text-xl">
                                    {{ user?.first_name }} {{ user?.last_name }}
                                </p>
                                <p
                                    v-if="jobData?.jobTitle"
                                    class="text-gray-500 mt-2"
                                >
                                    {{ jobData?.jobTitle }}
                                </p>
                                <p
                                    v-if="jobData?.teams?.length"
                                    class="text-gray-500"
                                >
                                    {{ teamNames(jobData?.teams ?? []) }}
                                </p>
                                <p
                                    v-if="jobData?.teams?.length"
                                    class="text-gray-500"
                                >
                                    {{ departmentNames(jobData?.teams ?? []) }}
                                </p>
                                <p
                                    v-if="jobData?.personalNumber"
                                    class="text-gray-500 mt-2"
                                >
                                    {{ jobData?.personalNumber }}
                                </p>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="mt-6 px-5">
                                <h3 class="card-title">{{ $t("Contact") }}:</h3>
                                <p class="text-gray-500 mt-2">
                                    {{ user?.email }}
                                </p>
                                <p
                                    v-if="user?.phone"
                                    class="text-gray-500 mt-2"
                                >
                                    {{ user?.phone }}
                                </p>
                                <p
                                    v-if="user?.mobile"
                                    class="text-gray-500 mt-2"
                                >
                                    {{ user?.mobile }}
                                </p>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="mt-6 px-5">
                                <h3 class="card-title">
                                    {{ $t("Location") }}:
                                </h3>
                                <p
                                    v-if="jobData?.location?.name"
                                    class="text-gray-500 mt-2"
                                >
                                    {{ jobData?.location?.name }}
                                </p>
                                <p
                                    v-if="jobData?.location?.street"
                                    class="text-gray-500 mt-2"
                                >
                                    {{ jobData?.location?.street }}
                                </p>
                                <p
                                    v-if="
                                        jobData?.location?.zip_code ||
                                        jobData?.location?.city
                                    "
                                    class="text-gray-500 mt-2"
                                >
                                    {{ jobData?.location?.zip_code }}
                                    {{ jobData?.location?.city }}
                                </p>
                                <p
                                    v-if="jobData?.location?.state"
                                    class="text-gray-500 mt-2"
                                >
                                    {{ jobData?.location?.state }}
                                </p>
                                <p
                                    v-if="jobData?.location?.country"
                                    class="text-gray-500 mt-2"
                                >
                                    {{ jobData?.location?.country }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="grid items-center grid-cols-2 gap-6 mt-5">
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.first_name"
                                    :required="true"
                                    :error="errors.name"
                                    class=""
                                    :label="$t('First Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="form.last_name"
                                    :required="true"
                                    :error="errors.name"
                                    class=""
                                    :label="$t('Last Name')"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <select-input
                                    v-model="form.language"
                                    :error="errors.language"
                                    class=""
                                    :label="$t('Language')"
                                >
                                    <option value="de">
                                        {{ $t("Deutsch") }}
                                    </option>
                                    <option value="en">
                                        {{ $t("English") }}
                                    </option>
                                </select-input>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <text-input
                                    v-model="user.email"
                                    class=""
                                    :label="$t('Email')"
                                    :readOnly="true"
                                />
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <CustomIcon name="lckIcon" />
                                        </span>
                                    </div>
                                    <text-input
                                        :key="form.inputType"
                                        v-model="form.password"
                                        :error="errors.password"
                                        :type="inputType"
                                        class=""
                                        :label="$t('Password')"
                                        :show-password="showPassword"
                                        @child-event="handleChildEvent"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <CustomIcon name="lckIcon" />
                                        </span>
                                    </div>
                                    <text-input
                                        v-model="form.confirmPassword"
                                        :error="errors.confirmPassword"
                                        :key="form.inputType"
                                        :type="inputType"
                                        :show-password="showPassword"
                                        @child-event="handleChildEvent"
                                        :label="$t('Confirm Password')"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 w-full flex flex-row-reverse">
                <loading-button :loading="isLoading" class="secondary-btn">
                    <span class="mr-2">
                        <CustomIcon name="updateIcon" />
                    </span>
                    <span>{{ $t("Update") }}</span>
                </loading-button>
            </div>
        </form>
    </div>
</template>

<script>
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import profilePictureMixin from "../../Mixins/profilePictureMixin";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    mixins: [profilePictureMixin],
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("auth", ["user"]),
    },
    components: {
        LoadingButton,
        TextInput,
        SelectInput,
        PageHeader,
    },

    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: this.$t("Profile"),
                    active: true,
                },
            ],
            hovering: false,
            showPassword: false,
            inputType: "password",
            userImage: localStorage.getItem("userProfileImage") || null,
            apiCalled: false,
            jobData: {},
            form: {
                first_name: this.user?.first_name,
                last_name: this.user?.last_name,
                language: this.user?.language ?? "de",
                password: "",
                confirmPassword: "",
            },
        };
    },
    mounted() {
        this.fetchData();
    },
    watch: {
        user() {
            this.fetchData();
        },
    },
    methods: {
        /**
         * fetches and returns department names from the teams array as string
         * @param {teams} teams array
         */
        departmentNames(teams) {
            try {
                let departmentNames = teams.map(
                    (team) => team?.department?.name ?? ""
                );
                departmentNames = Array.from(new Set(departmentNames));
                return departmentNames.join(", ");
            } catch (e) {
                return "";
            }
        },
        /**
         * fetches and returns teams names from the teams array as string
         * @param {teams} teams array
         */
        teamNames(teams) {
            try {
                let teamNames = teams.map((team) => team?.name ?? "");
                teamNames = Array.from(new Set(teamNames));
                return teamNames.join(", ");
            } catch (e) {
                return "";
            }
        },
        /**
         * set the form fields from the user state
         * calls the 'user-profile/job-by-user/{id}' route to fetch job data from the user id and set the jobData object
         * from the response
         */
        async fetchData() {
            this.form = {
                first_name: this.user?.first_name,
                last_name: this.user?.last_name,
                language: this.user?.language ?? "de",
                password: "",
                confirmPassword: "",
            };
            if (this.user?.id) {
                const response = await this.$store.dispatch(
                    "auth/profile",
                    this.user?.id
                );
                this.jobData = response?.data?.data ?? {};
            }
        },
        async handleFileChange(event) {
            try {
                const file = event.target.files[0];
                await this.setProfilePicture(file, this.user);
                this.$store
                    .dispatch("auth/show", { id: this.user.id })
                    .then((res) => {
                        this.$store.commit("auth/set_user", res?.data ?? {});
                    });
            } catch (e) {
                console.log(e);
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
        async update() {
            try {
                if (
                    this.form.password.length ||
                    this.form.confirmPassword.length
                ) {
                    if (this.form.password !== this.form.confirmPassword) {
                        this.$store.commit("errors", {
                            confirmPassword:
                                "Password and confirm password must match",
                        });
                        return;
                    }
                }
                if (!this.form.password.length) {
                    delete this.form["password"];
                    delete this.form["confirmPassword"];
                }
                const payload = {
                    ...this.user,
                    ...this.form,
                    types: Object.keys(this.user.types).filter(
                        (type) => this.user.types[type] == 1
                    ),
                    roles: this.user.roles,
                    set_company_id: this.user.company_id,
                    set_tenant_id: this.user.tenant_id,
                };
                delete payload["company_id"];
                delete payload["tenant_id"];

                this.$store.commit("isLoading", true);
                await this.$store.dispatch("auth/update", payload);
                this.$store
                    .dispatch("auth/show", {
                        id: this.user.id,
                    })
                    .then((res) => {
                        this.$store.commit("auth/set_user", res?.data ?? {});
                    });
            } catch (e) {}
        },
        handleChildEvent() {
            this.showPassword = !this.showPassword;
            this.inputType = this.showPassword ? "text" : "password";
        },
    },
};
</script>
