import List from './list-baler.vue'
import New from './new-baler.vue'
import Update from './update-baler.vue'


const routes = [
    {
        path: '/empacadora/lista',
        name: 'ListBaler',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/empacadora/nuevo',
        name: 'NewBaler',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/empacadora/actualizar/:id',
        name: 'UpdateBaler',
        component: Update,
        meta: { authRequired: true },
    }
]
export default routes