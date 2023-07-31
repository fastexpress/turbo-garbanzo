import Index from './index.vue'


const routes = [
    {
        path: '/ajustes',
        name: 'Setting',
        component: Index,
        meta: { authRequired: true },
    }
]
export default routes