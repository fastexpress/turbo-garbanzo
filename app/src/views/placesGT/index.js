import Index from './index.vue'


const routes = [
    {
        path: '/oficina-gt',
        name: 'PlaceGT',
        component: Index,
        meta: { authRequired: true },
    }
]
export default routes