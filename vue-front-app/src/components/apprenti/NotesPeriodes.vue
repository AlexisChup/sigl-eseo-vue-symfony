<template>
  <div class="p-1 p-md-3">
    <div v-if="this.$parent.$options.name !== 'Widget'">
      <div class="row justify-content-center text-center p-1">
        <h1>Bloc-notes</h1>
      </div>
    </div>

    <div class="row">
      <div class="row m-0 mb-3">
        <button
          class="btn btn-primary"
          v-if="this.$parent.$options.name !== 'Widget'"
          v-on:click="addNote()"
        >
          + Ajouter une note
        </button>
      </div>
      <div
        :class="
          this.$parent.$options.name == 'Widget'
            ? 'case'
            : 'case rounded shadow border p-1 p-md-3'
        "
      >
        <div class="row d-flex justify-content-around">
          <preloader class="my-5" v-if="!requestDone"></preloader>
          <p v-if="requestDone && listeNotes.length == 0">
            Vous n'avez aucune note renseignée
          </p>

          <ul
            class="nav nav-pills my-2"
            v-if="
              requestDone &&
              listeNotes.length > 0 &&
              this.$parent.$options.name !== 'Widget'
            "
          >
            <li class="nav-item">
              <a
                :class="0 === semestreActive ? 'nav-link active' : 'nav-link'"
                href="#"
                v-on:click="displayOnlySemestre(0)"
                >Tous</a
              >
            </li>
            <li
              v-for="semestre in semestres"
              class="nav-item mx-2"
              v-bind:key="semestre.id"
            >
              <a
                :class="
                  semestre.value === semestreActive
                    ? 'nav-link active'
                    : 'nav-link'
                "
                href="#"
                v-on:click="displayOnlySemestre(semestre.value)"
                >{{ semestre.nom }}</a
              >
            </li>
          </ul>

          <template v-for="(note, index) in listeNotes">
            <div
              :class="
                this.$parent.$options.name == 'Widget' ? 'col-10' : 'col-5'
              "
              class="card bg-light my-3 p-0"
              id="card_note"
              v-on:click="editNote(note.id)"
              v-if="note.semestre == semestreActive || semestreActive == 0"
              v-bind:key="index"
            >
              <div class="card-header">
                <h5 class="card-title mb-0">
                  <strong>{{ note.titre }}</strong>
                </h5>
                <i class="bi bi-x"></i>
              </div>
              <div
                v-if="this.$parent.$options.name == 'Widget'"
                class="card-body d-flex p-0 m-0 justify-content-center"
              >
                <p class="text-center p-0 m-0">Semestre {{ note.semestre }}</p>
              </div>
              <div
                v-if="this.$parent.$options.name !== 'Widget'"
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
                    {{ note.date }}
                  </span>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="modalCreateNote"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalCreateNoteLabel"
      aria-hidden="true"
      @keydown.esc="closeModal"
    >
      <div class="modal-dialog" role="document">
        <CreationNotePeriode
          v-bind:action="modalAction"
          @getNotesPeriode="getNotesPeriode"
          v-bind:noteToEdit="noteToEdit"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import CreationNotePeriode from "@/components/apprenti/ModalNotePeriode";
import { createToast } from "mosha-vue-toastify";
import Preloader from "@/components/utils/preLoader";
import { semestres } from "@/static/const";

export default {
  name: "NotesPeriodes",
  components: { CreationNotePeriode, Preloader },

  data() {
    return {
      listeNotes: [],
      noteToEdit: {
        titre: "",
        contenu: "",
        semestre: "",
      },
      modalAction: "add",
      requestDone: false,
      semestres: semestres,
      semestreActive: 0,
    };
  },

  methods: {
    addNote() {
      this.modalAction = "add";
      this.noteToEdit = {
        titre: "",
        contenu: "",
        semestre: "",
      };
      $("#modalCreateNote").modal("show");
    },

    async editNote(index) {
      this.noteToEdit = await API_Helper.get("/notes-periode/" + index)
        .then((response) => {
          return response.data;
        })
        .catch((err) => {
          createToast("Impossible de récupérer la note, Erreur serveur", {
            type: "danger",
          });
        });

      this.modalAction = "edit";
      $("#modalCreateNote").modal("show");
    },

    formatDateTime() {
      this.listeNotes.forEach((note) => {
        note.date = note.date.slice(0, 16).replace("T", " / "); // 2022-09-11 / 17:51
      });
    },

    async getNotesPeriode() {
      this.requestDone = false;
      this.listeNotes = [];

      this.listeNotes = await API_Helper.get(
        "/notes-periode/from/" + this.$store.state.id
      ).then((response) => {
        return response.data;
      });

      this.formatDateTime();
      if (this.$parent.$options.name == "Widget") {
        this.listeNotes = this.listeNotes.slice(0, 3);
      }
      this.requestDone = true;
    },

    closeModal() {
      this.noteToEdit = {
        titre: "",
        contenu: "",
      };
    },

    displayOnlySemestre(semestre) {
      this.semestreActive = +semestre;
    },
  },

  async mounted() {
    await this.getNotesPeriode();
  },
};
</script>

<style scoped>
#card_note {
  border-radius: 4px;
  background: #fff;
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.08), 0 0 6px rgba(0, 0, 0, 0.05);
  transition: 0.3s transform cubic-bezier(0.155, 1.105, 0.295, 1.12),
    0.3s box-shadow,
    0.3s -webkit-transform cubic-bezier(0.155, 1.105, 0.295, 1.12);
  padding: 14px 80px 18px 36px;
  cursor: pointer;
}

#card_note:hover {
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06);
}
</style>
