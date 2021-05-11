<template>
    <div>
        <h5>Today</h5>
        <ProjectLog v-for='(val, key) in byDate' :key='key' :date='key' :logs='val'/>
    </div>
</template>

<script>
import ProjectLog from './ProjectLog.vue';

export default {
  name: "Logs",
  components: {
      ProjectLog,
  },
  props: {
    projects: Array,
  },
  data: function() {
    return {
    };
  },
  computed:  {
      byDate() {
          var dates = {};
          for(var proj of this.projects) {
              for(var log of proj.logs) {
                // check if array already exists
                if(!dates[log.date])
                    dates[log.date] = [];

                // push this log to the array
                dates[log.date].push({
                    text: log.text,
                    id: log.id,
                    project_title: proj.title,
                    project_id: log.project_id,
                    creatorid: log.creatorid,
                });
              }
          }

          return dates;
      },
  }
};
</script>

<style scoped>

h5 {
    font-size: 16px;
    margin: 0;
    margin-left: 10px;
}

.day-wrap {
  display: flex;
  flex-direction: row;
  padding-bottom: 10px;
}

.proj-wrap {
  width: 250px;
}

.proj-wrap:hover {
  background: #dbdcd7;
}

.log-text {
  margin: 0;
  margin-left: 10px;
  font-size: 12px;
}

</style>
