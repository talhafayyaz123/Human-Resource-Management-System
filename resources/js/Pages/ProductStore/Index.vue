<template>
    <div>
        <PageHeader :items="breadcrumbItems" />
        <div>
            <div class="pr-view-buttons">
                <button @click="showGridView" :class="{ active: isGridView }">
                    <Icon name="gridIcon" />
                </button>
                <button @click="showListView" :class="{ active: !isGridView }">
                    <Icon name="listIcon" />
                </button>
            </div>
            <div v-if="isGridView" class="grid-view">
                <div class="product-row">
                    <div
                        class="product-col"
                        v-for="entry in storeEntries?.data ?? []"
                        :key="entry?.id"
                    >
                        <router-link :to="`/product-detail/${entry?.id}`">
                            <div class="product-card">
                                <!--                            <div class="badge">Hot</div>-->
                                <div
                                    style="height: 360px !important"
                                    class="product-tumb"
                                >
                                    <router-link
                                        :to="`/product-detail/${entry?.id}`"
                                    >
                                        <img
                                            style="
                                                object-fit: initial;
                                                background-position: center;
                                                background-size: cover;
                                                background-repeat: no-repeat;
                                                width: 100%;
                                                height: 100%;
                                                transition: 0.3s all ease-in-out;
                                            "
                                            v-if="entry?.image"
                                            :src="entry?.image?.url"
                                            alt=""
                                        />
                                    </router-link>
                                </div>
                                <div class="product-details">
                                    <span class="product-catagory">{{
                                        entry?.authorName
                                    }}</span>
                                    <h4>
                                        {{ entry?.productTitle }}
                                    </h4>
                                    <p class="description">
                                        {{ entry?.shortDescription }}
                                    </p>
                                    <div class="">
                                        <div class="rating">
                                            <i
                                                :data-star="entry?.rattingAvg"
                                            ></i>
                                            <span class="ml-2">{{
                                                entry?.totalReviews
                                            }}</span>
                                        </div>
                                    </div>
                                    <div class="product-bottom-details">
                                        <div class="product-price">
                                            {{
                                                $formatter(
                                                    entry?.sellPrice,
                                                    globalLanguage
                                                )
                                            }}
                                        </div>
                                        <div class="product-links">
                                            <button class="add-cart-btn">
                                                <font-awesome-icon
                                                    icon="fa fa-shopping-cart mr-2"
                                                />
                                                <span>Add To Cart</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
            <div v-else class="list-view">
                <div class="product-row">
                    <div
                        class="product-col"
                        v-for="entry in storeEntries?.data ?? []"
                        :key="entry?.id"
                    >
                        <div class="product-card">
                            <!-- <div class="badge">Hot</div> -->
                            <div class="product-tumb">
                                <router-link
                                    :to="`/product-detail/${entry?.id}`"
                                >
                                    <img
                                        v-if="entry?.image"
                                        :src="entry?.image?.url"
                                        alt=""
                                    />
                                </router-link>
                            </div>
                            <div class="product-details">
                                <div class="left-pr">
                                    <h4>
                                        <router-link
                                            :to="`/product-detail/${entry?.id}`"
                                        >
                                            {{ entry?.productTitle }}
                                        </router-link>
                                    </h4>
                                    <div class="rating">
                                        <i data-star="4.3"></i>
                                        <span class="ml-2">310</span>
                                    </div>
                                    <!-- <span class="product-catagory">Women,bag</span> -->
                                    <p class="description">
                                        {{ entry?.shortDescription }}
                                    </p>
                                </div>
                                <div class="right-pr">
                                    <div class="product-bottom-details">
                                        <div class="product-price">
                                            {{
                                                $formatter(
                                                    entry?.sellPrice,
                                                    globalLanguage
                                                )
                                            }}
                                        </div>
                                        <div class="product-links">
                                            <router-link
                                                class="detail-btn"
                                                :to="`/product-detail/${entry?.id}`"
                                            >
                                                <span>Details</span>
                                            </router-link>
                                            <button class="add-cart-btn">
                                                <font-awesome-icon
                                                    icon="fa fa-shopping-cart"
                                                    class="mr-2"
                                                ></font-awesome-icon>
                                                <span>Add To Cart</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import PageHeader from "@/Components/Layouts/Page-header.vue";
import Icon from "../../Components/Icon.vue";
export default {
    components: {
        PageHeader,
        Icon,
    },
    computed: {
        ...mapGetters("storeEntries", ["storeEntries", "count"]),
    },
    props: {
        filters: Object,
    },
    data() {
        return {
            breadcrumbItems: [
                {
                    text: "Admin portal",
                    to: "/dashboard",
                },
                {
                    text: "Products Store",
                    active: true,
                },
            ],
            page: 1,
            currentPage: 1,
            start: 0,
            perPage: 25,
            isGridView: true,
            reviews: [],
        };
    },
    async mounted() {
        await this.refresh(true, this.start, this.perPage);
    },
    methods: {
        async changePageOrSearch(page = 1) {
            this.page = page;
            await this.$store.dispatch("storeEntries/list", {
                page: this.page,
            });
        },
        async refresh(bypass, start, end) {
            try {
                if (!this.storeEntries?.length || bypass) {
                    await this.$store.dispatch("storeEntries/list", {
                        limit_start: start,
                        limit_count: end,
                    });
                }
            } catch (e) {}
        },
        showGridView() {
            this.isGridView = true;
        },
        showListView() {
            this.isGridView = false;
        },
    },
};
</script>
