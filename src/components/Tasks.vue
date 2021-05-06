<template>
<div class='tasks-wrap'>
    <Category v-for="cat in tasks" :key='cat.id' :title='cat.title' :tasks='cat.tasks' 
        :check='check' :selectTask='selectTask' :deleteTask="deleteTask" 
        :isFocused='cat.isFocused' :index='cat.id' :deleteProject='deleteProject'/>
</div>
</template>

<script>
import Category from './Category.vue';

export default {
  name: 'Tasks',
  components: {
      Category,
  },
  props: {
      tasks: Array,
      check: Function,
      selectTask: Function,
      deleteTask: Function,
      deleteProject: Function,
  },
  data: function() {
    return {
    };
  },
}
</script>

<style scoped>


</style>
