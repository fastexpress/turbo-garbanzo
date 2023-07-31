<template>
  <div>
    <!--  BEGIN NAVBAR  -->
    <div class="header-container fixed-top">
      <header class="header navbar navbar-expand-sm">
        <ul class="navbar-item theme-brand flex-row text-center">
          <li class="nav-item theme-logo">
            <router-link to="/">
              <img
                src="@/assets/images/logo.jpg"
                class="navbar-logo"
                alt="logo"
              />
            </router-link>
          </li>
          <li class="nav-item theme-text">
            <router-link to="/" class="nav-link"> Fast Express </router-link>
          </li>
        </ul>
        <div class="d-none horizontal-menu">
          <a
            href="javascript:void(0);"
            class="sidebarCollapse"
            data-placement="bottom"
            @click="
              $store.commit('toggleSideBar', !$store.state.is_show_sidebar)
            "
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              stroke-linecap="round"
              stroke-linejoin="round"
              class="feather feather-menu"
            >
              <line x1="3" y1="12" x2="21" y2="12"></line>
              <line x1="3" y1="6" x2="21" y2="6"></line>
              <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
          </a>
        </div>
        <div class="navbar-item flex-row ml-md-auto">
          <div class="dark-mode d-flex align-items-center">
            <a
              v-if="$store.state.dark_mode == 'light'"
              href="javascript:;"
              class="d-flex align-items-center"
              @click="toggleMode('dark')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-sun"
              >
                <circle cx="12" cy="12" r="5"></circle>
                <line x1="12" y1="1" x2="12" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="23"></line>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                <line x1="1" y1="12" x2="3" y2="12"></line>
                <line x1="21" y1="12" x2="23" y2="12"></line>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
              </svg>
              <span class="ml-2">Claro</span>
            </a>
            <a
              v-if="$store.state.dark_mode == 'dark'"
              href="javascript:;"
              class="d-flex align-items-center"
              @click="toggleMode('light')"
            >
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-moon"
              >
                <path
                  d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"
                ></path>
              </svg>
              <span class="ml-2">Oscuro</span>
            </a>
          </div>
          <b-dropdown
            toggle-tag="a"
            variant="icon-only"
            toggle-class="user nav-link"
            class="nav-item user-profile-dropdown"
            :right="true"
          >
            <template #button-content>
              <img src="@/assets/images/user.png" alt="avatar" />
            </template>
            <b-dropdown-item @click="closeSesion">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="feather feather-log-out"
              >
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
              </svg>
              Cerrar sesión
            </b-dropdown-item>
          </b-dropdown>
        </div>
      </header>
    </div>
    <!--  END NAVBAR  -->
    <!--  BEGIN NAVBAR  -->
    <div class="sub-header-container">
      <header class="header navbar navbar-expand-sm">
        <a
          href="javascript:void(0);"
          class="sidebarCollapse"
          data-placement="bottom"
          @click="$store.commit('toggleSideBar', !$store.state.is_show_sidebar)"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="feather feather-menu"
          >
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
          </svg>
        </a>

        <!-- Portal vue for Breadcrumb -->
        <portal-target name="breadcrumb"> </portal-target>
      </header>
    </div>
    <!--  END NAVBAR  -->
  </div>
</template>
<script>
import auth from "../../utils/auth";
export default {
  data() {
    return {};
  },
  mounted() {
    this.toggleMode();
  },
  methods: {
    toggleMode(mode) {
      this.$appSetting.toggleMode(mode);
    },
    async closeSesion() {
      await auth.logout();
      await this.$router.push({ name: "login" });
    },
  },
};
</script>
