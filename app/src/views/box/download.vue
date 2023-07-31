<template>
  <b-button @click="download" variant="primary" v-if="userRole() === 1"
    >Descargar</b-button
  >
</template>
<script>
import { mapGetters } from "vuex";

export default {
  props: {
    typeReport: {
      type: Object,
      default: () => ({
        name: "Oficina",
        value: "OFICINA",
      }),
    },
    customer: {
      type: Number,
    },
  },
  data: () => {
    return {};
  },
  methods: {
    ...mapGetters(["api", "userRole"]),
    download() {
      window.open(
        `${this.api()}/search-package-ups-download?customer=${
          this.customer
        }&type=${this.typeReport.value}&name=${this.typeReport.name}`,
        "_blank"
      );
    },
  },
};
</script>
