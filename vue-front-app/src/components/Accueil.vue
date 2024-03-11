<template>
  <div class="">
    <div v-if="role === 'ADMIN'">
      <div class="row justify-content-center text-center p-1">
        <h1>Tableau de bord</h1>
      </div>
    </div>

    <div v-if="role === 'ENSEIGNANT'">
      <div class="row justify-content-center text-center p-1">
        <h1>Tableau de bord</h1>
      </div>
    </div>

    <div v-if="role === 'COORDINATEUR'">
      <div class="row justify-content-center text-center p-1">
        <h1>Tableau de bord</h1>
      </div>
    </div>

    <div v-if="role === 'RESPONSABLE'">
      <div class="row justify-content-center text-center p-1">
        <h1>Tableau de bord</h1>
      </div>
    </div>

    <div v-if="role === 'APPRENTI'">
      <div class="row justify-content-center text-center p-1">
        <h1>Mon journal de formation</h1>
      </div>
      <!--			Enseignant - tuteur pédagogique-->
      <div class="row m-0 align-items-start">
        <Widget class="col-12 col-md-7" titre="Livrables">
          <Evenements />
        </Widget>
        <div class="col-12 col-md-5">
          <Widget class="col-12" titre="Notes périodiques">
            <NotesPeriodes />
          </Widget>
          <Widget class="col-12" titre="Docs pédagogiques">
            <DocsPedagos />
          </Widget>
        </div>
      </div>
    </div>

    <div v-if="role === 'MAITRE-APPRENTISSAGE'">
      <div class="row justify-content-center text-center p-1">
        <h1>Tableau de bord</h1>
      </div>
    </div>

    <div v-if="role === 'UNIDENTIFIED'">
      <!--			Qu'est-ce que tu fous là bordel-->
    </div>

    <router-view />
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import { createToast } from "mosha-vue-toastify";
import AjoutLivrable from "@/components/apprenti/ModalAjoutLivrable";
import Widget from "@/components/Widget";
import Evenements from "@/components/apprenti/Livrables";
import NotesPeriodes from "@/components/apprenti/NotesPeriodes";
import DocsPedagos from "@/components/coordinateur/DocsPedagos";

export default {
  name: "Accueil",
  components: { DocsPedagos, NotesPeriodes, AjoutLivrable, Widget, Evenements },

  data() {
    return {
      role: this.$store.state.perm,
      livrables: [],
      modalAction: "add",
    };
  },
};
</script>

<style scoped>
.card_dashboard {
  transition: 0.3s transform cubic-bezier(0.155, 1.105, 0.295, 1.12),
    0.3s box-shadow,
    0.3s -webkit-transform cubic-bezier(0.155, 1.105, 0.295, 1.12);
  cursor: pointer;
}

.card_dashboard:hover {
  transform: scale(1.05);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12), 0 4px 8px rgba(0, 0, 0, 0.06);
}
</style>
