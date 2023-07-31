import List from './list-charge.vue'
import Report from './report-charge.vue'

const routes = [
    {
        path: '/cobros/lista',
        name: 'ListCharge',
        component: List,
        meta: { authRequired: true },
    },
    {
        path: '/cobros/reporte',
        name: 'ReportCharge',
        component: Report,
        meta: { authRequired: true },
    }

]
export default routes