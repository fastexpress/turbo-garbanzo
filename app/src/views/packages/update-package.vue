<template>
  <div class="layout-px-spacing">
    <portal to="breadcrumb">
      <div class="page-header">
        <nav class="breadcrumb-one" aria-label="breadcrumb">
          <b-breadcrumb :items="breadcrumb"></b-breadcrumb>
        </nav>
      </div>
    </portal>
    <div class="container">
      <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-12 layout-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Actualizar Paquete</h4>
                </div>
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <ValidationObserver ref="observer">
                      <b-form
                        slot-scope="{ validate }"
                        @submit.prevent="validate().then(handleSubmit)"
                      >
                        <!-- RECEIVE -->
                        <ValidationProvider rules="required" name="recibe">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Recibe:"
                          >
                            <b-form-input
                              v-model="receive"
                              name="recibe"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END RECEIVE -->
                        <!-- CHECK TELEPHONE -->
                        <b-form-group
                          :label="
                            checkTelephone === true ? 'Número GT' : 'Número USA'
                          "
                        >
                          <label
                            class="switch s-icons s-outline s-outline-primary"
                          >
                            <input type="checkbox" v-model="checkTelephone" />
                            <span class="slider round"></span>
                          </label>
                        </b-form-group>
                        <!-- END CHECK TELEPHONE -->
                        <!-- TELEFONO -->
                        <ValidationProvider
                          :rules="
                            checkTelephone === true
                              ? 'required|length:9'
                              : 'required|length:12'
                          "
                          name="teléfono"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Teléfono:"
                          >
                            <b-form-input
                              v-model="telephone"
                              name="teléfono"
                              type="text"
                              v-mask="whatMasked(checkTelephone)"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END TELEFONO -->
                        <!-- CHECK TELEPHONE ALTERNATIVE -->
                        <b-form-group
                          :label="
                            checkAlternativeTelephone === true
                              ? 'Número GT'
                              : 'Número USA'
                          "
                        >
                          <label
                            class="switch s-icons s-outline s-outline-primary"
                          >
                            <input
                              type="checkbox"
                              v-model="checkAlternativeTelephone"
                            />
                            <span class="slider round"></span>
                          </label>
                        </b-form-group>
                        <!-- END CHECK TELEPHONE ALTERNATIVE -->
                        <!-- TELEFONO -->
                        <ValidationProvider
                          :rules="
                            checkAlternativeTelephone === true
                              ? 'length:9'
                              : 'length:12'
                          "
                          name="teléfono"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Teléfono del cliente(Alternativo):"
                          >
                            <b-form-input
                              v-model="alternativeTelephone"
                              name="teléfono"
                              type="text"
                              v-mask="whatMasked(checkAlternativeTelephone)"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END TELEFONO ALTERNATIVE -->
                        <!-- CITY -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="estado"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="EE.UU"
                            :state="errors.length > 0 ? false : null"
                          >
                            <!-- getShipments -->
                            <multiselect
                              id="estado"
                              name="estado"
                              ref="estado"
                              v-model="city"
                              :options="cities"
                              placeholder="Seleccione un estado"
                              :class="errors.length > 0 ? 'is-danger' : ''"
                            >
                            </multiselect>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END CITY -->
                        <!-- MODAL -->
                        <modal-package-new @save-package="savePackage" />
                        <!-- END MODAL -->
                        <b-table
                          ref="table"
                          responsive
                          hover
                          :items="packages"
                          :fields="columns"
                          :show-empty="true"
                          foot-clone
                        >
                          <template #cell(id)="row">
                            {{ row.index + 1 }}
                          </template>
                          <template #cell(key)="row">
                            <div class="d-flex align-items-center">
                              <span class="ml-2">
                                <b-select
                                  v-model="row.item.key"
                                  size="sm"
                                  @change="orderPackeges(row.item)"
                                >
                                  <b-select-option
                                    :value="pk"
                                    v-for="pk in listKeys"
                                    :key="pk"
                                    >{{ pk }}</b-select-option
                                  >
                                </b-select>
                              </span>
                            </div>
                          </template>
                          <template #cell(departament)="row">
                            {{ row.item.departament.name }}
                          </template>
                          <template #cell(type)="row">
                            {{ row.item.type.name }}
                          </template>
                          <template #cell(category)="row">
                            {{ row.item.category.name }}
                          </template>
                          <template #cell(weight)="row">
                            {{ myLbs(row.item.weight) }}
                          </template>
                          <template #cell(cost)="row">
                            {{ myDollar(row.item.cost) }}
                          </template>
                          <template #cell(baler)="row">
                            {{
                              row.item.baler.code + "-" + row.item.correlative
                            }}
                          </template>
                          <template #cell(payment)="row">
                            <b-badge
                              v-if="row.item.payment === false"
                              variant="danger"
                              >POR COBRAR</b-badge
                            >
                            <b-badge v-else variant="success"
                              >CANCELADO</b-badge
                            >
                          </template>
                          <template #cell(observation)="row">
                            {{ row.item.observation }}
                          </template>
                          <template #cell(subtotal)="row">
                            {{ myDollar(row.item.subtotal) }}
                          </template>
                          <!-- FOOTER -->
                          <template #foot(subtotal)="">
                            <h4>{{ myDollar(total) }}</h4>
                          </template>
                          <template #foot(observation)=""
                            ><h4 class="text-right">TOTAL:</h4></template
                          >
                          <template #foot()="">&nbsp;</template>
                          <template #cell(action)="row">
                            <div class="position-relative">
                              <b-dropdown
                                :right="true"
                                variant="icon-only"
                                toggle-tag="a"
                                class="custom-dropdown"
                              >
                                <template #button-content>
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
                                    class="feather feather-more-horizontal"
                                  >
                                    <circle cx="12" cy="12" r="1"></circle>
                                    <circle cx="19" cy="12" r="1"></circle>
                                    <circle cx="5" cy="12" r="1"></circle>
                                  </svg>
                                </template>
                                <b-dropdown-item
                                  @click="update(row.item)"
                                  link-class="action-edit"
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
                                    class="feather feather-edit-3"
                                  >
                                    <path d="M12 20h9"></path>
                                    <path
                                      d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"
                                    ></path>
                                  </svg>
                                  Actualizar
                                </b-dropdown-item>
                                <b-dropdown-item
                                  link-class="action-delete"
                                  @click="ifDelete(row.item.id)"
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
                                    class="feather feather-trash"
                                  >
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                      d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                                    ></path>
                                  </svg>
                                  Eliminar
                                </b-dropdown-item>
                              </b-dropdown>
                            </div>
                          </template>
                          <template #empty> No hay registros </template>
                        </b-table>
                        <!-- FOOTER -->
                        <hr />
                        <b-button block type="submit" variant="primary"
                          >Guardar</b-button
                        >
                      </b-form>
                    </ValidationObserver>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- MODALS -->
    <modal-package-update ref="updateModal" @update-package="updatePackage" />
    <!-- END MODALS -->
  </div>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import { mapGetters, mapMutations } from "vuex";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";
import "@/assets/sass/forms/switches.scss";
import modalPackage from "./update-modals/modal-package-new.vue";
import modalUpdatePackge from "./update-modals/modal-package-update.vue";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

Validator.localize({ es: es });
import { eight } from "../../utils/eight";

Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  metaInfo: { title: "Actualizar paquete" },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Actualizar paquete", active: true },
      ],
      columns: [
        { key: "id", label: "ID" },
        { key: "key", label: "Paquete No." },
        { key: "send", label: "Envía" },
        { key: "departament", label: "Departamento" },
        { key: "type", label: "Entrega en" },
        { key: "content", label: "Contenido" },
        { key: "content_en", label: "Traducción" },
        { key: "category", label: "Categoría" },
        { key: "weight", label: "Peso" },
        { key: "cost", label: "Costo" },
        { key: "payment", label: "Pago" },
        { key: "baler", label: "Empacadora" },
        { key: "guide", label: "Guía de envío" },
        { key: "office", label: "Oficina" },
        { key: "observation", label: "Observación" },
        { key: "subtotal", label: "Subtotal" },
        { key: "action", label: "Acciones", class: "actions text-center" },
      ],
      id: 0,
      serie: "",
      receive: "",
      packages: [],
      telephone: "",
      checkTelephone: false,
      alternativeTelephone: "",
      checkAlternativeTelephone: false,
      listKeys: [],
      total: 0,
      cities: [],
      city: "",
    };
  },
  watch: {
    receive: function (e) {
      this.receive = e.toUpperCase();
    },
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    Multiselect,
    "modal-package-new": modalPackage,
    "modal-package-update": modalUpdatePackge,
  },
  mounted() {
    this.getSettingStates();
    this.find();
  },
  methods: {
    ...mapGetters(["api", "getPackage", "userID", "getUpdatePackge"]),
    ...mapMutations(["setPackage", "setUpdatePackage"]),
    find() {
      axios
        .get(this.api() + `/package/${this.$route.params.id}`)
        .then((response) => {
          const { headerPackage, packages } = response.data;
          this.id = headerPackage.id;
          this.receive = headerPackage.receive;
          this.serie = headerPackage.serie;
          this.telephone = headerPackage.telephone;
          this.alternativeTelephone = headerPackage.alternativeTelephone;
          this.city = headerPackage.city;
          this.packages = [];
          packages.forEach((item) => {
            this.packages.push({
              id: item.id,
              correlative: item.correlative,
              key: item.idParent,
              send: item.send,
              departament: JSON.parse(item.departament),
              type: JSON.parse(item.type),
              content: item.content,
              content_en: item.content_en,
              category: JSON.parse(item.category),
              weight: item.weight,
              cost: item.cost,
              payment: item.payment === 0 ? true : false,
              baler: item.baler,
              guide: item.guide,
              office: item.office,
              observation: item.observation,
              subtotal: item.subtotal,
              updatedMultiplier: item.updatedMultiplier,
              multiplier: item.multiplier,
            });
            this.setPackage(this.packages[this.packages.length - 1]);
          });
          this.getListKey();
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getSettingStates() {
      axios
        .get(this.api() + "/setting/3")
        .then((response) => {
          this.cities = JSON.parse(response.data.value);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    parseDecimalFixed(value) {
      return parseFloat(value).toFixed(2);
    },
    parseDecimal(value) {
      return parseFloat(this.parseDecimalFixed(value));
    },
    myDollar(value) {
      return eight.dollar(parseFloat(value));
    },
    myLbs(value) {
      return eight.lbs(parseFloat(value));
    },
    whatMasked(value) {
      return value === true ? "####-####" : "###-###-####";
    },
    savePackage() {
      let MyPackage = this.getPackage();
      MyPackage = {
        ...MyPackage,
        id: this.uuidv4(),
        key: this.packages.length + 1,
      };
      this.packages.push(MyPackage);
      this.getListKey();
    },
    handleSubmit() {
      if (this.packages.length < 1) {
        this.$swal({
          text: "Para guardar este paquete es necesario agregar paquetes",
          icon: "error",
        });
        return;
      }
      let loader = this.$loading.show();
      axios
        .put(this.api() + `/package/${this.id}`, {
          // HEADER
          receive: this.receive,
          telephone: this.telephone,
          alternativeTelephone: this.alternativeTelephone,
          city: this.city,
          serie: this.serie,
          // PACKAGE
          packages: JSON.stringify(this.packages),
          user: this.userID(),
        })
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.setPackage({});
          this.$router.push({
            path: `/paquete/lista`,
          });
        })
        .catch((error) => {
          this.$swal({
            text: `${error.response.data.message}`,
            icon: "error",
          });
          loader.hide();
        });
    },
    uuidv4() {
      return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, (c) =>
        (
          c ^
          (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))
        ).toString(16)
      );
    },
    dynamicSort(property) {
      var sortOrder = 1;
      if (property[0] === "-") {
        sortOrder = -1;
        property = property.substr(1);
      }
      return function (a, b) {
        var result =
          a[property] < b[property] ? -1 : a[property] > b[property] ? 1 : 0;
        return result * sortOrder;
      };
    },
    orderPackeges() {
      this.packages.sort(this.dynamicSort("key"));
      this.getListKey();
    },
    getListKey() {
      this.listKeys = [];
      this.packages.forEach((item, index) => {
        this.listKeys.push(index + 1);
      });
      this.$refs.table.refresh();
      this.calculateTotal();
    },
    update(item) {
      this.setUpdatePackage(item);
      this.$refs.updateModal.openModal();
    },
    calculateTotal() {
      this.total = 0;
      this.packages.forEach((item) => {
        this.total = this.parseDecimal(
          this.parseDecimal(this.total) + this.parseDecimal(item.subtotal)
        );
      });
    },
    updatePackage() {
      let MyPackage = this.getPackage();
      const index = this.packages.findIndex((x) => x.id === MyPackage.id);
      if (index !== -1) {
        this.packages[index] = MyPackage;
        this.getListKey();
      }
    },
    ifDelete(id) {
      this.$swal({
        title: "Quieres eliminar este registro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          this.delete(id);
        }
      });
    },
    delete(id) {
      const index = this.packages.findIndex((x) => x.id === id);
      if (index !== -1) {
        this.packages.splice(index, 1);
        this.getListKey();
      }
    },
  },
};
</script>