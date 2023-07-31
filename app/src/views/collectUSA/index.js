import List from './list-collect.vue'
import New from './new-collect.vue'
import Update from './update-collect.vue'
import Report from './report-collect.vue';

const routes = [
    {
        path: '/recoleccion/usa/lista',
        name: 'ListCollect',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/recoleccion/usa/nuevo',
        name: 'NewCollect',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/recoleccion/usa/actualizar/:id',
        name: 'UpdateCollect',
        component: Update,
        meta: { authRequired: true },
    }, {
        path: '/recoleccion/usa/reporte',
        name: 'ReportCollect',
        component: Report,
        meta: { authRequired: true },
    }

]
export default routes