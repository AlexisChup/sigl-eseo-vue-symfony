import VuexPersistence from "vuex-persist";
import { createStore } from "vuex";

const vuexLocal = new VuexPersistence({
  storage: window.localStorage,
});

const store = createStore({
  state: {
    perm: "UNIDENTIFIED",
    id: undefined,
  },
  mutations: {
    LOGIN(state, payload) {
      const { perm, id } = payload;

      state.perm = perm;
      state.id = id;
    },
    LOGOUT(state) {
      state.perm = "UNIDENTIFIED";
      state.id = undefined;
    },
  },
  plugins: [vuexLocal.plugin],
});

export default store;
