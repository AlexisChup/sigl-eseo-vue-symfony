<template>
  <div class="p-1 p-md-3">
    <div class="row justify-content-center text-center p-1">
      <h1>Évènements</h1>
    </div>
    <div class="row">
      <div class="row m-0 mb-3">
        <button
          class="btn btn-primary"
          v-on:click="this.$router.push('/evenements/creation')"
        >
          + Ajouter un évènement
        </button>
      </div>

      <div class="case rounded shadow border p-1 p-md-3">
        <preloader class="my-5" v-if="!requestDone"></preloader>
        <p
          v-if="requestDone && listeEvenements.length == 0"
          class="text-center"
        >
          Aucun évènement n'a été renseigné.
        </p>

        <div class="row justify-content-sm-center justify-content-md-start">
          <Widget
            v-for="(evenement, index) in listeEvenements"
            :key="index"
            id="eventCard"
            class="col-lg-4 col-md-6 col-sm-10"
          >
            <div class="d-flex justify-content-between mb-3">
              <span
                class="py-1 px-2 rounded-pill"
                :style="colorEvent[evenement.type]"
                >{{ strEvent[evenement.type] }}</span
              >
              <span
                >{{ evenement.promotion.nom }} -
                {{ evenement.promotion.annee }}</span
              >
            </div>
            <div class="text-center">
              <strong>{{ evenement.nom }}</strong>
            </div>
            <div class="text-center" v-if="evenement.dateDebut">
              Début : {{ evenement.dateDebut.slice(0, 10) }}
            </div>
            <div class="text-center">
              Fin : {{ evenement.dateFin.slice(0, 10) }}
            </div>
            <div class="d-flex justify-content-around mt-3">
              <span
                ><i
                  class="fa-solid fa-pencil"
                  v-on:click="
                    this.$router.push({
                      name: 'event.edit',
                      params: { id: evenement.id },
                    })
                  "
                  style="cursor: pointer"
                ></i
              ></span>
              <span
                ><i
                  class="fa-solid fa-trash"
                  v-on:click="openRemoveModal(evenement)"
                  style="cursor: pointer"
                ></i
              ></span>
            </div>
          </Widget>
        </div>
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
        <ModalRemove :callbackConfirm="confirmRemove" :name="evtToRemove.nom" />
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import preLoader from "@/components/utils/preLoader";
import Preloader from "@/components/utils/preLoader";
import ModalPublishDocPedago from "@/components/coordinateur/ModalPublishDocPedago";
import { strEvent, colorEvent } from "@/static/const";
import Widget from "@/components/Widget";
import { createToast } from "mosha-vue-toastify";
import ModalRemove from "@/components/utils/ModalRemove";

export default {
  name: "Evenements",

  components: {
    Preloader,
    Widget,
    ModalRemove,
  },

  data() {
    return {
      requestDone: false,
      listeEvenements: [],
      strEvent: strEvent,
      colorEvent: colorEvent,
      evtToRemove: {},
    };
  },

  async mounted() {
    await this.loadEvenements();
  },

  methods: {
    openRemoveModal(evt) {
      this.evtToRemove = evt;
      $("#modalRemove").modal("show");
    },

    confirmRemove() {
      $("#modalRemove").modal("hide");
      this.deleteEvt(this.evtToRemove);
    },

    async loadEvenements() {
      this.requestDone = false;
      this.listeEvenements = [];
      this.listeEvenements = await API_Helper.get("event/event").then(
        (response) => {
          return response.data;
        }
      );
      this.requestDone = true;
    },

    async deleteEvt(evt) {
      await API_Helper.delete(`/event/${evt.id}`)
        .then((res) => {
          this.loadEvenements();
          createToast("L'évènement a été supprimé.", { type: "success" });
        })
        .catch((res) => {
          createToast("Suppression impossible.", { type: "danger" });
        });
    },
  },
};
</script>

<style scoped>
@media (max-width: 768px) {
  #eventCard {
    width: 100%;
  }
}
</style>
