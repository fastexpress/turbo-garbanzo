import List from './list-customer.vue'
import New from './new-customer.vue'
import Update from './update-customer.vue'


const routes = [
    {
        path: '/cliente/lista',
        name: 'ListCustomer',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/cliente/nuevo',
        name: 'NewCustomer',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/cliente/actualizar/:id',
        name: 'UpdateCustomer',
        component: Update,
        meta: { authRequired: true },
    }
]
export default routes