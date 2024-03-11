<template>
  <div class="p-1 p-md-3">
    <div class="row justify-content-center text-center p-1">
      <h1>Utilisateurs</h1>
    </div>
    <div class="row">
      <div class="row m-0 mb-3">
        <button
          class="btn btn-primary"
          v-on:click="this.$router.push('/gestion-utilisateurs/creation')"
        >
          + Ajouter un utilisateur
        </button>
      </div>
      <div class="case rounded shadow border p-1 p-md-3">
        <preloader class="my-5" v-if="!requestDone"></preloader>
        <p v-if="requestDone && users.length == 0" class="text-center">
          Aucun évènement n'a été renseigné.
        </p>
        <table class="table" v-if="users.length > 0 && requestDone">
          <thead>
            <tr>
              <th class="text-center" scope="col">Nom</th>
              <th class="text-center" scope="col">Email</th>
              <th class="text-center" scope="col">Adresse</th>
              <th class="text-center" scope="col"></th>
              <th class="text-center" scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in users" :key="index">
              <td class="text-center" scope="row">
                {{ user.prenom }}
                <span style="text-transform: uppercase">{{ user.nom }}</span>
              </td>
              <td class="text-center">{{ user.email }}</td>
              <td class="text-center">{{ user.adresse }}</td>
              <td>
                <i
                  v-on:click="editUserById(user.id)"
                  class="fa fa-pencil d-flex justify-content-center"
                  style="cursor: pointer"
                  aria-hidden="true"
                ></i>
              </td>
              <td>
                <i
                  v-on:click="openRemoveUserModal(user)"
                  class="fa fa-trash d-flex justify-content-center"
                  style="cursor: pointer"
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
      id="modalEditUser"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalEditUserLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <ModalUser
          v-bind:action="modalAction"
          @getUsers="getUsers"
          v-bind:userToEdit="userToEdit"
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
          :callbackConfirm="confirmRemoveUser"
          :name="userToRemove.nom"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import "mosha-vue-toastify/dist/style.css";
import ModalUser from "@/components/reglages/ModalUser";
import { createToast } from "mosha-vue-toastify";
import Preloader from "@/components/utils/preLoader";
import ModalRemove from "../utils/ModalRemove";

export default {
  name: "GestionUsers",
  components: { ModalUser, Preloader, ModalRemove },
  data() {
    return {
      users: [],
      userToEdit: {
        nom: "",
        prenom: "",
        email: "",
        adresse: "",
        mdp: "",
        pwd: "",
      },
      userToRemove: {},
      modalAction: "edit",
      requestDone: false,
    };
  },
  methods: {
    async getUsers() {
      this.requestDone = false;
      this.users = [];
      this.users = await API_Helper.get("/utilisateur/all")
        .then((response) => {
          return response.data;
        })
        .catch((e) => {
          console.log("erreur : ", e);
        });
      this.requestDone = true;
    },

    editUserById(idToEdit) {
      this.userToEdit = {};
      API_Helper.get("/utilisateur/" + idToEdit)
        .then((response) => {
          this.userToEdit = response.data;
          this.modalAction = "edit";
          $("#modalEditUser").modal("show");
        })
        .catch((e) => {});
    },

    openRemoveUserModal(user) {
      this.userToRemove = user;
      $("#modalRemove").modal("show");
    },

    confirmRemoveUser() {
      $("#modalRemove").modal("hide");
      this.removeUserById(this.userToRemove.id);
    },

    removeUserById(idToRemove) {
      API_Helper.delete("/utilisateur/remove/" + idToRemove)
        .then((response) => {
          createToast(
            "L'utilisateur " +
              this.users.find(({ id }) => id === idToRemove).nom +
              " a bien été supprimé.",
            { type: "success" }
          );
          this.getUsers();
        })
        .catch((e) => {
          console.log("erreur : ", e);
        });
    },
  },
  async created() {
    await this.getUsers();
  },
  onBeforeUpdate() {},
};
</script>

<style scoped></style>
