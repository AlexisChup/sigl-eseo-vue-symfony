<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5
        v-if="action === 'edit'"
        class="modal-title"
        id="modalCreateLivrableLabel"
      >
        Modifier les missions de
        {{ apprenti.utilisateur.prenom + " " + apprenti.utilisateur.nom }}
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
      <p><strong>Entreprise : </strong></p>
      <p>{{ apprenti.maitreApprentissage.entreprise.nom }}</p>

      <p><strong>Missions : </strong></p>
      <p>{{ apprenti.mission }}</p>
      <div class="container">
        <form v-on:input="formValidation">
          <div class="form-group">
            <label class="col-form-label-sm mb-0" for="statut" Name="statut"
              >Statut</label
            >
            <select
              class="form-control"
              Name="statut"
              v-model="apprenti.mission_valide"
              v-on:change="updateForm"
              required
            >
              <option value="" selected disabled>
                Validation des missions
              </option>
              <option value="true">Missions validées</option>
              <option value="false">Missions non validées</option>
            </select>
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
import consts from "@/static/const";

export default {
  name: "ModalMission",
  props: {
    action: String,
    apprenti: Object,
  },
  created() {},
  data() {
    return {
      isFormValid: this.apprenti.id ? true : false,
      isFormEmpty: true,
    };
  },
  updated() {},
  methods: {
    async formSubmit(e) {
      e.preventDefault();
      let missions = {
        missions: {
          contenu_mission: this.apprenti.mission,
          valide: this.apprenti.mission_valide,
        },
      };
      await API_Helper.put(
        "/apprenti/update/mission/" + this.apprenti.id,
        missions
      )
        .then((response) => {
          createToast(
            "Les missions de " +
              this.apprenti.utilisateur.prenom +
              " ont bien été modifiées.",
            { type: "success" }
          );
          this.isFormValid = false;
          this.isFormEmpty = true;
          this.$emit("getUsers");
          $("#ModalMission").modal("hide");
        })
        .catch((e) => {
          createToast(
            "Les missions de " +
              this.apprenti.utilisateur.prenom +
              " n'ont pas pu être modifiées.",
            { type: "danger" }
          );
        });
      this.afterAction();
    },
    formValidation() {
      this.updateForm();
    },

    resetValue(e) {
      e.preventDefault();
      this.isFormValid = false;
      this.isFormEmpty = true;
    },

    afterAction() {
      this.$emit("getMissionsValides");
      $("#ModalMission").modal("hide");
    },

    updateForm() {
      this.isFormValid = this.apprenti.mission_valide != null;
    },
  },
};
</script>
