import List from './list-package.vue'
import New from './new-package.vue'
import Update from './update-package.vue'
import Show from './show-package.vue'


const routes = [
    {
        path: '/paquete/lista',
        name: 'ListPackage',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/paquete/nuevo',
        name: 'NewPackage',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/paquete/actualizar/:id',
        name: 'UpdatePackage',
        component: Update,
        meta: { authRequired: true },
    }, {
        path: '/paquete/ver/:id',
        name: 'ShowPackage',
        component: Show,
        meta: { authRequired: true },
    }
]
export default routes