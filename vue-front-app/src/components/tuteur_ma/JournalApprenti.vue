<template>
  <div>
    <div v-if="app.id">
      <h1 class="text-center">Journal de formation</h1>
      <h3 class="text-center">
        {{ this.app.utilisateur.prenom }} {{ this.app.utilisateur.nom }}
      </h3>
    </div>

    <div
      v-if="!callLivrablesDone || !callNotesDone || !callLivrablesDone"
      class="case rounded shadow border p-1 p-md-3"
    >
      <preloader class="my-5"></preloader>
    </div>

    <div
      class="top-container"
      v-if="callLivrablesDone && callNotesDone && callLivrablesDone"
    >
      <div
        v-if="app.id && livrables.length > 0"
        class="case rounded shadow border pt-1 p-md-3"
      >
        <div v-if="callLivrablesDone" class="">
          <h2 class="text-center">Livrables</h2>
          <div class="livrable-container">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="text-center" scope="col"></th>
                  <th class="text-center" scope="col">Nom</th>
                  <th class="text-center" scope="col">Modifié le</th>
                  <th class="text-center" scope="col">Statut</th>
                  <th class="text-center" scope="col">Événement</th>
                  <th class="text-center" scope="col"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(livrable, index) of livrables" :key="index">
                  <td class="text-center" scope="row">
                    <i class="fa-solid fa-file-pdf text-danger h4"></i>
                  </td>
                  <td class="text-center">{{ livrable.libelle }}</td>
                  <td class="text-center">
                    {{
                      new Date(livrable.modifieLe).toISOString().slice(0, 10)
                    }}
                  </td>
                  <td class="text-center">{{ livrable.statut }}</td>
                  <td class="text-center">{{ livrable.event.nom }}</td>
                  <td>
                    <a href="#">
                      <i
                        v-on:click="downloadLivrable(livrable)"
                        class="fa fa-download d-flex justify-content-center h5 text-dark"
                        aria-hidden="true"
                      ></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div
        v-if="app.id && notes.length > 0"
        class="case rounded shadow border p-1 p-md-3 mt-3"
      >
        <div v-if="callNotesDone">
          <h2 class="text-center">Notes périodiques</h2>
          <div class="notes-container row d-flex justify-content-center">
            <!-- eslint-disable -->
            <template v-for="(note, index) in notes">
              <div
                class="card bg-light my-3 col-lg-3 col-md-5 col-sm-10 m-2 p-0"
                id="card_note"
                v-on:click="displayNoteContent(note)"
              >
                <div class="card-header">
                  <h5 class="card-title mb-0">
                    <strong>{{ note.titre }}</strong>
                  </h5>
                  <i class="bi bi-x"></i>
                </div>
                <div
                  class="card-body d-flex flex-column justify-content-between"
                >
                  <p class="m-0">{{ note.contenu.slice(0, 100) }}</p>
                  <div class="d-flex flex-row justify-content-between mt-3">
                    <span class="m-0 d-flex justify-content-end start">
                      Semestre {{ note.semestre }}
                    </span>
                    <span
                      class="m-0 d-flex justify-content-end align-content-end"
                    >
                      {{ note.date.slice(0, 10) }}
                    </span>
                  </div>
                </div>
              </div>
            </template>
            <!-- eslint-enable -->
          </div>
        </div>
      </div>

      <div v-if="app.id" class="case rounded shadow border p-1 p-md-3 mt-3">
        <div v-if="callCalendrierDone">
          <h2 class="text-center">Calendrier</h2>
          <div class="calendrier-container">
            <Widget class="col-12">
              <Calendrier v-bind:apprenti="this.app" />
            </Widget>
          </div>
        </div>
      </div>
      <!-- Modal -->
      <div
        class="modal fade"
        id="modalNoteDisplay"
        tabindex="-1"
        role="dialog"
        aria-labelledby="modalNoteDisplayLabel"
        aria-hidden="true"
      >
        <div class="modal-dialog" role="document">
          <ModalNoteDisplay v-bind:note="this.noteToDisplay" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { API_BASE_URL, API_Helper } from "@/api/helper_api";
import Widget from "@/components/Widget";
import Calendrier from "@/components/apprenti/Calendrier";
import Preloader from "@/components/utils/preLoader";
import ModalNoteDisplay from "@/components/apprenti/ModalNoteDisplay";
import axios from "axios";

export default {
  name: "JournalApprenti",
  components: { Preloader, Calendrier, Widget, ModalNoteDisplay },

  data() {
    return {
      app: {},
      livrables: [],
      callLivrablesDone: false,
      notes: [],
      callNotesDone: false,
      calendrier: {},
      callCalendrierDone: false,
      noteToDisplay: {},
    };
  },

  async mounted() {
    this.app = await API_Helper.get(`/apprenti/${this.$route.params.id}`).then(
      (res) => res.data
    );

    if (
      this.app.maitreApprentissage.utilisateur.id !== this.$store.state.id &&
      this.app.enseignant.utilisateur.id !== this.$store.state.id
    ) {
      this.$router.push("/mes-apprentis");
    }

    this.livrables = await API_Helper.get(
      `/evaluation/livrables/${this.app.utilisateur.id}`
    ).then((res) => res.data);
    this.callLivrablesDone = true;

    this.notes = await API_Helper.get(
      `/notes-periode/from/${this.app.utilisateur.id}`
    ).then((res) => res.data);
    this.callNotesDone = true;

    this.callCalendrierDone = true;
  },

  methods: {
    displayNoteContent(note) {
      this.noteToDisplay = note;
      $("#modalNoteDisplay").modal("show");
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
};
</script>

<style scoped></style>
