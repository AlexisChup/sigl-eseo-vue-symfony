<template>
  <div class="modal-content" id="modal-note-periode">
    <div class="modal-header">
      <h5 v-if="action === 'add'" class="modal-title" id="modalCreateNoteLabel">
        Ajouter une note périodique
      </h5>
      <h5
        v-if="action === 'edit'"
        class="modal-title"
        id="modalCreateNoteLabel"
      >
        Modifier une note périodique
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
          <label class="col-form-label-sm mb-0" for="titre">Titre</label>
          <input
            type="text"
            id="titre"
            class="form-control form-control-sm"
            v-model="noteToEdit.titre"
          />

          <label class="col-form-label-sm mb-0" for="contenu">Contenu</label>
          <textarea
            name="contenu"
            cols="30"
            rows="10"
            type="textfield"
            id="contenu"
            class="form-control form-control-sm"
            v-model="noteToEdit.contenu"
          ></textarea>

          <label class="col-form-label-sm mb-0" for="contenu"
            >Choix du semestre</label
          >
          <select
            class="form-control"
            name="semestre"
            id="semestre"
            v-model="noteToEdit.semestre"
          >
            <option
              v-for="(semestre, index) in semestres"
              v-bind:value="semestre.value"
              v-bind:key="index"
            >
              {{ semestre.nom }}
            </option>
          </select>
        </form>
      </div>
    </div>
    <div class="modal-footer">
      <div v-if="action === 'add'">
        <button
          class="btn btn-secondary"
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
        <button class="btn btn-danger" v-on:click="openRemoveNoteModal">
          Supprimer
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
          :callbackConfirm="confirmRemoveNote"
          :name="noteToEdit.titre"
        />
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
import ModalRemove from "../utils/ModalRemove";
import "mosha-vue-toastify/dist/style.css";
import { semestres } from "@/static/const";

export default {
  name: "CreationNotePeriode",
  components: { ModalRemove },
  props: {
    action: String,
    noteToEdit: Object,
  },
  mounted() {},
  data() {
    return {
      initialNote: {},
      isFormValid: false,
      isFormEmpty: true,
      semestres: semestres,
    };
  },
  methods: {
    formSubmit(e) {
      e.preventDefault();

      this.noteToEdit.user_id = this.$store.state.id;

      if (this.action === "add") {
        API_Helper.post("/notes-periode/create", this.noteToEdit)
          .then((response) => {
            createToast("La note a bien été ajoutée.", { type: "success" });
            this.afterAction();
          })
          .catch((e) => {
            createToast("Le note n'a pas pu être ajoutée.", { type: "danger" });
          });
      } else if (this.action === "edit") {
        API_Helper.put(
          "/notes-periode/update/" + this.noteToEdit.id,
          this.noteToEdit
        )
          .then((response) => {
            createToast("La note a bien été modifiée.", { type: "success" });
            this.afterAction();
          })
          .catch((e) => {
            createToast("La note n'a pas pu être modifiée.", {
              type: "danger",
            });
          });
      }
    },

    formValidation() {
      let noteTemp = {};
      noteTemp["titre"] = this.noteToEdit.titre;
      noteTemp["contenu"] = this.noteToEdit.contenu;

      this.isFormValid = isFormStringValid(noteTemp);
      this.isFormValid &= this.noteToEdit.semestres !== "";
      this.isFormEmpty = isFormStringEmpty(noteTemp);
    },

    resetValue(e) {
      e.preventDefault();
      resetValuesFormStringObject(this.noteToEdit);
      this.isFormValid = false;
      this.isFormEmpty = true;
    },

    openRemoveNoteModal() {
      $("#modalRemove").modal("show");
    },

    confirmRemoveNote() {
      $("#modalRemove").modal("hide");
      this.deleteNote();
    },

    deleteNote() {
      API_Helper.delete("/notes-periode/delete/" + this.noteToEdit.id)
        .then((res) => {
          this.afterAction();
          createToast("La note a bien été supprimée.", { type: "success" });
        })
        .catch((e) => {
          createToast("La note n'a pas pu être supprimée.", { type: "danger" });
        });
    },

    afterAction() {
      this.$emit("getNotesPeriode");
      $("#modalCreateNote").modal("hide");
      resetValuesFormStringObject(this.noteToEdit);
      this.isFormValid = false;
      this.isFormEmpty = true;
    },
  },
};
</script>
