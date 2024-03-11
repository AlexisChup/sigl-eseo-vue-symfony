<template>
  <div class="juries-container p-1">
    <preloader class="my-5" v-if="!callJuriesDone"></preloader>
    <div class="d-flex justify-content-between">
      <div v-if="this.isCoord && callJuriesDone">
        <button
          class="btn btn-primary"
          v-on:click="this.$router.push('/jurys/creation')"
        >
          + Ajouter un jury
        </button>
      </div>
      <div
        v-if="callJuriesDone && listJury.length > 0"
        class="event-filter d-flex align-content-center mb-3"
      >
        <h5 class="text-center mr-2">Évènement :</h5>
        <select
          class="form-select form-select"
          v-model="filterEventId"
          v-on:change="updateFilter()"
        >
          <option
            v-for="(soutenance, index) in soutenances"
            v-bind:value="soutenance.id"
            v-bind:key="index"
          >
            {{ soutenance.nom }}
          </option>
        </select>
      </div>
    </div>

    <p v-if="callJuriesDone && listJury.length == 0" class="text-center">
      Aucun jury n'a été renseigné.
    </p>

    <div v-if="callJuriesDone && listJury.length > 0" class="">
      <table class="table table-striped">
        <thead>
          <tr>
            <th class="" scope="col">Apprenti</th>
            <th class="" scope="col">Audience</th>
            <th class="" scope="col">Date soutenance</th>
            <th class="" v-if="this.isCoord" scope="col"></th>
            <th class="" v-if="this.isCoord" scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(jury, index) in filteredJuries" v-bind:key="index">
            <td>
              {{ jury.apprenti.utilisateur.nom }}
              {{ jury.apprenti.utilisateur.prenom }}
            </td>
            <td class="row">
              <p
                v-for="user of jury.auditeur"
                class="col-md-6 col-sm-12 m-0"
                v-bind:key="user.id"
              >
                {{ user.nom }} {{ user.prenom }}
              </p>
            </td>
            <td></td>
            <td v-if="this.isCoord">
              <i
                v-on:click="editJury(jury)"
                class="fa fa-pencil"
                style="cursor: pointer"
              ></i>
            </td>
            <td v-if="this.isCoord">
              <i
                v-on:click="removeJury(jury)"
                class="fa fa-trash"
                style="cursor: pointer"
              ></i>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { API_Helper } from "@/api/helper_api";
import PreLoader from "@/components/utils/preLoader";
import Preloader from "@/components/utils/preLoader";
import { EVENT_TYPE_SOUTENANCE } from "@/static/const";
import { createToast } from "mosha-vue-toastify";

export default {
  name: "ListeJurys",
  components: { Preloader, PreLoader },

  data() {
    return {
      isCoord: this.$store.state.perm === "COORDINATEUR",
      listJury: [],
      callJuriesDone: false,
      soutenances: [],
      filterEventId: 0,
      filteredJuries: [],
    };
  },

  async created() {
    await this.initListJury();
  },

  methods: {
    async initListJury() {
      this.callJuriesDone = false;

      if (this.isCoord) {
        this.listJury = await API_Helper.get(`/jury/juries`).then(
          (res) => res.data
        );
      } else {
        this.listJury = await API_Helper.get(
          `/jury/from-user/${this.$store.state.id}`
        ).then((res) => res.data);
      }

      this.soutenances = await API_Helper.get(
        `event/type/${EVENT_TYPE_SOUTENANCE}`
      ).then((res) => {
        if (res.data.length > 0) this.filterEventId = res.data[0].id;
        return res.data;
      });
      this.updateFilter();

      this.callJuriesDone = true;
    },

    updateFilter() {
      this.filteredJuries = this.listJury.filter(
        (jury) => jury.evenement.id === this.filterEventId
      );
    },

    async removeJury(jury) {
      await API_Helper.delete(`/jury/${jury.id}`)
        .then((res) => {
          createToast("Le jury a été supprimé", { type: "success" });
          this.initListJury();
        })
        .catch((err) => {
          createToast("Le jury n'a pas pu être supprimé.", { type: "danger" });
        });
    },

    editJury(jury) {
      this.$router.push(`/jurys/${jury.id}/edit`);
    },
  },
};
</script>

<style scoped>
td {
  font-size: large;
}
</style>
