<template>
  <b-modal
    ref="modal_add_bag"
    title="Agregar maleta"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <ValidationObserver ref="observer">
      <b-form>
        <!-- BAG -->
        <ValidationProvider
          tag="div"
          rules="required"
          name="equipaje"
          v-slot="{ errors }"
        >
          <b-form-group
            label="Tipo de equipaje:"
            :state="errors.length > 0 ? false : null"
          >
            <!-- getShipments -->
            <multiselect
              id="equipaje"
              name="equipaje"
              ref="equipaje"
              v-model="bag"
              :options="bags"
              placeholder="Seleccione el tipo de equipaje"
              :class="errors.length > 0 ? 'is-danger' : ''"
            >
            </multiselect>
            <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END BAG -->
        <!-- CAPACITY -->
        <ValidationProvider rules="required" name="peso">
          <b-form-group slot-scope="{ valid, errors }" label="Capacidad:">
            <b-form-input
              v-money="pounds"
              v-model="capacity"
              name="peso"
              type="text"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END CAPACITY -->
        <!-- USER -->
        <ValidationProvider
          tag="div"
          rules="required"
          name="viajero"
          v-slot="{ errors }"
        >
          <b-form-group
            label="Viajero"
            :state="errors.length > 0 ? false : null"
          >
            <!-- getShipments -->
            <multiselect
              id="viajero"
              name="viajero"
              ref="viajero"
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
            >
            </multiselect>
            <b-form-invalid-feedback :state="errors.length > 0 ? false : null">
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END USER -->
      </b-form>
    </ValidationObserver>
    <template #modal-footer>
      <b-button variant="danger" @click="closeModal">Cancelar</b-button>
      <b-button variant="primary" @click="handleSubmit">Guardar</b-button>
    </template>
  </b-modal>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import { mapGetters, mapMutations } from "vuex";
import Multiselect from "vue-multiselect";
import "vue-multiselect/dist/vue-multiselect.min.css";
import { VMoney } from "v-money";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });

export default {
  directives: { money: VMoney },
  components: {
    ValidationObserver,
    ValidationProvider,
    Multiselect,
  },
  watch: {},
  data() {
    return {
      pounds: {
        decimal: ".",
        thousands: ",",
        prefix: "",
        suffix: " lbs",
        precision: 2,
        masked: true,
      },
      id: 0,
      bag: "",
      bags: [],
      capacity: "",
      traveler: "",
      travelers: [],
      loaderTraveler: false,
    };
  },
  mounted() {
    this.getTypeBags();
  },
  methods: {
    ...mapGetters(["api", "getUpdateBag"]),
    ...mapMutations(["setUpdateBag"]),
    converMaskToNumber(value) {
      var myNumber = "";
      for (var i = 0; i < value.length; i += 1) {
        if (
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
    handleSubmit() {
      this.$refs["observer"]
        .validate()
        .then((isValid) => {
          if (isValid) {
            this.setUpdateBag({
              id: this.id,
              bag: this.bag,
              capacity: this.converMaskToNumber(this.capacity),
              traveler: this.traveler,
            });
            this.$emit("update-bag");
            this.clearData();
            this.closeModal();
          }
        })
        .catch((error) => {
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
    getTypeBags() {
      axios
        .get(this.api() + "/setting/8")
        .then((response) => {
          this.bags = JSON.parse(response.data.value);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    closeModal() {
      this.$refs["modal_add_bag"].hide();
    },
    openModal() {
      this.$refs["modal_add_bag"].show();
      const bag = this.getUpdateBag();
      this.id = bag.id;
      this.bag = bag.bag;
      this.capacity = bag.capacity;
      this.traveler = bag.traveler;
      this.travelers = [];
    },
    clearData() {
      this.bag = "";
      this.capacity = "";
      this.traveler = "";
      this.travelers = [];
      this.loaderTraveler = false;
    },
  },
};
</script>