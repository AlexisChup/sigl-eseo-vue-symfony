<template>
  <div class="">
    <div class="row justify-content-center px-3">
      <img
        class="img-fluid"
        src="./../assets/ESEOLogo.png"
        alt="Logo de l'ESEO"
      />
    </div>
    <div class="row my-5">
      <div class="col justify-content-center">
        <h1 class="h1 text-center">JOURNAL DE FORMATION</h1>
        <h2 class="h2 text-center">Apprentissage</h2>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-sm-10 col-md-8 col-lg-6 col-xl-4 justify-content-center">
        <form v-on:input="formValidation">
          <div class="form-group mb-4" v-if="!isLoading">
            <input
              :class="validityInputUsername"
              type="text"
              v-model="userCredentials.username"
              placeholder="Nom d'utilisateur"
              name="username"
              @blur="inputUsernameValidation"
              id="inputUsername"
            />
          </div>
          <div class="form-group" v-if="!isLoading">
            <input
              :class="validityInputPassword"
              type="password"
              v-model="userCredentials.password"
              placeholder="Mot de passe"
              name="password"
              autocomplete="on"
              @blur="inputPasswordValidation"
              id="inputPassword"
            />
          </div>
          <div class="d-flex justify-content-center mb-4" v-if="isLoading">
            <div
              class="spinner-border text-primary"
              role="status"
              style="width: 3rem; height: 3rem"
            >
              <span class="sr-only">Loading...</span>
            </div>
          </div>
          <button
            class="btn btn-primary w-100"
            v-on:click="connexion"
            :disabled="!isFormValid"
          >
            Connexion
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
import { API_Helper } from "@/api/helper_api";
import { createToast } from "mosha-vue-toastify";
import { isFormStringValid } from "../utils/form-utils";
var md5 = require("md5");

export default {
  name: "Connexion",
  data() {
    return {
      userCredentials: {
        username: "",
        password: "",
      },

      data: [],
      errors: [],

      error_message: "",

      isLoading: false,
      isFormValid: false,
      validityInputUsername: "form-control",
      validityInputPassword: "form-control",
    };
  },
  mounted() {},
  methods: {
    inputUsernameValidation() {
      if (this.userCredentials.username != "") {
        this.validityInputUsername = "form-control is-valid";
      } else {
        this.validityInputUsername = "form-control is-invalid";
      }
    },
    inputPasswordValidation() {
      if (this.userCredentials.password != "") {
        this.validityInputPassword = "form-control is-valid";
      } else {
        this.validityInputPassword = "form-control is-invalid";
      }
    },
    formValidation(e) {
      if (isFormStringValid(this.userCredentials) && !this.isLoading) {
        this.isFormValid = true;
      } else {
        this.isFormValid = false;
      }

      if (e) {
        if (e.srcElement.id == "inputPassword") {
          this.inputPasswordValidation();
        } else if (e.srcElement.id == "inputUsername") {
          this.inputUsernameValidation();
        }
      }
    },
    connexion(e) {
      e.preventDefault();
      this.isLoading = true;
      this.formValidation();

      const passwordEncrypted = md5(this.userCredentials.password);

      const data_connexion = {
        username: this.userCredentials.username,
        password: passwordEncrypted,
      };

      API_Helper.post("/utilisateur/connexion", data_connexion)
        .then((response) => {
          this.data = response.data;

          let data = JSON.parse(JSON.stringify(this.data));
          const { user, perm, error } = response.data;

          if (!error) {
            this.$store.commit("LOGIN", {
              perm,
              id: user.id,
            });
            createToast("Connexion réussie.", {
              position: "bottom-right",
              type: "success",
            });
            this.$router.push({ path: "/accueil" });
          } else {
            createToast("Identifiant ou mot de passe incorrect.", {
              position: "bottom-right",
              type: "danger",
            });
          }
        })
        .catch((error) => {
          console.log("POST /utilisateur/connexion ERROR : ", error);
          this.error_message = "Identifiant ou mot de passe incorrect.";
          this.errors.push(error);
          createToast("Erreur interne. Veuillez réessayer plus tard.", {
            position: "bottom-right",
            type: "danger",
          });
        })
        .finally(() => {
          this.isLoading = false;
          this.formValidation();
        });
    },
  },
};
</script>

<style scoped></style>
