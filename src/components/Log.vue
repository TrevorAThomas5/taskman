<template>
  <div>
    <span v-if='editable'
      ref="editable"
      v-on:input="textChange"
      spellcheck="false"
      class="textarea"
      role="textbox"
      contenteditable
      v-on:click='selectProj'
      >{{ text }}</span>

      <span v-else
      ref="editable"
      v-on:input="textChange"
      spellcheck="false"
      class="textarea"
      role="textbox"
      v-on:click='selectProj'
      >{{ text }}</span>

      <p>-{{creator}}</p>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "Log",
  components: {},
  props: {
    text: String,
    id: Number,
    changeLog: Function,
    projectid: Number,
    addLog: Function,
    creatorid: Number,
    selectTask: Function,
    date: String,
    myid: Number,
  },
  data: function() {
    return {
      textHeight: "",
      hasChanges: false,
      textData: "",
      needsPush: false,
      editable: false,
      creator: '',
    };
  },
  created: function() {
    window.setInterval(this.refreshLog, 1000);
    this.textData = this.text;

    // if this is a log not on the database
    if(this.id <= -1)
      this.needsPush = true;

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;
    this.editable = (this.date === today && this.creatorid == this.myid);

    // who wrote this?
    var log = this;
    axios
      .get("http://taskman.hanaroenterprise.com/api/getuser.php", {
        params: {
          id: log.creatorid,
        }
      })
      .then(function(response) {
        var username = response.data;
        if (username != 404) {
          log.creator = username;
        }
      })
      .catch(function(error) {
        console.log(error);
      });
  },
  beforeDestroy: function() {
    // update top level
    this.changeLog(this.id, this.textData);
  },
  methods: {
    selectProj: function() {
        this.selectTask(-1, this.projectid);
    },
    textChange() {
      this.textData = this.$refs.editable.innerText;
      this.hasChanges = true;
    },
    refreshLog() {
      if (this.hasChanges) {
        this.hasChanges = false;
        var log = this;

        // needs to create new log on the server
        if(this.needsPush) {
          this.needsPush = false;
          var today = new Date();
          var dd = String(today.getDate()).padStart(2, '0');
          var mm = String(today.getMonth() + 1).padStart(2, '0');
          var yyyy = today.getFullYear();
          today = yyyy + '-' + mm + '-' + dd;

          axios
          .get("http://taskman.hanaroenterprise.com/api/addlog.php", {
            params: {
              text: log.textData,
              date: today,
              creatorid: log.creatorid,
              project_id: log.projectid,
            }
          })
          .then(function(response) {
            log.addLog(log.id, response.data, log.textData, log.projectid, today);
            log.id = response.data;
          })
          .catch(function(error) {
            console.log(error);
          });
          return;
        }

        // needs to delete the log off the server: text empty
        if(this.textData === "") {
          axios
          .get("http://taskman.hanaroenterprise.com/api/deletelog.php", {
            params: {
              id: log.id,
              project_id: log.projectid,
            }
          })
          .then(function(response) {
            console.log(response);
            log.needsPush = true;
          })
          .catch(function(error) {
            console.log(error);
          });
          return;
        }

        // update database
        axios
          .get("http://taskman.hanaroenterprise.com/api/updatelog.php", {
            params: {
              text: log.textData,
              id: log.id
            }
          })
          .then(function(response) {
            console.log(response);
          })
          .catch(function(error) {
            console.log(error);
          });
      }
    }
  }
};
</script>

<style scoped>
textarea {
  border: none;
  resize: none;
  width: 225px;
  font-family: "Source Code Pro", monospace;
  color: black;
  margin-left: 10px;
  overflow: hidden;
  font-size: 13px;
}

.textarea:focus {
  outline: none;
}

.textarea {
  resize: none;
  display: block;
  width: 225px;
  overflow: hidden;
  font-size: 13px;
  font-family: "Source Code Pro", monospace;
  margin-left: 10px;
  cursor: text;
  min-height: 13px;
  line-height: 16px;
}

.textarea[contenteditable]:empty::before {
  content: "Enter what you did today";
  color: gray;
}

p {
  font-size: 13px;
  width: 225px;
  margin-top: 0;
  margin-bottom: 0;
  text-align: right;
}

</style>
