<template>
  <!-- eslint-disable -->
  <div class="p-1 p-md-3">
    <div class="row justify-content-center text-center p-1">
      <h1>Mes informations</h1>
    </div>

    <div class="row">
      <div class="col-6 row m-0 mb-3 justify-content-center">
        <h2>{{ data.prenom }} {{ data.nom }}</h2>
      </div>
      <div class="col-12 row m-0 justify-content-around">
        <div class="case rounded shadow border p-1 p-md-3 col col-md-5 mb-3">
          <div class="form-group">
            <input
              id="inputEmail"
              type="email"
              class="form-control"
              v-model="data.email"
              placeholder="Email"
            />
          </div>
          <div class="form-group">
            <input
              id="inputTel"
              type="text"
              class="form-control"
              v-model="data.tel"
              placeholder="N° de téléphone"
            />
          </div>
          <div class="form-group">
            <input
              id="inputAdresse"
              type="text"
              class="form-control"
              v-model="data.adresse"
              placeholder="Addresse postale"
            />
          </div>
          <input
            type="submit"
            class="btn btn-primary btn-block"
            v-on:click="formSubmit"
            value="Modifier les informations"
          />
        </div>
        <div class="case rounded shadow border p-1 p-md-3 col col-md-5 mb-3">
          <div class="form-group">
            <input
              id="inputPassword"
              type="password"
              class="form-control"
              v-model="data.pwd"
              placeholder="Mot de passe"
            />
          </div>
          <input
            type="submit"
            class="btn btn-primary btn-block"
            v-on:click="formSubmitPwd"
            value="Modifier le mot depasse"
            :disabled="!data.pwd"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import { createToast } from "mosha-vue-toastify";
var md5 = require("md5");

export default {
  name: "InfoPerso",

  data() {
    return {
      idUser: 0,
      data: {
        nom: "",
        prenom: "",
        email: "",
        tel: "",
        adresse: "",
        pwd: "",
      },
    };
  },

  created() {
    this.idUser = this.$store.state.id;
  },

  beforeMount() {
    this.dataGet();
  },

  methods: {
    dataGet() {
      API_Helper.get("/utilisateur/" + this.$store.state.id, this.user).then(
        (response) => {
          this.data.nom = response.data.nom;
          this.data.prenom = response.data.prenom;
          this.data.email = response.data.email;
          this.data.tel = response.data.tel;
          this.data.adresse = response.data.adresse;
        }
      );
    },

    formSubmit(e) {
      e.preventDefault();
      const passwordEncrypted = md5(this.data.pwd);
      const dataToPost = { ...this.data, pwd: passwordEncrypted };

      API_Helper.put(
        "/utilisateur/" + this.$store.state.id + "/update_contact",
        dataToPost
      )
        .then((response) => {
          createToast("Vos informations ont bien été modifiées.", {
            type: "success",
          });
        })
        .catch((e) => {
          createToast("Vos informations n'ont pas pu être modifiées.", {
            type: "danger",
          });
        });
    },

    formSubmitPwd(e) {
      e.preventDefault();
      const passwordEncrypted = md5(this.data.pwd);
      const dataToPost = { ...this.data, pwd: passwordEncrypted };

      API_Helper.put(
        "/utilisateur/" + this.$store.state.id + "/update_pwd",
        dataToPost
      )
        .then((response) => {
          createToast("Votre mot de passe a bien été modifié.", {
            type: "success",
          });
        })
        .catch((e) => {
          createToast("Votre mot de passe n'a pas pu être modifié.", {
            type: "danger",
          });
        });
    },
  },
};
</script>

<style scoped></style>
