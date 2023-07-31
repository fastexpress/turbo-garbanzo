import store from './store';
import { $themeConfig } from '@themeConfig';
import _ from 'lodash';

export default {
    init() {
        // set default styles
        let val = localStorage.getItem('dark_mode'); // light, dark, system
        if (!val) {
            val = $themeConfig.theme;
        }
        store.commit('toggleDarkMode', val);

        val = localStorage.getItem('menu_style'); // vertical, collapsible-vertical, horizontal
        if (!val) {
            val = $themeConfig.navigation;
        }
        store.commit('toggleMenuStyle', val);

        val = localStorage.getItem('layout_style'); // full, boxed-layout, large-boxed-layout
        if (!val) {
            val = $themeConfig.layout;
        }
        store.commit('toggleLayoutStyle', val);

        val = localStorage.getItem('token');
        if (_.isNull(val)) {
            store.commit('setToken', null);
        } else {
            store.commit('setToken', JSON.parse(val));
        }

        val = localStorage.getItem('user');
        if (_.isNull(val)) {
            store.commit('setUser', null);
        } else {
            store.commit('setUser', JSON.parse(val));
        }

        val = localStorage.getItem('permits');
        if (_.isNull(val)) {
            store.commit('setPermits', null);
        } else {
            store.commit('setPermits', JSON.parse(val));
        }
    },
    toggleMode(mode) {
        if (!mode) {
            let val = localStorage.getItem('dark_mode'); //light|dark|system
            mode = val;
            if (!val) {
                mode = 'light';
            }
        }
        store.commit('toggleDarkMode', mode || 'light');
        return mode;
    }
};
