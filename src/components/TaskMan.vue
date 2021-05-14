<template>
  <div>

    <div v-if='picAlert' style='position:fixed; top: 50vh; left: 50vw; transform: translate(-50%, -50%);
      border: 2px solid black; height: 120px; width: 300px; background:white; z-index: 30;
      display: flex; flex-direction: column; align-items: center;'>

      <p style='text-align: center; font-size: 16px;'>Please enter the url of your new profile picture.</p>
      <div style='display: flex; flex-direction: row; align-items: center; border: 2px solid black;'>
        <input placeholder='URL...' v-model='userPicture' class='pic-input' type='text' >
        <div id="submit-wrap" class="bonus-wrap">
                  <img
                    v-on:click='submitPic'
                    class="submit"
                    src="/submit.svg"
                  />
                  <div id="submit-bonus" class="bonus">
                    <h5>Submit.</h5>
                  </div>
                </div>
      </div>
    </div>

    <div v-if="list == 'task'" class="wrap">
      <div>
        <div class="header">
          <h2>TASKS</h2>
          <h5 class="today">{{ today }}</h5>

          <div id="profile-wrap" class="bonus-wrap">
            <img
              v-on:click="prev='task'; list = 'profile'"
              class="profile"
              src="/profile.svg"
            />
            <div id="profile-bonus" class="bonus">
              <h5>Switch to profile page.</h5>
            </div>
          </div>

          <div class="bonus-wrap">
            <img v-on:click="prev='task'; list = 'log'" class="log" src="/book.svg" />
            <div id="switch-bonus" class="bonus">
              <h5>Switch to activity log page.</h5>
            </div>
          </div>
        </div>

        <div class="tasklist-wrap">
          <div class="input-bar">
            <input v-model="name" placeholder="Task Name..." type="text" />
            <input v-model="category" placeholder="Project..." type="text" />
            <input v-model="date" placeholder="YYYY-MM-DD..." type="text" />

            <div class="add" v-on:click="addTask">
              <div class="bonus-wrap">
                <img src="/add.svg" />
                <div id="add-bonus" class="bonus">
                  <h5>Add a new task.</h5>
                </div>
              </div>
            </div>
          </div>

          <div class="tasks">
            <Tasks
              :tasks="projects"
              :check="check"
              :selectTask="selectTask"
              :deleteTask="deleteTask"
              :deleteProject="deleteProject"
            />
          </div>
        </div>

        <div class="bottom">

          <div id="logout-wrap" class="bonus-wrap">
            <img class="logout" src="/logout.svg" v-on:click="this.logout" />
            <div id="logout-bonus" class="bonus">
              <h5>Logout of TaskMan.</h5>
            </div>
          </div>
        </div>
      </div>

      <div class="side-wrap">
        <div>
          <h5>Description</h5>
          <div class="des-wrap">
            <textarea
              spellcheck="false"
              placeholder="Type here to give this task a description..."
              v-model="desc"
              v-on:change="descChange"
              v-on:input="textInput"
            ></textarea>
          </div>
        </div>

        <div>
          <div class="collab-wrap">
            <div class="collab-top-bar">
              <img class="team" src="/team.svg" />
              <h4 class="collab-title">{{ project_title }}</h4>

              <div id="add-team-wrap" class="bonus-wrap">
                <img
                  v-on:click="addCollaborator"
                  class="add_team"
                  src="/add_team.svg"
                />
                <div id="add-team-bonus" class="bonus">
                  <h5>Add user to this project.</h5>
                </div>
              </div>
            </div>

            <div v-if="addingCollaborator">
              <h5 class="collab_prompt">
                Enter the name of the user you wish to add to this project:
              </h5>
              <div class="collab-input">
                <textarea
                  placeholder="username..."
                  v-model="collaboratorName"
                ></textarea>

                <div id="submit-wrap" class="bonus-wrap">
                  <img
                    v-on:click="submitCollab"
                    class="submit"
                    src="/submit.svg"
                  />
                  <div id="submit-bonus" class="bonus">
                    <h5>Submit.</h5>
                  </div>
                </div>
              </div>
            </div>
            <div v-else>
              <div class='collaborator-wrap'>

              <div
                class="collaborator"
                v-for="user in collaborators"
                :key="user"
                v-on:click="viewUser(user);"
              >
                <h5>{{ user }}</h5>
              </div>
              </div>
            </div>
          </div>
          <h5>Collaborators: {{ collaborators.length }}</h5>
        </div>
      </div>
    </div>

    <div v-else-if="list == 'profile'" class="wrap">
      <div>
        <div class="header">
          <h2>YOUR PROFILE</h2>
          <h5 class="today">{{ today }}</h5>

          <div id="profile-wrap" class="bonus-wrap">
            <img v-on:click="list = prev; prev='profile';" class="log" src="/back.svg" />
            <div id="exit-bonus" class="bonus">
              <h5>Exit profile page.</h5>
            </div>
          </div>
        </div>

        <div class="tasklist-wrap">
          <div class="center-profile">
            <h2>{{ this.username }}</h2>
            <img
              style="cursor: pointer; object-fit: cover; border: 2px solid black; width: 200px; height:200px;"
              :src="userPicture"
              v-on:click="setPicture"
            />
            <div
              style="margin-top: 2px; margin-bottom: -2px;width: 200px; display:flex; flex-direction: row; align-items: center; justify-content: flex-start;"
            >
              <h3>{{ this.friends.length }} friends</h3>
            </div>
            <h3>{{ this.projectsProp.length }} projects</h3>
          </div>
        </div>

        <div class="bottom">

          <div id="logout-wrap" class="bonus-wrap">
            <img class="logout" src="/logout.svg" v-on:click="this.logout" />
            <div id="logout-bonus" class="bonus">
              <h5>Logout of TaskMan.</h5>
            </div>
          </div>
        </div>
      </div>

      <div class="side-wrap">
        <div>
          <h5>Description</h5>
          <div class="des-wrap">
            <textarea
              spellcheck="false"
              placeholder="Type here to give your profile a description..."
              v-on:change="userDescChange"
              v-model="userDescription"
              v-on:input="userDescChange"
            ></textarea>
          </div>
        </div>

        <div>
          <div class="collab-wrap">
            <div class="collab-top-bar">
              <img class="team" src="/team.svg" />
              <h4 class="collab-title">Your Friends</h4>

              <div id="add-team-wrap" class="bonus-wrap">
                <img
                  v-on:click="addCollaborator"
                  class="add_team"
                  src="/add_team.svg"
                />
                <div id="add-team-bonus" class="bonus">
                  <h5>Add user to your friends.</h5>
                </div>
              </div>
            </div>

            <div v-if="addingCollaborator">
              <h5 class="collab_prompt">
                Enter the name of the user you wish to add to your friends list:
              </h5>
              <div class="collab-input">
                <textarea
                  placeholder="username..."
                  v-model="collaboratorName"
                ></textarea>

                <div id="submit-wrap" class="bonus-wrap">
                  <img
                    v-on:click="submitFriend"
                    class="submit"
                    src="/submit.svg"
                  />
                  <div id="submit-bonus" class="bonus">
                    <h5>Submit.</h5>
                  </div>
                </div>
              </div>
            </div>
            <div v-else>
              <div
                class="collaborator"
                v-for="user in friends"
                :key="user"
                v-on:click="viewUser(user);"
              >
                <h5>{{ user }}</h5>
              </div>
            </div>
          </div>
          <h5>Friends: {{ friends.length }}</h5>
        </div>
      </div>
    </div>


    <div v-else-if="list == 'other'" class="wrap">
      <div>
        <div class="header">
          <h2>{{othername.toUpperCase()}}'S PROFILE</h2>
          <h5 class="today">{{ today }}</h5>

          <div id="profile-wrap" class="bonus-wrap">
            <img v-on:click="list = prev;" class="log" src="/back.svg" />
            <div id="exit-bonus" class="bonus">
              <h5>Exit profile page.</h5>
            </div>
          </div>
        </div>

        <div class="tasklist-wrap">
          <div class="center-profile">
            <h2>{{ this.othername }}</h2>
            <img
              style="cursor: pointer; object-fit: cover; border: 2px solid black; width: 200px; height:200px;"
              :src="otherpicture"
            />
            <div
              style="margin-top: 2px; margin-bottom: -2px;width: 200px; display:flex; flex-direction: row; align-items: center; justify-content: flex-start;"
            >
              <h3>{{ this.otherfriends.length }} friends</h3>

              <div id="add-team-wrap" class="bonus-wrap" style="margin-left: auto; margin-right: 0px;">
                <img
                  style='width:18px;'
                  v-on:click="submitFriend"
                  class="add_team"
                  src="/add_team.svg"
                />
                <div id="add-team-bonus" class="bonus">
                  <h5>Add user to your friends.</h5>
                </div>
              </div>
            </div>
            <h3>{{ this.otherprojects.length }} projects</h3>
          </div>
        </div>

        <div class="bottom">

          <div id="logout-wrap" class="bonus-wrap">
            <img class="logout" src="/logout.svg" v-on:click="this.logout" />
            <div id="logout-bonus" class="bonus">
              <h5>Logout of TaskMan.</h5>
            </div>
          </div>
        </div>
      </div>

      <div class="side-wrap">
        <div>
          <h5>Description</h5>
          <div class="des-wrap">
            <textarea
              readonly
              spellcheck="false"
              placeholder="This user doesn't have a description."
              v-model="otherdescription"
            ></textarea>
          </div>
        </div>

        <div>
          <div class="collab-wrap">
            <div class="collab-top-bar">
              <img class="team" src="/team.svg" />
              <h4 class="collab-title">Your Friends</h4>

              <div id="add-team-wrap" class="bonus-wrap">
                <img
                  v-on:click="addCollaborator"
                  class="add_team"
                  src="/add_team.svg"
                />
                <div id="add-team-bonus" class="bonus">
                  <h5>Add user to your friends.</h5>
                </div>
              </div>
            </div>

            <div v-if="addingCollaborator">
              <h5 class="collab_prompt">
                Enter the name of the user you wish to add to your friends list:
              </h5>
              <div class="collab-input">
                <textarea
                  placeholder="username..."
                  v-model="collaboratorName"
                ></textarea>

                <div id="submit-wrap" class="bonus-wrap">
                  <img
                    v-on:click="submitFriend"
                    class="submit"
                    src="/submit.svg"
                  />
                  <div id="submit-bonus" class="bonus">
                    <h5>Submit.</h5>
                  </div>
                </div>
              </div>
            </div>
            <div v-else>
              <div
                class="collaborator"
                v-for="user in friends"
                :key="user"
                v-on:click="viewUser(user);"
              >
                <h5>{{ user }}</h5>
              </div>
            </div>
          </div>
          <h5>Friends: {{ friends.length }}</h5>
        </div>
      </div>
    </div>



    <div v-else class="wrap">
      <div>
        <div class="header">
          <h2>ACTIVITY LOG</h2>
          <h5 class="today">{{ today }}</h5>

          <div id="profile-wrap" class="bonus-wrap">
            <img
              v-on:click="prev='log'; list = 'profile'"
              class="profile"
              src="/profile.svg"
            />
            <div id="profile-bonus" class="bonus">
              <h5>Switch to profile page.</h5>
            </div>
          </div>

          <div class="bonus-wrap">
            <img v-on:click="prev='log'; list = 'task'" class="log" src="/clip.svg" />
            <div id="switch-bonus" class="bonus">
              <h5>Switch to task page.</h5>
            </div>
          </div>
        </div>

        <div class="tasklist-wrap">
          <div class="tasks">
            <Logs
              :selectTask="selectTask"
              :addLog="addLog"
              :projects="projects"
              :changeLog="changeLog"
              :creatorid="userid"
            />
          </div>
        </div>
        <div class="bottom">

          <div id="logout-wrap" class="bonus-wrap">
            <img class="logout" src="/logout.svg" v-on:click="this.logout" />
            <div id="logout-bonus" class="bonus">
              <h5>Logout of TaskMan.</h5>
            </div>
          </div>
        </div>
      </div>
      <div class="side-wrap">
        <div>
          <h5>Instructions</h5>
          <div class="des-wrap">
            <p class="instr">
              Write your daily activities for each or your projects.
            </p>
            <div style="height: 10px;"></div>
            <p class="instr">
              You will also see the daily logs of other project members.
            </p>
            <div style="height: 10px;"></div>
            <p class="instr">
              We will only save the logs that you have added after today.
            </p>
          </div>
        </div>
        <div>
          <div class="collab-wrap">
            <div class="collab-top-bar">
              <img class="team" src="/team.svg" />
              <h4 class="collab-title">{{ project_title }}</h4>

              <div id="add-team-wrap" class="bonus-wrap">
                <img
                  v-on:click="addCollaborator"
                  class="add_team"
                  src="/add_team.svg"
                />
                <div id="add-team-bonus" class="bonus">
                  <h5>Add user to this project.</h5>
                </div>
              </div>
            </div>

            <div v-if="addingCollaborator">
              <h5 class="collab_prompt">
                Enter the name of the user you wish to add to this project:
              </h5>
              <div class="collab-input">
                <textarea
                  placeholder="username..."
                  v-model="collaboratorName"
                ></textarea>

                <div id="submit-wrap" class="bonus-wrap">
                  <img
                    v-on:click="submitCollab"
                    class="submit"
                    src="/submit.svg"
                  />
                  <div id="submit-bonus" class="bonus">
                    <h5>Submit.</h5>
                  </div>
                </div>
              </div>
            </div>
            <div v-else>
              <div class='collaborator-wrap'>
              <div
                class="collaborator"
                v-for="user in collaborators"
                :key="user"
                v-on:click="viewUser(user);"
              >
                <h5>{{ user }}</h5>
              </div>
              </div>
            </div>
          </div>
          <h5>Collaborators: {{ collaborators.length }}</h5>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Tasks from "./Tasks.vue";
import Logs from "./Logs.vue";
import axios from "axios";

export default {
  name: "TaskMan",
  components: {
    Tasks,
    Logs
  },
  props: {
    projectsProp: Array,
    userid: Number,
    username: String,
    logout: Function,
    userPicture: String,
    userDescription: String,
    friends: Array,
  },
  computed: {
    completion() {
      var total = 0;
      var completed = 0;
      for (const cat of this.projects) {
        for (const task of cat.tasks) {
          total++;
          if (task.isDone) completed++;
        }
      }
      return Math.round((completed / total) * 100);
    },
    today() {
      var d = new Date();
      var months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"
      ];
      var month = months[d.getMonth()];
      var dayNum = d.getDate();
      var days = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"
      ];
      var day = days[d.getDay()];
      var year = d.getFullYear();

      return day + ", " + month + " " + dayNum + ", " + year;
    }
  },
  mounted: function() {
    var taskman = this;
    var i = 0;
    for (const project of this.projectsProp) {
      // create new project
      taskman.projects.push({
        isFocused: false,
        title: project.title,
        tasks: [],
        logs: [],
        users: [],
        id: project.id
      });

      // remember this project's id
      this.projectIdMap[project.title] = project.id;

      // get all tasks in each project
      const tasks = JSON.parse(project.tasks);
      const n = i;
      for (const key in tasks) {
        const id = tasks[key];

        axios
          .get("http://taskman.hanaroenterprise.com/api/gettask.php", {
            params: {
              id: id
            }
          })
          .then(function(response) {
            var task = response.data;

            // add this project to the list of projects
            if (task != 404) {
              // add this task to the list of projects
              taskman.projects[n].tasks.push({
                title: task.title,
                date: task.date,
                isDone: task.is_done,
                description: task.description,
                id: task.id,
                dateS: new Date(task.date),
                focusedOn: false,
                project_id: task.project_id
              });

              // order by date
              taskman.projects[n].tasks.sort((a, b) => a.dateS - b.dateS);
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      }

      // add blank logs to each project for today's date
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, "0");
      var mm = String(today.getMonth() + 1).padStart(2, "0");
      var yyyy = today.getFullYear();
      today = yyyy + "-" + mm + "-" + dd;
      this.projects[i].logs.push({
        date: today,
        text: "",
        id: -project.id,
        dateS: new Date(today),
        project_id: project.id
      });

      // get all logs in each project
      const logs = JSON.parse(project.logs);
      for (const key in logs) {
        const id = logs[key];

        axios
          .get("http://taskman.hanaroenterprise.com/api/getlog.php", {
            params: {
              id: id
            }
          })
          .then(function(response) {
            var log = response.data;

            // add this project to the list of projects
            if (log != 404) {
              // add this task to the list of projects

              for (var i = 0; i < taskman.projects[n].logs.length; i++) {
                if (
                  log.date === today &&
                  taskman.projects[n].logs[i].id <= -1
                ) {
                  taskman.projects[n].logs[i] = {
                    date: log.date,
                    text: log.text,
                    id: log.id,
                    dateS: new Date(log.date),
                    project_id: log.project_id
                  };
                  taskman.projects[n].logs.sort((a, b) => b.dateS - a.dateS);
                  return;
                }
              }

              taskman.projects[n].logs.push({
                date: log.date,
                text: log.text,
                id: log.id,
                dateS: new Date(log.date),
                project_id: log.project_id
              });

              // order by date
              taskman.projects[n].logs.sort((a, b) => b.dateS - a.dateS);
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      }

      // get all the users in each project
      const users = JSON.parse(project.users);
      for (const key in users) {
        const id = users[key];
        axios
          .get("http://taskman.hanaroenterprise.com/api/getuser.php", {
            params: {
              id: id
            }
          })
          .then(function(response) {
            var username = response.data;
            if (username != 404) {
              // add this user to the list of users for this project
              taskman.projects[n].users.push(username);
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      }

      i++;
    }

    window.setInterval(this.refresh, 1000);
  },
  data: function() {
    return {
      name: "",
      category: "",
      date: "",
      focusedId: 0,
      focusedProjectId: 0,
      projects: [],
      desc: "",
      hasChanges: false,
      list: "task",
      prev: "task",
      projectIdMap: {},
      collaborators: [],
      project_title: "",
      addingCollaborator: false,
      collaboratorName: "",
      picAlert: false,
      userChanges: false,
      othername: "",
      otherpicture: "",
      otherfriends: [],
      otherprojects: [],
      otherid: [],
      otherdescription: "",
    };
  },
  methods: {
    viewUser: function(username) {
      var taskman = this;
      axios
        .get("http://taskman.hanaroenterprise.com/api/getuserbyname.php", {
          params: {
            username: username,
          }
        })
        .then(function(response) {
          var userData = response.data;
          if (userData != 404) {
            // switch to the page
            if(userData.username == taskman.username)  {
              taskman.list = 'profile';
              return;
            }
            taskman.othername = userData.username;
            taskman.otherfriends = JSON.parse(userData.friends);
            taskman.otherprojects = JSON.parse(userData.projects);
            taskman.otherpicture = userData.picture;
            taskman.otherid = userData.id;
            taskman.otherdescription = userData.description;
            if(taskman.list != 'other' && taskman.list != 'profile')
              taskman.prev = taskman.list;
            taskman.list = "other";
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    submitPic: function() {
      this.picAlert = false;

      // update database
      axios
        .get("http://taskman.hanaroenterprise.com/api/updatepic.php", {
          params: {
            id: this.userid,
            URL: this.userPicture,
          }
        })
        .then(function(response) {
          // update frontend
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });      
    },
    setPicture: function() {
      this.picAlert = true;
    },
    addLog(old_id, new_id, text, project_id, date) {
      for (var proj of this.projects) {
        if (proj.id == project_id) {
          for (var i = 0; i < proj.logs.length; i++) {
            if (proj.logs[i].id == old_id) {
              proj.logs[i] = {
                date: date,
                text: text,
                id: new_id,
                dateS: new Date(date),
                project_id: project_id
              };
              return;
            }
          }
        }
      }
    },
    deleteProject(id) {
      // update database
      const taskman = this;
      axios
        .get("http://taskman.hanaroenterprise.com/api/deleteproject.php", {
          params: {
            project_id: id
          }
        })
        .then(function(response) {
          // update frontend
          console.log(response);

          // remove this project
          for (var i = 0; i < taskman.projects.length; i++) {
            if (taskman.projects[i].id == id) {
              taskman.projects.splice(i, 1);
              taskman.focusedProjectId = -1;
              taskman.collaborators = [];
              taskman.project_title = "";
              break;
            }
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    changeLog(id, text) {
      for (var proj of this.projects) {
        for (var log of proj.logs) {
          if (log.id == id) {
            log.text = text;
            break;
          }
        }
      }
    },
    refresh() {
      if(this.userChanges) {
        this.userChanges = false;
        this.userDescChange();
      }
      if (this.hasChanges) {
        this.hasChanges = false;
        // push changes to database
        for (var cat of this.projects) {
          for (var task of cat.tasks) {
            if (this.focusedId == task.id) {
              task.description = this.desc;
              // update database
              axios
                .get("http://taskman.hanaroenterprise.com/api/updatetask.php", {
                  params: {
                    is_done: task.isDone,
                    description: task.description,
                    id: task.id
                  }
                })
                .then(function(response) {
                  console.log(response);
                })
                .catch(function(error) {
                  console.log(error);
                });

              return;
            }
          }
        }
      }
    },
    textInput() {
      this.hasChanges = true;
    },
    userDescInput() {
      this.userChanges = true;
    },
    userDescChange() {
      // update database
      var taskman = this;
      axios
        .get("http://taskman.hanaroenterprise.com/api/updateuser.php", {
          params: {
            description: taskman.userDescription,
            id: taskman.userid,
          }
        })
        .then(function(response) {
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    descChange() {
      for (var cat of this.projects) {
        for (var task of cat.tasks) {
          if (this.focusedId == task.id) {
            task.description = this.desc;
            this.hasChanges = false;

            // update database
            axios
              .get("http://taskman.hanaroenterprise.com/api/updatetask.php", {
                params: {
                  is_done: task.isDone,
                  description: task.description,
                  id: task.id
                }
              })
              .then(function(response) {
                console.log(response);
              })
              .catch(function(error) {
                console.log(error);
              });

            return;
          }
        }
      }
    },
    deleteTask(id) {
      for (var i = 0; i < this.projects.length; i++) {
        for (var j = 0; j < this.projects[i].tasks.length; j++) {
          if (this.projects[i].tasks[j].id == id) {
            this.projects[i].tasks.splice(j, 1);
            return;
          }
        }
      }
    },
    selectTask(id, proj_id = -1) {
      // clear previous selection
      for (const cat of this.projects) {
        cat.isFocused = false;
        for (const task of cat.tasks) {
          task.focusedOn = false;
        }
      }

      // the user clicked on a project with no tasks
      if (id == -1) {
        for (var proj of this.projects) {
          if (proj.id == proj_id) {
            proj.isFocused = true;
            this.focusedProjectId = proj_id;
            this.focusedId = -1;
            this.addingCollaborator = false;
            this.collaborators = proj.users;
            this.project_title = proj.title;
            break;
          }
        }
        return;
      }

      this.focusedId = id;
      this.addingCollaborator = false;

      for (const cat of this.projects) {
        for (const task of cat.tasks) {
          if (task.id == this.focusedId) {
            this.desc = task.description;
            task.focusedOn = true;

            // get the list of collaborators on this project
            for (const proj of this.projects) {
              if (proj.id == task.project_id) {
                this.collaborators = proj.users;
                this.project_title = proj.title;
                this.focusedProjectId = proj.id;
                proj.isFocused = true;
                return;
              }
            }

            return;
          }
        }
      }
      this.desc = "";
    },
    check(id, isDone) {
      for (var cat of this.projects) {
        for (var task of cat.tasks) {
          if (task.id == id) {
            task.isDone = isDone;

            // update database
            axios
              .get("http://taskman.hanaroenterprise.com/api/updatetask.php", {
                params: {
                  is_done: task.isDone,
                  description: task.description,
                  id: task.id
                }
              })
              .then(function(response) {
                console.log(response);
              })
              .catch(function(error) {
                console.log(error);
              });
            return;
          }
        }
      }
    },
    submitFriend() {
      // update database
      var taskman = this;

      if(this.list == 'other') {
         axios
          .get("http://taskman.hanaroenterprise.com/api/addfriend.php", {
            params: {
              username: taskman.othername,
              id: taskman.userid,
            }
          })
          .then(function(response) {
            const username = response.data;

            if (username == 404)
              alert("The username you've selected isn't a real user.");
            else if (username == 405)
              alert("This user is already your friend.");
            else {
              taskman.friends.push(username);
              taskman.addingCollaborator = false;
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      }
      else {
        axios
          .get("http://taskman.hanaroenterprise.com/api/addfriend.php", {
            params: {
              username: taskman.collaboratorName,
              id: taskman.userid,
            }
          })
          .then(function(response) {
            const username = response.data;

            if (username == 404)
              alert("The username you've selected isn't a real user.");
            else if (username == 405)
              alert("This user is already your friend.");
            else {
              taskman.friends.push(username);
              taskman.addingCollaborator = false;
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    },
    submitCollab() {
      // update database
      var taskman = this;
      axios
        .get("http://taskman.hanaroenterprise.com/api/addusertoproject.php", {
          params: {
            username: taskman.collaboratorName,
            project_id: taskman.focusedProjectId
          }
        })
        .then(function(response) {
          const username = response.data;

          if (username == 404)
            alert("The username you've selected isn't a real user.");
          else if (username == 405)
            alert("This user is already a collaborator on this project.");
          else if (username == 406)
            alert("This user already has this project in their projects list.");
          else {
            // add this username to the project's list of usernames
            for (var proj of taskman.projects) {
              if (proj.id == taskman.focusedProjectId) {
                proj.users.push(username);
                taskman.addingCollaborator = false;
                break;
              }
            }
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    addCollaborator() {
      this.addingCollaborator = !this.addingCollaborator;
    },
    addTask() {
      // check for blank input
      if (this.name === "" || this.category === "" || this.date === "") return;

      // check for existing category
      for (var cat of this.projects) {
        if (cat.title == this.category) {
          for (const task of cat.tasks) {
            if (this.name == task.title) return;
          }

          // push to database
          var tasklist = this;
          axios
            .get("http://taskman.hanaroenterprise.com/api/addtask.php", {
              params: {
                title: tasklist.name,
                date: tasklist.date,
                creatorid: tasklist.userid,
                project_id: tasklist.projectIdMap[tasklist.category],
                category: tasklist.category
              }
            })
            .then(function(response) {
              const task_index = response["data"];

              cat.tasks.push({
                id: task_index,
                title: tasklist.name,
                category: tasklist.category,
                date: tasklist.date,
                dateS: new Date(tasklist.date),
                isDone: false,
                project_id: tasklist.projectIdMap[tasklist.category],
                focusedOn: false
              });

              cat.tasks.sort((a, b) => a.dateS - b.dateS);
            })
            .catch(function(error) {
              console.log(error);
            });

          return;
        }
      }

      // push to database
      tasklist = this;
      axios
        .get("http://taskman.hanaroenterprise.com/api/addtask.php", {
          params: {
            title: tasklist.name,
            date: tasklist.date,
            creatorid: tasklist.userid,
            project_id: -1,
            category: tasklist.category
          }
        })
        .then(function(response) {
          var data = response["data"].split(" ");
          const project_index = parseInt(data[0]);
          const task_index = parseInt(data[1]);

          // update id map
          tasklist.projectIdMap[tasklist.category] = project_index;

          var today = new Date();
          var dd = String(today.getDate()).padStart(2, "0");
          var mm = String(today.getMonth() + 1).padStart(2, "0");
          var yyyy = today.getFullYear();
          today = yyyy + "-" + mm + "-" + dd;

          // make new category
          tasklist.projects.push({
            isFocused: false,
            logs: [
              {
                date: today,
                text: "",
                id: -project_index,
                dateS: new Date(today),
                project_id: project_index
              }
            ],
            title: tasklist.category,
            id: project_index,
            users: [tasklist.username],
            focusedOn: false,
            tasks: [
              {
                id: task_index,
                project_id: project_index,
                title: tasklist.name,
                category: tasklist.category,
                date: tasklist.date,
                dateS: new Date(tasklist.date),
                isDone: false,
                focusedOn: false
              }
            ]
          });
        })
        .catch(function(error) {
          console.log(error);
        });
    }
  }
};
</script>

<style scoped>
.tasklist-wrap {
  border: 2px solid black;
  width: 500px;
  height: 450px;
  display: flex;
  flex-direction: column;
  backdrop-filter: blur(15px);
}

.tasks {
  overflow: scroll;
}

.tasks::-webkit-scrollbar {
  display: none;
}

.wrap {
  position: fixed;
  top: 50vh;
  left: 50vw;
  transform: translate(-50%, -50%);
  display: flex;
  align-items: center;
}

h5 {
  margin: 0;
  margin-left: 2px;
  font-weight: 500;
}

.input-bar {
  display: flex;
  flex-direction: row;
  align-items: center;
}

.input-bar input {
  padding: 10px;
  border: 2px solid black;
  border-left: none;
  border-top: none;
  width: 26%;
  height: 15px;
  font-family: "Source Code Pro", monospace;
  font-size: 13px;
  background: white;
  font-weight: 500;
}

.input-bar input:focus {
  outline: none;
}

.add {
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  border-bottom: 2px solid black;
  background-color: transparent;
  width: 10%;
  height: 35px;
}

.add:hover img {
  filter: drop-shadow(0px 0px 1px black);
}

img {
  transform: translate(2px, 3px);
}

.side-wrap {
  display: flex;
  flex-direction: column;
  margin-left: 25px;
  height: 495px;
  justify-content: flex-start;
  align-items: center;
  transform: translateY(8px);
  z-index: -10;
}

.des-wrap {
  border: 2px solid black;
  height: 250px;
  width: 180px;
  padding: 10px;
  font-size: 12px;
}

.collab-wrap {
  margin-top: 20px;
  border: 2px solid black;
  height: 155px;
  width: 200px;
}

textarea {
  font-size: 13px;
  border: none;
  resize: none;
  width: 175px;
  height: 245px;
  font-family: "Source Code Pro", monospace;
  color: black;
}

textarea:focus {
  outline: none;
}

p {
  margin: 10px;
  font-size: 12px;
}

h2 {
  margin: 0;
  font-weight: 600;
  line-height: 22px;
}

.header {
  display: flex;
  align-items: flex-end;
  margin-bottom: 2px;
}

.bottom {
  display: flex;
  align-items: flex-end;
}

.today {
  line-height: 15px;
}

.moon {
  width: 18px;
  height: 18px;
  margin-left: auto;
  margin-right: 3px;
  cursor: pointer;
  transform: translate(2px, 5px);
}

.moon:hover {
  filter: drop-shadow(0px 0px 1px black);
}

#moon-wrap {
  margin-left: auto;
}

#moon-bonus {
  top: -50px;
  left: 20px;
  height: 37px;
  width: 110px;
}

.profile {
  width: 18px;
  height: 17px;
  margin-right: 3px;
  cursor: pointer;
}

.profile:hover {
  filter: drop-shadow(0px 0px 1px black);
}

#profile-wrap {
  margin-left: auto;
}

.bonus {
  border: 2px solid black;
  position: absolute;
  z-index: 10;
  background: white;
  padding: 5px;
  text-align: center;
  visibility: hidden;
}

#profile-bonus {
  top: -50px;
  left: 20px;
  height: 37px;
  width: 110px;
}

.bonus-wrap {
  position: relative;
}

.bonus-wrap:hover .bonus {
  visibility: visible;
}

.log {
  width: 18px;
  height: 18px;
  margin-left: 0px;
  margin-right: 5px;
  cursor: pointer;
}

.log:hover {
  filter: drop-shadow(0px 0px 1px black);
}

#switch-bonus {
  top: -49px;
  left: 20px;
  height: 37px;
  width: 150px;
}

#exit-bonus {
  top: -49px;
  left: 20px;
  height: 37px;
  width: 100px;
}

h3 {
  margin: 0;
  margin-left: 10px;
  margin-top: 10px;
  font-weight: 700;
  font-size: 16px;
}

h4 {
  font-weight: 700;
  font-size: 14px;
  margin: 0;
  margin-top: 5px;
  margin-left: 10px;
}

#logout-wrap {
  margin-left: auto;
  height: 17px;
}

.logout {
  cursor: pointer;
  height: 17px;
  margin-right: 3px;
}

.logout:hover {
  filter: drop-shadow(0px 0px 1px black);
}

#logout-bonus {
  bottom: -55px;
  left: 20px;
  height: 37px;
  width: 80px;
}

.collaborator {
  width: 100%;
  cursor: pointer;
}

.collaborator h5 {
  margin-left: 10px;
  margin-right: 10px;
}

.collaborator:hover {
  background: #dbdcd7;
}

.collab-top-bar {
  border-bottom: 2px solid black;
  display: flex;
  height: 40px;
  flex-direction: row;
  margin-bottom: 5px;
  align-items: center;
}

::placeholder {
  font-weight: 600;
}

.team {
  transform: translateY(-1px);
  height: 30px;
  margin-left: 10px;
}

#add-team-wrap {
  margin-left: auto;
  margin-right: 12px;
  height: 15px;
}

#add-team-bonus {
  top: -50px;
  left: 20px;
  height: 37px;
  width: 120px;
}

.add_team {
  height: 15px;
  transform: translateY(0px);
  cursor: pointer;
}

.add_team:hover {
  filter: drop-shadow(0px 0px 1px black);
}

.collab-title {
  margin: 0;
  margin-left: 5px;
  line-height: 1;
}

.collab-input {
  margin-left: 10px;
  height: 26px;
  width: 168px;
  margin-top: 13px;
  border: 2px solid black;
  padding-left: 5px;
  display: flex;
  flex-direction: row;
  align-items: center;
}

.collab-input textarea {
  height: 18px;
  width: 130px;
  white-space: nowrap;
  overflow-wrap: normal;
  overflow-x: hidden;
}

.collab_prompt {
  margin-top: 10px;
  margin-left: 10px;
  margin-right: 10px;
}

.submit {
  cursor: pointer;
  width: 20px;
  margin-right: 5px;
  margin-left: 5px;
  margin-bottom: 3px;
}

.submit:hover {
  filter: drop-shadow(0px 0px 1px black);
}

#submit-bonus {
  top: -35px;
  left: 25px;
  height: 20px;
  width: 60px;
}

#add-bonus {
  top: -50px;
  left: 20px;
  height: 37px;
  width: 80px;
}

.instr {
  font-size: 13px;
  margin: 0;
}

.center-profile {
  position: absolute;
  right: 250px;
  top: 225px;
  transform: translate(50%, -55%);
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.center-profile h2 {
  align-self: flex-start;
}

.center-profile h3 {
  font-size: 14px;
  font-weight: 500;
  margin: 1px;
  align-self: flex-start;
  text-align: left;
}

.pic-input {
  padding: 10px;
  border: none;
  width: 80%;
  height: 15px;
  font-family: "Source Code Pro", monospace;
  font-size: 13px;
  background: white;
  font-weight: 500;
}

.pic-input:focus {
  outline: none;
}

</style>
