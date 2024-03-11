<template>
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="container">
          <form>
            <div class="form-row">
              <div
                class="flex w-full h-screen items-center justify-center text-center"
              >
                <div
                  class="p-5 bg-gray-100 border border-gray-300"
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
                    <div class="">
                      Glissez-ici votre document ou cliquez-ici dans la zone
                      pour choisir un fichier.
                    </div>
                    <img
                      class="mt-5"
                      src="@/assets/download.png"
                      width="40"
                      height="45"
                      alt=""
                    />
                  </label>
                  <ul class="p-0" v-if="this.filelist.length" v-cloak>
                    <li
                      class="text-sm p-1"
                      v-for="(file, index) in filelist"
                      :key="index"
                    >
                      {{ file.name }}
                      <button
                        class="ml-1 btn btn-danger"
                        type="button"
                        @click="remove(filelist.indexOf(file))"
                        title="Remove file"
                      >
                        x
                      </button>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          v-on:click="closeModal"
        >
          Fermer
        </button>
        <button
          type="button"
          class="btn btn-success"
          data-dismiss="modal"
          v-on:click="formSubmit"
        >
          Appliquer
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
import formData from "form-data";

export default {
  name: "ModalPublishDocPedago",

  mounted() {},
  data() {
    return {
      filelist: [], // Store our uploaded files
    };
  },

  methods: {
    closeModal() {
      this.filelist = [];
    },

    onChange() {
      this.filelist = [...this.$refs.file.files];
    },

    remove(i) {
      this.filelist.splice(i, 1);
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

    async formSubmit(e) {
      e.preventDefault();

      let data = new FormData();
      data.append("file", this.filelist[0]);
      data.append("type", "PEDAGO");
      data.append("user_id", this.$store.state.id);
      data.append("promo_id", 2);

      const config = {
        method: "post",
        url: API_BASE_URL + "/document/publish",
        headers: {
          "Content-Type": "multipart/form-data",
        },
        data: data,
      };

      axios(config)
        .then((response) => {
          createToast("Document déposé", { type: "success" });
          this.afterAction();
        })
        .catch(() => {
          createToast("Impossible de déposer le document", { type: "danger" });
        });
    },

    afterAction() {
      this.filelist = [];
      this.$emit("getDocumentFromPromo");
      $("#modalCreateLivrable").modal("hide");
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
