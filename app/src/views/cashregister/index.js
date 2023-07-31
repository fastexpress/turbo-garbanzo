import List from './list-cashregister.vue'
import New from './new-cashregister.vue'
import Update from './update-cashregister.vue'


const routes = [
    {
        path: '/caja/lista',
        name: 'ListCashRegister',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/caja/nuevo',
        name: 'NewCashRegister',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/caja/actualizar/:id',
        name: 'UpdateCashRegister',
        component: Update,
        meta: { authRequired: true },
    }
]
export default routes