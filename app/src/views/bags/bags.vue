<template>
  <div class="layout-px-spacing">
    <portal to="breadcrumb">
      <div class="page-header">
        <nav class="breadcrumb-one" aria-label="breadcrumb">
          <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        </nav>
      </div>
    </portal>
    <div class="row">
      <div class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox panel box box-shadow">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                <h4>Filtros</h4>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <b-row>
              <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                <flatpickr
                  v-model="date"
                  :config="config"
                  class="form-control flatpickr active"
                  @on-change="get"
                ></flatpickr>
              </b-col>
              <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                <multiselect
                  v-model="traveler"
                  :options="travelers"
                  :show-labels="false"
                  :searchable="true"
                  label="name"
                  track-by="name"
                  placeholder="Seleccione un viajero"
                  @search-change="getTravelers"
                  :internal-search="false"
                  :class="errors.length > 0 ? 'is-danger' : ''"
                  :loading="loaderTraveler"
                  @close="get"
                >
                </multiselect>
              </b-col>
              <b-col cols="12" xl="4" lg="4" md="12" sm="12">
                <multiselect
                  v-model="city"
                  :options="cities"
                  :allow-empty="false"
                  :show-labels="false"
                  :searchable="true"
                  placeholder="Seleccione una ciudad"
                  @close="get"
                >
                </multiselect>
              </b-col>
            </b-row>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox panel box box-shadow">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                <h4>Generar manifiesto</h4>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-6">
                <h3>Paquetes pendientes</h3>
                <b-button
                  v-if="
                    city !== null &&
                    traveler !== null &&
                    packages.length > 0 &&
                    bags.length > 0
                  "
                  variant="success"
                  @click="moveRestBags"
                >
                  Agregar paquetes restantes
                </b-button>
                <hr />
                <multiselect
                  v-model="category"
                  :options="categoryList"
                  placeholder="Filtro para paquetes"
                  @input="listOfPackages"
                >
                </multiselect>
                <hr />
                <draggable
                  class="list-group"
                  :list="packagesFiltered"
                  group="people"
                  @add="addToList"
                >
                  <b-media
                    class="d-block d-sm-flex"
                    v-for="p in packagesFiltered"
                    :key="p.id"
                  >
                    <template #aside>
                      <b-badge :variant="getColor(JSON.parse(p.category).id)">{{
                        JSON.parse(p.category).name
                      }}</b-badge>
                    </template>
                    <div class="d-flex justify-content-between">
                      <div class="">
                        <p class="">
                          Peso:{{ myLbs(p.weight) }}&nbsp;{{ p.baler.code }}-{{
                            p.correlative
                          }}
                        </p>
                        <h6 class="">{{ p.content }}</h6>
                        <p class="">{{ dateGT(p.created_at) }}</p>
                      </div>
                      <div class="" @click="moveListToBag(p)">
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
                          class="feather feather-star f-icon-line"
                          style="display: block"
                        >
                          <circle cx="12" cy="12" r="10"></circle>
                          <polyline points="12 16 16 12 12 8"></polyline>
                          <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                      </div>
                    </div>
                  </b-media>
                </draggable>
              </div>
              <div class="col-6">
                <h3>Maletas</h3>
                <b-tabs content-class="mt-3" v-model="tabIndex">
                  <b-tab
                    :title="`${bag.bag} de ${myLbs(
                      bag.capacity
                    )}, Disponible: ${myLbs(
                      parseDecimal(
                        parseDecimal(bag.capacity) - parseDecimal(bag.total)
                      )
                    )}`"
                    v-for="(bag, index) in bags"
                    :key="bag.id"
                  >
                    <div class="fixed-bottom">
                      <b-alert show variant="primary"
                        ><h5 class="text-light text-center">
                          Maleta: {{ index + 1 }} Total:&nbsp;{{
                            myLbs(bag.total)
                          }}
                        </h5></b-alert
                      >
                    </div>
                    <draggable
                      class="list-group"
                      :list="bag.packages"
                      group="people"
                      @add="addToBag"
                    >
                      <b-media
                        class="d-block d-sm-flex"
                        v-for="p in bag.packages"
                        :key="p.id"
                      >
                        <template #aside>
                          <div @click="moveBagToList(p)">
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
                              class="feather feather-star f-icon-line"
                              style="display: block"
                            >
                              <circle cx="12" cy="12" r="10"></circle>
                              <polyline points="12 8 8 12 12 16"></polyline>
                              <line x1="16" y1="12" x2="8" y2="12"></line>
                            </svg>
                          </div>
                        </template>
                        <div class="d-flex justify-content-start">
                          <div class="">
                            <p class="">
                              Peso:{{ myLbs(p.weight) }}&nbsp;{{
                                p.baler.code
                              }}-{{ p.correlative }}
                            </p>
                            <h6 class="">{{ p.content }}</h6>
                            <p class="">{{ dateGT(p.created_at) }}</p>
                          </div>
                          <div class="">
                            <b-badge
                              :variant="getColor(JSON.parse(p.category).id)"
                              >{{ JSON.parse(p.category).name }}</b-badge
                            >
                          </div>
                        </div>
                      </b-media>
                    </draggable>
                    <hr />
                    <h5>Total:&nbsp;{{ myLbs(bag.total) }}</h5>
                  </b-tab>
                </b-tabs>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <b-toast
      id="toast"
      header-class="d-none"
      body-class="toast-success d-flex justify-content-between"
      toaster="b-toaster-bottom-right"
    >
      {{ msgToast }}
      <a
        href="javascript:;"
        class="text-white ml-2"
        @click="$bvToast.hide('toast')"
        >OK</a
      >
    </b-toast>
  </div>
</template>
<script>
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import axios from "axios";
import { mapGetters } from "vuex";
import "@/assets/sass/drag-drop/drag-drop.css";
import draggable from "vuedraggable";
import "flatpickr/dist/flatpickr.min.css";
import "@/assets/sass/forms/custom-flatpickr.css";
import flatpickr from "flatpickr";
import { Spanish } from "flatpickr/dist/l10n/es";
import Vueflatpickr from "vue-flatpickr-component";
flatpickr.localize(Spanish);

import { eight } from "../../utils/eight";

export default {
  metaInfo: { title: "Generar manifiesto" },
  components: {
    draggable,
    flatpickr: Vueflatpickr,
    Multiselect,
  },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Generar manifiesto", active: true },
      ],
      date: new Date(),
      config: {
        wrap: true, // set wrap to true only when using 'input-group'
        altFormat: "d-m-Y",
        altInput: true,
        dateFormat: "Y-m-d",
        locale: Spanish, // locale for this instance only
      },
      tabIndex: -1,
      travelers: [],
      traveler: null,
      loaderTraveler: false,

      cities: [],
      city: null,

      packages: [],
      packagesFiltered: [],
      bags: [],

      categories: [],

      categoryList: [],
      category: "Todos",

      msgToast: "",
    };
  },
  mounted() {
    this.getCities();
    this.getCategories();
  },
  methods: {
    ...mapGetters(["api"]),
    parseDecimalFixed(value) {
      return parseFloat(value).toFixed(2);
    },
    parseDecimal(value) {
      return parseFloat(this.parseDecimalFixed(value));
    },
    myLbs(value) {
      return eight.lbs(parseFloat(value));
    },
    getColor(id) {
      return this.categories.find((x) => x.id === id).color;
    },
    get() {
      if (
        this.city === null ||
        this.city === "" ||
        this.traveler === "" ||
        this.traveler === null
      ) {
        return;
      } else {
        let loader = this.$loading.show();
        axios
          .get(
            this.api() +
              `/getPackages?city=${this.city}&user=${
                this.traveler.id
              }&date=${this.$moment(this.date).format("YYYY-MM-DD")}`
          )
          .then((response) => {
            const { packages, bags } = response.data;
            this.packagesFiltered = packages;
            this.packages = packages;
            this.bags = bags;
            this.listOfPackages(this.category);
            loader.hide();
          })
          .catch((error) => {
            console.log(error);
            loader.hide();
          });
      }
    },
    getCategories() {
      const colors = [
        "primary",
        "secondary",
        "success",
        "danger",
        "warning",
        "info",
        "dark",
      ];
      let count = 0;
      let loader = this.$loading.show();

      axios
        .get(this.api() + "/getAllCategories")
        .then((response) => {
          loader.hide();
          const categories = response.data;
          this.categoryList.push("Todos");
          categories.forEach((category, index) => {
            this.categoryList.push(category.name);
            if (index < 7) {
              this.categories.push({
                id: category.id,
                name: category.name,
                color: colors[index],
              });
            } else {
              this.categories.push({
                id: category.id,
                name: category.name,
                color: colors[count],
              });
              if (count > 6) count = 0;
              else count++;
            }
          });
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    getTravelers(search) {
      this.loaderTraveler = true;
      axios
        .get(this.api() + `/getTravelers?filter=${search}`)
        .then((response) => {
          this.loaderTraveler = false;
          this.travelers = response.data.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderTraveler = false;
        });
    },
    getCities() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + "/setting/3")
        .then((response) => {
          loader.hide();
          this.cities = JSON.parse(response.data.value);
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    addToBag: function (evt) {
      this.moveListToBag(evt.item._underlying_vm_);
    },
    addToList: function (evt) {
      this.moveBagToList(evt.item._underlying_vm_);
    },
    moveListToBag(item) {
      if (this.bags.length < 1) {
        this.$swal({
          text: "No hay maletas disponibles",
          icon: "error",
        });
        return;
      }
      const items = this.isNumberAndHeaderEqual(
        item.id,
        item.idParent,
        item.idHeaderPackage
      );

      if (items.length > 0) {
        items.push(item);
        let loader = this.$loading.show();
        items.forEach((p) => {
          axios
            .put(this.api() + `/moveListToBag/${p.id}`, {
              bag: this.bags[this.tabIndex].id,
            })
            .then((response) => {
              this.msgToast = response.data.message;
              this.$bvToast.show("toast");
              if (items[items.length - 1] === p) {
                this.get();
                loader.hide();
              }
            })
            .catch((error) => {
              loader.hide();
              console.log(error);
            });
        });
      } else {
        let loader = this.$loading.show();
        axios
          .put(this.api() + `/moveListToBag/${item.id}`, {
            bag: this.bags[this.tabIndex].id,
          })
          .then((response) => {
            loader.hide();
            this.msgToast = response.data.message;
            this.$bvToast.show("toast");
            this.get();
          })
          .catch((error) => {
            loader.hide();
            console.log(error);
          });
      }
    },
    moveBagToList(item) {
      const items = this.isNumberAndHeaderEqualBag(
        item.id,
        item.idParent,
        item.idHeaderPackage
      );

      if (items.length > 0) {
        items.push(item);
        let loader = this.$loading.show();
        items.forEach((p) => {
          axios
            .put(this.api() + `/moveBagToList/${p.id}`)
            .then((response) => {
              this.msgToast = response.data.message;
              this.$bvToast.show("toast");
              if (items[items.length - 1] === p) {
                this.get();
                loader.hide();
              }
            })
            .catch((error) => {
              loader.hide();
              console.log(error);
            });
        });
      } else {
        let loader = this.$loading.show();
        axios
          .put(this.api() + `/moveBagToList/${item.id}`)
          .then((response) => {
            loader.hide();
            this.msgToast = response.data.message;
            this.$bvToast.show("toast");
            this.get();
          })
          .catch((error) => {
            loader.hide();
            console.log(error);
          });
      }
    },
    listOfPackages(value) {
      if (value === "Todos") this.packagesFiltered = this.packages;
      else
        this.packagesFiltered = this.packages.filter(
          (x) => JSON.parse(x.category).name === value
        );
    },
    dateGT(date) {
      const newDate = this.$moment(date);
      return newDate.format("DD-MM-YYYY, h:mm:ss a");
    },
    isNumberAndHeaderEqual(id, idParent, idHeaderPackage) {
      return this.packages.filter(
        (x) =>
          x.idParent === idParent &&
          x.idHeaderPackage === idHeaderPackage &&
          x.id !== id
      );
    },
    isNumberAndHeaderEqualBag(id, idParent, idHeaderPackage) {
      return this.bags[this.tabIndex].packages.filter(
        (x) =>
          x.idParent === idParent &&
          x.idHeaderPackage === idHeaderPackage &&
          x.id !== id
      );
    },
    moveRestBags() {
      let loader = this.$loading.show();
      axios
        .post(this.api() + `/bags/moveRestPackages`, {
          date: this.$moment(this.date).format("YYYY-MM-DD"),
          user: this.traveler.id,
          city: this.city,
        })
        .then((response) => {
          loader.hide();
          this.msgToast = response.data.message;
          this.$bvToast.show("toast");
          this.get();
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
  },
};
</script>