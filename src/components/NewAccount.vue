<template>
<div class='all-wrap'>
<div class='login-wrap'>
    <h2>make account</h2>
    <input v-model='username' placeholder="username" v-on:keyup='enterPress'/>
    <input v-model='password' placeholder='password' type='password' v-on:keyup='enterPress'/>
    <button v-on:click='newAccountClick'>create</button>
</div>
    <div class='bot-wrap'>
      <h3>Already have an account?</h3>
      <h3 v-on:click='logout' class='create'>Login</h3>
  </div>
</div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Login',
  components: {
  },
  props: {
      login: Function,
      logout: Function,
  },
  data: function() {
    return {
      username: '',
      password: '',
    };
  },
  methods: {
    enterPress(e) {
      if(e.keyCode == 13)
        this.newAccountClick();
    },
    newAccountClick() {
      var username = this.username;
      var password = this.password;
      var login = this.login;

      axios.get('http://taskman.hanaroenterprise.com/api/newaccount.php',
      {
        params: {
          'username': username,
          'password': password,
        },
      })
      .then(function(response) {
        var userData = response.data;

        if(userData != 404) 
          login(userData);
        else
          alert('This username has already been taken!');
      })
      .catch(function(error) {
        console.log(error);
      });
    }
  },
}
</script>

<style scoped>

.login-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;

    border: 2px solid black;
    width: 300px;
    height: 235px;
}

input {
  padding: 10px;
  border: 2px solid black;
  width: 200px;
  height: 10px;
  font-family: 'Source Code Pro', monospace;
  font-size: 12px;
  margin-bottom: 20px;
}

input:focus {
    outline: none;
}

h2 {
    font-weight: 400;
}

button {
  border: 2px solid black;
  width: 220px;
  height: 30px;
  font-family: 'Source Code Pro', monospace;
  font-size: 12px;
 color: white;
  background: black;
    cursor: pointer;

}

button:hover {
    background: #222222;
    
}


.all-wrap {
      position: fixed;
left: 50vw;
    top: 50vh;
        transform: translate(-50%, -50%);
}

.bot-wrap {
  display: flex;
  flex-direction: row;
  align-items: center;
  justify-content: center;
}

h3 {
  margin: 0;
  font-size: 14px;
  font-weight: 500;
}

.create {
  margin-left: 5px;
  font-weight: bold;
  cursor: pointer;
}

.create:hover {
  filter: drop-shadow(0px 0px 1px black);
}

</style>