<template>
  <div class="p-1 p-md-3">
    <div class="row justify-content-center text-center p-1">
      <h1>Gestion des équipes tutorales</h1>
    </div>
    <div class="row">
      <div class="case rounded shadow border p-1 p-md-3">
        <PreLoader v-if="!requestDone" class="my-5"></PreLoader>
        <table v-if="requestDone" class="table">
          <thead>
            <tr>
              <th class="text-center" scope="col">Apprentis</th>
              <th class="text-center" scope="col">Maître d'apprentissage</th>
              <th class="text-center" scope="col">Tuteur</th>
              <th class="text-center" scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(apprenti, index) in apprentis" :key="index">
              <td class="text-center" scope="row">
                {{ apprenti.utilisateur.prenom }}
                <span style="text-transform: uppercase">{{
                  apprenti.utilisateur.nom
                }}</span>
              </td>
              <td class="text-center" scope="row">
                {{ apprenti.maitreApprentissage.utilisateur.prenom }}
                <span style="text-transform: uppercase">{{
                  apprenti.maitreApprentissage.utilisateur.nom
                }}</span>
              </td>
              <td class="text-center" scope="row">
                {{ apprenti.enseignant.utilisateur.prenom }}
                <span style="text-transform: uppercase">{{
                  apprenti.enseignant.utilisateur.nom
                }}</span>
              </td>
              <td>
                <i
                  v-on:click="editEquipeTutoraleById(apprenti.id)"
                  style="cursor: pointer"
                  class="fa fa-pencil d-flex justify-content-center"
                  aria-hidden="true"
                ></i>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div
      class="modal fade"
      id="ModalEditEquipeTutoral"
      tabindex="-1"
      role="dialog"
      aria-labelledby="ModalEditEquipeTutoralLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <ModalEquipeTutoral
          @getApprentisEquipe="getApprentisEquipe"
          v-bind:apprentiToEdit="apprentiToEdit"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import "mosha-vue-toastify/dist/style.css";
import ModalEquipeTutoral from "@/components/coordinateur/ModalEquipeTutoral";
import PreLoader from "@/components/utils/preLoader";

export default {
  name: "GestionTuteur",
  components: { ModalEquipeTutoral, PreLoader },
  data() {
    return {
      requestDone: false,
      apprentis: [],
      apprentiToEdit: {
        appId: 0,
        appNom: "",
        appPrenom: "",
        maId: 0,
        maNom: "",
        maPrenom: "",
        ensId: 0,
        ensNom: "",
        ensPrenom: "",
      },
    };
  },
  methods: {
    getApprentisEquipe() {
      this.requestDone = false;
      this.apprentis = [];
      API_Helper.get("/apprenti/apprenti")
        .then((response) => {
          this.apprentis = response.data;
        })
        .catch((e) => {
          console.log("erreur : ", e);
        });
      this.requestDone = true;
    },
    editEquipeTutoraleById(idToEdit) {
      this.apprentiToEdit = {};
      API_Helper.get("/apprenti/equipe/" + idToEdit)
        .then((response) => {
          this.apprentiToEdit.appId = response.data.id;
          this.apprentiToEdit.appPrenom = response.data.utilisateur.prenom;
          this.apprentiToEdit.appNom = response.data.utilisateur.nom;
          this.apprentiToEdit.ensId = response.data.enseignant.utilisateur.id;
          this.apprentiToEdit.ensPrenom =
            response.data.enseignant.utilisateur.prenom;
          this.apprentiToEdit.ensNom = response.data.enseignant.utilisateur.nom;
          this.apprentiToEdit.maId =
            response.data.maitreApprentissage.utilisateur.id;
          this.apprentiToEdit.maPrenom =
            response.data.maitreApprentissage.utilisateur.prenom;
          this.apprentiToEdit.maNom =
            response.data.maitreApprentissage.utilisateur.nom;
          this.modalAction = "edit";
          $("#ModalEditEquipeTutoral").modal("show");
        })
        .catch((e) => {});
    },
  },
  created() {
    this.getApprentisEquipe();
  },
  onBeforeUpdate() {},
};
</script>

<style scoped></style>
