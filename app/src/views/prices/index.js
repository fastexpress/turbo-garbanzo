import List from './list-price.vue'
import New from './new-price.vue'
import Update from './update-price.vue'


const routes = [
    {
        path: '/precios/lista',
        name: 'ListPrice',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/precios/nuevo',
        name: 'NewPrice',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/precios/actualizar/:id',
        name: 'UpdatePrice',
        component: Update,
        meta: { authRequired: true },
    }
]
export default routes