<template>
<div style='margin-top: 5px;'>
<div class='cat-wrap' :style='focus' v-on:click='projClick'>
    <div class='title-wrap'>
      <h5>{{this.title}}</h5>


      

    <div id='edit-wrap' class='bonus-wrap'>
        <img :style='imgFocus' id='pen' src='pen.svg' >
        <div :style='tooltip' id='edit-bonus' class='bonus'>
            <h5 class='bonus-text'>Edit this project.</h5>
        </div>
    </div>
    <div id='delete-wrap' class='bonus-wrap'>

      <img :style='imgFocus' id='skull' src='skull.svg' v-on:click='deleteClick'>
      <div :style='tooltip' id='delete-bonus' class='bonus'>
            <h5 class='bonus-text'>Delete this project.</h5>
        </div>
    </div>
    </div>
    <div v-if='this.tasks.length >= 1' >
      <Task v-for='task in tasks' :key='task.id' :title='task.title' :date='task.date' 
          :isDone='(task.isDone == 1 ? true : false)' :id='task.id' :check='check' 
          :selectTask='selectTask' :category='title' :deleteTask="deleteTask" 
          :focusedOn='task.focusedOn' :dateS='task.dateS' :project_id="task.project_id"/>
    </div>
    <div v-else>
      <h6>- There are currently no tasks for this project.</h6>
    </div>
</div>
</div>
</template>

<script>
import Task from './Task.vue';

export default {
  name: 'Category',
  components: {
      Task,
  },
  props: {
    title: String,
    tasks: Array,
    check: Function,
    selectTask: Function,
    deleteTask: Function,
    isFocused: Boolean,
    index: Number,
    deleteProject: Function,
  },
  data: function() {
    return {
    };
  },
  methods: {
    deleteClick() {
      this.deleteProject(this.index);
    },
    projClick() {
      if(this.tasks.length <= 0) {
        // this project has no tasks, focus on just the project
        this.selectTask(-1, this.index);
      }
    },
  },
  computed: {
    tooltip() {
      if(!this.isFocused) {
        return {
          visibility: 'hidden',
        };
      }
      return {};
    },
    focus() {
      if(this.isFocused) {
        return {
          background: '#F5F5F5',
        };
      }
      return {};
    },
    imgFocus() {
      if(this.isFocused) {
        return {
          visibility: 'visible',
        };
      }
      return {};
    }
  }
}
</script>

<style scoped>

h5 {
    font-size: 14px;
    margin: 0;
    margin-left: 10px;
}

h6 {
    font-weight: 600;
    margin: 0;
    font-size: 12px;
    margin-left: 10px;
    cursor: pointer;
}

.title-wrap {
  display: flex;
  flex-direction: row;
  align-items: center;
}

img {
     margin: 0px;
    cursor: pointer;
    height: 14px;
    width: 14px;
    visibility: hidden;
}

img:hover {
  filter: drop-shadow(0px 0px 1px black);
}

.bonus {
  border: 2px solid black;
  position: fixed;
  background: white;
  padding: 5px;
  text-align: center;
  visibility: hidden;
}

.bonus-wrap {
  position: relative;
}

.bonus-wrap:hover .bonus {
  visibility: visible;
}

#delete-bonus {
  height: 37px;
  width: 100px;
  transform: translate(15px, -73px);
}

#delete-wrap {
  margin-right: 10px;
  margin-left: 5px;
}

.bonus-text {
  margin: 0;
  margin-left: 2px;
  font-weight: 500;
}

#edit-wrap {
    margin-left: auto;
}

#edit-bonus {
    height: 37px;
  width: 90px;
  transform: translate(15px, -73px);
}


</style>