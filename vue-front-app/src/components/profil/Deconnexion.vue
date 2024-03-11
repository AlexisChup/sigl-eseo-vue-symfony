<template>
  <!-- eslint-disable -->
  <div>
    <h2>Deconnexion</h2>
    <div class="d-flex justify-content-center">
      <div
        class="spinner-border text-primary"
        role="status"
        style="width: 3rem; height: 3rem"
      >
        <span class="sr-only">Loading...</span>
      </div>
    </div>
  </div>
</template>

<script>
import { createToast } from "mosha-vue-toastify";

export default {
  name: "Deconnexion",
  mounted() {
    this.logout();
  },
  methods: {
    logout() {
      this.$store.commit("LOGOUT");
      createToast("Déconnexion réussie.", {
        position: "bottom-right",
        type: "success",
      });
      this.$router.push({ path: "/connexion" });
    },
  },
};
</script>

<style scoped></style>
