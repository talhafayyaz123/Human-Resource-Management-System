<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $t("User") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <User :profileForm="data" :errors="errors" />
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Personal Data") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <PersonalData :profileForm="data" :errors="errors" />
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Private Details") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <ContactDetails :profileForm="data" :errors="errors" />
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Private Address") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <PrivateAddress :profileForm="data" :errors="errors" />
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Children") }}</h3>
        </div>
        <div class="card-body">
            <Children :profileForm="data" :errors="errors" />
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Tax") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <Tax :profileForm="data" :errors="errors" />
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Bank") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <Bank :profileForm="data" :errors="errors" />
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Social Security") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <SocialSecurity :profileForm="data" :errors="errors" />
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Insurance") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <Insurance :profileForm="data" :errors="errors" />
            </div>
        </div>
    </div>
    <div class="flex items-center justify-end mt-4">
        <router-link to="/user-profiles" class="primary-btn me-3">
            <span class="mr-1">
                <CustomIcon name="cancelIcon" />
            </span>
            <span>{{ $t("Cancel") }}</span>
        </router-link>
        <loading-button :loading="isLoading" class="secondary-btn">
            <span class="mr-1">
                <CustomIcon name="saveIcon" />
            </span>
            {{
                $route.name === "UserProfileUpdate" || data.id
                    ? $t("Update")
                    : $t("Save and Proceed")
            }}
        </loading-button>
    </div>
</template>

<script>
import User from "./Profile/User.vue";
import PersonalData from "./Profile/PersonalData.vue";
import PrivateAddress from "./Profile/PrivateAddress.vue";
import ContactDetails from "./Profile/ContactDetails.vue";
import Children from "./Profile/Children.vue";
import Tax from "./Profile/Tax.vue";
import Bank from "./Profile/Bank.vue";
import SocialSecurity from "./Profile/SocialSecurity.vue";
import Insurance from "./Profile/Insurance.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import { mapGetters } from "vuex";

export default {
    computed: {
        ...mapGetters(["isLoading"]),
    },
    props: ["data", "errors"],
    components: {
        User,
        PersonalData,
        PrivateAddress,
        ContactDetails,
        Children,
        Tax,
        Bank,
        SocialSecurity,
        Insurance,
        LoadingButton,
    },
};
</script>
