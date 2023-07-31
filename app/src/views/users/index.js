import List from './list-user.vue'
import New from './new-user.vue'
import Update from './update-user.vue'


const routes = [
    {
        path: '/usuario/lista',
        name: 'ListUser',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/usuario/nuevo',
        name: 'NewUser',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/usuario/actualizar/:id',
        name: 'UpdateUser',
        component: Update,
        meta: { authRequired: true },
    }
]
export default routes