import Vue from "vue";
import Vuex from "vuex";
import moment from "moment";
import auth from "../utils/auth";
import _ from "lodash";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    layout: "app",
    token: null,
    user: null,
    permits: null,
    api: 'https://core.fastexpressgt.tk/api',
    upload: 'https://core.fastexpressgt.tk/',
    // api: "http://core.test/api",
    // upload: "http://core.test`/",
    is_show_sidebar: true,
    is_show_search: false,
    is_dark_mode: false,
    dark_mode: "light",
    menu_style: "vertical",
    layout_style: "full",
    package: {},
    updatePackage: {},
    bag: [],
    updateBag: [],
    price: {},
    updatePrice: {},
  },
  mutations: {
    setToken(state, payload) {
      if (_.isNull(payload)) {
        localStorage.setItem("token", payload);
      } else {
        localStorage.setItem("token", JSON.stringify(payload));
      }
      state.token = payload;
    },
    setLayout(state, payload) {
      state.layout = payload;
    },
    setUser(state, payload) {
      if (_.isNull(payload)) {
        localStorage.setItem("user", payload);
      } else {
        localStorage.setItem("user", JSON.stringify(payload));
      }
      state.user = payload;
    },
    setPackage(state, payload) {
      state.package = payload;
    },
    setUpdatePackage(state, payload) {
      state.updatePackage = payload;
    },
    setBag(state, payload) {
      state.bag = payload;
    },
    setUpdateBag(state, payload) {
      state.updateBag = payload;
    },
    setPrice(state, payload) {
      state.setPrice = payload;
    },
    setUpdatePrice(state, payload) {
      state.updatePrice = payload;
    },
    setPermits(state, payload) {
      if (_.isNull(payload)) {
        localStorage.setItem("permits", payload);
      } else {
        localStorage.setItem("permits", JSON.stringify(payload));
      }
      state.permits = payload;
    },
    toggleSideBar(state, value) {
      state.is_show_sidebar = value;
    },
    toggleSearch(state, value) {
      state.is_show_search = value;
    },
    toggleDarkMode(state, value) {
      //light|dark|system
      value = value || "light";
      localStorage.setItem("dark_mode", value);
      state.dark_mode = value;
      if (value == "light") {
        state.is_dark_mode = false;
      } else if (value == "dark") {
        state.is_dark_mode = true;
      } else if (value == "system") {
        if (
          window.matchMedia &&
          window.matchMedia("(prefers-color-scheme: dark)").matches
        ) {
          state.is_dark_mode = true;
        } else {
          state.is_dark_mode = false;
        }
      }

      if (state.is_dark_mode) {
        document.querySelector("body").classList.add("dark");
      } else {
        document.querySelector("body").classList.remove("dark");
      }
    },
    toggleMenuStyle(state, value) {
      //horizontal|vertical|collapsible-vertical
      value = value || "";
      localStorage.setItem("menu_style", value);
      state.menu_style = value;
      if (!value || value === "vertical") {
        state.is_show_sidebar = true;
      } else if (value === "collapsible-vertical") {
        state.is_show_sidebar = false;
      }
    },
    toggleLayoutStyle(state, value) {
      //boxed-layout|large-boxed-layout|full
      value = value || "";
      localStorage.setItem("layout_style", value);
      state.layout_style = value;
    },
    logout(state) {
      state.token = null;
      state.user = null;
      state.permits = null;
    },
  },
  getters: {
    layout(state) {
      return state.layout;
    },
    getPackage(state) {
      return state.package;
    },
    getUpdatePackage(state) {
      return state.updatePackage;
    },
    getBag(state) {
      return state.bag;
    },
    getUpdateBag(state) {
      return state.updateBag;
    },
    getPrice(state) {
      return state.price;
    },
    getUpdatePrice(state) {
      return state.updatePrice;
    },
    isLoggedIn(state) {
      const now = moment().unix();
      if (_.isNull(state.token)) {
        return false;
      } else {
        if (now > state.token.expires_in) {
          auth.logout();
          return false;
        } else {
          return true;
        }
      }
    },
    api(state) {
      return state.api;
    },
    upload(state) {
      return state.upload;
    },
    userID(state) {
      return state.user.id;
    },
    userRole(state) {
      return state.user.role;
    },
    getPermit: (state) => (namePermit) => {
      if (state.permits === null) {
        return false;
      } else {
        let permit = state.permits.find(
          (x) => x.name === namePermit && x.status === 1
        );
        if (typeof permit === "undefined") {
          return false;
        } else {
          return true;
        }
      }
    },
    getItemSidebar: (state) => (namePermit) => {
      if (state.permits === null) {
        return false;
      } else {
        let permit = state.permits.find((x) => x.name === namePermit);
        let permits = state.permits.filter(
          (y) => parseInt(y.idParent) === permit.id && y.status === 1
        );
        if (permits.length > 0) {
          return true;
        } else {
          return false;
        }
      }
    },
  },
  actions: {},
  modules: {},
});
