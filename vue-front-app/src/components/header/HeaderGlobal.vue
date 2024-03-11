<template>
  <nav
    class="navbar navbar-expand-xl navbar-light bg-white"
    id="navbar"
    v-if="isDropDownDisplayed"
  >
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css"
    />
    <div class="navbar-brand mx-0 mx-xl-5">
      <router-link to="/accueil" class="navbar-brand" href="#">
        <img src="../../assets/ESEO-E-RVB.png" width="40" height="40" alt="" />
      </router-link>
    </div>
    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <div
      class="collapse navbar-collapse row justify-content-between mx-0"
      id="navbarSupportedContent"
    >
      <ul class="navbar-nav mr-auto">
        <li class="nav-item mt-4 mt-xl-0">
          <router-link
            v-for="(item, index) in headerUserItems"
            :key="index"
            :to="item.url"
            class="btn btn-light mr-3"
          >
            {{ item.name }}
          </router-link>
        </li>
      </ul>
      <div class="row mx-0" v-if="isDropDownDisplayed">
        <div class="dropdown show ml-3">
          <a
            class="btn btn-light dropdown-toggle"
            href="#"
            role="button"
            id="dropdownMenuLink"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
          >
            <i class="fa-solid fa-user"></i> Profil
          </a>
          <div
            class="dropdown-menu dropdown-menu-right"
            aria-labelledby="dropdownMenuLink"
          >
            <router-link to="/profil/informations" class="dropdown-item"
              >Mon profil</router-link
            >
            <router-link to="/profil/notifications" class="dropdown-item"
              >Notifications</router-link
            >
            <router-link to="/profil/deconnexion" class="dropdown-item"
              >Déconnexion</router-link
            >
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import headerUserConfig from "../../static/headerUserConfig.js";
import { API_Helper } from "@/api/helper_api";

export default {
  name: "HeaderGlobal",
  data() {
    return {
      headerUserItems: headerUserConfig.headerItems[this.$store.state.perm],
      isDropDownDisplayed: this.isLogged(),
      key: 0,
    };
  },
  updated() {},
  mounted() {},
  created: function () {
    this.unsubscribe = this.$store.subscribe((action, state) => {
      if (action.type === "LOGIN" || action.type === "LOGOUT") {
        this.headerUserItems =
          headerUserConfig.headerItems[this.$store.state.perm];

        this.isDropDownDisplayed = this.isLogged();
      }
    });
  },
  beforeDestroy: function () {
    this.unsubscribe();
  },

  methods: {
    isLogged() {
      if (
        this.$store.state.perm != "UNIDENTIFIED" &&
        this.$store.state.perm != undefined &&
        this.$store.state.perm != null &&
        this.$store.state.perm != ""
      ) {
        return true;
      }

      return false;
    },
  },
};
</script>

<style scoped>
#navbar {
  padding: 1em;
  font-family: Helvetica;
}

.navbar-brand {
  margin-left: 1em;
  margin-right: 1.5em;
}

#navbar a {
  text-decoration: none;
}

#navbarSupportedContent ul {
  flex-grow: 1;
}

#navbarSupportedContent ul li {
  display: inline;
  margin-right: 2.5em;
  font-size: 20px;
  padding: 0.5em 1em;
  color: black;
  border-radius: 0.3em;
  transition: all 0.2s;
  white-space: nowrap;
}

#right-nav {
  display: flex;
  margin-right: 4em;
}

.btn-param:hover {
  background-color: #8b61a1;
  color: white;
}

.navbar-dropdown a {
  background-color: white;
  color: white;
  padding: 0.5em 1em;
  font-size: 20px;
  cursor: pointer;
  border-radius: 0.3em;
  color: black;
  margin-right: 1em;
  transition: all 0.2s;
  font-family: Helvetica;
}

/*
#navbarSupportedContent ul{
  flex-grow: 1;
}

#navbarSupportedContent ul li{
  display: inline;
  margin-right: 2.5em;
  font-size: 20px;
  padding: .5em .5em;
  border-radius: 0.3em;
  transition: all 0.2s;
  white-space:nowrap;
}*/

#navbarSupportedContent ul li a {
  background-color: #8b61a1;
  border-color: #8b61a1;
  color: white;
}

#navbarSupportedContent ul li a:hover {
  background-color: #704e80;
  border-color: #704e80;
}

/*
#right-nav{
  display: flex;
  margin-right: 4em;
}

.navbar-dropdown a{
  background-color: white;
  color: white;
  padding: .5em .5em;
  font-size: 20px;
  cursor: pointer;
  border-radius: .3em;
  color: black;
  margin-right: 1em;
  transition: all 0.2s;
>>>>>>> origin/develop
}

.dropdown-menu {
	width: auto;
}

<<<<<<< HEAD
.dropdown-menu a {
	display: flex;
	width: max-content;
}

.navbar-dropdown a:hover {
	background-color: #8b61a1;
	color: white;
}
=======
.dropdown-menu a{
  display: flex;
  width: max-content;
}*/
</style>
