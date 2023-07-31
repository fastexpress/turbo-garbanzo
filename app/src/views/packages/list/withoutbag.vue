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
              <b-badge variant="primary" v-if="row.item.status === 2"
                >GUATEMALA</b-badge
              >
              <b-badge variant="success" v-if="row.item.status === 1"
                >USA</b-badge
              >
              <b-badge variant="danger" v-if="row.item.status === 0"
                >ANULADO</b-badge
              >
            </template>
            <template #cell(total)="row">
              {{ row.item.total }} paquetes
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
                    @click="update(row.item.id, 1)"
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
                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"
                      ></path>
                      <path
                        d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"
                      ></path>
                    </svg>
                    Actualizar
                  </b-dropdown-item>
                  <b-dropdown-item
                    @click="ifChangeStatus(row.item.id, 0)"
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
                      <line x1="18" y1="6" x2="6" y2="18"></line>
                      <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Anular
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
        { key: "total", label: "Paquetes" },
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
    changeStatus(id, status) {
      let loader = this.$loading.show();
      axios
        .post(this.api() + `/package-change-status`, {
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
    ifChangeStatus(id,status) {
      this.$swal({
        title: "Quieres marcar este paquete como anulado?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si",
        cancelButtonText: "Cancelar",
      }).then((result) => {
        if (result.isConfirmed) {
          this.changeStatus(id,status);
        }
      });
    },
    
    update(id) {
      this.$router.push({
        name: "UpdatePackage",
        params: {
          id,
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
            `/search-package-without-bag?show=${this.show}&search=${
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
