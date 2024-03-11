<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5
        v-if="action === 'add'"
        class="modal-title"
        id="modalCreateEntrepriseLabel"
      >
        Ajouter une entreprise
      </h5>
      <h5
        v-if="action === 'edit'"
        class="modal-title"
        id="modalCreateEntrepriseLabel"
      >
        Modifier {{ entrepriseToEdit.nom }}
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
              placeholder="THALES"
              v-model="entrepriseToEdit.nom"
            />
          </div>

          <div class="form-group">
            <label class="col-form-label-sm mb-0" for="adresse">Adresse</label>
            <input
              type="text"
              id="adresse"
              class="form-control form-control-sm"
              placeholder="110 Av. du Maréchal Leclerc"
              v-model="entrepriseToEdit.adresse"
            />
          </div>
          <div class="form-row">
            <div class="form-group col-8">
              <label class="col-form-label-sm mb-0" for="ville">Ville</label>
              <input
                type="text"
                id="ville"
                class="form-control form-control-sm"
                placeholder="Cholet"
                v-model="entrepriseToEdit.ville"
              />
            </div>
            <div class="form-group col-4">
              <label class="col-form-label-sm mb-0" for="codePostal"
                >Code Postal</label
              >
              <input
                type="text"
                id="codePostal"
                class="form-control form-control-sm"
                placeholder="49300"
                v-model="entrepriseToEdit.codePostal"
              />
            </div>
          </div>
          <div class="form-group">
            <label class="col-form-label-sm mb-0" for="siret">SIRET</label>
            <input
              type="text"
              id="siret"
              class="form-control form-control-sm"
              placeholder="38347093700061"
              v-model="entrepriseToEdit.siret"
            />
          </div>
        </form>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">
        Fermer
      </button>
      <div v-if="action === 'add'">
        <button
          class="btn btn-danger"
          v-on:click="resetValue"
          :disabled="isFormEmpty"
        >
          Effacer
        </button>
        <button
          class="btn btn-success ml-2"
          v-on:click="formSubmit"
          :disabled="!isFormValid"
        >
          Créer
        </button>
      </div>
      <div v-if="action === 'edit'">
        <button
          class="btn btn-danger"
          v-on:click="resetValue"
          :disabled="isFormEmpty"
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
  name: "InscriptionEntreprise",
  props: {
    action: String,
    entrepriseToEdit: Object,
  },
  created() {},
  data() {
    return {
      entreprise: {
        nom: "",
        siret: "",
        adresse: "",
        ville: "",
        codePostal: "",
      },
      isFormValid: this.entrepriseToEdit.id ? true : false,
      isFormEmpty: true,
    };
  },
  methods: {
    formSubmit(e) {
      e.preventDefault();

      if (this.action === "add") {
        API_Helper.post("/entreprise/create", this.entrepriseToEdit)
          .then((response) => {
            createToast(
              "L'entreprise " +
                this.entrepriseToEdit.nom +
                " a bien été ajoutée.",
              { type: "success" }
            );
            resetValuesFormStringObject(this.entrepriseToEdit);
            this.isFormValid = false;
            this.isFormEmpty = true;
            this.$emit("getEntreprises");
            $("#modalCreateEntreprise").modal("hide");
          })

          .catch((e) => {
            createToast(
              "L'entreprise " +
                this.entrepriseToEdit.nom +
                " n'a pas pu être ajoutée.",
              { type: "danger" }
            );
          });
      } else if (this.action === "edit") {
        API_Helper.put(
          "/entreprise/edit/" + this.entrepriseToEdit.id,
          this.entrepriseToEdit
        )
          .then((response) => {
            createToast(
              "L'entreprise " +
                this.entrepriseToEdit.nom +
                " a bien été modifiée.",
              { type: "success" }
            );
            resetValuesFormStringObject(this.entrepriseToEdit);
            this.isFormValid = false;
            this.isFormEmpty = true;
            this.$emit("getEntreprises");
            $("#modalCreateEntreprise").modal("hide");
          })

          .catch((e) => {
            createToast(
              "L'entreprise " +
                this.entrepriseToEdit.nom +
                " n'a pas pu être modifiée.",
              { type: "danger" }
            );
          });
      }
    },
    formValidation() {
      this.isFormValid = isFormStringValid(this.entrepriseToEdit);
      this.isFormEmpty = isFormStringEmpty(this.entrepriseToEdit);
    },
    resetValue(e) {
      e.preventDefault();
      resetValuesFormStringObject(this.entrepriseToEdit);
      this.isFormValid = false;
      this.isFormEmpty = true;
    },
  },
};
</script>
