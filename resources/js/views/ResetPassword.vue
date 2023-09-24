<template>
    <div>
        <v-sheet max-width="400" class="mx-auto pa-6 ma-5" elevation="5">
            <h2>新しいパスワードを入力</h2>
            <p class="mt-2 pb-2">{{ getMessage }}</p>
            <v-form fast-fail @submit.prevent="reset">

                <input type="email" :value="email" readonly>
                <v-text-field
                    v-model="password"
                    label="password"
                    type="password"
                    :rules="passwordRules"
                ></v-text-field>
                <v-text-field
                    v-model="passwordConfirmation"
                    label="password[確認]"
                    type="password"
                    :rules="passwordConfirmationRules"
                ></v-text-field>
                <v-btn type="submit" block>登録</v-btn>
            </v-form>
        </v-sheet>
    </div>
</template>
<script>
export default {
    data() {
        return {
            getMessage: '',
            password: '',
            passwordConfirmation: '',
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
            passwordConfirmationRules: [
                value => {
                    if (!value) return '入力は必須です。'
                    if (value.length < 8) return 'パスワードは8文字以上が必須です。'
                    if (value.length > 255) return '入力できる文字数を超えています。'
                    if (value != this.password) return 'パスワードが一致しません。'

                    return true;
                },
            ],
        };
    },
    props: ['email', 'token'],
    methods: {
        reset() {
            axios.post('/api/reset-password', {
                token: this.token,
                email: this.email,
                password: this.password,
                password_confirmation: this.passwordConfirmation
            })
            .then((res) => {
                this.getMessage = 'パスワード登録が完了しました。'
            })
            .catch(error => alert(error))
        }
    }
};
</script>

<style>
h2{
    text-align: center;
}
</style>