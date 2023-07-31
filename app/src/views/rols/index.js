import List from './list-rol.vue'
import New from './new-rol.vue'
import Update from './update-rol.vue'


const routes = [
    {
        path: '/rol/lista',
        name: 'ListRol',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/rol/nuevo',
        name: 'NewRol',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/rol/actualizar/:id',
        name: 'UpdateRol',
        component: Update,
        meta: { authRequired: true },
    }
]
export default routes