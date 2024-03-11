<template>
  <div class="p-1 p-md-3">
    <div v-if="this.$parent.$options.name !== 'Widget'">
      <div class="row justify-content-center text-center p-1">
        <h1>Livrables</h1>
      </div>
    </div>
    <div class="row">
      <div class="row m-0 mb-3">
        <button
          class="btn btn-primary"
          v-if="this.$parent.$options.name !== 'Widget'"
          v-on:click="addLivrable()"
        >
          + Ajouter un livrable
        </button>
      </div>

      <div
        :class="
          this.$parent.$options.name == 'Widget'
            ? 'case'
            : 'case rounded shadow border p-1 p-md-3'
        "
      >
        <preloader class="my-5" v-if="!requestDone"></preloader>
        <p v-if="requestDone && livrables.length == 0" class="text-center">
          Vous n'avez aucun livrable renseigné
        </p>

        <table class="table table-striped" v-if="livrables.length > 0">
          <thead>
            <tr>
              <th class="text-center" scope="col"></th>
              <th class="text-center" scope="col">Nom</th>
              <th class="text-center" scope="col">Modifié le</th>
              <th
                class="text-center"
                v-if="this.$parent.$options.name !== 'Widget'"
                scope="col"
              >
                Auteur
              </th>
              <th class="text-center" scope="col">Statut</th>
              <th
                class="text-center"
                v-if="this.$parent.$options.name !== 'Widget'"
                scope="col"
              >
                Événement
              </th>
              <th class="text-center" scope="col"></th>
              <th
                class="text-center"
                scope="col"
                v-if="this.$parent.$options.name !== 'Widget'"
              ></th>
              <th
                class="text-center"
                scope="col"
                v-if="this.$parent.$options.name !== 'Widget'"
              ></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(livrable, index) in livrables" v-bind:key="index">
              <td class="text-center" scope="row">
                <i class="fa-solid fa-file-pdf text-danger h4"></i>
              </td>
              <td class="text-center">{{ livrable.libelle }}</td>
              <td class="text-center">
                {{ new Date(livrable.modifieLe).toISOString().slice(0, 10) }}
              </td>
              <td
                class="text-center"
                v-if="this.$parent.$options.name !== 'Widget'"
              >
                {{ livrable.modifiePar }}
              </td>
              <td class="text-center">{{ livrable.statut }}</td>
              <td
                class="text-center"
                v-if="this.$parent.$options.name !== 'Widget'"
              >
                {{ livrable.event.nom }}
              </td>
              <td>
                <a href="#">
                  <i
                    v-on:click="downloadLivrable(livrable)"
                    class="fa fa-download d-flex justify-content-center h5 text-dark"
                    aria-hidden="true"
                  ></i>
                </a>
              </td>
              <td v-if="this.$parent.$options.name !== 'Widget'">
                <a href="#">
                  <i
                    v-on:click="getLivrableById(livrable.id)"
                    class="fa fa-pencil d-flex justify-content-center h5 text-dark"
                    aria-hidden="true"
                  ></i>
                </a>
              </td>
              <td v-if="this.$parent.$options.name !== 'Widget'">
                <a href="#">
                  <i
                    v-on:click="openRemoveLivrableModal(livrable)"
                    class="fa fa-trash d-flex justify-content-center h5 text-dark"
                    aria-hidden="true"
                  ></i>
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="modalCreateLivrable"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalCreateLivrableLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <AjoutLivrable
          v-bind:action="modalAction"
          @getLivrables="getLivrables"
          v-bind:livrableToEdit="livrableToEdit"
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
          :callbackConfirm="confirmRemoveLivrable"
          :name="livrableToRemove.libelle"
        />
      </div>
    </div>
  </div>
</template>

<script>
import AjoutLivrable from "@/components/apprenti/ModalAjoutLivrable";
import ModalRemove from "@/components/utils/ModalRemove";
import { API_BASE_URL, API_Helper } from "@/api/helper_api";
import { createToast } from "mosha-vue-toastify";
import Preloader from "@/components/utils/preLoader";
import axios from "axios";

export default {
  name: "Livrables",
  components: { AjoutLivrable, Preloader, ModalRemove },
  data() {
    return {
      livrables: [],
      livrableToEdit: {
        modifieLe: "",
        modifiePar: "",
        statut: "",
        libelle: "",
        idEvent: {},
      },
      livrableToRemove: {},
      modalAction: "add",
      requestDone: false,
    };
  },
  methods: {
    addLivrable() {
      this.modalAction = "add";
      this.livrableToEdit = {
        modifieLe: "",
        modifiePar: "",
        statut: "",
        libelle: "",
        idEvent: {},
      };
      $("#modalCreateLivrable").modal("show");
    },

    async getLivrables() {
      this.requestDone = false;
      this.livrables = [];
      await API_Helper.get("/evaluation/livrables/" + this.$store.state.id)
        .then((response) => {
          this.livrables = response.data;
        })
        .catch((e) => {});
      if (this.$parent.$options.name == "Widget") {
        this.livrables = this.livrables.slice(0, 5);
      }
      this.requestDone = true;
    },

    getLivrableById(id) {
      this.livrableToEdit = {};
      API_Helper.get("/evaluation/" + id)
        .then((response) => {
          this.livrableToEdit = response.data;
          this.livrableToEdit.idEvent = this.livrableToEdit.event.id;
          this.modalAction = "edit";
          $("#modalCreateLivrable").modal("show");
        })
        .catch((e) => {});
    },

    openRemoveLivrableModal(livrable) {
      this.livrableToRemove = livrable;
      $("#modalRemove").modal("show");
    },

    confirmRemoveLivrable() {
      $("#modalRemove").modal("hide");
      this.removeLivrableById(this.livrableToRemove);
    },

    async removeLivrableById(livrable) {
      await API_Helper.delete("/evaluation/remove/" + livrable.id)
        .then(async (response) => {
          createToast("Le livrable a bien été supprimé.", { type: "success" });
          this.getLivrables();
        })

        .catch((e) => {
          createToast("Le livrable n'a pas pu être supprimé.", {
            type: "danger",
          });
        });
    },

    async downloadLivrable(livrable) {
      await axios({
        url: API_BASE_URL + "/document/download/" + livrable.document.id,
        method: "GET",
        responseType: "blob",
      }).then((response) => {
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
        var fileLink = document.createElement("a");

        fileLink.href = fileURL;
        fileLink.setAttribute("download", livrable.document.name);
        document.body.appendChild(fileLink);

        fileLink.click();
      });
    },
  },

  async mounted() {
    this.requestDone = false;
    await this.getLivrables();
  },
};
</script>

<style scoped></style>
