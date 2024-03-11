<template>
  <div class="p-1 p-md-3">
    <div class="row justify-content-center text-center p-1">
      <h1>Entreprises</h1>
    </div>
    <div class="row">
      <div class="row m-0 mb-3">
        <button class="btn btn-primary" v-on:click="addEntreprise()">
          + Ajouter une entreprise
        </button>
      </div>
      <div class="case rounded shadow border p-1 p-md-3">
        <preloader class="my-5" v-if="!requestDone"></preloader>
        <p v-if="requestDone && entreprises.length == 0" class="text-center">
          Aucune entreprise renseignée.
        </p>
        <table class="table" v-if="entreprises.length > 0 && requestDone">
          <thead>
            <tr>
              <th class="text-center" scope="col">Nom</th>
              <th class="text-center" scope="col">Adresse</th>
              <th class="text-center" scope="col">Ville</th>
              <th class="text-center" scope="col">Code Postal</th>
              <th class="text-center" scope="col"></th>
              <th class="text-center" scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(entreprise, index) in entreprises" :key="index">
              <td class="text-center" scope="row">{{ entreprise.nom }}</td>
              <td class="text-center">{{ entreprise.adresse }}</td>
              <td class="text-center">{{ entreprise.ville }}</td>
              <td class="text-center">{{ entreprise.codePostal }}</td>
              <td>
                <i
                  v-on:click="getEntrepriseById(entreprise.id)"
                  style="cursor: pointer"
                  class="fa fa-pencil d-flex justify-content-center"
                  aria-hidden="true"
                ></i>
              </td>
              <td>
                <i
                  v-on:click="openRemoveEntrepriseModal(entreprise)"
                  style="cursor: pointer"
                  class="fa fa-trash d-flex justify-content-center"
                  aria-hidden="true"
                ></i>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="modalCreateEntreprise"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalCreateEntrepriseLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <InscriptionEntreprise
          v-bind:action="modalAction"
          @getEntreprises="getEntreprises"
          v-bind:entrepriseToEdit="entrepriseToEdit"
        />
      </div>
    </div>

    <div
      class="modal fade"
      id="modalRemove"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalRemoveLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <ModalRemove
          :callbackConfirm="confirmRemoveEntreprise"
          :name="entrepriseToRemove.nom"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import "mosha-vue-toastify/dist/style.css";
import InscriptionEntreprise from "@/components/respcursus/InscriptionEntreprise";
import { createToast } from "mosha-vue-toastify";
import Preloader from "@/components/utils/preLoader";
import ModalRemove from "../utils/ModalRemove";

export default {
  name: "GestionEntreprises",
  components: { InscriptionEntreprise, Preloader, ModalRemove },
  data() {
    return {
      entreprises: [],
      entrepriseToEdit: {
        nom: "",
        siret: "",
        adresse: "",
        ville: "",
        codePostal: "",
      },
      entrepriseToRemove: {},
      modalAction: "add",
      requestDone: false,
    };
  },
  methods: {
    addEntreprise() {
      this.modalAction = "add";
      this.entrepriseToEdit = {
        nom: "",
        siret: "",
        adresse: "",
        ville: "",
        codePostal: "",
      };

      $("#modalCreateEntreprise").modal("show");
    },
    async getEntreprises() {
      this.requestDone = false;
      this.entreprises = [];

      await API_Helper.get("/entreprise/entreprises")
        .then((response) => {
          this.entreprises = response.data;
        })

        .catch((e) => {});

      this.requestDone = true;
    },
    getEntrepriseById(id) {
      this.entrepriseToEdit = {};

      API_Helper.get("/entreprise/get/" + id)
        .then((response) => {
          this.entrepriseToEdit = response.data;
          this.modalAction = "edit";
          $("#modalCreateEntreprise").modal("show");
        })

        .catch((e) => {});
    },

    openRemoveEntrepriseModal(entreprise) {
      this.entrepriseToRemove = entreprise;
      $("#modalRemove").modal("show");
    },

    confirmRemoveEntreprise() {
      $("#modalRemove").modal("hide");
      this.removeEntrepriseById(this.entrepriseToRemove.id);
    },

    removeEntrepriseById(idToRemove) {
      API_Helper.delete("/entreprise/remove/" + idToRemove)
        .then((response) => {
          createToast(
            "L'entreprise " +
              this.entreprises.find(({ id }) => id === idToRemove).nom +
              " a bien été supprimée.",
            { type: "warning" }
          );
          this.getEntreprises();
        })

        .catch((e) => {});
    },
  },
  async created() {
    await this.getEntreprises();
  },
};
</script>

<style scoped></style>
