<template>
  <div>
    <ProjectLog
      v-for="(val, key) in byDate"
      :key="key"
      :date="key"
      :logs="val"
      :changeLog="changeLog"
      :addLog="addLog"
      :creatorid="creatorid"
      :selectTask='selectTask'
    />
  </div>
</template>

<script>
import ProjectLog from "./ProjectLog.vue";

export default {
  name: "Logs",
  components: {
    ProjectLog,
  },
  props: {
    projects: Array,
    changeLog: Function,
    creatorid: Number,
    addLog: Function,
    selectTask: Function,
  },
  data: function() {
    return {};
  },
  computed: {
    byDate() {
      var dates = {};
      for (var proj of this.projects) {
        for (var log of proj.logs) {
          // check if array already exists
          if (!dates[log.date]) dates[log.date] = [];

          // push this log to the array
          dates[log.date].push({
            text: log.text,
            id: log.id,
            project_title: proj.title,
            project_id: log.project_id,
            creatorid: log.creatorid
          });
        }
      }

      return dates;
    }
  }
};
</script>

<style scoped>

.log-text {
  margin: 0;
  margin-left: 10px;
  font-size: 12px;
}

h6 {
  font-size: 14px;
  margin: 0;
  margin-left: 10px;
}

.project {
        width: 50%;

}

.projects-wrap {
    display: flex;
    flex-wrap: wrap;
    flex-direction: row;
    margin-bottom: 20px;
}

h5 {
    text-align: center;
    font-size: 16px;
    margin: 0;
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
