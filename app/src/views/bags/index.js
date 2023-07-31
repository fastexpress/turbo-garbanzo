import Bag from './bags.vue'
import Report from './report.vue'
import Number from './number.vue'
import Send from './send.vue'
import List from './list.vue'


const routes = [
    {
        path: '/maleta',
        name: 'Bag',
        component: Bag,
        meta: { authRequired: true },
    },
    {
        path: '/maleta/reporte',
        name: 'ReportBag',
        component: Report,
        meta: { authRequired: true },
    },
    {
        path: '/maleta/numeros',
        name: 'NumberBag',
        component: Number,
        meta: { authRequired: true },
    },
    {
        path: '/maleta/estado',
        name: 'SendBag',
        component: Send,
        meta: { authRequired: true },
    },
    {
        path: '/maleta/lista',
        name: 'ListBag',
        component: List,
        meta: { authRequired: true },
    }
]
export default routes