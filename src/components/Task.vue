<template>
<div class='task-wrap' v-on:click='selectMe' :style='focused'>
    <input v-model='cross' @change='checked' type='checkbox' >
    <h6 id='title' :style='crossed'>{{this.title}}</h6>
    <div id='edit-wrap' class='bonus-wrap'>
    <img id='edit' src='/pen.svg'>
    <div id='edit-bonus' class='bonus'>
            <h5>Edit this task.</h5>
        </div>
    </div>

    <div class='bonus-wrap'>
        <img v-on:click='deleteMe' id='trash' src='/trash.svg'>
        <div id='delete-bonus' class='bonus'>
            <h5>Delete this task.</h5>
        </div>
    </div>
    
    <h6 id='date' :style='dateColor'>{{this.date}}</h6>   
</div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Task',
  props: {
    title: String,
    category: String,
    date: String,
    dateS: Date,
    isDone: Boolean,
    description: String,
    image: String,
    collaborators: Array,
    check: Function,
    id: Number,
    selectTask: Function,
    deleteTask: Function,
    focusedOn: Boolean,
    project_id: Number,
  },
  data: function() {
    return {
        cross: this.isDone,
    };
  },
  computed: {
      dateColor() {
          var today = new Date();
          var diff = Math.floor((this.dateS - today) / (1000*60*60*24));
          if(diff < 0) {
              return {
                  fontWeight: '800',
              };
          }
          else if(diff < 2) {
              return {
                  fontWeight: '700',
              };
          }
          else if(diff < 4) {
              return {
                  fontWeight: '600',
              };
          }
          else if(diff < 6) {
              return {
                  fontWeight: '500',
              };
          }
          else if(diff < 8) {
              return {
                  fontWeight: '400',
              };
          }
          else if(diff < 10) {
              return {
                  fontWeight: '300',
              };
          }
          else if(diff < 12) {
              return {
                  fontWeight: '200',
              };
          }
          else {
              return { 
                  fontWeight: '100',
                }
          }
      },
      focused() {
          if(this.focusedOn) {
              return {
                  background: '#dbdcd7',
              }
          }
          else
            return {};
      },
      crossed() {
          if(this.cross) {
              return {
                  textDecoration: 'line-through',
              }
          }
          else {
            return {}
          }
      }
  },
  methods: {
      deleteMe() {
            this.deleteTask(this.id);
            
            // update database
            var task = this;
            axios.get('http://taskman.hanaroenterprise.com/api/deletetask.php',
            {
              params: {
                'id': task.id,
                'project_id': task.project_id,
              },
            })
            .then(function(response) {
              console.log(response);
            })
            .catch(function(error) {
              console.log(error);
            });
      },
      selectMe() {
          this.selectTask(this.id);
      },
      checked() {
          this.check(this.id, !this.isDone);
      }
  }
}
</script>

<style scoped>

#date {

    margin-right: 15px;
}

#title {
    font-weight: 600;
}

h6 {
    font-weight: 300;
    margin: 0;
    font-size: 12px;
    margin-left: 5px;
}

input {
    margin-left: 10px;
}

.task-wrap {
    width: 100%;
    display: flex;
    flex-direction: row;
    align-items: center;
    cursor: pointer;
}

.task-wrap:hover {
    background: #dbdcd7;
}

.task-wrap:hover img {
    visibility: visible;
}

img {
    cursor: pointer;
    visibility: hidden;
    height: 14px;
    width: 14px;
}

img:hover {
  filter: drop-shadow(0px 0px 1px black);
}

#edit {
    margin-left: auto;
}

#trash {
    margin-left: 10px;
    margin-right: 3px;
}


.bonus {
  border: 2px solid black;
  position: fixed;
  z-index: 10;
  background: white;
  padding: 5px;
  text-align: center;
  visibility: hidden;
  overflow: visible;
}

.bonus-wrap {
  position: relative;
}

.bonus-wrap:hover .bonus {
  visibility: visible;
}

#delete-bonus {
  height: 37px;
  width: 90px;
  transform: translate(25px, -73px);
}

h5 {
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