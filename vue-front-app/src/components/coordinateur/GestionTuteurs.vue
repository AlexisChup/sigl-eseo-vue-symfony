<template>
  <div>
    <h1 class="text-center">Équipe tutorale</h1>

    <router-link :to="{ name: 'edition-tuteurs', params: { id: 0 } }"
      >Ajouter un enseignant</router-link
    >

    <div v-if="errors && errors.length">
      <div v-for="(error, index) in errors" :key="index">
        <p style="color: red">{{ error.message }}</p>
      </div>
    </div>

    <div v-if="data && data.length">
      <div v-for="(enseignant, index) in data" :key="index">
        <span>{{ enseignant.prenom }} {{ enseignant.nom }}</span>
        <span>{{ enseignant.email }}</span>
        <span
          ><router-link
            :to="{ name: 'edition-tuteurs', params: { id: enseignant.id } }"
            >modifier</router-link
          ></span
        >
      </div>
    </div>

    <router-view />
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";

export default {
  name: "GestionTuteurs",

  data() {
    return {
      data: [],
      errors: [],
    };
  },

  created() {
    API_Helper.get("/utilisateur/enseignants")
      .then((response) => (this.data = response.data))
      .catch((e) => this.errors.push(e));
  },
  updated() {
    API_Helper.get("/utilisateur/enseignants")
      .then((response) => (this.data = response.data))
      .catch((e) => this.errors.push(e));
  },
};
</script>

<style scoped></style>
