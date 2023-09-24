<template>
    <div>
        <v-sheet max-width="380" class="mx-auto pa-6 ma-5" elevation="5">
            <h2>ログイン</h2>
            <p class="mt-2 pb-2 error">{{ getUserMessage }}</p>
            <v-form fast-fail @submit.prevent="login">
                <v-text-field
                    v-model="email"
                    label="email"
                    type="email"
                    :rules="emailRules"
                ></v-text-field>

                <v-text-field
                    v-model="pass"
                    label="password"
                    :type="showPassword ? 'text' : 'password'"
                    :rules="passwordRules"
                ></v-text-field>
                <v-checkbox id="showPassword" v-model="showPassword" label="パスワードを表示する"></v-checkbox>
                <v-btn type="submit" block>ログイン</v-btn>
            </v-form>
            <div class="pt-3 text-end">
                <!-- ログインユーザーのみに変更 -->
                <!-- <span>[仮] 新規登録の方は <router-link :to="{ name : 'Signup' }">こちら</router-link></span><br> -->
                <span>パスワードをお忘れの方は <router-link :to="{ name : 'ForgotPassword' }">こちら</router-link></span>
            </div>
        </v-sheet>
    </div>
</template>
<script>
export default {
    data() {
        return {
            email: '',
            pass: '',
            showPassword: false,
            getUserMessage: "",
            emailRules: [
                value => {
                    if (!value) return '入力は必須です。'
                    if (value.length > 255) return '入力できる文字数を超えています。'

                    return true;
                },
            ],
            passwordRules: [
                value => {
                    if (!value) return '入力は必須です。'
                    if (value.length < 8) return 'パスワードは8文字以上が必須です。'
                    if (value.length > 255) return '入力できる文字数を超えています。'

                    return true;
                },
            ],
        };
    },
    methods: {
        login() {
            axios.get('/sanctum/csrf-cookie').then((res) => {
                axios.post('/api/login', {
                    email: this.email,
                    password: this.pass,
                })
                .then((res) => {
                    if(res.data.result === true) {
                        this.$router.push("/")
                    }else{
                        this.getUserMessage = 'ログインに失敗しました。'
                    }
                })
                .catch((err) => {
                    console.log(err)
                    this.getUserMessage = 'ログインに失敗しました。'
                })
            })
            .catch((err) => {
                console.log(err)
            })
        }
    }
};
</script>

<style>
h2{
    text-align: center;
}
.error{
    color: red;
}
</style>