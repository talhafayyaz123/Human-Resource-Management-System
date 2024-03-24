<template>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $t("User Information") }}</h3>
        </div>
        <div class="card-body">
            <div class="grid items-center grid-cols-2 gap-6">
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="userInfo.personalNumber"
                            class=""
                            :label="$t('Personal Number')"
                            :isReadonly="true"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="userInfo.firstName"
                            class=""
                            :label="$t('First Name')"
                            :isReadonly="true"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="userInfo.lastName"
                            class=""
                            :label="$t('Last Name')"
                            :isReadonly="true"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <text-input
                            v-model="userInfo.location"
                            class=""
                            :label="$t('Location')"
                            :isReadonly="true"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :key="userInfo.teams"
                            class=""
                            :options="[]"
                            v-model="userInfo.teams"
                            :multiple="true"
                            label="name"
                            :isDisabled="true"
                            :textLabel="$t('Teams')"
                            trackBy="id"
                        />
                    </div>
                </div>
                <div class="w-full">
                    <div class="form-group">
                        <MultiSelectInput
                            :key="userInfo.departments"
                            class=""
                            :options="[]"
                            v-model="userInfo.departments"
                            :multiple="true"
                            label="name"
                            :isDisabled="true"
                            :textLabel="$t('Departments')"
                            trackBy="id"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $t("Fill CIP Details") }}</h3>
        </div>
        <div class="card-body">
            <CIPForm ref="cipForm" :action-form="form" />
        </div>
    </div>
    <h6
        class="text-xl font- normal leading-normal mt-0 mb-2 text-cyan-800"
    ></h6>
    <div class="flex items-center justify-end mt-4">
          <button @click="cancel" class="primary-btn me-3">
            <span class="mr-1">
              <CustomIcon name="cancelIcon"/>
            </span>
            <span>{{$t("Cancel")}}</span>
          </button>
          <loading-button
                @click="store"
                v-if="$can(`${$route.meta.permission}.create`)"
                :loading="isLoading"
                class="secondary-btn"
                >
                <span class="mr-1">
              <CustomIcon name="saveIcon"/>
            </span>
                {{ $t("Save and Proceed") }}
          </loading-button
            >
        </div>

</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import CIPForm from "./CIPForm.vue";
import { mapGetters } from "vuex";

export default {
    name: "AddImprovement",
    computed: {
        ...mapGetters(["isLoading"]),
    },
    components: {
        TextInput,
        MultiSelectInput,
        LoadingButton,
        CIPForm,
    },
    async mounted() {
        // get the employee info and fill the userInfo form
        try {
            this.$store.commit("showLoader", true);
            const response = await this.$store.dispatch(
                "userProfile/showBasicEmployeeInfo",
                localStorage.getItem("user_id")
            );
            this.userInfo = response?.data ?? this.userInfo;
        } catch (e) {
            console.log(e);
        } finally {
            this.$store.commit("showLoader", false);
        }
    },
    data() {
        return {
            userInfo: {
                id: "",
                personalNumber: "",
                firstName: "",
                lastName: "",
                location: "",
                teams: [],
                departments: [],
            },
            form: {
                processNumber: "",
                title: "",
                date: new Date(),
                description: "",
                suggestedSolution: "",
                affectedGroup: "",
                files: [],
                userId: "",
            },
        };
    },
    methods: {
        /**
         * hits the CIP store API
         */
        async store() {
            try {
                const form = this.$refs.cipForm.form;
                this.$store.commit("isLoading", true);
                const formData = new FormData();
                formData.append("processNumber", form.processNumber);
                formData.append("title", form.title);
                formData.append("date", form.date.toLocaleDateString());
                formData.append("description", form.description);
                formData.append("suggestedSolution", form.suggestedSolution);
                formData.append("affectedGroupId", form.affectedGroup?.id);
                formData.append("userId", this.userInfo?.id);
                form.files.forEach((file, index) => {
                    formData.append(`files[${index}]`, file);
                });
                await this.$store.dispatch(
                    "continuousImprovementProcess/create",
                    formData
                );
                //reset the form
                this.cancel();
            } catch (e) {
                console.log(e);
            }
        },
        // resets the form
        cancel() {
            this.form = {
                processNumber: "",
                title: "",
                date: new Date(),
                description: "",
                suggestedSolution: "",
                affectedGroup: "",
                files: [],
                userId: this.userInfo?.id,
            };
        },
    },
};
</script>

<style scoped></style>
