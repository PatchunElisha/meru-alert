import { createRouter, createWebHistory } from 'vue-router'
import { useUserStore } from '../stores/user'

const routes = [
    {
        path: "/login",
        name: "Login",
        component: () => import('../views/Login.vue'),
    },
    {
        path: '/forgot-password',
        name: 'ForgotPassword',
        component: () => import('../views/ForgotPassword.vue'),
    },
    {
        path: '/reset-password',
        name: 'ResetPassword',
        component: () => import('../views/ResetPassword.vue'),
        props: (route) => ({
            email: route.query.email,
            token: route.query.token,
        }),
    },
    {
        // path: '/signup',
        path: '/login',
        name: 'Signup',
        // component: () => import('../views/Signup.vue'),
        component: () => import('../views/Login.vue'),
    },
    {
        path: '/',
        name: 'Home',
        component: () => import('../views/Home.vue'),
        meta: {
            requiresAuth: true
        },
    },
    {
        path: '/search',
        name: 'Search',
        component: () => import('../views/Search.vue'),
        meta: {
            requiresAuth: true
        },
    },
    {
        path: '/:catchAll(.*)',
        redirect: '/login'
    },
]

const router = createRouter({
    routes,
    history: createWebHistory(),
})

// ログインチェック
router.beforeEach(async (to, from, next) => {
    const requiresAuth = to.meta.requiresAuth;
    if(requiresAuth === true){
        const isAuthenticated = async () => {
            try {
                const res = await axios.get('/api/user');
                useUserStore().setUser(res.data)
                return !!res.data
            } catch (error) {
                return false
            }
        };

        if (!(await isAuthenticated())) {
            useUserStore().clearUser()
            next({ name: 'Login' })
            return
        }
    }
    next();

});

export default router;
