import store from '../store';
export default {
    async logout() {
        store.commit('logout');
        await localStorage.setItem('token', null);
        await localStorage.setItem('user', null);
        await localStorage.setItem('permits', null);

    }
}