<template>
  <div class="p-1 p-md-3">
    <div v-if="this.$parent.$options.name !== 'Widget'">
      <div class="row justify-content-center text-center p-1">
        <h1>Documents pédagogiques</h1>
      </div>
    </div>
    <div class="row">
      <div
        class="row m-0 mb-3"
        v-if="
          this.$store.state.perm !== 'APPRENTI' &&
          this.$store.state.perm !== 'ENSEIGNANT' &&
          this.$store.state.perm !== 'MAITRE-APPRENTISSAGE'
        "
      >
        <button
          class="btn btn-primary"
          v-if="this.$parent.$options.name !== 'Widget'"
          v-on:click="openPublishModal()"
        >
          + Ajouter un document
        </button>
      </div>
      <div
        :class="
          this.$parent.$options.name == 'Widget'
            ? 'case'
            : 'case rounded shadow border p-1 p-md-3'
        "
      >
        <p class="text-center" v-if="requestDone && docsPromo.length == 0">
          Aucun document n'a été déposé
        </p>
        <preloader class="my-5" v-if="!requestDone"></preloader>
        <table v-if:="docsPromo.length > 0" class="table table-striped">
          <thead>
            <tr>
              <th class="text-center" scope="col"></th>
              <th class="text-center" scope="col">Nom</th>
              <th
                class="text-center"
                v-if="this.$parent.$options.name !== 'Widget'"
                scope="col"
              >
                Modifié le
              </th>
              <th
                class="text-center"
                v-if="this.$parent.$options.name !== 'Widget'"
                scope="col"
              >
                Auteur
              </th>
              <th class="text-center" scope="col"></th>
              <th
                class="text-center"
                v-if="this.$store.state.perm != 'APPRENTI'"
                scope="col"
              ></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(doc, index) in docsPromo" v-bind:key="index">
              <td class="text-center" scope="row">
                <i class="fa-solid fa-file-pdf text-danger h4"></i>
              </td>
              <td class="text-center">{{ doc.name }}</td>
              <td
                class="text-center"
                v-if="this.$parent.$options.name !== 'Widget'"
              >
                {{ doc.date.slice(0, 10) }}
              </td>
              <td
                class="text-center"
                v-if="this.$parent.$options.name !== 'Widget'"
              >
                {{ doc.utilisateur.nom }}
                {{ doc.utilisateur.prenom }}
              </td>
              <td class="text-center">
                <a href="#">
                  <i
                    class="fa-solid fa-download text-dark h4"
                    v-on:click="downloadDoc(doc)"
                  ></i>
                </a>
              </td>
              <td
                class="text-center"
                v-if="this.$store.state.perm != 'APPRENTI'"
              >
                <a href="#">
                  <i
                    class="fa-solid fa-trash text-dark h4"
                    v-on:click="openRemoveDocModal(doc)"
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
      id="modalPublishDocPedago"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalPublishDocPedagoLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <ModalPublishDocPedago
          @getDocumentFromPromo="getDocumentFromPromo(this.userIdPromo)"
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
          :callbackConfirm="confirmRemoveDoc"
          :name="docToRemove.name"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { API_BASE_URL, API_Helper } from "@/api/helper_api";
import axios from "axios";
import ModalPublishDocPedago from "@/components/coordinateur/ModalPublishDocPedago";
import Preloader from "@/components/utils/preLoader";
import ModalRemove from "../utils/ModalRemove";
import { createToast } from "mosha-vue-toastify";

export default {
  name: "DocsPedagos",

  components: {
    Preloader,
    ModalPublishDocPedago,
    ModalRemove,
  },

  data() {
    return {
      docsPromo: [],
      listePormo: [],
      userIdPromo: null,
      user: {},
      requestDone: false,
      docToRemove: {},
    };
  },

  async mounted() {
    await this.loadDocs();
  },

  methods: {
    async loadDocs() {
      this.requestDone = false;
      if (this.$store.state.perm == "APPRENTI") {
        await this.getPromoFromApprenti();

        if (this.userIdPromo != null) {
          await this.getDocumentFromPromo(this.userIdPromo);
        }
      } else {
        await this.getDocumentFromPromo(null);
      }
      this.requestDone = true;
    },

    openPublishModal() {
      $("#modalPublishDocPedago").modal("show");
    },

    async getListePromos() {
      this.liste_promo = await API_Helper.get("/promotion/promotions").then(
        (response) => {
          return response.data;
        }
      );
    },

    async getPromoFromApprenti() {
      this.user = await API_Helper.get(
        "/utilisateur/" + this.$store.state.id + "/role"
      ).then((response) => {
        return response.data.data[0];
      });
      this.userIdPromo = this.user.idPromotion.id;
    },

    async getDocumentFromPromo(idPromo) {
      if (this.$store.state.perm == "APPRENTI") {
        this.docsPromo = await API_Helper.get("/document/pedago").then(
          (response) => {
            return response.data;
          }
        );
      } else {
        this.docsPromo = await API_Helper.get("/document/pedago").then(
          (response) => {
            return response.data;
          }
        );
      }

      this.requestDone = true;
    },

    async downloadDoc(item) {
      await axios({
        url: API_BASE_URL + "/document/download/" + item.id,
        method: "GET",
        responseType: "blob",
      }).then((response) => {
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));
        var fileLink = document.createElement("a");

        fileLink.href = fileURL;
        fileLink.setAttribute("download", item.name);
        document.body.appendChild(fileLink);

        fileLink.click();
      });
    },

    openRemoveDocModal(doc) {
      this.docToRemove = doc;
      $("#modalRemove").modal("show");
    },

    confirmRemoveDoc() {
      $("#modalRemove").modal("hide");
      this.deleteDoc(this.docToRemove);
    },

    async deleteDoc(item) {
      await API_Helper.delete("/document/delete/" + item.id)
        .then((response) => {
          createToast(`Le document : ${item.name} a été supprimé.`, {
            type: "success",
          });
        })
        .catch((err) => {
          createToast(`Le document : ${item.name} n'a pas été supprimé.`, {
            type: "danger",
          });
        });

      await this.loadDocs();
    },
  },
};
</script>

<style scoped></style>
