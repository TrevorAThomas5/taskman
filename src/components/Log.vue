<template>
  <div>
    <span
      ref="editable"
      v-on:input="textChange"
      spellcheck="false"
      class="textarea"
      role="textbox"
      contenteditable
            v-on:click='selectProj'

      >{{ text }}</span>
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
  },
  data: function() {
    return {
      textHeight: "",
      hasChanges: false,
      textData: "",
      needsPush: false,
    };
  },
  created: function() {
    window.setInterval(this.refreshLog, 1000);
    this.textData = this.text;

    // if this is a log not on the database
    if(this.id <= -1) {
      this.needsPush = true;
    }
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
            console.log(response);
            log.addLog(log.id, response.data, log.textData, log.projectid, today);
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
</style>
