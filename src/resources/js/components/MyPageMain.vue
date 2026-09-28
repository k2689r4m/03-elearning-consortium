<template>
    <div class="container">
        <ul>
            <draggable v-model="cards">
                <li v-for="(card, index) in cards">
                    <label v-if="!card.btnCardTitle" @click="btnCardTitleOn(card)">{{card.title}}</label>
                    <input v-else
                           v-model="card.title"
                           v-click-outside="btnCardTitleOff"
                           v-on:keydown.esc="btnCardTitleOff"
                           v-on:keydown.enter="btnCardTitleOff">
                    <ul>
                        <draggable :list="card.data" :group="{name:'row'}">
                            <li v-for="(item, index) in card.data" @click="btnCardItemOn(item)">{{item}}</li>
                        </draggable>
                    </ul>

                    <template v-if="card.btnAddCardItem">
                        <br>
                        <input v-model="cardItem"
                               v-click-outside="btnAddCardItemOff"
                               v-on:keydown.esc="btnAddCardItemOff"
                               v-on:keydown.enter="btnAddCardItemOn('ADD', card)"/>
                        <button @click.stop="btnAddCardItemOn('ADD', card)">
                            Add Card
                        </button>
                        <button @click.stop="btnAddCardItemOn('EXIT', card)">
                            X
                        </button>
                    </template>
                    <template v-else>
                        <button @click.stop="btnAddCardItemOn('ON', card)">
                            Add another card
                        </button>
                    </template>
                </li>
            </draggable>
        </ul>
        <br><br><br>
        <div>
            <input
                v-if="btnAddCard"
                v-click-outside="btnAddCardOff"
                v-on:keydown.esc="btnAddCardOff"
                v-on:keydown.enter="btnAddCardOn"
                v-model="cardData"
            />
            <button @click="btnAddCardOn">
                Add another list
            </button>
        </div>

        <br><br><br><br><br>

        <div v-if="btnCardItem">
            {{isCardItem}}
        </div>

<!--        <div v-if="btnCard">-->
<!--            card on-->
<!--        </div>-->
    </div>
</template>

<script>
export default {
    data() {
        return {
            myIndex: 0,
            cards: [],
            tempCard: '',
            isCardItem: null,
            cardData: null,
            cardItem: null,
            btnCardItem: false,
            btnAddCard: false,
        }
    },
    created() {     //렌더링이 되기전
        this.init();
    },
    mounted() {     //렌더링이 되고 나서
        this.$nextTick(() => {
            // 모든 화면이 렌더링된 후 실행
        });
    },
    beforeUpdate() {    //data 값이 바뀌기는 전 순간에 호출
    },
    updated(){          //data 값이 바뀌고나서 호출
    },
    methods: {
        init(){

        },
        btnAddCardOn(){
            if(this.btnAddCard){
                if(this.cardData){
                    this.addNewCard(this.cardData);
                    this.btnAddCard = false;
                }
                else{

                }
            }
            else{
                this.btnAddCard = true;
            }
        },
        btnAddCardOff(){
            if(this.btnAddCard){
                this.btnAddCard = false;
            }
        },
        btnCardTitleOn(item, data){
            if(!item.btnCardTitle){
                if(this.tempCard.btnCardTitle){
                    this.tempCard.btnCardTitle = false;
                    this.tempCard = '';
                }

                item.btnCardTitle = true;
                this.tempCard = item;
            }
        },
        btnCardTitleOff(){
            if(this.tempCard.btnCardTitle){
                this.tempCard.btnCardTitle = false;
                this.tempCard = '';
            }
        },
        btnCardItemOn(item){
            if(this.btnCardItem && this.isCardItem === item){
                this.btnCardItem = false;
            }
            else{
                this.btnCardItem = true;
                this.isCardItem = item;
            }
        },
        btnAddCardItemOn(flag, item){
            if(flag === 'ON'){
                if(!item.btnAddCardItem){
                    if(this.tempCard){
                        this.tempCard.btnAddCardItem = false;
                    }
                    this.tempCard = item;
                    item.btnAddCardItem = true;
                }
            }
            else if(flag === 'ADD'){
                if(this.cardItem){
                    item.data.push(this.cardItem);
                    this.cardItem = null;
                    this.tempCard = '';
                    item.btnAddCardItem = false;
                }
                else{

                }
            }
            else if(flag === 'EXIT'){
                if(item.btnAddCardItem){
                    this.tempCard = '';
                    this.cardItem = '';
                    item.btnAddCardItem = false;
                }
            }
        },
        btnAddCardItemOff(){
            if(this.tempCard.btnAddCardItem){
                this.tempCard.btnAddCardItem = false;
                this.tempCard = '';
            }
        },
        addNewCard(title){
            this.cards.push({
                id: this.myIndex++,
                title: title,
                data: [],
                btnCardTitle: false,
                btnAddCardItem: false,
            })

            this.cardData = null;
        },

    },
}
</script>
