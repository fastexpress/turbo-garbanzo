<template>
  <b-modal
    ref="modal_deliver_package"
    title="Entregar paquete"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <p class="modal-text">Código rastreo: {{ headerPackage.id }}</p>
    <h4 class="modal-heading mb-4 mt-2" v-if="headerPackage.receive !== null">
      Cliente: {{ headerPackage.receive }}
    </h4>
    <b-table
      responsive
      striped
      hover
      :items="packages"
      :fields="column"
      :show-empty="true"
      foot-clone
    >
      <template #cell(subtotal)="row">
        {{ myDollar(row.item.subtotal) }}
      </template>
      <template #cell(weight)="row">
        {{ myLbs(row.item.weight) }}
      </template>
      <template #cell(cost)="row">
        {{ myDollar(row.item.cost) }}
      </template>
      <template #cell(payment)="row">
        <b-badge variant="warning" v-if="row.item.payment === '0'">No</b-badge>
        <b-badge variant="success" v-if="row.item.payment === '1'">Sí</b-badge>
      </template>
      <template #cell(delivered)="row">
        <b-form-group
          :label="row.item.delivered === true ? 'Entregado' : 'No entregado'"
        >
          <label class="switch s-icons s-outline s-outline-primary">
            <input type="checkbox" v-model="row.item.delivered" />
            <span class="slider round"></span>
          </label>
        </b-form-group>
      </template>
      <!-- BEGIN FOOTER -->
      <template #foot(content)=""><h4 class="text-right">TOTAL:</h4></template>
      <template #foot(subtotal)="">
        <h4>{{ myDollar(totalSubtotal) }}</h4>
      </template>
      <template #foot(cost)="">
        <h4>--</h4>
      </template>
      <template #foot(weight)="">
        <h4>{{ myLbs(totalWeight) }}</h4>
      </template>
      <template #foot()="">&nbsp;</template>
      <!-- END FOOTER -->
    </b-table>
    <h3>Total a pagar: {{ myDollar(totalSubtotal) }}</h3>
    <ValidationObserver ref="observer">
      <b-form>
        <!-- AMOUNT-->
        <ValidationProvider rules="required" name="monto">
          <b-form-group slot-scope="{ valid, errors }" label="Monto:">
            <b-form-input
              v-model="amount"
              v-money="dollar"
              name="monto"
              type="text"
              @input="calculateChange"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END AMOUNT -->
      </b-form>
    </ValidationObserver>
    <h3>Cambio: {{ myDollar(change) }}</h3>

    <template #modal-footer>
      <b-button variant="danger" @click="closeModal">Cancelar</b-button>
      <b-button variant="primary" @click="handleSubmit">Guardar</b-button>
    </template>
  </b-modal>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import { mapGetters } from "vuex";
import { VMoney } from "v-money";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";
import { eight } from "../../../../utils/eight";

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
export default {
  directives: { money: VMoney },
  components: {
    ValidationObserver,
    ValidationProvider,
  },
  data() {
    return {
      dollar: {
        decimal: ".",
        thousands: ",",
        prefix: "$ ",
        suffix: "",
        precision: 2,
        masked: true,
      },
      id: "",
      city: "",
      date: "",
      amount: "",
      packages: [],
      headerPackage: "",
      totalWeight: 0,
      totalCost: 0,
      totalSubtotal: 0,
      change: 0,
      column: [
        { key: "codeFast", label: "Código" },
        { key: "payment", label: "Pagado" },
        { key: "send", label: "Envía" },
        { key: "content", label: "Contenido" },
        { key: "weight", label: "Peso" },
        { key: "cost", label: "Costo" },
        { key: "subtotal", label: "Subtotal" },
        { key: "delivered", label: "Entregado" },
        { key: "observation", label: "Observación" },
      ],
    };
  },
  methods: {
    ...mapGetters(["api", "userID"]),
    myDollar(value) {
      return eight.dollar(parseFloat(value));
    },
    myLbs(value) {
      return eight.lbs(parseFloat(value));
    },
    parseDecimalFixed(value) {
      return parseFloat(value).toFixed(2);
    },
    parseDecimal(value) {
      return parseFloat(this.parseDecimalFixed(value));
    },
    calculateChange() {
      this.change = this.parseDecimal(
        this.parseDecimal(this.converMaskToNumber(this.amount)) -
          this.parseDecimal(this.totalSubtotal)
      );
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
    openModal(id, date, city) {
      this.findPackage(id);
      this.date = date;
      this.city = city;
      this.$refs["modal_deliver_package"].show();
    },
    findPackage(id) {
      this.id = id;
      let loader = this.$loading.show();
      axios
        .get(this.api() + `/package/${id}`)
        .then((response) => {
          loader.hide();
          this.headerPackage = response.data.headerPackage;
          this.packages = response.data.packages;
          this.totalWeight = 0;
          this.totalCost = 0;
          this.totalSubtotal = 0;

          this.packages.forEach((item, index) => {
            this.packages[index].delivered =
              item.delivered === 0 ? true : false;
            if (item.payment === "0")
              this.totalSubtotal = this.parseDecimal(
                this.parseDecimal(this.totalSubtotal) +
                  this.parseDecimal(item.subtotal)
              );
            this.totalCost = this.parseDecimal(
              this.parseDecimal(this.totalCost) + this.parseDecimal(item.cost)
            );
            this.totalWeight = this.parseDecimal(
              this.parseDecimal(this.totalWeight) +
                this.parseDecimal(item.weight)
            );
          });
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    handleSubmit() {
      this.$refs["observer"].validate().then((isValid) => {
        if (isValid) {
          if (this.packages.filter((x) => x.delivered === false).length > 0) {
            this.$swal({
              text: "Aun faltan paquetes por entregar",
              icon: "error",
            });
            return;
          }
          if (this.change < 0) {
            this.$swal({
              text: "Aun falta dinero",
              icon: "error",
            });
            return;
          }
          let loader = this.$loading.show();
          axios
            .post(this.api() + `/package-delivered`, {
              id: this.id,
              city: this.city,
              date: this.date,
              user: this.userID(),
            })
            .then((response) => {
              loader.hide();
              this.$swal({ text: `${response.data.message}`, icon: "success" });
              this.$emit("save");
              this.closeModal();
            })
            .catch((error) => {
              loader.hide();
              this.$swal({
                text: `${error.response.data.message}`,
                icon: "error",
              });
            });
        }
      });
    },
    closeModal() {
      this.id = "";
      this.city = "";
      this.date = "";
      this.amount = "";
      this.packages = [];
      this.headerPackage = "";
      this.totalWeight = 0;
      this.totalCost = 0;
      this.totalSubtotal = 0;
      this.change = 0;
      this.$refs["modal_deliver_package"].hide();
    },
  },
};
</script>