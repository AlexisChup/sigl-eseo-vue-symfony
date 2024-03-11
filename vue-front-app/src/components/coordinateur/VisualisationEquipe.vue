<template>
  <div class="p-1 p-md-3">
    <div class="row justify-content-center text-center p-1">
      <h1>Mes Apprentis</h1>
    </div>
    <div class="row">
      <div class="case rounded shadow border p-1 p-md-3">
        <table class="table">
          <thead>
            <tr>
              <th class="text-center" scope="col">Nom</th>
              <th class="text-center" scope="col">Tuteur</th>
              <th class="text-center" scope="col">Maitre d'apprentissage</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(tutoral, index) in tutoraleList" :key="index">
              <td class="text-center" scope="row">
                {{ tutoral.appPrenom }}
                <span style="text-transform: uppercase">{{
                  tutoral.appNom
                }}</span>
              </td>
              <td class="text-center" scope="row">
                {{ tutoral.tutPrenom }}
                <span style="text-transform: uppercase">{{
                  tutoral.tutNom
                }}</span>
              </td>
              <td class="text-center" scope="row">
                {{ tutoral.maPrenom }}
                <span style="text-transform: uppercase">{{
                  tutoral.maNom
                }}</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import "mosha-vue-toastify/dist/style.css";
import ModalApprenti from "@/components/coordinateur/ModalApprenti";

export default {
  name: "GestionEquipeTutorale",
  props: {
    tutoraleToEdit: Object,
  },
  data() {
    return {
      idUser: 0,
      tutoraleList: [],
      tutoraleToEdit: {
        appId: 0,
        appPrenom: "",
        appNom: "",
        tutId: 0,
        tutPrenom: "",
        tutNom: "",
        maId: 0,
        maPrenom: "",
        maNom: "",
      },
      datas: [],
      data: {
        perm: "",
      },
    };
  },
  created() {
    //this.idUser = this.$route.params.id;
    this.idUser = 19;
    this.getPermission();
  },
  methods: {
    async getPermission() {
      await API_Helper.get("/user/ " + this.idUser + "/rights").then(
        (response) => {
          this.data.perm = response.data.perm;

          this.getApprentis();
        }
      );
    },
    async getApprentis() {
      this.apprentis = [];
      await API_Helper.post("/user/equipe_tutorale/" + this.idUser, this.data)
        .then((response) => {
          this.datas = response.data;
          this.datas.forEach((value, index) => {
            this.tutoraleToEdit.appId = value.idUser.id;
            this.tutoraleToEdit.appPrenom = value.idUser.prenom;
            this.tutoraleToEdit.appNom = value.idUser.nom;
            this.tutoraleToEdit.tutId = value.idEnseignant.idUser.id;
            this.tutoraleToEdit.tutPrenom = value.idEnseignant.idUser.prenom;
            this.tutoraleToEdit.tutNom = value.idEnseignant.idUser.nom;
            this.tutoraleToEdit.maId = value.idMA.idUser.id;
            this.tutoraleToEdit.maPrenom = value.idMA.idUser.prenom;
            this.tutoraleToEdit.maNom = value.idMA.idUser.nom;
            this.tutoraleList.push(this.tutoraleToEdit);
          });
        })
        .catch((e) => {
          console.log("erreur : ", e);
        });
    },
  },
};
</script>

<style scoped></style>
