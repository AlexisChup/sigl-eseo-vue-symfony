<template>
  <div>
    <h2>{{ form.prenom }} {{ form.nom }}</h2>

    <div v-if="errors && errors.length">
      <div v-for="(error, index) in errors" v-bind:key="index">
        <p style="color: red">{{ error.message }}</p>
      </div>
    </div>

    <form v-on:submit.prevent="submit">
      <input v-model="idUser" type="hidden" />
      <input v-model="form.actif" type="hidden" />
      <input v-model="form.motDePasse" type="hidden" />
      <div>
        <input
          v-model="form.prenom"
          type="text"
          placeholder="Prénom"
          required
        />
        <input v-model="form.nom" type="text" placeholder="Nom" required />
      </div>
      <div>
        <input
          v-model="form.email"
          type="email"
          placeholder="prenom.nom@eseo.fr"
          required
        />
      </div>
      <div>
        <input v-model="form.tel" type="text" placeholder="+33" required />
      </div>
      <div>
        <textarea
          v-model="form.adresse"
          placeholder="10 Boulevard Jean Jeanneteau, 49100 Angers"
          required
        />
      </div>
      <div><input type="submit" value="Enregistrer" /></div>
    </form>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";

export default {
  name: "EditionTuteurs",

  data() {
    return {
      idUser: 0,
      form: {
        email: "",
        motDePasse: "",
        nom: "",
        prenom: "",
        tel: "",
        adresse: "",
        actif: true,
      },
      data: [],
      errors: [],
    };
  },
  created() {
    this.idUser = this.$route.params.id;
    if (this.idUser > 0) {
      API_Helper.get("/utilisateur/" + this.idUser)
        .then((response) => {
          this.data = response.data;
          this.form.email = this.data.email;
          this.form.motDePasse = this.data.motDePasse;
          this.form.prenom = this.data.prenom;
          this.form.nom = this.data.nom;
          this.form.tel = this.data.tel;
          this.form.adresse = this.data.adresse;
          this.form.actif = this.data.actif;
        })
        .catch((e) => this.errors.push(e));
    }
  },
  updated() {
    this.idUser = this.$route.params.id;
    if (this.idUser > 0) {
      API_Helper.get("/utilisateur/" + this.idUser)
        .then((response) => {
          this.data = response.data;
          this.form.email = this.data.email;
          this.form.motDePasse = this.data.motDePasse;
          this.form.prenom = this.data.prenom;
          this.form.nom = this.data.nom;
          this.form.tel = this.data.tel;
          this.form.adresse = this.data.adresse;
          this.form.actif = this.data.actif;
        })
        .catch((e) => this.errors.push(e));
    }
  },
  methods: {
    submit() {
      if (this.idUser > 0) {
        API_Helper.post("/utilisateur/" + this.idUser + "/update", this.form)
          .then((response) => {})
          .catch((e) => this.errors.push(e));
      } else {
        API_Helper.post("/utilisateur/create", this.form)
          .then((response) => console.log(response))
          .catch((e) => {
            this.errors.push(e);
            console.log(e);
          });
      }
    },
  },
};
</script>

<style scoped></style>
