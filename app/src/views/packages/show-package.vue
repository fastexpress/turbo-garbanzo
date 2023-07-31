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
      <div class="row layout-top-spacing">
        <div id="basic" class="col-lg-12 layout-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Ver Paquete</h4>
                </div>
              </div>
              <div class="panel-body">
                <div class="row">
                  <div class="col-xl-6 col-md-6 col-sm-12 col-6 br-6 p-8">
                    <p>Código de rastreo: {{ id }}</p>
                    <h4>Cliente: {{ receive }}</h4>
                    <h4>Teléfono: {{ telephone }}</h4>
                    <h4>Teléfono alternativo: {{ alternativeTelephone }}</h4>
                    <h4>Para: {{ city }}</h4>
                  </div>
                  <div class="col-xl-6 col-md-6 col-sm-12 col-6">
                    <div class="education layout-spacing">
                      <h3 class="">Estado</h3>
                      <div class="timeline-alter">
                        <div class="item-timeline">
                          <div class="t-meta-date">
                            <p class="">GUATEMALA</p>
                          </div>
                          <div class="t-dot"></div>
                          <div class="t-text">
                            <h4>{{ status === 2 ? "⬅️" : "" }}</h4>
                          </div>
                        </div>
                        <div class="item-timeline">
                          <div class="t-meta-date">
                            <p class="">USA</p>
                          </div>
                          <div class="t-dot"></div>
                          <div class="t-text">
                            <h4>{{ status === 1 ? "⬅️" : "" }}</h4>
                          </div>
                        </div>
                        <div class="item-timeline">
                          <div class="t-meta-date">
                            <p class="">ANULADO</p>
                          </div>
                          <div class="t-dot"></div>
                          <div class="t-text">
                            <h4>{{ status === 0 ? "⬅️" : "" }}</h4>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xl-6 col-md-6 col-sm-12 col-6">
                    <div class="education layout-spacing">
                      <h3 class="">WhatsApp</h3>
                      <div class="timeline-alter">
                        <div class="item-timeline">
                          <div class="t-meta-date">
                            <p class="">NO ENVIADO</p>
                          </div>
                          <div class="t-dot"></div>
                          <div class="t-text">
                            <h4>{{ status === 0 ? "⬅️" : "" }}</h4>
                          </div>
                        </div>
                        <div class="item-timeline">
                          <div class="t-meta-date">
                            <p class="">ENVIADO</p>
                          </div>
                          <div class="t-dot"></div>
                          <div class="t-text">
                            <h4>{{ status === 1 ? "⬅️" : "" }}</h4>
                          </div>
                        </div>
                        <hr />
                        <p>{{ observationWhatsapp }}</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6 col-md-6 col-sm-12 col-6">
                    <div class="education layout-spacing">
                      <h3 class="">Llamada</h3>
                      <div class="timeline-alter">
                        <div class="item-timeline">
                          <div class="t-meta-date">
                            <p class="">PENDIENTE</p>
                          </div>
                          <div class="t-dot"></div>
                          <div class="t-text">
                            <h4>{{ status === 0 ? "⬅️" : "" }}</h4>
                          </div>
                        </div>
                        <div class="item-timeline">
                          <div class="t-meta-date">
                            <p class="">LLAMADO</p>
                          </div>
                          <div class="t-dot"></div>
                          <div class="t-text">
                            <h4>{{ status === 1 ? "⬅️" : "" }}</h4>
                          </div>
                        </div>
                        <div class="item-timeline">
                          <div class="t-meta-date">
                            <p class="">NO CONTESTO</p>
                          </div>
                          <div class="t-dot"></div>
                          <div class="t-text">
                            <h4>{{ status === 2 ? "⬅️" : "" }}</h4>
                          </div>
                        </div>
                        <hr />
                        <p>{{ observationCall }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Paquetes</h4>
                </div>
              </div>
              <div class="panel-body">
                <b-table
                  responsive
                  striped
                  hover
                  :items="packages"
                  :fields="column"
                  :show-empty="true"
                >
                  <template #cell(id)="row">
                    {{ row.item.codeFast }}
                  </template>
                  <template #cell(departament)="row">
                    {{ row.item.departament.name }}
                  </template>
                  <template #cell(type)="row">
                    {{ row.item.type.name }}
                  </template>
                  <template #cell(category)="row">
                    {{ row.item.category.name }}
                  </template>
                  <template #cell(weight)="row">
                    {{ myLbs(row.item.weight) }}
                  </template>
                  <template #cell(cost)="row">
                    {{ myDollar(row.item.cost) }}
                  </template>
                  <template #cell(baler)="row">
                    {{ row.item.baler.code + "-" + row.item.correlative }}
                  </template>
                  <template #cell(payment)="row">
                    <b-badge v-if="row.item.payment === false" variant="danger"
                      >POR COBRAR</b-badge
                    >
                    <b-badge v-else variant="success">CANCELADO</b-badge>
                  </template>
                  <template #cell(observation)="row">
                    {{ row.item.observation }}
                  </template>
                  <template #cell(subtotal)="row">
                    {{ myDollar(row.item.subtotal) }}
                  </template>
                </b-table>
                <h3>Peso total: {{ myLbs(totalWeight) }}</h3>
                <h3>Total a pagar: {{ myDollar(totalSubtotal) }}</h3>
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
import "@/assets/sass/users/user-profile.scss";
import { eight } from "../../utils/eight";

export default {
  metaInfo: { title: "Actualizar paquete" },
  data() {
    return {
      breadcrumb: [
        { text: "Inicio", href: "/" },
        { text: "Actualizar paquete", active: true },
      ],
      column: [
        { key: "id", label: "ID" },
        { key: "send", label: "Envía" },
        { key: "departament", label: "Departamento" },
        { key: "type", label: "Entrega en" },
        { key: "content", label: "Contenido" },
        { key: "category", label: "Categoría" },
        { key: "weight", label: "Peso" },
        { key: "cost", label: "Costo" },
        { key: "payment", label: "Pago" },
        { key: "guide", label: "Guía de envío" },
        { key: "office", label: "Oficina" },
        { key: "observation", label: "Observación" },
        { key: "subtotal", label: "Subtotal" },
      ],
      id: 0,
      receive: "",
      status: 0,
      callStatus: 0,
      observationCall: "",
      observationWhatsapp: "",
      packages: [],
      telephone: "",
      checkTelephone: false,
      alternativeTelephone: "",
      checkAlternativeTelephone: false,
      city: "",
      totalWeight: 0,
      totalSubtotal: 0,
    };
  },

  mounted() {
    this.find();
  },
  methods: {
    ...mapGetters(["api"]),
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
    find() {
      axios
        .get(this.api() + `/package/${this.$route.params.id}`)
        .then((response) => {
          const { headerPackage, packages } = response.data;
          this.id = headerPackage.id;
          this.receive = headerPackage.receive;
          this.telephone = headerPackage.telephone;
          this.alternativeTelephone = headerPackage.alternativeTelephone;
          this.city = headerPackage.city;
          this.status = headerPackage.status;
          this.callStatus = headerPackage.callStatus;
          this.observationCall = headerPackage.observationCall;
          this.observationWhatsapp = headerPackage.observationWhatsapp;
          this.observationCall = headerPackage.observationCall;

          this.packages = [];

          packages.forEach((item) => {
            this.totalSubtotal = this.parseDecimal(
              this.parseDecimal(this.totalSubtotal) +
                this.parseDecimal(item.subtotal)
            );
            this.totalWeight = this.parseDecimal(
              this.parseDecimal(this.totalWeight) +
                this.parseDecimal(item.weight)
            );
            this.packages.push({
              id: item.id,
              codeFast: item.codeFast,
              correlative: item.correlative,
              key: item.idParent,
              send: item.send,
              departament: JSON.parse(item.departament),
              type: JSON.parse(item.type),
              content: item.content,
              category: JSON.parse(item.category),
              weight: item.weight,
              cost: item.cost,
              payment: item.payment === 0 ? true : false,
              guide: item.guide,
              office: item.office,
              observation: item.observation,
              subtotal: item.subtotal,
              updatedMultiplier: item.updatedMultiplier,
              multiplier: item.multiplier,
            });
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>