import List from './list-ups.vue'
import New from './new-ups.vue'
import Update from './update-ups.vue'
import Show from './show-ups.vue'
import ReportTypical from './report-typical.vue'
import ReportAll from './report-all.vue'
import ReportUPS from './report-ups.vue'
import Control from "./update-ups-control.vue"

const routes = [
    {
        path: '/ups/lista',
        name: 'ListUPS',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/ups/nuevo',
        name: 'NewUPS',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/ups/actualizar/:id',
        name: 'UpdateUPS',
        component: Update,
        meta: { authRequired: true },
    }, {
        path: '/ups/actualizar/control/:id',
        name: 'UpdateControlUPS',
        component: Control,
        meta: { authRequired: true },
    }, {
        path: '/ups/ver/:id',
        name: 'ShowUPS',
        component: Show,
        meta: { authRequired: true },
    }, {
        path: '/ups/reporte/tipicos',
        name: 'ReportTypicalUPS',
        component: ReportTypical,
        meta: { authRequired: true },
    }, {
        path: '/ups/reporte/completo',
        name: 'ReportAllUPS',
        component: ReportAll,
        meta: { authRequired: true },
    }, {
        path: '/ups/reporte/ups',
        name: 'ReportUPS',
        component: ReportUPS,
        meta: { authRequired: true },
    }
]
export default routes