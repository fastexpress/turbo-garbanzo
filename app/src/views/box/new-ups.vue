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
                  <h4>Nuevo Caja UPS</h4>
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
                        <!-- SENDER -->
                        <ValidationProvider rules="required" name="envía">
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Envía:"
                          >
                            <b-input-group class="mt-3">
                              <b-form-input
                                v-model="sender"
                                name="envía"
                                type="text"
                                :state="errors[0] ? false : valid ? true : null"
                              ></b-form-input>
                              <b-input-group-append>
                                <b-button
                                  variant="primary"
                                  @click="generateRandomName('sender')"
                                  >Generar nombre</b-button
                                >
                              </b-input-group-append>
                            </b-input-group>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END SENDER -->
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
                              @blur="findSamePackage"
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
                        <!-- CHECK TYPICAL -->
                        <template>
                          <b-form-group
                            :label="typical === true ? 'TIPICO' : 'CLIENTES'"
                          >
                            <label
                              class="switch s-icons s-outline s-outline-primary"
                            >
                              <input type="checkbox" v-model="typical" />
                              <span class="slider round"></span>
                            </label>
                          </b-form-group>
                        </template>
                        <!-- END CHECK TYPICAL -->
                        <!-- COMPONENTS -->
                        <typical
                          ref="typical"
                          v-show="typical === true"
                          :typical="typical"
                        />
                        <customer
                          ref="customer"
                          v-show="typical === false"
                          :typical="typical"
                        />
                        <!-- END COMPONENTS -->
                        <!-- DATE OFFICE -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="fecha"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Fecha de ingreso:"
                            :state="errors.length > 0 ? false : null"
                          >
                            <flatpickr
                              id="fecha"
                              name="fecha"
                              ref="fecha"
                              v-model="inOffice"
                              :config="config"
                              class="form-control flatpickr active"
                            ></flatpickr>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END DATE OFFICE -->
                        <!-- BALER -->
                        <ValidationProvider
                          tag="div"
                          rules="required"
                          name="empacadora"
                          v-slot="{ errors }"
                        >
                          <b-form-group
                            label="Empacadora"
                            :state="errors.length > 0 ? false : null"
                          >
                            <!-- getShipments -->
                            <multiselect
                              id="empacadora"
                              name="empacadora"
                              ref="empacadora"
                              v-model="baler"
                              :options="balers"
                              :show-labels="false"
                              :searchable="true"
                              label="name"
                              track-by="name"
                              placeholder="Seleccione una empacadora"
                              @search-change="getBalers"
                              :internal-search="false"
                              :class="errors.length > 0 ? 'is-danger' : ''"
                              :loading="loaderBaler"
                              :custom-label="customLabel"
                            >
                            </multiselect>
                            <b-form-invalid-feedback
                              :state="errors.length > 0 ? false : null"
                            >
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END BALER -->
                        <!-- SEND -->
                        <ValidationProvider
                          rules="required"
                          name="guía de envío"
                        >
                          <b-form-group
                            slot-scope="{ valid, errors }"
                            label="Guía de envío:"
                          >
                            <b-form-input
                              v-model="guide"
                              name="guía de envío"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-form-group>
                        </ValidationProvider>
                        <!-- END SEND -->
                        <!-- OBSERVATION -->
                        <b-form-group label="Observación:">
                          <b-form-textarea
                            v-model="observation"
                            type="text"
                            rows="3"
                            max-rows="6"
                          ></b-form-textarea>
                        </b-form-group>
                        <!-- END OBSERVATION -->
                        <hr />
                        <!-- ADD BOX -->
                        <b-button
                          block
                          variant="secondary"
                          @click="$refs.add.openModal()"
                          >Agregar caja</b-button
                        >
                        <!-- END BOX -->
                        <!-- BOXES -->
                        <b-table
                          ref="table"
                          responsive
                          hover
                          :items="boxes"
                          :fields="columnBox"
                          :show-empty="true"
                          foot-clone
                        >
                          <template #cell(id)="row">
                            Caja {{ row.index + 1 }}
                          </template>
                          <template #cell(weightKg)="row">
                            {{ myKg(row.item.weightKg) }}
                          </template>
                          <!-- BEGIN FOOTER -->
                          <template #foot(id)=""
                            ><h4 class="text-right">TOTAL:</h4></template
                          >
                          <template #foot(weightKg)="">
                            <h4>{{ myKg(totalKg) }}</h4>
                          </template>
                          <template #foot()="">&nbsp;</template>
                          <!-- END FOOTER -->
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
                                  @click="updateBox(row.item)"
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
                                  @click="ifDeleteBox(row.item.id)"
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
                        <!-- END BOXES -->
                        <!-- ADD CONTENT -->
                        <b-button
                          block
                          variant="secondary"
                          @click="$refs.addContent.openModal()"
                          >Agregar contenido</b-button
                        >
                        <!-- END CONTENT -->
                        <!-- CONTENT -->
                        <b-table
                          ref="table-content"
                          responsive
                          hover
                          :items="contents"
                          :fields="columnContent"
                          :show-empty="true"
                          foot-clone
                        >
                          <template #cell(id)="row">
                            {{ row.index + 1 }}
                          </template>
                          <template #cell(quantity)="row">
                            {{ myQuantity(row.item.quantity) }}
                          </template>
                          <template #cell(price)="row">
                            {{ myDollar(row.item.price) }}
                          </template>
                          <template #cell(subtotal)="row">
                            {{ myDollar(row.item.subtotal) }}
                          </template>
                          <!-- BEGIN FOOTER -->
                          <template #foot(price)=""
                            ><h4 class="text-right">TOTAL:</h4></template
                          >
                          <template #foot(subtotal)="">
                            <h4>{{ myDollar(totalContent) }}</h4>
                          </template>
                          <template #foot()="">&nbsp;</template>
                          <!-- END FOOTER -->
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
                                  @click="updateContent(row.item)"
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
                                  @click="ifDeleteContent(row.item.id)"
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
                        <!-- END CONTENT -->
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
    <!-- BEGIN MODALS -->
    <add ref="add" @save-box="saveBox" />
    <update ref="update" @update-box="updateBoxes" />
    <add-content ref="addContent" @save-content="saveContent" />
    <update-content ref="updateContent" @update-content="updateContents" />
    <find ref="findPackage" />
    <!-- END MODALS -->
  </div>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import { mapGetters } from "vuex";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";
import { eight } from "../../utils/eight";
import Add from "./modal-box-add/add.vue";
import Update from "./modal-box-add/update.vue";
import AddContent from "./modal-content-add/add.vue";
import UpdateContent from "./modal-content-add/update.vue";
import Typical from "./prices/typical.vue";
import Customer from "./prices/customer.vue";
import find from "./find.vue";
import { VMoney } from "v-money";
import { State } from "country-state-city";
import "flatpickr/dist/flatpickr.min.css";
import "@/assets/sass/forms/custom-flatpickr.css";
import flatpickr from "flatpickr";
import { Spanish } from "flatpickr/dist/l10n/es";
import Vueflatpickr from "vue-flatpickr-component";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";

flatpickr.localize(Spanish);
Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  directives: { money: VMoney },
  components: {
    ValidationObserver,
    ValidationProvider,
    add: Add,
    update: Update,
    "add-content": AddContent,
    "update-content": UpdateContent,
    find,
    typical: Typical,
    customer: Customer,
    flatpickr: Vueflatpickr,
    Multiselect,
  },
  data: function () {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Nuevo caja", active: true },
      ],
      pounds: {
        decimal: ".",
        thousands: ",",
        prefix: "",
        suffix: " lbs",
        precision: 2,
        masked: true,
      },
      dollar: {
        decimal: ".",
        thousands: ",",
        prefix: "$ ",
        suffix: "",
        precision: 2,
        masked: true,
      },
      config: {
        disableMobile: true,
        enableTime: true,
        wrap: true, // set wrap to true only when using 'input-group'
        altFormat: "d-m-Y H:i",
        altInput: true,
        dateFormat: "Y-m-d H:i",
        locale: Spanish, // locale for this instance only
      },
      baler: null,
      balers: [],
      loaderBaler: false,
      inOffice: new Date(),
      typical: true,
      receive: "",
      sender: "",
      telephone: "",
      checkTelephone: false,
      alternativeTelephone: "",
      checkAlternativeTelephone: false,
      totalKg: 0,
      totalPayment: 0,
      boxes: [],
      guide: "",
      observation: "",
      columnBox: [
        { key: "id", label: "No." },
        { key: "weightKg", label: "Peso(Kg)" },
        { key: "action", label: "Acciones", class: "actions text-center" },
      ],
      contents: [],
      columnContent: [
        { key: "id", label: "No." },
        { key: "quantity", label: "Cantidad" },
        { key: "content", label: "Contenido" },
        { key: "content_en", label: "Contenido(En)" },
        { key: "price", label: "Precio" },
        { key: "subtotal", label: "Subtotal" },
        { key: "action", label: "Acciones", class: "actions text-center" },
      ],
      totalContent: 0,
    };
  },
  watch: {
    sender: function (e) {
      this.sender = e.toUpperCase();
    },
    receive: function (e) {
      this.receive = e.toUpperCase();
    },
    fakeReceive: function (e) {
      this.fakeReceive = e.toUpperCase();
    },
  },
  mounted() {},

  methods: {
    ...mapGetters(["api", "userID"]),
    parseDecimalFixed(value) {
      return parseFloat(value).toFixed(2);
    },
    parseDecimal(value) {
      return parseFloat(this.parseDecimalFixed(value));
    },
    calculateTotal() {
      this.totalKg = 0;
      this.boxes.forEach((item) => {
        this.totalKg = this.parseDecimal(
          this.parseDecimal(this.totalKg) + this.parseDecimal(item.weightKg)
        );
      });
    },
    calculateTotalContent() {
      this.totalContent = 0;
      this.contents.forEach((item) => {
        this.totalContent = this.parseDecimal(
          this.parseDecimal(this.totalContent) +
            this.parseDecimal(item.subtotal)
        );
      });
    },
    myLbs(value) {
      return eight.lbs(parseFloat(value));
    },
    myQuantity(value) {
      return eight.quantity(parseFloat(value));
    },
    myKg(value) {
      return eight.kg(parseFloat(value));
    },
    myDollar(value) {
      return eight.dollar(parseFloat(value));
    },

    saveBox(box) {
      this.boxes.push(box);
      this.calculateTotal();
    },
    updateBox(box) {
      this.$refs["update"].openModal(box);
    },
    updateContent(content) {
      this.$refs["updateContent"].openModal(content);
    },
    updateBoxes(box) {
      const index = this.boxes.findIndex((x) => x.id === box.id);
      if (index !== -1) {
        this.boxes[index] = box;
      }
      this.$refs.table.refresh();
      this.calculateTotal();
    },
    updateContents(content) {
      const index = this.contents.findIndex((x) => x.id === content.id);
      if (index !== -1) {
        this.contents[index] = content;
      }
      this.$refs["table-content"].refresh();
      this.calculateTotalContent();
    },
    saveContent(content) {
      this.contents.push(content);
      this.calculateTotalContent();
    },
    whatMasked(value) {
      return value === true ? "####-####" : "###-###-####";
    },
    generateRandomName(variable) {
      const firstName = [
        "Adrián",
        "Agustín",
        "Alberto",
        "Alejandro",
        "Alexander",
        "Alexis",
        "Alonso",
        "Andrés Felipe",
        "Ángel",
        "Anthony",
        "Antonio",
        "Bautista",
        "Benicio",
        "Benjamín",
        "Carlos",
        "Carlos Alberto",
        "Carlos Eduardo",
        "Carlos Roberto",
        "César",
        "Cristóbal",
        "Daniel",
        "David",
        "Diego",
        "Dylan",
        "Eduardo",
        "Emiliano",
        "Emmanuel",
        "Enrique",
        "Erik",
        "Ernesto",
        "Ethan",
        "Fabián",
        "Facundo",
        "Felipe",
        "Félix",
        "Félix María",
        "Fernando",
        "Francisco",
        "Francisco Javier",
        "Gabriel",
        "Gaspar",
        "Gustavo Adolfo",
        "Hugo",
        "Ian",
        "Iker",
        "Isaac",
        "Jacob",
        "Javier",
        "Jayden",
        "Jeremy",
        "Jerónimo",
        "Jesús",
        "Jesús Antonio",
        "Jesús Víctor",
        "Joaquín",
        "Jorge",
        "Jorge  Alberto",
        "Jorge Luis",
        "José",
        "José Antonio",
        "José Daniel",
        "José David",
        "José Francisco",
        "José Gregorio",
        "José Luis",
        "José Manuel",
        "José Pablo",
        "Josué",
        "Juan",
        "Juan Ángel",
        "Juan Carlos",
        "Juan David",
        "Juan Esteban",
        "Juan Ignacio",
        "Juan José",
        "Juan Manuel",
        "Juan Pablo",
        "Juan Sebastián",
        "Julio",
        "Julio Cesar",
        "Justin",
        "Kevin",
        "Lautaro",
        "Liam",
        "Lian",
        "Lorenzo",
        "Lucas",
        "Luis",
        "Luis Alberto",
        "Luis Emilio",
        "Luis Fernando",
        "Manuel",
        "Manuel Antonio",
        "Marco Antonio",
        "Mario",
        "Martín",
        "Mateo",
        "Matías",
        "Maximiliano",
        "Maykel",
        "Miguel",
        "Miguel  ngel",
        "Nelson",
        "Noah",
        "Oscar",
        "Pablo",
        "Pedro",
        "Rafael",
        "Ramón",
        "Raúl",
        "Ricardo",
        "Rigoberto",
        "Roberto",
        "Rolando",
        "Samuel",
        "Samuel David",
        "Santiago",
        "Santino",
        "Santos",
        "Sebastián",
        "Thiago",
        "Thiago Benjamín",
        "Tomás",
        "Valentino",
        "Vicente",
        "Víctor",
        "Víctor Hugo",
      ];
      const lastName = [
        "Garcia",
        "Gonzalez",
        "Rodriguez",
        "Fernandez",
        "Lopez",
        "Martinez",
        "Sanchez",
        "Perez",
        "Gomez",
        "Martin",
        "Jimenez",
        "Ruiz",
        "Hernandez",
        "Diaz",
        "Moreno",
        "Alvarez",
        "Muñoz",
        "Romero",
        "Alonso",
        "Gutierrez",
        "Navarro",
        "Torres",
        "Dominguez",
        "Vazquez",
        "Ramos",
        "Gil",
        "Ramirez",
        "Serrano",
        "Blanco",
        "Suarez",
        "Molina",
        "Morales",
        "Ortega",
        "Delgado",
        "Castro",
        "Ortiz",
        "Rubio",
        "Marin",
        "Sanz",
        "Nuñez",
        "Iglesias",
        "Medina",
        "Garrido",
        "Santos",
        "Castillo",
        "Cortes",
        "Lozano",
        "Guerrero",
        "Cano",
        "Prieto",
        "Mendez",
        "Calvo",
        "Cruz",
        "Gallego",
        "Vidal",
        "Leon",
        "Herrera",
        "Marquez",
        "Peña",
        "Cabrera",
        "Flores",
        "Campos",
        "Vega",
        "Diez",
        "Fuentes",
        "Carrasco",
        "Caballero",
        "Nieto",
        "Reyes",
        "Aguilar",
        "Pascual",
        "Herrero",
        "Santana",
        "Lorenzo",
        "Hidalgo",
        "Montero",
        "Ibañez",
        "Gimenez",
        "Ferrer",
        "Duran",
        "Vicente",
        "Benitez",
        "Mora",
        "Santiago",
        "Arias",
        "Vargas",
        "Carmona",
        "Crespo",
        "Roman",
        "Pastor",
        "Soto",
        "Saez",
        "Velasco",
        "Soler",
        "Moya",
        "Esteban",
        "Parra",
        "Bravo",
        "Gallardo",
        "Rojas",
        "Pardo",
        "Merino",
        "Franco",
        "Espinosa",
        "Izquierdo",
        "Lara",
        "Rivas",
        "Silva",
        "Rivera",
        "Casado",
        "Arroyo",
        "Redondo",
        "Camacho",
        "Rey",
        "Vera",
        "Otero",
        "Luque",
        "Galan",
        "Montes",
        "Rios",
        "Sierra",
        "Segura",
        "Carrillo",
        "Marcos",
        "Marti",
        "Soriano",
        "Mendoza",
      ];
      this[variable] =
        firstName[Math.floor(Math.random() * firstName.length)] +
        " " +
        lastName[Math.floor(Math.random() * lastName.length)];
    },
    handleSubmit() {
      if (this.totalContent > 500) {
        this.$swal({
          text: "Para guardar este envío no se debe superar los $500",
          icon: "error",
        });
        return;
      }
      let loader = this.$loading.show();
      let data = {};
      if (this.typical === true) {
        data = {
          receive: this.receive,
          sender: this.sender,
          typical: this.typical,
          telephone: this.telephone,
          telephoneAlternative: this.telephoneAlternative,
          address: this.$refs["typical"].address,
          postalCode: this.$refs["typical"].postalCode,
          state: State.getStateByCodeAndCountry(
            this.$refs["typical"].state,
            this.$refs["typical"].countryIsoCode
          ).name,
          city: this.$refs["typical"].city,
          totalKg: this.totalKg,
          totalContent: this.totalContent,
          boxes: JSON.stringify(this.boxes),
          contents: JSON.stringify(this.contents),
          user: this.userID(),
          inOffice: this.$moment(this.inOffice).format("YYYY-MM-DD hh:mm:ss"),
          baler: this.baler.id,
          observation: this.observation,
          guide: this.guide,
          totalPayment: this.$refs["typical"].totalPayment,
        };
        let customers = [];
        this.$refs["typical"].customer.forEach((c) => {
          customers.push({
            ...c,
            cost: this.converMaskToNumber(c.cost),
            weight: this.converMaskToNumber(c.weight),
          });
        });
        data = {
          ...data,
          customers: JSON.stringify(customers),
        };
      } else {
        data = {
          receive: this.receive,
          sender: this.sender,
          typical: this.typical,
          telephone: this.telephone,
          telephoneAlternative: this.telephoneAlternative,
          address: this.$refs["customer"].address,
          postalCode: this.$refs["customer"].postalCode,
          state: State.getStateByCodeAndCountry(
            this.$refs["customer"].state,
            this.$refs["customer"].countryIsoCode
          ).name,
          city: this.$refs["customer"].city,
          idAccountPersonal: JSON.stringify(
            this.$refs["customer"].accountPersonal
          ),
          totalKg: this.totalKg,
          totalContent: this.totalContent,
          weight: this.converMaskToNumber(this.$refs["customer"].weight),
          totalPayment: this.$refs["customer"].total,
          cost: this.converMaskToNumber(this.$refs["customer"].cost),
          multiplicater: this.$refs["customer"].multiplier,
          updatedMultiplier: this.$refs["customer"].updatedMultiplier,
          boxes: JSON.stringify(this.boxes),
          contents: JSON.stringify(this.contents),
          user: this.userID(),
          office: this.$refs["customer"].office_guatemala,
          category: JSON.stringify(this.$refs["customer"].category),
          departament: JSON.stringify(this.$refs["customer"].departament),
          type: JSON.stringify(this.$refs["customer"].type),
          typePayment: this.$refs["customer"].typePayment,
          inOffice: this.$moment(this.inOffice).format("YYYY-MM-DD hh:mm:ss"),
          baler: this.baler.id,
          observation: this.observation,
          guide: this.guide,
        };
      }
      axios
        .post(this.api() + "/package-ups", data)
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.$router.go(this.$router.currentRoute);
        })
        .catch((error) => {
          this.$swal({
            text: `${error.response.data.message}`,
            icon: "error",
          });
          loader.hide();
        });
    },
    myMoney(value) {
      return eight.money(parseFloat(value));
    },
    converMaskToNumber(value) {
      var myNumber = "";
      for (var i = 0; i < value.length; i += 1) {
        if (
          value.charAt(i) === "k" ||
          value.charAt(i) === "g" ||
          value.charAt(i) === "l" ||
          value.charAt(i) === "b" ||
          value.charAt(i) === "s" ||
          value.charAt(i) === "$" ||
          value.charAt(i) === "," ||
          value.charAt(i) === " " ||
          value.charAt(i) === "%"
        ) {
          continue;
        } else {
          myNumber += value.charAt(i);
        }
      }
      return parseFloat(myNumber).toFixed(2);
    },
    ifDeleteBox(id) {
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
          this.deleteBox(id);
        }
      });
    },
    deleteBox(id) {
      const index = this.boxes.findIndex((x) => x.id === id);
      if (index !== -1) {
        this.boxes.splice(index, 1);
        this.calculateTotal();
        this.$refs.table.refresh();
      }
    },
    ifDeleteContent(id) {
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
          this.deleteContent(id);
        }
      });
    },
    deleteContent(id) {
      const index = this.boxes.findIndex((x) => x.id === id);
      if (index !== -1) {
        this.boxes.splice(index, 1);
        this.calculateTotalContent();
        this.$refs["table-content"].refresh();
      }
    },
    getBalers(search) {
      this.loaderBaler = true;
      axios
        .get(this.api() + `/getBaler?filter=${search}`)
        .then((response) => {
          this.loaderBaler = false;
          this.balers = response.data;
        })
        .catch((error) => {
          console.log(error);
          this.loaderBaler = false;
        });
    },
    customLabel({ name, code }) {
      return `${name}-${code}`;
    },
    findSamePackage() {
      let loader = this.$loading.show();
      axios
        .get(this.api() + `/package-ups-find/${this.telephone}`)
        .then((response) => {
          loader.hide();
          if (response.data.length > 0) {
            this.$refs["findPackage"].openModal(response.data);
          }
        })
        .catch(() => {
          loader.hide();
        });
    },
  },
};
</script>
