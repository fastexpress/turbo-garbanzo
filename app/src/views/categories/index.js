import List from './list-category.vue'
import New from './new-category.vue'
import Update from './update-category.vue'


const routes = [
    {
        path: '/categoria/lista',
        name: 'ListCategory',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/categoria/nuevo',
        name: 'NewCategory',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/categoria/actualizar/:id',
        name: 'UpdateCategory',
        component: Update,
        meta: { authRequired: true },
    }
]
export default routes