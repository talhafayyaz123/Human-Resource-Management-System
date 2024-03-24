<template>
    <div>
        <div class="flex flex-wrap -mb-1">
            <template v-for="link in links" :key="link">
                <div
                    @click="changePage(link)"
                    :class="[
                        link == page
                            ? 'text-white bg-[#2957a4]'
                            : 'text-grey-400',
                        'mb-1',
                        'mr-1',
                        'px-4',
                        'py-3',
                        'text-sm',
                        'leading-4',
                        'border rounded',
                        'cursor-pointer',
                    ]"
                    v-html="link"
                />
            </template>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        count: Number | String,
        perPage: Number | String,
        start: Number | String,
        length: Number | String,
        currentPage: Number | String,
    },
    data() {
        return {
            page: this.currentPage,
        };
    },
    watch: {
        currentPage: {
            handler: function (val) {
                this.page = val;
            },
            deep: true,
        },
    },
    methods: {
        changePage(link) {
            this.page = link;
            this.$emit("paginationInfo", {
                start: +this.perPage * link - +this.perPage,
                end: +this.perPage,
            });
        },
    },
    computed: {
        links() {
            return Math.ceil(+this.count / +this.perPage);
        },
    },
};
</script>
