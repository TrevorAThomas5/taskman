<template>
<div class='all-wrap'>
<div class='login-wrap'>
    <h2>account login</h2>
    <input v-model='username' placeholder="username" v-on:keyup='enterPress'/>
    <input v-model='password' placeholder='password' type='password' v-on:keyup='enterPress'/>
    <button v-on:click='loginClick'>login</button>
</div>
  <div class='bot-wrap'>
      <h3>Don't have an account?</h3>
      <h3 v-on:click='accountSwitch' class='create'>Create one</h3>
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
      accountSwitch: Function,
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
        this.loginClick();
    },
    loginClick() {
      var username = this.username;
      var password = this.password;
      var login = this.login;
      axios.get('http://taskman.hanaroenterprise.com/api/login.php',
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
          alert('Your username or password is incorrect!');
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

</style>