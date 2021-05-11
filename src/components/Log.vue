<template>
    <div>
        <span spellcheck='false' class="textarea" role="textbox" contenteditable>{{text}}</span>
    </div>
</template>

<script>
export default {
  name: "Log",
  components: {
  },
  props: {
      text: String,
  },
  data: function() {
    return {
        textHeight: '',
    };
  },
  methods: {
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
  content: "Enter what you did today here!";
  color: gray;
}

</style>