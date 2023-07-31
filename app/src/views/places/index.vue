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
      <div class="row" v-for="place in places" :key="place.id">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>{{ place.name }}</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <ValidationObserver ref="observer">
                    <b-form
                      slot-scope="{ validate }"
                      @submit.prevent="validate().then(handleSubmit(place))"
                    >
                      <!-- ADDRESS -->
                      <ValidationProvider rules="required" name="dirección">
                        <b-form-group
                          slot-scope="{ valid, errors }"
                          label="Dirección"
                        >
                          <b-form-input
                            v-model="place.address"
                            name="dirección"
                            type="text"
                            :state="errors[0] ? false : valid ? true : null"
                          ></b-form-input>
                          <b-form-invalid-feedback>
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END ADDRESS -->
                      <!-- ADDRESS -->
                      <ValidationProvider rules="required" name="dirección">
                        <b-form-group
                          slot-scope="{ valid, errors }"
                          label="Dirección para el manifiesto"
                        >
                          <b-form-input
                            v-model="place.addressManifest"
                            name="dirección"
                            type="text"
                            :state="errors[0] ? false : valid ? true : null"
                          ></b-form-input>
                          <b-form-invalid-feedback>
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END ADDRESS -->
                      <!-- addressManifest -->
                      <!-- GEOLOCATION -->
                      <ValidationProvider
                        rules="required"
                        name="geolocalización"
                      >
                        <b-form-group
                          slot-scope="{ valid, errors }"
                          label="Geolocalización"
                        >
                          <b-input-group>
                            <b-form-input
                              v-model="place.geolocation"
                              name="geolocalización"
                              type="text"
                              :state="errors[0] ? false : valid ? true : null"
                            ></b-form-input>
                            <b-input-group-append>
                              <b-button variant="info" @click="openMap(place)"
                                >Ver en Google Maps</b-button
                              >
                            </b-input-group-append>
                            <b-form-invalid-feedback>
                              {{ errors[0] }}
                            </b-form-invalid-feedback>
                          </b-input-group>
                        </b-form-group>
                      </ValidationProvider>
                      <!-- END GEOLOCATION -->
                      <!-- IMAGE -->
                      <b-form-group label="Mapa">
                        <b-container fluid class="p-4 bg-dark">
                          <b-row>
                            <b-col>
                              <b-img
                                thumbnail
                                fluid
                                :src="
                                  place.img === null || place.img === ''
                                    ? upload() + '/places/no-image-found.jpg'
                                    : upload() + `/places/${place.img}`
                                "
                                width="300%"
                              ></b-img>
                            </b-col>
                            <b-col>
                              <UploadImages
                                :max="1"
                                maxError="Solo un mapa"
                                uploadMsg="Seleccione un mapa"
                                fileError="Error :("
                                clearAll="Eliminar imágenes"
                                :ref="`map-${place.id}`"
                              />
                            </b-col>
                          </b-row>
                        </b-container>
                      </b-form-group>

                      <!-- END IMAGE -->
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

Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });
import UploadImages from "vue-upload-drop-images";

export default {
  metaInfo: { title: "Ajustes" },

  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Ajustes", active: true },
      ],
      quetzal: {
        decimal: ".",
        thousands: ",",
        prefix: "Q ",
        suffix: "",
        precision: 2,
        masked: true,
      },
      address: "",
      geolocation: "",
      map: "",
      places: [],
    };
  },
  components: {
    ValidationObserver,
    ValidationProvider,
    UploadImages,
  },
  mounted() {
    this.getOffices();
  },
  methods: {
    ...mapGetters(["api", "upload"]),
    getOffices() {
      axios
        .get(this.api() + "/office")
        .then((response) => {
          this.places = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    openMap(place) {
      const location = place.geolocation.split(",");
      if (location.length > 1) {
        const latitude = location[0].replace(/\s/g, "");
        const longitude = location[1].replace(/\s/g, "");
        window.open(
          `https://www.google.com/maps/search/?api=1&query=${latitude}%2C${longitude}`,
          "_blank"
        );
      }
    },
    handleSubmit(place) {
      // console.log(this.$refs["map-1"][0].files[0]);
      let loader = this.$loading.show();
      const formData = new FormData();
      formData.append("name", place.name);
      formData.append("addressManifest", place.addressManifest);
      formData.append("address", place.address);
      if (place.geolocation !== null || place.geolocation !== "") {
        formData.append("geolocation", place.geolocation);
      }
      if (this.$refs[`map-${place.id}`][0].files.length > 0) {
        formData.append("image", this.$refs["map-1"][0].files[0]);
      }
      formData.append("_method", "put");
      axios
        .post(this.api() + `/office/${place.id}`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          loader.hide();
          this.getOffices();
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
  },
};
</script>
