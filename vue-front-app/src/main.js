import {createApp} from "vue";
import App from "./App.vue";
import router from "./router/router";
import {SetupCalendar, Calendar, DatePicker} from "v-calendar";
import 'v-calendar/dist/style.css';

import store from "./store/store";

/* import the fontawesome core */
import {library} from "@fortawesome/fontawesome-svg-core";

/* import font awesome icon component */
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

/* import specific icons */
import {faBars, faXmark} from "@fortawesome/free-solid-svg-icons";

/* add icons to the library */
library.add(faBars, faXmark);

const app = createApp(App)
    .component("font-awesome-icon", FontAwesomeIcon)
    .use(router)
    .use(store)
    .use(SetupCalendar)
    .component('Calendar', Calendar)
    .component('DatePicker', DatePicker)
    .mount("#app");

router.app = app;
