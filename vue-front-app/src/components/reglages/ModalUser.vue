<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="modalEditUserLabel">
        Modifier {{ userToEdit.nom }}
      </h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="container">
        <form v-on:input="formValidation">
          <div class="form-group">
            <label class="col-form-label-sm mb-0" for="nom">Nom</label>
            <input
              type="text"
              id="nom"
              class="form-control form-control-sm"
              v-model="userToEdit.nom"
            />
          </div>

          <div class="form-group">
            <label class="col-form-label-sm mb-0" for="prenom">Prenom</label>
            <input
              type="text"
              id="prenom"
              class="form-control form-control-sm"
              v-model="userToEdit.prenom"
            />
          </div>
          <div class="form-row">
            <div class="form-group col-8">
              <label class="col-form-label-sm mb-0" for="email">Email</label>
              <input
                :class="validityInputEmail"
                name="email"
                type="email"
                id="email"
                @blur="inputEmailValidation"
                v-on:input="validateEmail(userToEdit.email)"
                class="form-control form-control-sm"
                v-model="userToEdit.email"
              />
            </div>
            <div class="form-group col-4">
              <label class="col-form-label-sm mb-0" for="adresse"
                >Adresse</label
              >
              <input
                type="text"
                id="adresse"
                class="form-control form-control-sm"
                v-model="userToEdit.adresse"
              />
            </div>
          </div>
          <div class="form-group">
            <label class="col-form-label-sm mb-0">
              Mot de passe - (Remplir pour changer de mot de passe)
            </label>

            <input
              type="password"
              id="password"
              class="form-control form-control-sm"
              v-model="this.data.pwd"
            />
            <!---v-model="userToEdit.motDePasse"-->
          </div>
        </form>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">
        Fermer
      </button>
      <div>
        <button class="btn btn-danger" v-on:click="resetValue">Effacer</button>
        <button
          class="btn btn-success ml-2"
          v-on:click="formSubmit"
          :disabled="!isFormValid"
        >
          Modifier
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import { createToast } from "mosha-vue-toastify";
import {
  resetValuesFormStringObject,
  isFormStringValid,
  isFormStringEmpty,
} from "@/utils/form-utils";
import "mosha-vue-toastify/dist/style.css";
import md5 from "md5";

export default {
  name: "ModalUser",
  props: {
    action: String,
    userToEdit: Object,
  },
  updated() {
    this.validateEmail();
  },
  data() {
    return {
      isFormValid: true,
      isFormEmpty: true,
      validityInputEmail: "form-control",
      data: {
        pwd: "",
      },
    };
  },
  methods: {
    formSubmit(e) {
      e.preventDefault();
      const passwordEncrypted = md5(this.data.pwd);
      const dataToPost = { ...this.data, pwd: passwordEncrypted };

      API_Helper.put(
        "/utilisateur/" + this.userToEdit.id + "/update_pwd",
        dataToPost
      )
        .then((response) => {
          createToast(
            "L'utilisateur " + this.userToEdit.nom + " a bien été modifiée.",
            { type: "success" }
          );
          resetValuesFormStringObject(this.userToEdit);
          this.isFormValid = false;
          this.isFormEmpty = true;
          this.$emit("getUsers");
          $("#modalEditUser").modal("hide");
        })
        .catch((e) => {
          createToast(
            "L'utilisateur " +
              this.userToEdit.nom +
              " n'a pas pu être modifiée.",
            { type: "danger" }
          );
        });
    },
    formValidation() {
      if (
        isFormStringValid(this.userToEdit) &&
        this.validityInputEmail == "form-control is-valid"
      ) {
        this.isFormValid = true;
      } else {
        this.isFormValid = false;
      }
      this.isFormEmpty = isFormStringEmpty(this.userToEdit);
    },

    resetValue(e) {
      e.preventDefault();
      resetValuesFormStringObject(this.userToEdit);
      this.isFormValid = true;
      this.isFormEmpty = true;
    },
    validateEmail() {
      var re =
        /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      this.inputEmailValidation(re.test(this.userToEdit.email));
      return this.userToEdit.email;
    },
    inputEmailValidation(testedMail) {
      if (testedMail) {
        this.validityInputEmail = "form-control is-valid";
      } else {
        this.validityInputEmail = "form-control is-invalid";
      }
      this.formValidation();
    },
  },
};
</script>
