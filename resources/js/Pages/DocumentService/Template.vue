<template>
    <h1 class="header mb-8 text-3xl font-bold">
        <router-link class="secondary-color" to="/document-service">{{
            $t("Document Service")
        }}</router-link>
        <span class="secondary-color font-medium">/</span>
        <span class="text-color">{{ $t("Create") }}</span>
    </h1>
    <div
        style="grid-template-columns: 1fr 3fr 1fr; min-height: 100%"
        class="grid gap-2"
    >
        <FileStructure />
        <div class="card rounded shadow-lg">
            <div class="grid grid-cols-[1fr,5fr] py-1 px-2">
                <label for="">{{ $t("Directory") }}</label>
                <text-input class="pb-8 pr-6 w-full" />
            </div>
            <div class="grid grid-cols-[1fr,5fr] py-1 px-2">
                <label for="">{{ $t("File") }}</label>
                <text-input class="pb-8 pr-6 w-full" />
            </div>
            <div class="grid grid-cols-[1fr,5fr] py-1 px-2">
                <label for="">{{ $t("Meta Mask") }}</label>
                <select-input class="pb-8 pr-6 w-full" />
            </div>
            <div class="grid grid-cols-[1fr,5fr] py-1 px-2">
                <label for="">{{ $t("Users/Groups") }}</label>
                <div class="card rounded shadow-lg">
                    <div>
                        <div
                            v-for="userGroup in form.usersGroups"
                            :key="'user-group-' + userGroup.id"
                            class="grid grid-cols-[1fr,1fr] gap-3 p-1"
                        >
                            <select-input v-model="userGroup.name" />
                            <div class="flex justify-between items-center">
                                <div>
                                    <input
                                        v-model="userGroup.r"
                                        type="checkbox"
                                    />
                                    <label class="ml-2" for="">R</label>
                                </div>
                                <div>
                                    <input
                                        v-model="userGroup.w"
                                        type="checkbox"
                                    />
                                    <label class="ml-2" for="">W</label>
                                </div>
                                <div>
                                    <input
                                        v-model="userGroup.d"
                                        type="checkbox"
                                    />
                                    <label class="ml-2" for="">D</label>
                                </div>
                                <div>
                                    <input
                                        v-model="userGroup.e"
                                        type="checkbox"
                                    />
                                    <label class="ml-2" for="">E</label>
                                </div>
                                <div>
                                    <input
                                        v-model="userGroup.l"
                                        type="checkbox"
                                    />
                                    <label class="ml-2" for="">L</label>
                                </div>
                                <div>
                                    <input
                                        v-model="userGroup.p"
                                        type="checkbox"
                                    />
                                    <label class="ml-2" for="">P</label>
                                </div>
                            </div>
                        </div>
                        <div
                            @click="addUsersGroups"
                            class="flex justify-center mb-2 mt-2 cursor-pointer"
                        >
                            <font-awesome-icon icon="fas fa-plus p-2" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-[1fr,5fr] py-1 px-2">
                <label for="">{{ $t("References") }}</label>
                <div class="card rounded shadow-lg">
                    <select-input
                        v-for="reference in form.references"
                        :key="'reference-' + reference.id"
                        v-model="reference.name"
                        class="p-2 w-full"
                    />
                    <div
                        @click="addReference"
                        class="flex justify-center mb-2 cursor-pointer"
                    >
                        <font-awesome-icon icon="fas fa-plus p-2" />
                    </div>
                </div>
            </div>
        </div>
        <div class="card rounded shadow-lg">
            <text-input
                class="m-2"
                type="text"
                :placeholder="$t('Search...')"
            ></text-input>
        </div>
    </div>
</template>

<script>
import FileStructure from "./FileStructure.vue";
import TextInput from "../../Components/TextInput.vue";
import SelectInput from "../../Components/SelectInput.vue";
import { v4 as uuidv4 } from "uuid";

export default {
    data() {
        return {
            form: {
                directory: "",
                file: "",
                metaMask: "",
                usersGroups: [
                    {
                        id: uuidv4(),
                        name: "",
                        r: false,
                        w: false,
                        d: false,
                        e: false,
                        l: false,
                        p: false,
                    },
                ],
                references: [
                    {
                        id: uuidv4(),
                        name: "",
                    },
                ],
            },
        };
    },
    methods: {
        addUsersGroups() {
            this.form.usersGroups.push({
                id: uuidv4(),
                name: "",
                r: false,
                w: false,
                d: false,
                e: false,
                l: false,
                p: false,
            });
        },
        addReference() {
            this.form.references.push({
                id: uuidv4(),
                name: "",
            });
        },
    },
    components: {
        FileStructure,
        TextInput,
        SelectInput,
    },
};
</script>
