<template>
  <div>
    <div>
      <h1>Page inexistante</h1>
    </div>
    <div>
      <p>
        Oups ! La page que vous cherchez n'existe pas, mais ne vous inquiétez
        pas, cliquez
        <button class="btn btn-success btn-sm" v-on:click="redirectUser">
          <i class="fa-solid fa-arrow-right"></i>&nbsp;ici
        </button>
        pour retrouver votre chemin !
      </p>
    </div>
    <div></div>
  </div>
</template>

<script>
export default {
  name: "PageNotFound",
  methods: {
    isLogged() {
      if (
        this.$store.state.perm != "UNIDENTIFIED" &&
        this.$store.state.perm != undefined &&
        this.$store.state.perm != null &&
        this.$store.state.perm != ""
      ) {
        return true;
      }

      return false;
    },
    redirectUser() {
      if (this.isLogged()) {
        this.$router.push("/");
      } else {
        this.$router.push("/connexion");
      }
    },
  },
};
</script>

<style scoped></style>
