<template>
  <b-modal
    ref="modal_box_ups"
    :title="`${name} - ${id}`"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
    class="modal-dialog modal-xl"
  >
    <h4>Cajas</h4>
    <ValidationObserver ref="observer-box">
      <b-form>
        <!-- WEIGHT KG-->
        <ValidationProvider rules="required" name="peso">
          <b-form-group slot-scope="{ valid, errors }" label="Peso (Kg):">
            <b-form-input
              v-model="weightKg"
              v-money="kg"
              name="peso"
              type="text"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END WEIGHT KG -->
        <!-- BEGIN BUTTONS -->
        <b-button-group>
          <b-button variant="primary" @click="addBox" v-if="typeBox === 'save'"
            >Agregar</b-button
          >
          <b-button variant="primary" @click="updateBox" v-else
            >Actualizar</b-button
          >
          <b-button variant="danger" @click="resetBox">Cancelar</b-button>
        </b-button-group>
        <!-- END BUTTONS -->
      </b-form>
    </ValidationObserver>
    <hr />
    <!-- END WEIGHT KG -->
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
      <template #cell(id)="row"> Caja {{ row.index + 1 }} </template>
      <template #cell(weightKg)="row">
        {{ myKg(row.item.weightKg) }}
      </template>
      <!-- BEGIN FOOTER -->
      <template #foot(id)=""><h6 class="text-right">TOTAL:</h6></template>
      <template #foot(weightKg)="">
        <h6>{{ myKg(totalKg) }}</h6>
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
            <b-dropdown-item @click="update(row.item)" link-class="action-edit">
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
    <!-- CONTENT -->
    <h4>Contenido</h4>
    <ValidationObserver ref="observer-content">
      <b-form inline>
        <!-- QUANTITY -->
        <ValidationProvider rules="required" name="cantidad">
          <b-form-group slot-scope="{ valid, errors }" label="Cantidad:">
            <b-form-input
              class="mb-2 mr-sm-2 mb-sm-0"
              @input="calculateSubtotal"
              v-model="quantity"
              name="cantidad"
              type="number"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END QUANTITY -->
        <!-- CONTENT-->
        <ValidationProvider rules="required" name="contenido">
          <b-form-group slot-scope="{ valid, errors }" label="Contenido:">
            <b-form-input
              class="mb-2 mr-sm-2 mb-sm-0"
              v-model="content"
              @blur="translate"
              name="contenido"
              type="text"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END CONTENT -->
        <!-- CONTENT EN-->
        <ValidationProvider rules="required" name="contenido">
          <b-form-group
            slot-scope="{ valid, errors }"
            label="Contenido(Ingles):"
          >
            <b-form-input
              class="mb-2 mr-sm-2 mb-sm-0"
              v-model="content_en"
              name="contenido"
              type="text"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END CONTENT EN-->
        <!-- PRICE-->
        <ValidationProvider rules="required" name="precio">
          <b-form-group slot-scope="{ valid, errors }" label="Precio:">
            <b-form-input
              class="mb-2 mr-sm-2 mb-sm-0"
              @input="calculateSubtotal"
              v-model="price"
              v-money="dollar"
              name="precio"
              type="text"
              :state="errors[0] ? false : valid ? true : null"
            ></b-form-input>
            <b-form-invalid-feedback>
              {{ errors[0] }}
            </b-form-invalid-feedback>
          </b-form-group>
        </ValidationProvider>
        <!-- END PRICE -->
        <b-form-group label="Subtotal:">
          <h6 class="mb-2 mr-sm-2 mb-sm-0">{{ myDollar(subtotal) }}</h6>
        </b-form-group>
        <!-- BEGIN BUTTONS -->
        <!-- END BUTTONS -->
      </b-form>
    </ValidationObserver>
    <hr />
    <b-button-group>
      <b-button
        variant="primary"
        @click="addContent"
        v-if="typeContent === 'save'"
        >Agregar</b-button
      >
      <b-button variant="primary" @click="updateContent" v-else
        >Actualizar</b-button
      >
      <b-button variant="danger" @click="resetContent">Cancelar</b-button>
    </b-button-group>
    <hr />
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
      <template #foot(price)=""><h6 class="text-right">TOTAL:</h6></template>
      <template #foot(subtotal)="">
        <h6>{{ myDollar(totalContent) }}</h6>
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
              @click="updateC(row.item)"
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
    <template #modal-footer>
      <b-button variant="danger" @click="closeModalBox">Cancelar</b-button>
      <b-button variant="primary" @click="saveBoxes">Guardar</b-button>
    </template>
  </b-modal>
</template>
<script>
import Vue from "vue";
import axios from "axios";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import { mapGetters } from "vuex";
import es from "vee-validate/dist/locale/es";
import { VMoney } from "v-money";
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
      kg: {
        decimal: ".",
        thousands: ",",
        prefix: "",
        suffix: " kg",
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
      id: 0,
      name: "",
      columnBox: [
        { key: "id", label: "No." },
        { key: "weightKg", label: "Peso(Kg)" },
        { key: "action", label: "Acciones", class: "actions text-center" },
      ],
      idBox: 0,
      typeBox: "save",
      boxes: [],
      weightKg: "",
      totalKg: 0,
      //   CONTENTS
      idContent: 0,
      typeContent: "save",
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

      quantity: "",
      content: "",
      content_en: "",
      price: "",
      subtotal: 0,
      totalContent: 0,
    };
  },
  methods: {
    ...mapGetters(["api", "userID"]),
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
    uuidv4() {
      return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, (c) =>
        (
          c ^
          (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))
        ).toString(16)
      );
    },
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
    addBox() {
      this.$refs["observer-box"].validate().then((isValid) => {
        if (isValid) {
          if (this.converMaskToNumber(this.weightKg) < 1) {
            this.$swal({
              text: `El peso caja deber ser mayor a 0`,
              icon: "error",
            });
            return;
          }
          this.boxes.push({
            id: this.uuidv4(),
            weightKg: this.converMaskToNumber(this.weightKg),
          });
          // this.$swal({ text: `Caja agregada`, icon: "success" });
          this.resetBox();
          this.calculateTotal();
          this.$refs.table.refresh();
        }
      });
    },
    updateBox() {
      this.$refs["observer-box"].validate().then((isValid) => {
        if (isValid) {
          if (this.converMaskToNumber(this.weightKg) < 1) {
            this.$swal({
              text: `El peso caja deber ser mayor a 0`,
              icon: "error",
            });
            return;
          }
          const index = this.boxes.findIndex((x) => x.id === this.idBox);
          if (index !== -1) {
            this.boxes[index].weightKg = this.weightKg;
          }
          // this.$swal({ text: `Caja actualizada`, icon: "success" });
          this.resetBox();
          this.calculateTotal();
          this.$refs.table.refresh();
        }
      });
    },
    openModal(upsPackage) {
      this.id = upsPackage.id;
      this.name = upsPackage.receive;
      this.boxes = upsPackage.boxes;

      this.contents = upsPackage.contents;
      this.$refs["modal_box_ups"].show();
      this.resetBox();
      this.resetContent();
    },
    saveBoxes() {
      if (this.boxes.length < 1) {
        this.$swal({
          text: "Para guardar este envío es necesario agregar cajas",
          icon: "error",
        });
        return;
      }
      if (this.contents.length < 1) {
        this.$swal({
          text: "Para guardar este envío es necesario agregar contenido",
          icon: "error",
        });
        return;
      }
      let loader = this.$loading.show();
      axios
        .post(this.api() + "/package-ups-content", {
          id: this.id,
          totalKg: this.totalKg,
          totalContent: this.totalContent,
          boxes: JSON.stringify(this.boxes),
          contents: JSON.stringify(this.contents),
          user: this.userID(),
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.$emit("save");
          this.closeModalBox();
        })
        .catch((error) => {
          this.$swal({
            text: `${error.response.data.message}`,
            icon: "error",
          });
          loader.hide();
        });
    },
    myQuantity(value) {
      return eight.quantity(parseFloat(value));
    },
    myLbs(value) {
      return eight.lbs(parseFloat(value));
    },
    myKg(value) {
      return eight.kg(parseFloat(value));
    },
    myDollar(value) {
      return eight.dollar(parseFloat(value));
    },
    closeModalBox() {
      this.codeUPS = "";
      this.$refs["modal_box_ups"].hide();
    },
    update(box) {
      this.idBox = box.id;
      this.weightKg = box.weightKg;
      this.typeBox = "update";
    },
    resetBox() {
      this.idBox = 0;
      this.weightKg = "";
      this.typeBox = "save";
    },
    updateC(content) {
      this.idContent = content.id;
      this.quantity = content.quantity;
      this.content = content.content;
      this.content_en = content.content_en;
      this.price = content.price;
      this.typeContent = "update";
    },
    resetContent() {
      this.idContent = 0;
      this.quantity = "";
      this.content = "";
      this.content_en = "";
      this.price = "";
      this.typeContent = "save";
    },
    ifDeleteBox(id) {
      this.$swal({
        title: "Quieres eliminar esta caja?",
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
        this.resetBox();
        this.calculateTotal();
        this.$refs.table.refresh();
      }
    },
    calculateSubtotal() {
      if (this.quantity === "" || this.price === "") this.subtotal = 0;
      else
        this.subtotal = this.parseDecimal(
          this.parseDecimal(this.quantity) *
            this.parseDecimal(this.converMaskToNumber(this.price))
        );
    },
    translate() {
      let loader = this.$loading.show();
      axios
        .post("https://translate.argosopentech.com/translate", {
          q: this.content,
          source: "es",
          target: "en",
          format: "text",
        })
        .then((response) => {
          loader.hide();
          this.content_en = response.data.translatedText.toUpperCase();
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
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
    addContent() {
      this.$refs["observer-content"].validate().then((isValid) => {
        if (isValid) {
          if (this.converMaskToNumber(this.price) < 1) {
            this.$swal({
              text: `El precio del contenido deber ser mayor a 0`,
              icon: "error",
            });
            return;
          }
          this.contents.push({
            id: this.uuidv4(),
            quantity: this.quantity,
            content: this.content,
            content_en: this.content_en,
            price: this.converMaskToNumber(this.price),
            subtotal: this.subtotal,
          });
          // this.$swal({ text: `Contenido agregado`, icon: "success" });
          this.resetContent();
          this.calculateTotalContent();
          this.$refs["table-content"].refresh();
        }
      });
    },
    updateContent() {
      this.$refs["observer-content"].validate().then((isValid) => {
        if (isValid) {
          if (this.converMaskToNumber(this.price) < 1) {
            this.$swal({
              text: `El precio del contenido deber ser mayor a 0`,
              icon: "error",
            });
            return;
          }
          const index = this.contents.findIndex((x) => x.id === this.idContent);
          if (index !== -1) {
            this.contents[index].weightKg = this.weightKg;
            this.contents[index].quantity = this.quantity;
            this.contents[index].content = this.content;
            this.contents[index].content_en = this.content_en;
            this.contents[index].price = this.converMaskToNumber(this.price);
            this.contents[index].subtotal = this.subtotal;
          }
          // this.$swal({ text: `Contenido actualizado`, icon: "success" });
          this.resetContent();
          this.calculateTotalContent();
          this.$refs["table-content"].refresh();
        }
      });
    },
    ifDeleteContent(id) {
      this.$swal({
        title: "Quieres eliminar este contenido?",
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
      const index = this.contents.findIndex((x) => x.id === id);
      if (index !== -1) {
        this.contents.splice(index, 1);
        this.resetContent();
        this.calculateTotalContent();
        this.$refs["table-content"].refresh();
      }
    },
  },
};
</script>