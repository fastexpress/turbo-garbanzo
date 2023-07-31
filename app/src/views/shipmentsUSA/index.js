import List from './list-shipment.vue'
import New from './new-shipment.vue'
import Update from './update-shipment.vue'


const routes = [
    {
        path: '/envios/usa/lista',
        name: 'ListShipments',
        component: List,
        meta: { authRequired: true },
    }, {
        path: '/envios/usa/nuevo',
        name: 'NewShipments',
        component: New,
        meta: { authRequired: true },
    }, {
        path: '/envios/usa/actualizar/:id',
        name: 'UpdateShipments',
        component: Update,
        meta: { authRequired: true },
    }
]
export default routes