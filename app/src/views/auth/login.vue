<template>
  <div class="form full-form auth-cover">
    <div class="form-container">
      <div class="form-form">
        <div class="form-form-wrap">
          <div class="form-container">
            <div class="form-content">
              <h1 class="">
                Ingresar
                <router-link to="/"
                  ><span class="brand-name">Fast Express</span></router-link
                >
              </h1>
              <ValidationObserver ref="observer">
                <b-form
                  slot-scope="{ validate }"
                  @submit.prevent="validate().then(handleSubmit)"
                >
                  <div class="form">
                    <!-- USER -->
                    <ValidationProvider
                      rules="required|length:9"
                      name="teléfono"
                    >
                      <b-form-group slot-scope="{ valid, errors }">
                        <div id="username-field" class="field-wrapper input">
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
                            class="feather feather-user"
                          >
                            <path
                              d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"
                            ></path>
                            <circle cx="12" cy="7" r="4"></circle>
                          </svg>
                          <b-input
                            placeholder="Teléfono"
                            v-model="user"
                            v-mask="'####-####'"
                            :state="errors[0] ? false : valid ? true : null"
                          ></b-input>
                          <b-form-invalid-feedback>
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </div>
                      </b-form-group>
                    </ValidationProvider>
                    <!-- END USER -->
                    <ValidationProvider rules="required" name="contraseña">
                      <b-form-group slot-scope="{ valid, errors }">
                        <div
                          id="password-field"
                          class="field-wrapper input mb-2"
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
                            class="feather feather-lock"
                          >
                            <rect
                              x="3"
                              y="11"
                              width="18"
                              height="11"
                              rx="2"
                              ry="2"
                            ></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                          </svg>
                          <b-input
                            type="password"
                            placeholder="Contraseña"
                            v-model="password"
                            :state="errors[0] ? false : valid ? true : null"
                          ></b-input>
                          <b-form-invalid-feedback>
                            {{ errors[0] }}
                          </b-form-invalid-feedback>
                        </div>
                      </b-form-group>
                    </ValidationProvider>
                    <div class="d-sm-flex justify-content-between">
                      <div
                        class="
                          field-wrapper
                          toggle-pass
                          d-flex
                          align-items-center
                        "
                      ></div>
                      <div class="field-wrapper">
                        <b-button type="submit" variant="primary"
                          >Ingresar</b-button
                        >
                      </div>
                    </div>
                  </div>
                </b-form>
              </ValidationObserver>
              <p class="terms-conditions">
                <span
                  >Powered by
                  <a
                    href="//www.eight-software.tk"
                    target="_blank"
                    rel="nofollow"
                    >Eight</a
                  >
                  {{ new Date().getFullYear() }}</span
                >
              </p>
            </div>
          </div>
        </div>
      </div>
      <div class="form-image">
        <div class="l-image"></div>
      </div>
    </div>
  </div>
</template>

<script>
import "@/assets/sass/authentication/auth.scss";
import Vue from "vue";
import axios from "axios";
import { mapMutations, mapGetters } from "vuex";
import VeeValidate, {
  Validator,
  ValidationObserver,
  ValidationProvider,
} from "vee-validate";
import es from "vee-validate/dist/locale/es";
Validator.localize({ es: es });
Vue.use(VeeValidate, { locale: "es", fieldsBagName: "vvFields" });

export default {
  metaInfo: { title: "Iniciar sesión" },
  components: {
    ValidationObserver,
    ValidationProvider,
  },
  data() {
    return {
      user: "",
      password: "",
    };
  },
  mounted() {},
  methods: {
    ...mapMutations(["setToken", "setUser", "setPermits"]),
    ...mapGetters(["api"]),
    handleSubmit() {
      this.show = false;
      let loader = this.$loading.show();
      axios
        .post(this.api() + "/auth/login", {
          user: this.user,
          password: this.password,
        })
        .then((response) => {
          loader.hide();
          // Operations Date
          const dateInit = this.$moment()
            .add(response.data.expires_in, "seconds")
            .unix();
          const token = {
            access_token: response.data.access_token,
            expires_in: dateInit,
            token_type: response.data.token_type,
          };
          const user = {
            id: response.data.user.id,
            name: response.data.user.name,
            user: response.data.user.user,
            role: response.data.user.idRol,
          };
          const permits = response.data.permits;
          this.setToken(token);
          this.setUser(user);
          this.setPermits(permits);

          this.$router.push({
            path: "/dashboard",
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
  },
};
</script>
