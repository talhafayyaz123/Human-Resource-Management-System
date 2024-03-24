<template>
    <div>
        <!-- <div class="header flex justify-between items-baseline">
            <h1 class="header mb-8 text-3xl font-bold secondary-color">
                {{ $t("My Assets") }}
            </h1>
            <search-filter
                :isFilter="true"
                v-model="form.search"
                class="mr-4 w-full max-w-md"
            >
                <label class="block text-gray-700 mt-2"
                    >{{ $t("Status") }}:</label
                >
                <select v-model="form.status" class="form-select w-full">
                    <option
                        v-for="type in ['stock', 'assigned']"
                        :key="type"
                        :value="type"
                    >
                        {{ type }}
                    </option>
                </select>
            </search-filter>
        </div> -->
        <div class="card asset-list mt-5 mx-16">
            <div class="flex">
                <div class="asset-profile">
                    <div class="user">
                        <img
                            :src="
                                !!user.profile_image
                                    ? user.profile_image
                                    : defaultImg
                            "
                            alt="img"
                        />
                    </div>
                    <h3>
                        {{ myAssetUser?.firstName ? myAssetUser?.firstName + " " + myAssetUser?.lastName : "-" }}
                    </h3>
                </div>
                <div class="asset-details">
                    <ul>
                        <li>
                            <h3>{{ $t("Personal Nummer") }}:</h3>
                            <p>{{ myAssetUser.personalNumber ?? " - " }}</p>
                        </li>
                        <li>
                            <h3>{{ $t("Business Unit") }}:</h3>
                            <p>{{ myAssetUser?.department ?? " - " }}</p>
                        </li>
                        <li>
                            <h3>{{ $t("Department") }}:</h3>
                            <p>{{ myAssetUser.department ?? " - " }}</p>
                        </li>
                        <li>
                            <h3>{{ $t("Supervisor") }}:</h3>
                            <p>
                                {{
                                    supervisor?.first_name
                                        ? supervisor?.first_name +
                                          " " +
                                          supervisor?.last_name
                                        : " - "
                                }}
                            </p>
                        </li>
                        <li>
                            <h3>{{ $t("Location") }}:</h3>
                            <p>{{ myAssetUser?.location ?? " - " }}</p>
                        </li>
                        <li>
                            <h3>{{ $t("Date") }}:</h3>
                            <p>{{ myAssetUser?.dateOfBirth ?? " - " }}</p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="asset-desc">
                <p>{{ myAssetText }}</p>
            </div>

             <div
                class="asset-listing"
                v-if="myAssets && myAssets.length > 0"
            >
                <h3>Assets</h3>
                <div class="asset-card-list gap-5 grid-cols-1 lg:grid-cols-3">
                    <div
                        class=""
                         v-for="(asset, index) in myAssets"
                        :key="'asset-' + index"
                    >
                        <div class="asset-card" :key="'asset-' + index">
                            <div class="asset-card-img">
                                <div
                                    v-if="asset?.assetImage?.base64"
                                    :style="{
                                        backgroundImage:
                                            'url(data:image/png;base64,' +
                                            (asset?.assetImage?.base64 ?? '') +
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
import SearchFilter from "@/Components/SearchFilter.vue";
import profilePic from "@/assets/images/user.png";
import prodImgPlaceholder from "@/assets/images/imagePlaceholder.png";

import { mapGetters } from "vuex";

export default {
    components: { SearchFilter },
    computed: {
        ...mapGetters("auth", ["user"]),
        ...mapGetters("userProfile", ["userProfiles"]),
    },
    data() {
        return {
            defaultImg: profilePic,
            imgPlaceholder: prodImgPlaceholder,
            form: {
                search: "",
                status: "",
            },
            myAssets: [],
            myAssetUser: "",
            myAssetText: "",
            supervisor: "",
        };
    },
    watch: {
        form: {
            deep: true,
            handler: function () {
                this.throttle(async () => {
                    try {
                        const response = await this.$store.dispatch(
                            "assets/myAssets",
                            this.form
                        );
                        this.myAssets = response?.data?.data;
                    } catch (e) {
                        console.log(e);
                    }
                }, 150)();
            },
        },
    },
    async mounted() {
        try{
        await this.$store.commit("showLoader", true);
            const userResponse = await this.$store.dispatch(
                "userProfile/showProfileByUserId",
                this.user?.id
            );
            this.myAssetUser = userResponse?.data?.data;
            const response = await this.$store.dispatch("assets/myAssets");
            console.log('aaa', response);
            this.myAssets = response?.data?.assetArticles;
            this.myAssetText = response?.data?.employeeText;
            // pass supervisor id to get the supervisor name
            if(userResponse?.data?.data?.supervisorId){
                const getUserByIdRes = await this.$store.dispatch("auth/show", {
                id: userResponse?.data?.data?.supervisorId,
                });
                this.supervisor = getUserByIdRes?.data;
            }
            
         }
        catch(e){}
        finally {
            this.$store.commit("showLoader", false);
        }
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
</style>
