import List from './list-account.vue'
import New from './new-account.vue'
import Update from './update-account.vue'
import Report from "./report-account.vue"

const routes = [
    {
        path: '/cuenta/lista',
        name: 'ListAccount',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/cuenta/nuevo',
        name: 'NewAccount',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/cuenta/actualizar/:id',
        name: 'UpdateAccount',
        component: Update,
        meta: { authRequired: true },
    }, {
        path: '/cuenta/reporte',
        name: 'ReporteAccount',
        component: Report,
        meta: { authRequired: true },
    }
]
export default routes