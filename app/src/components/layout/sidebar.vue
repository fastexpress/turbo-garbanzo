<template>
  <!--  BEGIN SIDEBAR  -->
  <div class="sidebar-wrapper sidebar-theme">
    <nav ref="menu" id="sidebar">
      <div class="shadow-bottom"></div>

      <perfect-scrollbar
        class="list-unstyled menu-categories"
        tag="ul"
        :options="{
          wheelSpeed: 0.5,
          swipeEasing: !0,
          minScrollbarLength: 40,
          maxScrollbarLength: 300,
          suppressScrollX: true,
        }"
      >
        <!-- INICIO -->
        <li class="menu">
          <a
            href="#dashboard"
            v-b-toggle
            class="dropdown-toggle"
            @click.prevent
          >
            <div class="">
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
                class="feather feather-home"
              >
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
              </svg>
              <span>Panel</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="dashboard" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                tag="li"
                to="/dashboard"
                @click.native="toggleMobileMenu"
              >
                <a>Inicio</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END INICIO -->
        <!-- CATEGORY -->
        <li class="menu" v-if="getItemSidebar()('Categorias')">
          <a href="#category" v-b-toggle class="dropdown-toggle" @click.prevent>
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <polyline points="8 6 21 6"></polyline>
                <polyline points="8 12 21 12"></polyline>
                <polyline points="8 18 21 18"></polyline>
                <polyline points="3 6 3.01 6"></polyline>
                <polyline points="3 12 3.01 12"></polyline>
                <polyline points="3 18 3.01 18"></polyline>
              </svg>
              <span>Categorias</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="category" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista categoría')"
                tag="li"
                to="/categoria/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear categoría')"
                tag="li"
                to="/categoria/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END CATEGORY -->
        <!--  -->
        <!-- PLACE -->
        <router-link
          tag="li"
          to="/oficina"
          class="menu"
          @click.native="toggleMobileMenu"
          v-if="getItemSidebar()('Oficinas USA')"
        >
          <a class="dropdown-toggle">
            <div class="">
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
                class="feather feather-pie-chart"
              >
                <path
                  d="M9 3H5a2 2 0 0 0-2 2v4m6-6h10a2 2 0 0 1 2 2v4M9 3v18m0 0h10a2 2 0 0 0 2-2V9M9 21H5a2 2 0 0 1-2-2V9m0 0h18"
                ></path>
              </svg>
              <span>Oficinas USA</span>
            </div>
          </a>
        </router-link>
        <!-- END PLACE -->
        <!-- PLACE GT-->
        <router-link
          tag="li"
          to="/oficina-gt"
          class="menu"
          @click.native="toggleMobileMenu"
          v-if="getItemSidebar()('Oficinas GT')"
        >
          <a class="dropdown-toggle">
            <div class="">
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
                class="feather feather-pie-chart"
              >
                <path
                  d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"
                ></path>
                <line x1="4" y1="22" x2="4" y2="15"></line>
              </svg>
              <span>Oficinas GT</span>
            </div>
          </a>
        </router-link>
        <!-- END PLACE GT-->
        <!-- ACCOUNTS -->
        <li class="menu" v-if="getItemSidebar()('Cuentas')">
          <a href="#account" v-b-toggle class="dropdown-toggle" @click.prevent>
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <polyline points="21 8 21 21 3 21 3 8"></polyline>
                <rect x="1" y="3" width="22" height="5"></rect>
                <line x1="10" y1="12" x2="14" y2="12"></line>
              </svg>
              <span>Cuentas</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="account" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista cuenta')"
                tag="li"
                to="/cuenta/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear cuenta')"
                tag="li"
                to="/cuenta/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear cuenta')"
                tag="li"
                to="/cuenta/reporte"
                @click.native="toggleMobileMenu"
              >
                <a>Reporte</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END ACCOUNTS -->
        <!-- PERSONAL ACCOUNT -->
        <li class="menu" v-if="getItemSidebar()('Cuentas Personales')">
          <a
            href="#accountpersonal"
            v-b-toggle
            class="dropdown-toggle"
            @click.prevent
          >
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
              </svg>
              <span>Ctas. personales</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="accountpersonal" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista cuenta personal')"
                tag="li"
                to="/cuentas-personales/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear cuenta personal')"
                tag="li"
                to="/cuentas-personales/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END PERSONAL ACCOUNT -->
        <!-- CASH REGISTER -->
        <li class="menu" v-if="getItemSidebar()('Cajas')">
          <a
            href="#cashregister"
            v-b-toggle
            class="dropdown-toggle"
            @click.prevent
          >
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                <path
                  d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"
                ></path>
              </svg>
              <span>Cajas</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="cashregister" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista caja')"
                tag="li"
                to="/caja/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear categoría')"
                tag="li"
                to="/caja/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END CASH REGISTER -->
        <!-- PERSONAL ACCOUNT -->
        <li class="menu" v-if="getItemSidebar()('Categorias')">
          <a href="#charges" v-b-toggle class="dropdown-toggle" @click.prevent>
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <polyline points="17 11 19 13 23 9"></polyline>
              </svg>
              <span>Cobros</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="charges" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista categoría')"
                tag="li"
                to="/cobros/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Lista categoría')"
                tag="li"
                to="/cobros/reporte"
                @click.native="toggleMobileMenu"
              >
                <a>Reporte</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END PERSONAL ACCOUNT -->
        <!-- PRICES -->
        <li class="menu" v-if="getItemSidebar()('Precios USA')">
          <a href="#prices" v-b-toggle class="dropdown-toggle" @click.prevent>
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <path
                  d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"
                ></path>
                <polyline points="12 1 12 23"></polyline>
              </svg>
              <span>Precios a USA</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="prices" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista precios USA')"
                tag="li"
                to="/precios/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear precios USA')"
                tag="li"
                to="/precios/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END PRICES -->
        <!-- BEGIN TRANSACTION -->
        <li class="menu" v-if="getItemSidebar()('Transacciones')">
          <a
            href="#transaction"
            v-b-toggle
            class="dropdown-toggle"
            @click.prevent
          >
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <polyline points="17 1 21 5 17 9"></polyline>
                <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                <polyline points="7 23 3 19 7 15"></polyline>
                <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
              </svg>
              <span>Transacciones</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="transaction" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista Transacciones')"
                tag="li"
                to="/transaccion/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Movimiento')"
                tag="li"
                to="/transaccion/movimiento"
                @click.native="toggleMobileMenu"
              >
                <a>Movimiento</a>
              </router-link>
              <router-link
                v-if="getPermit()('Retiro/Ingreso')"
                tag="li"
                to="/transaccion/ingreso-retiro"
                @click.native="toggleMobileMenu"
              >
                <a>Retiro/Ingreso</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END TRANSACTION -->
        <!-- CUSTOMER -->
        <li class="menu" v-if="getItemSidebar()('Usuarios')">
          <a href="#customer" v-b-toggle class="dropdown-toggle" @click.prevent>
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
              </svg>
              <span>Clientes</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="customer" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista usuario')"
                tag="li"
                to="/cliente/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear usuario')"
                tag="li"
                to="/cliente/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END CUSTOMER -->
        <!-- BAG USA -->
        <li class="menu" v-if="getItemSidebar()('Maletas')">
          <a href="#bag" v-b-toggle class="dropdown-toggle" @click.prevent>
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
              </svg>
              <span>Maletas</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="bag" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Manifiesto')"
                tag="li"
                to="/maleta"
                @click.native="toggleMobileMenu"
              >
                <a>Manifiesto</a>
              </router-link>
              <router-link
                v-if="getPermit()('Descargar manifiesto')"
                tag="li"
                to="/maleta/reporte"
                @click.native="toggleMobileMenu"
              >
                <a>Descargas</a>
              </router-link>
              <router-link
                v-if="getPermit()('Números')"
                tag="li"
                to="/maleta/numeros"
                @click.native="toggleMobileMenu"
              >
                <a>Numeros</a>
              </router-link>
              <router-link
                v-if="getPermit()('Estado')"
                tag="li"
                to="/maleta/estado"
                @click.native="toggleMobileMenu"
              >
                <a>Estado</a>
              </router-link>
              <router-link
                v-if="getPermit()('Lista de cobros')"
                tag="li"
                to="/maleta/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista de paquetes</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END BAG USA  -->
        <!-- SHIPMENTS USA -->
        <li class="menu" v-if="getItemSidebar()('Envios a USA')">
          <a
            href="#shipmentsUSA"
            v-b-toggle
            class="dropdown-toggle"
            @click.prevent
          >
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <line x1="22" y1="2" x2="11" y2="13" />
                <polyline points="22 2 15 22 11 13 2 9 22 2"></polyline>
              </svg>
              <span>Envios a USA</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="shipmentsUSA" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista envio a USA')"
                tag="li"
                to="/envios/usa/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear envio a USA')"
                tag="li"
                to="/envios/usa/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END SHIPMENTS USA  -->
        <!-- COLLECT USA -->
        <li class="menu" v-if="getItemSidebar()('Recolección')">
          <a
            href="#collectUSA"
            v-b-toggle
            class="dropdown-toggle"
            @click.prevent
          >
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <rect x="1" y="3" width="15" height="13" />
                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                <circle cx="5.5" cy="18.5" r="2.5" />
                <circle cx="18.5" cy="18.5" r="2.5" />
              </svg>
              <span>Recolección</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="collectUSA" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista recolección')"
                tag="li"
                to="/recoleccion/usa/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear recolección')"
                tag="li"
                to="/recoleccion/usa/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
              <router-link
                v-if="getPermit()('Lista recolección')"
                tag="li"
                to="/recoleccion/usa/reporte"
                @click.native="toggleMobileMenu"
              >
                <a>Reporte</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END SHIPMENTS USA  -->
        <!--  -->
        <!-- PAQUETES -->
        <li class="menu" v-if="getItemSidebar()('Paquetes')">
          <a href="#package" v-b-toggle class="dropdown-toggle" @click.prevent>
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line>
                <path
                  d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"
                ></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
              </svg>
              <span>Paquetes</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="package" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Listar paquetes')"
                tag="li"
                to="/paquete/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear paquete')"
                tag="li"
                to="/paquete/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END PAQUETES -->
        <!-- PAQUETES -->
        <li class="menu" v-if="getItemSidebar()('Paquetes UPS')">
          <a href="#ups" v-b-toggle class="dropdown-toggle" @click.prevent>
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <path
                  d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"
                ></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
              </svg>
              <span>Paquetes UPS</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="ups" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Listar paquetes UPS')"
                tag="li"
                to="/ups/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear paquete UPS')"
                tag="li"
                to="/ups/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
              <router-link
                v-if="getPermit()('Reporte')"
                tag="li"
                to="/ups/reporte/tipicos"
                @click.native="toggleMobileMenu"
              >
                <a>Reporte tipicos</a>
              </router-link>
              <router-link
                v-if="getPermit()('Reporte')"
                tag="li"
                to="/ups/reporte/completo"
                @click.native="toggleMobileMenu"
              >
                <a>Reporte completo</a>
              </router-link>
              <router-link
                v-if="getPermit()('Reporte')"
                tag="li"
                to="/ups/reporte/ups"
                @click.native="toggleMobileMenu"
              >
                <a>Reporte UPS</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END PAQUETES -->
        <!-- BALERS -->
        <li class="menu" v-if="getItemSidebar()('Categorias')">
          <a href="#baler" v-b-toggle class="dropdown-toggle" @click.prevent>
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
              </svg>
              <span>Empacadoras</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="baler" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista categoría')"
                tag="li"
                to="/empacadora/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear categoría')"
                tag="li"
                to="/empacadora/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END BALERS -->
        <!-- USER -->
        <li class="menu" v-if="getItemSidebar()('Usuarios')">
          <a href="#user" v-b-toggle class="dropdown-toggle" @click.prevent>
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
              </svg>
              <span>Usuarios</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="user" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista usuario')"
                tag="li"
                to="/usuario/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear usuario')"
                tag="li"
                to="/usuario/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END USER -->
        <!-- ROL -->
        <li class="menu" v-if="getItemSidebar()('Rol')">
          <a href="#rol" v-b-toggle class="dropdown-toggle" @click.prevent>
            <div class="">
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
                class="feather feather-dollar-sign"
              >
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
              </svg>
              <span>Rol</span>
            </div>
            <div>
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
                class="feather feather-chevron-right"
              >
                <polyline points="9 18 15 12 9 6"></polyline>
              </svg>
            </div>
          </a>
          <b-collapse id="rol" accordion="menu">
            <ul class="collapse submenu list-unstyled show">
              <router-link
                v-if="getPermit()('Lista rol')"
                tag="li"
                to="/rol/lista"
                @click.native="toggleMobileMenu"
              >
                <a>Lista</a>
              </router-link>
              <router-link
                v-if="getPermit()('Crear rol')"
                tag="li"
                to="/rol/nuevo"
                @click.native="toggleMobileMenu"
              >
                <a>Nuevo</a>
              </router-link>
            </ul>
          </b-collapse>
        </li>
        <!-- END ROL -->
        <!-- PERMISOS -->
        <router-link
          tag="li"
          to="/permiso/actualizar"
          class="menu"
          @click.native="toggleMobileMenu"
          v-if="getItemSidebar()('Permiso')"
        >
          <a class="dropdown-toggle">
            <div class="">
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
                class="feather feather-pie-chart"
              >
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="8.5" cy="7" r="4" />
                <polyline points="17 11 19 13 23 9" />
              </svg>
              <span>Permisos</span>
            </div>
          </a>
        </router-link>
        <!-- END PERMISOS -->
        <!-- WHATSAPP -->
        <!-- <li
          class="menu"
          v-if="getItemSidebar()('Whatsapp')"
          @click="openNumbers"
        >
          <a class="dropdown-toggle">
            <div class="">
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
                class="feather feather-pie-chart"
              >
                <path
                  d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                />
              </svg>
              <span>Whatsapp</span>
            </div>
          </a>
        </li> -->
        <!-- END WHATSAPP -->
        <!-- AJUSTES -->
        <router-link
          tag="li"
          to="/ajustes"
          class="menu"
          @click.native="toggleMobileMenu"
          v-if="getItemSidebar()('Permiso')"
        >
          <a class="dropdown-toggle">
            <div class="">
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
                class="feather feather-pie-chart"
              >
                <circle cx="12" cy="12" r="3"></circle>
                <path
                  d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"
                ></path>
              </svg>
              <span>Ajustes</span>
            </div>
          </a>
        </router-link>
        <!-- END AJUSTES -->
      </perfect-scrollbar>
    </nav>
  </div>
  <!--  END SIDEBAR  -->
</template>
<script>
import _ from "lodash";
import { mapGetters } from "vuex";

export default {
  data() {
    return { menu_collapse: "dashboard" };
  },

  watch: {
    $route(to) {
      const selector = document.querySelector(
        '#sidebar a[href="' + to.path + '"]'
      );
      if (!_.isNull(selector)) {
        const ul = selector.closest("ul.collapse");

        if (!ul) {
          const ele = document.querySelector(".dropdown-toggle.not-collapsed");
          if (ele) {
            ele.click();
          }
        }
      }
    },
  },

  mounted() {
    // default menu selection on refresh
    const selector = document.querySelector(
      '#sidebar a[href="' + window.location.pathname + '"]'
    );
    if (selector) {
      const ul = selector.closest("ul.collapse");
      if (ul) {
        let ele = ul.closest("li.menu").querySelectorAll(".dropdown-toggle");
        if (ele) {
          ele = ele[0];
          setTimeout(() => {
            ele.click();
          });
        }
      } else {
        selector.click();
      }
    }
  },
  methods: {
    ...mapGetters(["api", "getItemSidebar", "getPermit"]),
    toggleMobileMenu() {
      if (window.innerWidth < 991) {
        this.$store.commit("toggleSideBar", !this.$store.state.is_show_sidebar);
      }
    },
    openNumbers() {
      window.open(this.api() + "/getNumbers");
    },
  },
};
</script>
