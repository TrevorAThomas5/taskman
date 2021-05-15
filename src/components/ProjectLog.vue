<template>
    <div>
        <h5>------ {{date}} ------</h5>
        <div class='projects-wrap'>
        <div class='project' v-for='(proj, key) in byProject' :key='proj.id'>
                <h6>{{key}}</h6>
                <Log v-for='log in proj.logs' :key='log.id' :text='log.text' :id='log.id' 
                :changeLog='changeLog' :addLog='addLog' :projectid='proj.id'  :creatorid='log.creatorid' 
                :selectTask='selectTask' :date='date' :myid='creatorid'/>
        </div>
        </div>
    </div>
</template>

<script>
import Log from './Log.vue';

export default {
  name: "ProjectLog",
  components: {
      Log,
  },
  props: {
      date: String,
      logs: Array,
      changeLog: Function,
      addLog: Function,
      creatorid: Number,
      selectTask: Function
  },
  data: function() {
    return {};
  },
  computed: {
    byProject() {
        var projects = {};
        for(var log of this.logs) {
            // create new project if it doesn't exist already
            if(!projects[log.project_title]) {
                projects[log.project_title] = {};
                projects[log.project_title].id = log.project_id;
                projects[log.project_title].logs = [];
            }

            // add the log to this project
            projects[log.project_title].logs.push({
                text: log.text,
                id: log.id,
                project_id: log.project_id,
                creatorid: log.creatorid,
            });

        }

        return projects;
    },
  }
};
</script>

<style scoped>

h5 {
    text-align: center;
    font-size: 16px;
    margin: 0;
    margin-bottom: 10px;
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

</style>