<template>
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
            selectable
            @row-selected="viewItem"
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
</template>
<script>
import axios from "axios";
import { mapGetters } from "vuex";
import "@/assets/sass/apps/todolist.scss";

export default {
  data() {
    return {
      items: [],
      columns: [
        { key: "id", label: "ID" },
        { key: "receive", label: "Destinatario" },
        { key: "telephone", label: "Teléfono" },
        { key: "alternativeTelephone", label: "Teléfono alternativo" },
        { key: "status", label: "Lugar" },
        { key: "packages", label: "Paquetes" },
        { key: "created_at", label: "Fecha de creación" },
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
        title: "Actualizar observación de la llamada",
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
          return fetch(this.api() + "/package-observartion-call", {
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
        .post(this.api() + `/package-status-call`, {
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
    viewItem(item) {
      this.$router.push({
        name: "ShowPackage",
        params: {
          id: item[0].id,
        },
      });
    },
    get(date, city) {
      this.isBusy = true;
      if (this.search !== "" && this.pagination.current_page != 1)
        this.pagination.current_page = 1;
      axios
        .get(
          this.api() +
            `/search-package-all?show=${this.show}&search=${this.search}&page=${
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
