<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5
        v-if="action === 'add'"
        class="modal-title"
        id="modalCreateLivrableLabel"
      >
        Ajouter une livrable
      </h5>
      <h5
        v-if="action === 'edit'"
        class="modal-title"
        id="modalCreateLivrableLabel"
      >
        Modifier {{ livrableToEdit.libelle }}
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
          <label class="col-form-label-sm mb-0" for="titre">Libelle</label>
          <input
            type="text"
            id="titre"
            class="form-control form-control-sm"
            v-model="livrableToEdit.libelle"
            v-on:change="checkForm"
          />

          <div class="form-group">
            <label class="col-form-label-sm mb-0" for="libelle" Name="libelle"
              >Livrable</label
            >
            <select
              class="form-control"
              Name="libelle"
              required
              v-model="livrableToEdit.idEvent"
              v-on:change="checkForm"
            >
              <option value="" selected disabled>
                Sélectionnez un livrable
              </option>
              <option
                v-for="(event, index) in livrableEventList"
                :value="event.id"
                :key="index"
              >
                {{ event.nom }}
              </option>
            </select>
          </div>

          <div class="form-group">
            <label class="col-form-label-sm mb-0" for="statut" Name="statut"
              >Statut</label
            >
            <select
              class="form-control"
              Name="statut"
              v-model="livrableToEdit.statut"
              required
              v-on:change="checkForm"
            >
              <option value="" selected disabled>Sélectionnez un état</option>
              <option value="Brouillon">Brouillon</option>
              <option value="Déposé">Déposé</option>
              <option value="Validé">Validé</option>
            </select>
          </div>

          <div class="form-row" v-if="action === 'add'">
            <label class="col-form-label-sm mb-0" for="doc" Name="doc"
              >Importer un fichier</label
            >
            <div
              class="flex w-full h-screen items-center justify-center text-center"
            >
              <div
                class="p-12 bg-gray-100 border border-gray-300"
                @dragover="dragover"
                @dragleave="dragleave"
                @drop="drop"
              >
                <input
                  type="file"
                  multiple
                  name="fields[assetsFieldHandle][]"
                  id="assetsFieldHandle"
                  style="display: none"
                  @change="onChange"
                  ref="file"
                  accept=".pdf"
                />

                <label for="assetsFieldHandle" class="block cursor-pointer">
                  <br /><br />
                  <div>
                    Glissez-ici votre livrable ou cliquez-ici dans la zone pour
                    choisir un fichier.
                  </div>
                  <br />
                  <img
                    src="../../assets/download.png"
                    width="40"
                    height="45"
                    alt=""
                  />
                </label>
                <ul class="mt-4" v-if="this.filelist.length" v-cloak>
                  <li
                    class="text-sm p-1"
                    v-for="(file, index) in filelist"
                    v-bind:key="index"
                  >
                    {{ file.name }}
                    <button
                      class="btn btn-danger ml-2"
                      type="button"
                      @click="remove(filelist.indexOf(file))"
                      title="Remove file"
                    >
                      x
                    </button>
                  </li>
                </ul>
                <br /><br />
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">
        Fermer
      </button>
      <div v-if="action === 'add'">
        <button
          class="btn btn-success ml-2"
          v-on:click="formSubmit"
          :disabled="!formValid"
        >
          Ajouter
        </button>
      </div>
      <div v-if="action === 'edit'">
        <button
          class="btn btn-success ml-2"
          v-on:click="formSubmit"
          :disabled="!formValid"
        >
          Modifier
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { API_BASE_URL, API_Helper } from "@/api/helper_api";
import { createToast } from "mosha-vue-toastify";
import {
  resetValuesFormStringObject,
  isFormStringValid,
  isFormStringEmpty,
} from "@/utils/form-utils";
import "mosha-vue-toastify/dist/style.css";
import axios from "axios";
import consts from "@/static/const";

export default {
  name: "AjoutLivrable",
  props: {
    action: String,
    livrableToEdit: Object,
  },
  async beforeMount() {
    await this.getEventLivrable();
  },
  data() {
    return {
      formValid: false,
      livrableEventList: [],
      filelist: [], // Store our uploaded files
      livrable: {
        modifie_le: "",
        modifie_par: "",
        statut: "",
        libelle: "",
        idEvent: "",
      },
    };
  },

  methods: {
    onChange() {
      this.filelist = [...this.$refs.file.files];
      this.checkForm();
    },

    remove(i) {
      this.filelist.splice(i, 1);
      this.checkForm();
    },

    dragover(event) {
      event.preventDefault();
      // Add some visual fluff to show the user can drop its files
      if (!event.currentTarget.classList.contains("bg-green-300")) {
        event.currentTarget.classList.remove("bg-gray-100");
        event.currentTarget.classList.add("bg-green-300");
      }
    },

    dragleave(event) {
      // Clean up
      event.currentTarget.classList.add("bg-gray-100");
      event.currentTarget.classList.remove("bg-green-300");
    },

    drop(event) {
      event.preventDefault();
      this.$refs.file.files = event.dataTransfer.files;
      this.onChange(); // Trigger the onChange event manually
      // Clean up
      event.currentTarget.classList.add("bg-gray-100");
      event.currentTarget.classList.remove("bg-green-300");
    },

    formSubmit(e) {
      e.preventDefault();

      this.livrableToEdit.user_id = this.$store.state.id;

      let data = new FormData();
      data.append("file", this.filelist[0]);
      data.append("type", "LIVRABLE");
      data.append("user_id", this.$store.state.id);

      const config = {
        method: "post",
        url: API_BASE_URL + "/document/publish",
        headers: {
          "Content-Type": "multipart/form-data",
        },
        data: data,
      };

      if (this.action === "add") {
        axios(config)
          .then((response) => {
            this.livrableToEdit.idDoc = response.data.id;

            API_Helper.post("/evaluation/create", this.livrableToEdit)
              .then((response) => {
                this.afterAction();
                createToast("Le livrable a bien été ajoutée.", {
                  type: "success",
                });
              })
              .catch((e) => {
                createToast("Le livrable n'a pas pu être ajoutée.", {
                  type: "danger",
                });
              });
          })
          .catch(() => {
            createToast("Le livrable n'a pas pu être ajoutée.", {
              type: "danger",
            });
          });
      } else if (this.action === "edit") {
        API_Helper.put(
          "/evaluation/edit/" + this.livrableToEdit.id,
          this.livrableToEdit
        )
          .then((response) => {
            createToast("Le livrable a bien été modifiée.", {
              type: "success",
            });
            this.afterAction();
          })
          .catch((e) => {
            createToast("Le livrable n'a pas pu être modifiée.", {
              type: "danger",
            });
          });
      }

      this.filelist = [];
      this.checkForm();
    },

    formValidation() {
      let livrableTemp = {};
      livrableTemp["statut"] = this.livrableToEdit.statut;
      livrableTemp["libelle"] = this.livrableToEdit.libelle;
      livrableTemp["idEvent"] = this.livrableToEdit.idEvent;

      this.isFormValid = isFormStringValid(this.livrableToEdit);
      this.isFormEmpty = isFormStringEmpty(this.livrableToEdit);
    },

    resetValue(e) {
      e.preventDefault();
      resetValuesFormStringObject(this.livrableToEdit);
      this.isFormValid = false;
      this.isFormEmpty = true;
    },

    afterAction() {
      this.$emit("getLivrables");
      $("#modalCreateLivrable").modal("hide");
      resetValuesFormStringObject(this.livrableToEdit);
    },

    async getEventLivrable() {
      this.livrableEventList = [];
      this.livrableEventList = await API_Helper.get(
        `/event/type/${consts.EVENT_TYPE_LIVRABLE}`
      ).then((response) => {
        return response.data;
      });
    },

    checkForm() {
      this.formValid =
        this.livrableToEdit.libelle !== "" &&
        this.livrableToEdit.statut !== "" &&
        this.livrableToEdit.idEvent !== "";

      if (this.action === "add") {
        this.formValid &= this.filelist.length === 1;
      }
    },
  },
};
</script>

<style scoped>
[v-cloak] {
  display: none;
}

ul {
  list-style-type: none;
}
</style>
