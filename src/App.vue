<template>
  <div id="app">
    <img class="robot" src="/robot.svg" />
    <h1>taskman</h1>
    <Login v-if="page == 'login'" :login="loginClick" :accountSwitch='accountSwitch' />
    <NewAccount v-else-if="page == 'account'" :login="loginClick" :logout='logout' />
    <TaskMan
      v-else
      :projectsProp="projectsProp"
      :userid="userid"
      :logout="logout"
      :username='username'
    />
  </div>
</template>

<script>
import TaskMan from "./components/TaskMan.vue";
import Login from "./components/Login.vue";
import NewAccount from "./components/NewAccount.vue";
import axios from "axios";

export default {
  name: "App",
  components: {
    TaskMan,
    Login,
    NewAccount,
  },
  data: function() {
    return {
      page: 'login',
      projectsProp: [],
      userid: 0,
      projectsLoaded: 0,
      username: '',
    };
  },
  methods: {
    accountSwitch() {
      this.page = 'account';
    },
    logout() {
      this.projectsProp = [];
      this.projectsLoaded = 0;
      this.userid = 0;
      this.page = 'login';
    },
    loginClick(userData) {
      // set userid
      this.userid = userData.id;
      this.username = userData.username;

      // get the user's projects
      var projectsProp = this.projectsProp;
      var app = this;
      const projects = JSON.parse(userData.projects);

      // if the user has no projects
      if (projects.length <= 0) app.page = 'main';

      for (const key in projects) {
        const id = projects[key];
        axios
          .get("http://taskman.hanaroenterprise.com/api/getproject.php", {
            params: {
              id: id
            }
          })
          .then(function(response) {
            var project = response.data;

            // add this project to the list of projects
            if (project != 404) {
              projectsProp.push(project);
              app.projectsLoaded++;
            }
            if (app.projectsLoaded == Object.keys(projects).length) {
              // go to the main application page
              app.page = 'main';
              app.projectsLoaded = 0;
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    }
  }
};
</script>

<style>
@import url("https://fonts.googleapis.com/css2?family=Source+Code+Pro&display=swap");

#app {
  font-family: "Source Code Pro", monospace;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: left;
  display: flex;
  flex-direction: row;
  align-items: center;
}

h1 {
  font-weight: 400;
  letter-spacing: 5px;
  margin-left: 10px;
}

.robot {
  width: 35px;
  height: 35px;
  margin-left: 20px;
  transform: translateY(-4px);
}

body {
  overflow: hidden;
}
</style>
