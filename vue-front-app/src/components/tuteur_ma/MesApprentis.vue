<template>
  <!-- eslint-disable -->
  <div>
    <h1 class="text-center">Mon/Mes Apprenti(s)</h1>

    <div class="case rounded shadow border p-1 p-md-3">
      <preloader class="my-5" v-if="!requestDone"></preloader>
      <p v-if="requestDone && users.length == 0" class="text-center">
        Aucun apprenti.
      </p>

      <div
        v-for="(user, index) of users"
        :key="index"
        v-if="users.length > 0 && requestDone"
        class="d-flex row-lg pt-4"
        id="row"
      >
        <div
          class="card px-5"
          style="cursor: pointer"
          v-on:click="this.$router.push(`/mes-apprentis/${user.id}/journal`)"
        >
          <i class="fa fa-user fa-xl" />
          <div class="card-body p-0">
            <div class="card-text text-center">
              <p>
                <strong
                  >{{ user.utilisateur.prenom }}
                  {{ user.utilisateur.nom }}</strong
                >
              </p>
              <p>
                Promotion : {{ user.idPromotion.nom }} -
                {{ user.idPromotion.annee }}
              </p>
              <p>{{ user.maitreApprentissage.entreprise.nom }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import Preloader from "@/components/utils/preLoader";

export default {
  name: "ListeApprentis",

  components: { Preloader },

  data() {
    return {
      users: [],
      errors: [],
      requestDone: false,
    };
  },

  async created() {
    this.requestDone = false;

    if (this.$store.state.perm === "MAITRE-APPRENTISSAGE") {
      await API_Helper.get(
        `/maitre-aprentissage/${this.$store.state.id}/apprentis`
      )
        .then((response) => {
          this.users = response.data;
        })
        .catch((e) => console.error(e));
    } else if (this.$store.state.perm === "ENSEIGNANT") {
      await API_Helper.get(`/enseignant/${this.$store.state.id}/apprentis`)
        .then((response) => {
          this.users = response.data;
        })
        .catch((e) => console.error(e));
    }
    this.requestDone = true;
  },
};
</script>

<style Scoped>
.context h2 {
  color: #4b93cf;
}

.card-text.text-center p {
  font-size: x-large;
  margin: 0.5em;
}

.d-flex.row-lg.pt-4 > .card {
  flex-grow: 1;
  flex-direction: row;
  align-items: center;
}

.card:hover {
  box-shadow: 1px 4px 10px grey;
  transition: box-shadow 0.1s ease-out;
  -webkit-transition: box-shadow 0.1s ease-out;
}
</style>
