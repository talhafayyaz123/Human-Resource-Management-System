<template>
    <div class="card">
        <div class="card-header flex items-center gap-2" v-if="$route.name !== 'WorkshopTemplatesShowRecord'">
          <h3 class="card-title">{{ $t("Template Editor") }}</h3>
          <CustomIcon name="iIcon" />
        </div>
        <div class="card-body">
            <div
                :class="[
                    $route.name !== 'WorkshopTemplatesShowRecord'
                        ? 'template-editor card py-5 px-8'
                        : '',
                ]"
            >
                <!-- 
                    the selected widget configuration will appear here.
                    is toggled when the gear icon is clicked on the right side of the display
                    if the showPreview state is set to true then hide the configuration
                -->
                <WidgetConfiguration v-if="!showPreview" />
                <!-- 
                    Toolbar contains all the available widget icons that can be dragged and dropped onto a column
                    Toolbar is hidden for the show page
                -->
                <Toolbar v-if="$route.name !== 'WorkshopTemplatesShowRecord'" />
                <!-- 
                    The cards will hold our accordions, not the components themselves but the data related to the components.
                    using draggable allows us to change the ordering of the card using drag and drop
                -->
                <draggable
                    :move="() => !showPreview"
                    v-model="cards"
                    group="cards"
                    item-key="id"
                >
                    <!-- The accordion component is the main component that holds are our inputs -->
                    <template #item="{ element: card, index }">
                        <Accordion
                            @click.native="selectWidget(index)"
                            @removeCard="removeCard(index)"
                            :card="card"
                            :id="card.id"
                            class="my-5"
                        />
                    </template>
                </draggable>
                <!-- 
                    This section displays a add card button of sorts that adds a card 
                    if the showPreview state is set to true then hide this section
                -->
                <div v-if="!showPreview" class="flex justify-center">
                    <div
                        @click="addCard"
                        class="secondary-btn cursor-pointer"
                    >
                        <span class="flex"
                            ><font-awesome-icon
                                class="cursor-pointer cross text-black self-center mr-2"
                                icon="fa-solid fa-plus"
                            />
                            <h1>Add Card</h1></span
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
import Accordion from "../Widgets/Accordion.vue";
import Toolbar from "./Toolbar.vue";
import WidgetConfiguration from "./WidgetConfiguration.vue";
import mainMixin from "../Mixins/mainMixin";

export default {
    name: "TemplateEditor",
    mixins: [mainMixin],
    props: {
        workshopTemplateId: {
            type: Number,
            required: true,
        },
        cardsFromAPI: {
            type: Array,
            default: () => [],
        },
    },
    mounted() {
        this.syncCardsAndCardsFromAPI();
    },
    watch: {
        // when the cardsFromAPI is changed then update the cards
        cardsFromAPI() {
            this.syncCardsAndCardsFromAPI();
        },
    },
    data() {
        return {
            cards: [], // holds our accordion components data
        };
    },
    unmounted() {
        // reset inputConfigurations state on component unmount
        this.$store.commit("workshopTemplates/inputConfigurations", {
            widgets: {},
            groups: {},
        });
    },
    methods: {
        /**
         * syncs the cards with the cardsFromAPI prop value
         */
        syncCardsAndCardsFromAPI() {
            this.cards = this.cardsFromAPI;
        },
        /**
         * sets the 'selectedWidget' state
         * @param {index} index of the selected card
         */
        selectWidget(index) {
            // if the showPreview state is set to true then prevent widget selection
            if (!this.showPreview) {
                this.$store.commit(
                    "workshopTemplates/selectedWidget",
                    this.cards[index]
                );
                this.$store.commit(
                    "workshopTemplates/selectedWidgetType",
                    "card"
                );
            }
        },
        /**
         * Adds an accordion to the editor
         */
        async addCard() {
            try {
                // create a card
                const card = {
                    id: this.uuid(), //unique identifier
                    type: "card", // widget type
                    rows: [], // contains row components part of a card
                    configuration: {
                        heading: `Card Heading ${this.cards.length + 1}`, //set a default card heading
                    },
                };
                const response = await this.$store.dispatch(
                    "workshopTemplateCards/create",
                    {
                        ...card,
                        workshopTemplateId: this.workshopTemplateId,
                    }
                );
                card.id = response?.data?.id ?? card.id; // set the card id from the API response
                // push the card to the cards array
                this.cards = [...this.cards, { ...card }];
            } catch (e) {
                console.log(e);
            }
        },
        /**
         * removes the card from the cards array
         * @param {index} index of the card to be removed
         */
        async removeCard(index) {
            try {
                // call the card delete API
                await this.$store.dispatch(
                    "workshopTemplateCards/destroy",
                    this.cards[index]?.id
                );
                const removedCard = this.cards.splice(index, 1); //splice from the array on the index
                // remove the sliced card from the selectedWidget if the deleted card was the selected one
                if (removedCard?.[0]?.id == this.selectedWidget?.id) {
                    this.$store.commit(
                        "workshopTemplates/selectedWidget",
                        null
                    );
                    this.$store.commit(
                        "workshopTemplates/selectedWidgetType",
                        ""
                    );
                }
            } catch (e) {
                console.log(e);
            }
        },
    },
    components: {
        Accordion,
        WidgetConfiguration,
        Toolbar,
    },
};
</script>

<style scoped>
.add-card-section {
    min-height: 50px;
    width: 300px;
    border: 2px solid gray;
    border-style: dashed;
    margin: 1rem;
    cursor: pointer;
    user-select: none;
}
.template-editor {
    background-color: white;
}
</style>
