<template>
  <b-modal
    ref="modal_find_package"
    title="Paquetes similares"
    title-tag="h4"
    size="xl"
    footer-class="justify-content-center"
    centered
  >
    <b-table
      ref="basic_table"
      responsive
      hover
      :items="packages"
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
      <template #cell(status)="row">
        <b-badge variant="primary" v-if="row.item.status === 'OFICINA'"
          >OFICINA</b-badge
        >
        <b-badge variant="success" v-if="row.item.status === 'CODE'"
          >GUÍA WORD</b-badge
        >
        <b-badge variant="warning" v-if="row.item.status === 'GENERATED'"
          >PENDIENTE CÓDIGO UPS</b-badge
        >
        <b-badge variant="danger" v-if="row.item.status === 'READY'"
          >LISTO PARA EL ENVÍO</b-badge
        >
      </template>
      <!-- <template #cell(action)="row">
        <b-button
          variant="success"
          class="mb-2 mr-2 rounded-circle"
          @click="show(row.item)"
          ><svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="feather feather-sun"
          >
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
            <circle cx="12" cy="12" r="3"></circle></svg
        ></b-button>
      </template> -->
    </b-table>
    <template #modal-footer>
      <b-button variant="danger" @click="closeModal">Cerrar</b-button>
    </template>
  </b-modal>
</template>
<script>
export default {
  data() {
    return {
      columns: [
        { key: "id", label: "ID" },
        { key: "receive", label: "Destinatario" },
        { key: "telephone", label: "Teléfono" },
        { key: "status", label: "Estado" },
        // { key: "action", label: "Acciones", class: "actions text-center" },
      ],
      isBusy: false,
      packages: [],
    };
  },
  methods: {
    openModal(packages) {
      this.packages = packages;
      this.$refs["modal_find_package"].show();
    },

    closeModal() {
      this.$refs["modal_find_package"].hide();
    },
    show(item) {
      console.log("🚀 ~ file: find.vue ~ line 102 ~ show ~ item", item);
    },
  },
};
</script>