<template>
  <div>
    <hr v-if="city !== ''" />
    <div class="row" v-if="city !== ''">
      <div class="col-xl-12 col-lg-12 col-sm-12">
        <div class="panel">
          <b-form-file
            ref="csv"
            @input="getTelephones"
            browse-text="Buscar"
            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
            placeholder="Seleccione la lista números"
          ></b-form-file>
        </div>
      </div>
    </div>
    <hr v-if="city !== ''" />
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-sm-12">
        <div class="panel br-6 p-0">
          <div class="custom-table">
            <div class="table-header">
              <!-- SHOW -->
              <div class="d-flex align-items-center">
                <span>Mostrar :</span>
                <span class="ml-2">
                  <b-select v-model="show" size="sm" @change="$emit('call')">
                    <b-select-option value="5">5</b-select-option>
                    <b-select-option value="10">10</b-select-option>
                    <b-select-option value="20">20</b-select-option>
                    <b-select-option value="50">50</b-select-option>
                  </b-select>
                </span>
              </div>
              <!-- END SHOW -->
              <!-- SEARCH -->
              <div class="header-search">
                <b-input
                  v-model="search"
                  size="sm"
                  placeholder="Buscar..."
                  @input="$emit('call')"
                />
                <div class="search-image">
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
                    class="feather feather-search"
                  >
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                  </svg>
                </div>
              </div>
              <!-- END SEARCH -->
            </div>

            <b-table
              ref="basic_table"
              responsive
              hover
              :items="items"
              :fields="columns"
              :busy="isBusy"
              :show-empty="true"
            >
              <template #cell(telephone)="row">
                <a
                  :href="
                    row.item.telephone.length === 9
                      ? `tel:+502-${row.item.telephone}`
                      : `tel:+1-${row.item.telephone}`
                  "
                  >{{ row.item.telephone }}</a
                >
              </template>
              <template #cell(alternativeTelephone)="row">
                <template v-if="row.item.alternativeTelephone === null">
                  <p>{{ row.item.alternativeTelephone }}</p>
                </template>
                <template v-if="row.item.alternativeTelephone !== null">
                  <a
                    :href="
                      row.item.alternativeTelephone.length === 9
                        ? `tel:+502-${row.item.alternativeTelephone}`
                        : `tel:+1-${row.item.alternativeTelephone}`
                    "
                    >{{ row.item.alternativeTelephone }}</a
                  >
                </template>
              </template>
              <template #cell(whatsapp)="row">
                <b-badge variant="warning" v-if="row.item.whatsapp === 0"
                  >No enviado</b-badge
                >
                <b-badge variant="success" v-if="row.item.whatsapp === 1"
                  >Enviado</b-badge
                >
              </template>
              <template #cell(status)="row">
                <b-badge variant="primary" v-if="row.item.status == 2"
                  >GUATEMALA</b-badge
                >
                <b-badge variant="success" v-if="row.item.status == 1"
                  >USA</b-badge
                >
                <b-badge variant="danger" v-if="row.item.status == 0"
                  >ANULADO</b-badge
                >
              </template>
              <template #cell(packages)="row">
                {{ row.item.packages.length }} paquetes
              </template>
              <template #cell(created_at)="row">
                {{ dateGT(row.item.created_at) }}
              </template>
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
                      @click="answer(row.item.id, 1)"
                      link-class="action-edit"
                      v-if="row.item.callStatus === 0"
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
                        class="feather feather-send"
                      >
                        <path
                          d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                        ></path>
                      </svg>
                      Contestado
                    </b-dropdown-item>
                    <b-dropdown-item
                      @click="answer(row.item.id)"
                      link-class="action-edit"
                      v-if="row.item.callStatus === 0"
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
                        class="feather feather-send"
                      >
                        <line x1="23" y1="1" x2="17" y2="7"></line>
                        <line x1="17" y1="1" x2="23" y2="7"></line>
                        <path
                          d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"
                        ></path>
                      </svg>
                      No contesto
                    </b-dropdown-item>
                    <b-dropdown-item
                      @click="
                        addObservation(row.item.id, row.item.observationWhatsapp)
                      "
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
                        class="feather feather-send"
                      >
                        <path
                          d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"
                        ></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                      </svg>
                      Agregar Observación
                    </b-dropdown-item>
                  </b-dropdown>
                </div>
              </template>
              <template #empty> No hay registros </template>
            </b-table>

            <div class="table-footer">
              <div
                class="
                  paginating-container
                  pagination-solid
                  flex-column
                  align-items-right
                "
              >
                <!-- END SHO REGISTRES -->
                <b-pagination
                  @change="handlePage"
                  :total-rows="pagination.total"
                  :per-page="pagination.per_page"
                  prev-text="Prev"
                  next-text="Next"
                  first-text="First"
                  last-text="Last"
                  first-class="first"
                  prev-class="prev"
                  next-class="next"
                  last-class="last"
                  class="rounded"
                >
                  <template #first-text>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11 19l-7-7 7-7m8 14l-7-7 7-7"
                      />
                    </svg>
                  </template>
                  <template #prev-text>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                      />
                    </svg>
                  </template>
                  <template #next-text>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5l7 7-7 7"
                      />
                    </svg>
                  </template>
                  <template #last-text>
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="24"
                      height="24"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M13 5l7 7-7 7M5 5l7 7-7 7"
                      />
                    </svg>
                  </template>
                </b-pagination>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import { mapGetters } from "vuex";
import "@/assets/sass/apps/todolist.scss";
export default {
  components: {},
  data() {
    return {
      items: [],
      columns: [
        { key: "id", label: "ID" },
        { key: "receive", label: "Destinatario" },
        { key: "telephone", label: "Teléfono" },
        { key: "alternativeTelephone", label: "Teléfono alternativo" },
        { key: "whatsapp", label: "Whatsapp" },
        { key: "status", label: "Lugar" },
        { key: "packages", label: "Paquetes" },
        { key: "observationWhatsapp", label: "Observación" },
        { key: "created_at", label: "Fecha de creación" },
        { key: "action", label: "Acciones", class: "actions text-center" },
      ],
      show: 10,
      isBusy: false,
      pagination: {
        current_page: 1,
        total: 1,
        per_page: 1,
      },
      search: "",
      meta: {},
      city: "",
      date: "",
    };
  },
  mounted() {},
  methods: {
    ...mapGetters(["api"]),
    dateGT(date) {
      const newDate = this.$moment(date);
      return newDate.format("DD-MM-YYYY, h:mm:ss a");
    },
    handlePage(value) {
      this.pagination.current_page = value;
      this.$emit("call");
    },
    addObservation(id, oldObservation) {
      this.$swal({
        title: "Actualizar observación",
        input: "text",
        inputValue: oldObservation,
        inputAttributes: {
          autocapitalize: "off",
        },
        showCancelButton: true,
        confirmButtonText: "Guardar",
        cancelButtonText: "Cancelar",
        showLoaderOnConfirm: true,
        preConfirm: (observation) => {
          return fetch(this.api() + "/package-observartion-whatsapp", {
            method: "POST",
            body: JSON.stringify({
              id: id,
              observation: observation,
            }),
            headers: {
              "Content-type": "application/json; charset=UTF-8",
            },
          })
            .then((response) => {
              if (!response.ok) {
                throw new Error(response.statusText);
              }
              return response.json();
            })
            .catch((error) => {
              this.$swal.showValidationMessage(`Error: ${error}`);
            });
        },
        allowOutsideClick: () => !this.$swal.isLoading(),
      }).then((result) => {
        this.$emit("call");
        if (result.isConfirmed === true)
          this.$swal({ text: `${result.value.message}`, icon: "success" });
      });
    },
    answer(id, status) {
      let loader = this.$loading.show();
      axios
        .post(this.api() + `/package-status-whatsapp`, {
          id: id,
          status: status,
        })
        .then((response) => {
          loader.hide();
          this.$swal({ text: `${response.data.message}`, icon: "success" });
          this.$emit("call");
        })
        .catch((error) => {
          loader.hide();
          console.log(error);
        });
    },
    getTelephones(csv) {
      let telephones = "";
      let reader = new FileReader();
      reader.readAsBinaryString(csv);
      reader.onload = () => {
        telephones = reader.result.split("\r" + "\n");
        let cel = [];
        telephones.forEach((item) => {
          if (item !== "") {
            const t = item.split("");
            if (t[0] === "1")
              cel.push(
                t[1] +
                  t[2] +
                  t[3] +
                  "-" +
                  t[4] +
                  t[5] +
                  t[6] +
                  "-" +
                  t[7] +
                  t[8] +
                  t[9] +
                  t[10]
              );
            else
              cel.push(
                t[3] + t[4] + t[5] + t[6] + "-" + t[7] + t[8] + t[9] + t[10]
              );
          }
        });
        let loader = this.$loading.show();
        axios
          .post(this.api() + `/package-csv-whatsapp`, {
            telephones: JSON.stringify(cel),
            city: this.city,
            date: this.date,
          })
          .then((response) => {
            loader.hide();
            this.$swal({ text: `${response.data.message}`, icon: "success" });
            this.$emit("call");
          })
          .catch((error) => {
            loader.hide();
            console.log(error);
          });
      };
    },
    get(date, city) {
      this.date = this.$moment(date).format("YYYY-MM-DD");
      this.city = city;
      this.isBusy = true;
      if (this.search !== "" && this.pagination.current_page != 1)
        this.pagination.current_page = 1;
      axios
        .get(
          this.api() +
            `/search-package-whatsapp?show=${this.show}&search=${
              this.search
            }&page=${
              this.pagination.current_page
            }&city=${city}&date=${this.$moment(date).format("YYYY-MM-DD")}`
        )
        .then((response) => {
          this.isBusy = false;
          this.items = response.data.data;
          this.pagination = {
            current_page: response.data.current_page,
            total: response.data.total,
            per_page: response.data.per_page,
          };
        })
        .catch((error) => {
          this.isBusy = false;
          console.log(error);
        });
    },
  },
};
</script>
