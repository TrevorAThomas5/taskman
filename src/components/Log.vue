<template>
  <div>
    <span
      ref="editable"
      v-on:input="textChange"
      spellcheck="false"
      class="textarea"
      role="textbox"
      contenteditable
      >{{ text }}</span
    >
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
  },
  data: function() {
    return {
      textHeight: "",
      hasChanges: false,
      textData: ""
    };
  },
  created: function() {
    window.setInterval(this.refreshLog, 1000);
    this.textData = this.text;
  },
  beforeDestroy: function() {
    // update top level
    this.changeLog(this.id, this.textData);
  },
  methods: {
    textChange() {
      this.textData = this.$refs.editable.innerText;
      this.hasChanges = true;
    },
    refreshLog() {
      if (this.hasChanges) {
        this.hasChanges = false;

        // update database
        var log = this;
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
