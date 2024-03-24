<template>
    <div>
        <div
            v-for="(directory) in directoryStruct.directories"
            :key="directory.id"
        >
            <div>
                <div v-if="directory.directories" class="flex items-center cursor-pointer">
                    <icon name="rightArrow" />
                    <p class="text-[#ffffff] text-sm">{{ directory.name }}</p>
                </div>
                <div
                    v-else
                    @click="saveCurrentFile(directory)"
                    class="cursor-pointer selected "
                >
                    <p class="text-[#ffffff] text-sm">{{ directory.name }}</p>
                </div>
            </div>
            <directoryStructure
                v-if="directory.directories"
                :directoryStruct="directory"
                class="pl-5"
            />
        </div>
    </div>
</template>

<script>
import { Store, mapGetters } from "vuex";
import icon from "@/Components/Icon.vue";

export default {
    name: "directoryStructure",
    // emits: ["toggleChangeConfigModal"],
    props: {
        changeConfigModal: {
            type: Boolean,
            default: false,
        },
        changeConfigModal: {
            type: Object,
        },
        directoryStruct: {
            type: Object,
        },
    },
    components: {
        icon,
    },
    computed: {
        ...mapGetters(["errors", "isLoading"]),
        ...mapGetters("serverPools", ["serverPools", "serverPoolId"]),
        ...mapGetters("companies", ["partners"]),
    },
    methods: {
      
        saveCurrentFile(data) {
            this.$store.dispatch("serverPools/fileConfigData", data);
        },
    },
};
</script>

<style scoped>
.selected:hover {
  background-color: #717171;
}
.selected:active{  
    background-color: #575757;
}
.selected:focus {
    background-color: #555555;
}
</style>
