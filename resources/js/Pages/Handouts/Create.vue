<template>
    <div>
        <PageHeader :items="breadcrumbItems" />

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Configuration") }}</h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-2 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                v-model="form.customer"
                                :required="true"
                                class="="
                                :textLabel="$t('Customer')"
                                :placeholder="$t('Select Customer')"
                                :options="companies.data"
                                :multiple="false"
                                label="companyName"
                                trackBy="id"
                                moduleName="companies"
                                :error="errors.customer"
                            />
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                v-model="form.system"
                                :required="true"
                                class="="
                                :textLabel="$t('System')"
                                :placeholder="$t('Select System')"
                                :options="premiseSystems.data"
                                :multiple="false"
                                label="systemName"
                                trackBy="id"
                                moduleName="systems"
                                :query="{ type: 'premise' }"
                                :error="errors.system"
                            />
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="form-group">
                                <label class="input-label"
                                    ><span style="color: red">*</span>&nbsp;{{
                                        $t("Creation Date")
                                    }}:</label
                                >
                                <datepicker class="form-control"
                                    :clearable="false"
                                    :enable-time-picker="false"
                                    auto-apply
                                    :close-on-auto-apply="false"
                                    :style="dropdownStyles"
                                    v-model="form.createDate"
                                    :class="errors.createDate ? 'error' : ''"
                                />
                                <div
                                    v-if="errors?.createDate"
                                    class="form-error"
                                >
                                    {{ errors.createDate }}
                                </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                v-if="shouldShow"
                                class="="
                                v-model="form.consultant"
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
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <router-link to="/handouts" class="primary-btn mr-3">
                <span class="mr-1">
                    <CustomIcon name="cancelIcon" />
                </span>
                <span>{{ $t("Cancel") }}</span>
            </router-link>
            <loading-button :loading="isLoading" class="secondary-btn">
                <span class="mr-1">
                    <CustomIcon name="saveIcon" />
                </span>
                {{ $t("Save and Proceed") }}
            </loading-button>
        </div>

        <!-- <div class="w-full bg-white rounded-md shadow">
            
            <button class="docsHeroColorBlue px-3 py-2 rounded ml-8 mb-8">
                {{ $t("Save") }}
            </button>
        </div> -->

        <br />
        <br />
    </div>
</template>

<script>
import MultiSelectInput from "../../Components/MultiSelectInput.vue";
import LoadingButton from "../../Components/LoadingButton.vue";
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
export default {
    components: {
        MultiSelectInput,
        PageHeader,
        LoadingButton,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: this.$t("Admin portal"),
                    to: "/dashboard",
                },
                {
                    text: this.$t("Consulting"),
                    to: "/handouts",
                },
                {
                    text: this.$t("Handouts"),
                    to: "/handouts",
                },
                {
                    text: "Create",
                    active: true,
                },
            ],
            shouldShow: true,
            form: {
                customer: "",
                system: "",
                createDate: new Date(),
                consultant: "",
            },
        };
    },
    async mounted() {
        try {
            await this.$store.dispatch("companies/list", {
                perPage: 25,
                page: 1,
            });
            await this.$store.dispatch("systems/list", {
                perPage: 25,
                page: 1,
                type: "premise",
            });
            this.shouldShow = false;
            await this.$store.dispatch("userProfile/list");
            this.form.consultant = this.userProfiles.data.find(
                (user) => user.userId == this.user?.id
            );
        } catch (e) {
            console.log(e);
        } finally {
            this.shouldShow = true;
        }
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("companies", ["companies"]),
        ...mapGetters("systems", ["premiseSystems"]),
        ...mapGetters("userProfile", ["userProfiles"]),
        ...mapGetters("auth", ["user"]),
        dropdownStyles() {
            return {
                "--elem-hover-bg-color": "#312E81",
                "--elem-selected-bg-color": "#312E81",
            };
        },
    },
};
</script>

<style scoped></style>
