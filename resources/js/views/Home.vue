<template>
    <div class="ma-3">
        <p v-if="items.length === 0">検索対象が登録されていません。<br>検索タブから登録してください。</p>
        <div v-for="(item, index) in items" :key="index">
            <h1>{{ item.id }} {{ item.keyword }}</h1>
            <v-row v-for="result in results">
                <v-col v-for="row in result" class="d-flex child-flex" cols="6" sm="4" md="3" lg="2" :key="row.id">
                    <v-card v-if="row.search_lists_id === item.id" width="330">
                        <a class="card-link" :href="'https://jp.mercari.com'+row['url']" target="_blank">
                            <v-img :src="row.image_url" aspect-ratio="1" cover class="bg-grey-lighten-2" width="330" max-height="330"></v-img>
                            <v-card-title class="text-h6">
                                {{ row.product_name }}
                            </v-card-title>
                            <v-card-text class="text-h6 price">
                                {{ row.price }}円
                            </v-card-text>
                        </a>
                    </v-card>
                </v-col>
            </v-row>
        </div>
    </div>
</template>

<script>
import { useUserStore } from '../stores/user'

export default {
    data() {
        return {
            items: [''],
            results: [],
            cols: 0,
            user: null,
        };
    },
    created() {
        this.user = useUserStore().user;

        axios.get('/api/search/' + this.user.id).then((res) => {
            this.items = res.data
        }).catch(error => {
            this.items = []
            console.log(error)
        })

        axios.get('/api/home/' + this.user.id).then((res) => {
            this.results = res.data
        }).catch(error => console.log(error))
    },
};
</script>

<style>
    .card-link{
        color: inherit;
        text-decoration: none;
    }
    .price{
        text-align: right;
    }
</style>