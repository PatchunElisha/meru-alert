import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
    state: () => ({
        user: null,
    }),
    persist: true,
    actions: {
        setUser(userData) {
            this.user = userData;
        },
        clearUser() {
            this.user = null;
        },
    },
})