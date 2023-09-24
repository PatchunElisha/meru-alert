<template>
    <v-toolbar>
        <router-link :to="{ name : 'Home' }">
            <v-btn height="100" color="black">HOME</v-btn>
        </router-link>
        <router-link :to="{ name : 'Search' }">
            <v-btn height="100" color="black">検索</v-btn>
        </router-link>
        <v-spacer />
        <p v-if="user != null" class="pr-4">{{ user.name }}</p>
        <v-btn v-if="user != null" class="mr-0" height="100" @click="logout">ログアウト</v-btn>
    </v-toolbar>
</template>

<script>
import { useUserStore } from '../stores/user'

export default {
    data() {
        return {
            userStore: useUserStore(),
        };
    },
    computed: {
        user() {
            return this.userStore.user;
        }
    },
    methods: {
        logout() {
            axios.post("api/logout").then(res => {
                useUserStore().clearUser()
                this.$router.push("/login");
            }).catch(error => { console.log(error) });
        }
    },
};
</script>
