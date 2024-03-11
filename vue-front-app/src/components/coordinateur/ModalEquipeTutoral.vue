<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="ModalEditEquipeTutoralLabel">
        Modifier {{ apprentiToEdit.appPrenom + "  " + apprentiToEdit.appNom }}
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
            <label for="exampleFormControlSelect1"
              >Maitre d'apprentissage</label
            >
            <select class="form-control" v-model="this.EquipeToEdit.id_ma_id">
              <option
                v-for="(maitreApp, index) in listeMa"
                :key="index"
                v-bind:value="maitreApp.utilisateur.id"
              >
                {{
                  maitreApp.utilisateur.prenom + " " + maitreApp.utilisateur.nom
                }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label for="exampleFormControlSelect1">Enseignant</label>
            <select
              class="form-control"
              v-model="this.EquipeToEdit.id_enseignant_id"
            >
              <option
                v-for="(ens, index) in listeEnseignant"
                :key="index"
                v-bind:value="ens.utilisateur.id"
              >
                {{ ens.utilisateur.prenom + " " + ens.utilisateur.nom }}
              </option>
            </select>
          </div>
        </form>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">
        Fermer
      </button>
      <button class="btn btn-success ml-2" v-on:click="formSubmit">
        Modifier
      </button>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import { createToast } from "mosha-vue-toastify";
import {
  isFormStringEmpty,
  isFormStringValid,
  resetValuesFormStringObject,
} from "@/utils/form-utils";

export default {
  name: "ModalEquipeTutoral",
  props: {
    apprentiToEdit: Object,
  },
  data() {
    return {
      listeEnseignant: [],
      listeMa: [],
      EquipeToEdit: {
        id_ma_id: "",
        id_enseignant_id: "",
      },
      isFormEmpty: true,
    };
  },
  beforeMount() {
    this.setAppID();
    this.getAllMa();
    this.getAllEnseignant();
  },

  methods: {
    getAllMa() {
      API_Helper.get("/maitre-aprentissage/all")
        .then((response) => {
          this.listeMa = response.data;
        })
        .catch((e) => {
          console.log("erreur : ", e);
        });
    },
    getAllEnseignant() {
      API_Helper.get("/enseignant/all")
        .then((response) => {
          this.listeEnseignant = response.data;
        })
        .catch((e) => {
          console.log("erreur : ", e);
        });
    },
    setAppID() {
      this.EquipeToEdit.appId = this.apprentiToEdit.appId;
    },
    formSubmit(e) {
      e.preventDefault();
      API_Helper.post(
        "/apprenti/update/EquipeTutorale/" + this.apprentiToEdit.appId,
        this.EquipeToEdit
      )
        .then((response) => {
          createToast(
            "L'utilisateur " +
              this.apprentiToEdit.appPrenom +
              "  " +
              this.apprentiToEdit.appNom +
              " a bien été modifiée.",
            { type: "success" }
          );
          resetValuesFormStringObject(this.EquipeToEdit);
          this.isFormValid = false;
          this.isFormEmpty = true;
          this.$emit("getApprentisEquipe");
          $("#ModalEditEquipeTutoral").modal("hide");
        })

        .catch((e) => {
          createToast(
            "L'utilisateur " +
              this.apprentiToEdit.appPrenom +
              " n'a pas pu être modifiée.",
            { type: "danger" }
          );
        });
    },
  },
};
</script>

<style scoped></style>
