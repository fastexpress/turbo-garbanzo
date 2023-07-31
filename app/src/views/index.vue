<template>
  <div class="layout-px-spacing dash_2">
    <portal to="breadcrumb">
      <ul class="navbar-nav flex-row">
        <li>
          <div class="page-header">
            <nav class="breadcrumb-one" aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">
                  <a href="javascript:;">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                  <span>Inicio</span>
                </li>
              </ol>
            </nav>
          </div>
        </li>
      </ul>
    </portal>
    <div class="container">
      <div class="row">
        <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
          <div class="statbox panel box box-shadow">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Bienvenido al Sistema Fast Express GT</h4>
                </div>
              </div>
            </div>
            <div class="panel-body">
              <!-- :interval="4000"
                controls
                indicators -->
              <b-carousel
                ref="carousel1"
                v-model="slide"
                class="style-custom-1"
              >
                <b-carousel-slide>
                  <template #img>
                    <img
                      class="d-block w-100 slide-image"
                      src="https://source.unsplash.com/random/900×700/?usa"
                      alt="First slide"
                    />
                  </template>
                </b-carousel-slide>
                <!-- <b-carousel-slide>
                  <template #img>
                    <img
                      class="d-block w-100 slide-image"
                      src="https://source.unsplash.com/random"
                      alt="Second slide"
                    />
                  </template>
                  <h3>Frase del día</h3>
                  <div class="media">
                    <div class="media-body">
                      <h6 class="user-name">{{ phrase.phrase }}</h6>
                      <h4 class="user-name">{{ phrase.author }}</h4>
                    </div>
                  </div>
                </b-carousel-slide> -->
              </b-carousel>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import axios from "axios";
import "@/assets/sass/components/custom-carousel.scss";
export default {
  metaInfo: { title: "Inicio" },
  data() {
    return {
      slide: 0,
      // phrase: {
      //   phrase: "",
      //   author: "",
      // },
    };
  },
  // mounted() {
  //   this.getPhrase();
  // },
  // methods: {
  //   getPhrase() {
  //     axios
  //       .get("https://frasedeldia.azurewebsites.net/api/phrase")
  //       .then((response) => {
  //         this.phrase = response.data;
  //       });
  //   },
  // },
};
</script>
