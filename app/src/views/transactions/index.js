import List from './list-transaction.vue'
import Movement from './movement.vue';
import Account from './account.vue';

const routes = [
    {
        path: '/transaccion/lista',
        name: 'ListTransaction',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/transaccion/movimiento',
        name: 'Movement',
        component: Movement,
        meta: { authRequired: true },
    }, {
        path: '/transaccion/ingreso-retiro',
        name: 'Account',
        component: Account,
        meta: { authRequired: true },
    }
]
export default routes