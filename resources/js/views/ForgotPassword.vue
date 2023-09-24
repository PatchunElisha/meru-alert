<template>
    <div>
        <v-sheet max-width="400" class="mx-auto pa-6 ma-5" elevation="5">
            <h2>パスワードリセット</h2>
            <p class="mt-2 pb-2">{{ getMessage }}</p>
            <v-form fast-fail @submit.prevent="reset">
                <v-text-field
                    v-model="email"
                    label="email"
                    type="email"
                    :rules="emailRules"
                ></v-text-field>
                <v-btn type="submit" block>リセット</v-btn>
            </v-form>
        </v-sheet>
    </div>
</template>
<script>
export default {
    data() {
        return {
            email: '',
            getMessage: '',
            emailRules: [
                value => {
                    if (!value) return '入力は必須です。'
                    if (value.length > 255) return '入力できる文字数を超えています。'

                    return true;
                },
            ],
        };
    },
    methods: {
        reset() {
            axios.post('/api/forgot-password', {
                email: this.email,
            })
            .then((res) => {
                this.getMessage = 'パスワードリセットのメールを送信しました。'

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