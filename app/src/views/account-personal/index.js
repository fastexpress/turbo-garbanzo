import List from './list-account-personal.vue'
import New from './new-account-personal.vue'
import Update from './update-account-personal.vue'


const routes = [
    {
        path: '/cuentas-personales/lista',
        name: 'ListPersonalAccount',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/cuentas-personales/nuevo',
        name: 'NewPersonalAccount',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/cuentas-personales/actualizar/:id',
        name: 'UpdatePersonalAccount',
        component: Update,
        meta: { authRequired: true },
    }
]
export default routes