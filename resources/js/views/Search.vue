<template>
    <div class="ma-3">
        <v-btn @click="createConfirm()">新規登録</v-btn>
        <v-table>
            <thead>
                <tr>
                    <th class="text-left">ID</th>
                    <th class="text-left">Keyword</th>
                    <th class="text-left">最安値</th>
                    <th class="text-left">最高値</th>
                    <th class="text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in items" :key="item.id">
                    <td>{{ item.id }}</td>
                    <td>{{ item.keyword }}</td>
                    <td>{{ item.price_min }}</td>
                    <td>{{ item.price_max }}</td>
                    <td>
                        <v-icon
                        class="mr-1"
                        size="large"
                        color="blue-grey-darken-2"
                        icon="mdi-pencil-outline"
                        @click="updateConfirm(item.id)"
                        ></v-icon>
                        <v-icon
                        size="large"
                        color="blue-grey-darken-2"
                        icon="mdi-delete"
                        @click="deleteConfirm(item.id)"
                        ></v-icon>
                    </td>
                </tr>
            </tbody>
        </v-table>
    </div>

    <!-- 登録・更新ダイアログ -->
    <v-dialog v-model="createDialog" persistent width="512">
        <v-form validate-on="submit lazy" @submit.prevent="submit">
            <!-- <template v-slot:activator="{ props }"></template> -->
            <v-card>
                <v-card-title>
                    <span v-if="checkId === 0" class="text-h5">新規登録</span>
                    <span v-else class="text-h5">内容更新</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                v-model="keyword"
                                label="検索内容*"
                                :rules="keywordRules"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                v-model="excludeKeyword"
                                label="除外内容"
                                :rules="excludeKeywordRules"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                v-model="priceMin"
                                type="number"
                                label="最安値"
                                :rules="priceMinRules"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                v-model="priceMax"
                                type="number"
                                label="最高値"
                                :rules="priceMaxRules"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                        <!-- 発送料-->
                        <!-- 商品の状態 -->
                    </v-container>
                    <small>* 必須項目</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn color="blue-darken-1" variant="text" @click="createDialog = false">閉じる</v-btn>
                    <v-btn type="submit">保存</v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </v-dialog>

    <!-- 削除確認ダイアログ Persistent -->
    <v-dialog v-model="deleteDialog" persistent max-width="512">
        <v-card>
            <v-card-title class="headline">削除確認</v-card-title>
            <v-card-text>ID:{{ deleteId }}を削除してもよろしいですか？</v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn color="blue-darken-1" text @click="deleteDialog = false">キャンセル</v-btn>
                <v-btn text @click="deleteItem(deleteId)">削除</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { useUserStore } from '../stores/user'
export default {
    data() {
        return {
            items: [],
            createDialog: false,
            deleteDialog: false,
            deleteId: null,
            checkId: 0,
            user: null,

            // Rules
            keyword: '',
            keywordRules: [
                value => {
                    if (!value) return '検索内容が入力されていません。'
                    if (value.toString().length >= 255) return '入力できる文字数を超えています。'

                    return true
                },
            ],
            excludeKeyword: '',
            excludeKeywordRules: [
                value => {
                    if (value.toString().length >= 255) return '入力できる文字数を超えています。'
                    return true;
                },
            ],
            priceMin: '',
            priceMinRules: [
                value => {
                    if (value === null) return true
                    if (parseFloat(value) < 300) return '設定できる最低金額は300です。'
                    if (parseFloat(value.toString().length) > 9) return '設定できる桁数を超えています。'

                    return true
                },
            ],
            priceMax: '',
            priceMaxRules: [
                value => {
                    if (value === null) return true
                    if (parseFloat(value) < 300) return '設定できる最低金額は300です。'
                    if (parseFloat(value.toString().length) > 9) return '設定できる桁数を超えています。'

                    return true
                },
            ],
        };
    },
    created() {
        this.user = useUserStore().user;

        axios.get('/api/search/' + this.user.id).then((res) => {
            this.items = res.data
        }).catch(error => console.log(error))
    },
    methods: {
        createConfirm() {
            this.checkId = 0
            this.keyword = ''
            this.excludeKeyword = ''
            this.priceMin = ''
            this.priceMax = ''
            this.createDialog = true
        },
        updateConfirm($id) {
            const targetData = this.items.find(item => item.id === $id)

            this.checkId = $id
            this.keyword = targetData.keyword
            this.excludeKeyword = targetData.exclude_keyword
            this.priceMin = targetData.price_min
            this.priceMax = targetData.price_max
            this.createDialog = true
        },
        async submit(event) {
            const results = await event

            if(results.valid === true){
                if(this.checkId === 0){
                    axios.post('/api/register', {
                        params: {
                            users_id: this.user.id,
                            keyword: this.keyword,
                            exclude_keyword: this.excludeKeyword,
                            price_min: this.priceMin,
                            price_max: this.priceMax,
                        }
                    })
                    .then((res) => axios.get('/api/search/' + this.user.id))
                    .then((res) => {
                        this.items = res.data
                        this.createDialog = false
                    })
                    .catch(error => alert(error))

                }else{
                    axios.put('/api/register/' + this.checkId, {
                        params: {
                            keyword: this.keyword,
                            exclude_keyword: this.excludeKeyword,
                            price_min: this.priceMin,
                            price_max: this.priceMax,
                        }
                    })
                    .then((res) => axios.get('/api/search/' + this.user.id))
                    .then((res) => {
                        this.items = res.data
                        this.createDialog = false
                    })
                    .catch(error => alert(error))
                }
            }
        },
        deleteConfirm(id) {
            this.deleteDialog = true
            this.deleteId = id
        },
        deleteItem(id) {
            axios.delete('/api/register/' + id)
            .then((res) => axios.get('/api/search/' + this.user.id))
            .then((res) => {
                this.items = res.data
                this.deleteDialog = false
            })
            .catch(error => alert(error))
        },
    },
}
</script>
