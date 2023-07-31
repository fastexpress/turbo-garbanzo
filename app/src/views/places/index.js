import Index from './index.vue'


const routes = [
    {
        path: '/oficina',
        name: 'Place',
        component: Index,
        meta: { authRequired: true },
    }
]
export default routes