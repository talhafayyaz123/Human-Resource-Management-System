<template>
    <div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $t("Fill Cancel Request Details") }}</h3>
            </div>
            <div class="card-body">
                <div class="grid items-center grid-cols-3 gap-6">
                    <div class="w-full">
                        <div class="form-group">
                        
                            <MultiSelectInput
                                    v-model="form.customerId"
                                    :key="form.customerId"
                                    :required="true"
                                    :options="companies.data"
                                    :multiple="false"
                                    textLabel="Customer"
                                    label="companyName"
                                    trackBy="id"
                                    :error="errors.customerId"
                                    moduleName="companies"
                                /> 
                         
                        </div>
                    </div>
                    <div class="w-full">
                        <div class="form-group">
                            <MultiSelectInput
                                class=""
                                v-model="form.partnerId"
                                :textLabel="$t('Partner')"
                                :error="errors['partner']"
                                :key="form.partnerId"
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
                    <div class="w-full">
                        <div class="form-group">
                           
                            <MultiSelectInput
                                class=""
                                v-model="form.storeEntryId"
                                :textLabel="$t('Store Entries')"
                                :error="errors['store_entry_id']"
                                :key="form.storeEntryId"
                                :options="storeEntries?.data"
                                :required="true"
                                :multiple="true"
                                label="productTitle"
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
                                :content="form.reason"
                                :content-type="'html'"
                                :error="errors.reason"
                                :label="$t('Reason')"
                                @delta="form.reason = $event"
                            />
                        </div>
                    </div>

                </div>
                
              
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <router-link to="/cancel-requests" class="primary-btn mr-3">
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

</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import TextAreaInput from "@/Components/TextareaInput.vue";
import FileInputs from "@/Components/FileInputs.vue";
import SelectInput from "@/Components/SelectInput.vue";
import LoadingButton from "@/Components/LoadingButton.vue";
import MultiSelectInput from "@/Components/MultiSelectInput.vue";
import QuillTextEditor from "@/Components/QuillTextEditor.vue";

import { mapGetters } from "vuex";

export default {

    computed: {
        ...mapGetters(["errors", "isLoading", "pusher"]),
        ...mapGetters("companies", ["companies", "partners"]),
        ...mapGetters("storeEntries", ["storeEntries", "count"]),
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
        MultiSelectInput,
        QuillTextEditor,
        SelectInput,
        TextAreaInput,
        FileInputs,
    },
    
    props: {
        recordId: {
            type: Number,
            required: false,
        },
    },
    data() {
        return {
            perPage: 10,
            request: {},
            form: {
                customerId: "",
                partnerId: "",
                reason: "",
                storeEntryId: ""
            },
        };
    },

    async mounted() {
        await this.$store.dispatch("companies/list", {
            perPage: 25,
            page: 1,
            customerType: "partner",
        });

        await this.$store.dispatch("companies/list", {
            perPage: 25,
            page: 1,
        });

        await this.$store.dispatch("storeEntries/list", {
                    page:1,
                    search: '',
        });
       
        if (this.$route.params.id) {
            try {
                this.$store.commit("showLoader", true);
                const response = await this.$store.dispatch(
                    "cancelRequests/show",
                    this.$route.params.id
                );
                const {
                    customer,
                    partner,
                    entries,
                    reason
                } = response?.data?.data;
                this.request = response?.data?.data ?? {};
                this.form = {
                    id: this.$route.params.id,
                    customerId:customer,
                    partnerId:partner,
                    reason: reason,
                    storeEntryId:entries
                };

            } catch (e) {
                console.log(e);
            } finally {
                this.$store.commit("showLoader", false);
            }
           
        }
    },
    methods: {
    
        async store() {
            try {
                this.$store.commit("isLoading", true);
                if (this.$route.params.id) {
                    var ids= this.form.storeEntryId.map(item=>{
                        return item.id;
                    });
                    await this.$store.dispatch("cancelRequests/update", {
                        id: this.$route.params.id,
                        data: {
                            ...(this.form.customerId && {
                            customerId: this.form.customerId.id,
                        }),
                        
                        ...(this.form.partnerId && {
                            partnerId: this.form.partnerId.id,
                        }),
                        ...(this.form.reason && { reason: this.form.reason }),
                        ...(this.form.storeEntryId && { storeEntryId: ids }),
                        ...(this.form.id && { id: this.form.id }),
                        },
                    });
                } else {
                    var ids= this.form.storeEntryId.map(item=>{
                        return item.id;
                    });
                      
                    await this.$store.dispatch("cancelRequests/create", {
                        ...(this.form.customerId && {
                            customerId: this.form.customerId.id,
                        }),
                        
                        ...(this.form.partnerId && {
                            partnerId: this.form.partnerId.id,
                        }),
                        ...(this.form.reason && { reason: this.form.reason }),
                        ...(this.form.storeEntryId && { storeEntryId: ids }),
                        
                    });
                }
                this.$router.push("/cancel-requests");
            } catch (e) {
                console.log(e);
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
