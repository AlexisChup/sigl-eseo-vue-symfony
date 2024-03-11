<template>
  <!-- eslint-disable -->
  <div class="d-flex flex-column vh-100">
    <Suspense>
      <HeaderGlobal :key="key_header"/>
    </Suspense>
    <div class="d-flex justify-content-center">
      <div class="container rounded m-5 p-3 shadow" id="context">
        <router-view/>
      </div>
    </div>
  </div>
</template>

<script>
import HeaderGlobal from "@/components/header/HeaderGlobal";
import Accueil from "@/components/Accueil";

export default {
  name: "App",
  components: {
    HeaderGlobal,
    Accueil,
  },

  created() {
    window.onclose = function () {
      this.$store.commit("LOGOUT");
    };
  },

  mounted() {
  },

  data() {
    return {
      key_header: 0,
    };
  },

  methods: {
    forceRender: function () {
      this.key_header += 1;
    },
  },
};
</script>

<style>
/* BOOTSTRAP */
@import "https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css";

/* APP */
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: #2c3e50;
}

body {
  background-image: url("./assets/eseo.png");
  background-size: cover;
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-position: center;
}

/* context */
#context {
  background: rgba(255, 255, 255, 0.9);
}

#context h1 {
  color: #4d9cd6;
}

#context h2 {
  color: #33688f;
  margin-bottom: 0.5em;
}

.case {
  background: rgba(255, 255, 255, 1);
  width: 100%;
}
</style>
