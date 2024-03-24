<template>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.firstName"
                :required="true"
                :error="errors.firstName"
                class=""
                :label="$t('First Name')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.lastName"
                :required="true"
                :error="errors.lastName"
                class=""
                :label="$t('Last Name')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.mobile"
                :error="errors.mobile"
                class=""
                :label="$t('Mobile')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                v-model="profileForm.publicPhone"
                :error="errors.publicPhone"
                class=""
                :label="$t('Phone')"
            />
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <text-input
                :isReadonly="existingUser"
                :required="true"
                v-model="profileForm.email"
                :error="errors.email"
                class=""
                :label="$t('Email')"
            />
        </div>
    </div>
    <div class="w-full" v-if="!existingUser">
        <div class="form-group">
            <text-input
                :key="inputType"
                :type="inputType"
                :required="true"
                v-model="profileForm.password"
                :error="errors.password"
                :label="$t('Password')"
                :show-password="showPassword"
                @child-event="handleChildEvent"

            />
        </div>
    </div>
    <div class="w-full" v-if="!existingUser">
        <div class="form-group">
            <text-input
                :key="inputType"
                :type="inputType"
                :required="true"
                v-model="profileForm.confirmPassword"
                :error="errors.confirmPassword"
                :label="$t('Confirm Password')"
                :show-password="showPassword"
                @child-event="handleChildEvent"
            />
            <p
                v-if="
                    profileForm?.password?.length &&
                    profileForm?.confirmPassword?.length &&
                    profileForm?.password !== profileForm?.confirmPassword
                "
                class="text-red-500"
            >
                Passwords must match
            </p>
        </div>
    </div>
    <div class="w-full">
        <div class="form-group">
            <MultiSelectInput
                v-if="!existingUser"
                :textLabel="'Roles'"
                class=""
                :trackBy="'id'"
                :label="'title'"
                v-model="profileForm.roles"
                :search-param-name="'search_string'"
                moduleName="roles"
                :options="modifiedRoles"
            />
        </div>
    </div>
</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import { mapGetters } from "vuex";

export default {
    inject: ["existingUser"],
    computed: {
        ...mapGetters("roles", ["roles"]),
        ...mapGetters("auth", ["user"]),
        modifiedRoles() {
            if (this.user?.roles?.includes("admin")) {
                return this.roles;
            } else {
                return this.roles.filter((role) => role !== "admin");
            }
        },
    },
    components: {
        TextInput,
        MultiSelectInput,
    },
    props: ["profileForm", "errors"],
    data() {
        return {
            showPassword: false,
            inputType: "password"

        };
    },
    methods:{
        handleChildEvent() {
            this.showPassword = !this.showPassword;
            this.inputType = this.showPassword ? "text" : "password";
        },
    }
};
</script>
