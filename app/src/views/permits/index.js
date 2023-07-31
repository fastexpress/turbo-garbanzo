import Update from './update-permit.vue'
const routes = [
    {
        path: '/permiso/actualizar',
        name: 'UpdatePermit',
        component: Update,
        meta: { authRequired: true },
    },
]
export default routes
