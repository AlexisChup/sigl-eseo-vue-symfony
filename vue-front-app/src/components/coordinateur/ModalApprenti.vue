<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="modalEditApprentiLabel">
        Modifier {{ apprentiToEdit.nom }}
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
              v-model="apprentiToEdit.nom"
            />
          </div>

          <div class="form-group">
            <label class="col-form-label-sm mb-0" for="prenom">Prenom</label>
            <input
              type="text"
              id="prenom"
              class="form-control form-control-sm"
              v-model="apprentiToEdit.prenom"
            />
          </div>
          <div class="form-row">
            <div class="form-group col-8">
              <label class="col-form-label-sm mb-0" for="email">Email</label>
              <input
                type="text"
                id="email"
                class="form-control form-control-sm"
                v-model="apprentiToEdit.email"
              />
            </div>
            <div class="form-group col-4">
              <label class="col-form-label-sm mb-0" for="adresse">Adresse</label>
              <input
                type="text"
                id="adresse"
                class="form-control form-control-sm"
                v-model="apprentiToEdit.adresse"
              />
            </div>
          </div>
          <div class="form-group">
            <label class="col-form-label-sm mb-0" for="motDePasse">Mot de passe</label>
            <input
              type="text"
              id="motDePasse"
              class="form-control form-control-sm"
              v-model="apprentiToEdit.motDePasse"
            />
          </div>
        </form>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">
        Fermer
      </button>
      <div>
        <button
            type="button"
            v-on:click="resetValue"
          >
          Effacer
        </button>
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

export default {
  name: "ModalApprenti",
  props: {
    action: String,
    apprentiToEdit: Object,
  },
  created() {},
  data() {
    return {
      apprenti: {
        nom: "",
        prenom: "",
        email: "",
        adresse: "",
        motDePasse: "",
      },
      isFormValid: this.apprentiToEdit.id ? true : false,
      isFormEmpty: true,
    };
  },
  methods: {
    formSubmit(e) {
      e.preventDefault();

      API_Helper.put("/apprenti/edit/" + this.apprentiToEdit.id, this.apprentiToEdit)
          .then((response) => {
            createToast(
              "L'utilisateur " +
                this.apprentiToEdit.nom +
                " a bien été modifiée.",
              { type: "success" }
            );
            resetValuesFormStringObject(this.apprentiToEdit);
            this.isFormValid = false;
            this.isFormEmpty = true;
            this.$emit("getApprentis");
            $("#modalEditApprenti").modal("hide");
          })

          .catch((e) => {
            createToast(
              "L'utilisateur " +
                this.apprentiToEdit.nom +
                " n'a pas pu être modifiée.",
              { type: "danger" }
            );
          });
    },
    formValidation() {
      this.isFormValid = isFormStringValid(this.apprentiToEdit);
      this.isFormEmpty = isFormStringEmpty(this.apprentiToEdit);
    },
    resetValue(e) {
      e.preventDefault();
      resetValuesFormStringObject(e)
      this.isFormValid = false;
      this.isFormEmpty = true;
    },
  },
};
</script>
<!-- :disabled="isFormEmpty" -->