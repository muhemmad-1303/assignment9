<template>
  <div class="mainregister">
    <welcome></welcome>
    <div class="registercard">
      <h1>Create New</h1>
      <form @submit.prevent="handleSubmit">
        <div class="registerbody">
          <div class="inputbox">
            <label>User name:</label>
            <input type="text" v-model="form.name" />
            <span v-if="errors && errors.name">{{ errors && errors.name.join('')}}</span>
          </div>
          <div class="inputbox">
            <label>Email:</label>
            <input type="text" v-model="form.email" />
            <span v-if="errors && errors.email">{{ errors && errors.email.join('')}}</span>
           
          </div>
          <div class="inputbox">
            <label>password</label>
            <input type="password" v-model="form.password" />
            <span v-if="errors && errors.password">{{ errors && errors.password.join('')}}</span>
           
          </div>
          <button type="submit">Sign Up</button>
        </div>
      </form>
      <div id="signin">
        <router-link to="/">Already have an account?SignIn</router-link>
      </div>
    </div>
  </div>
</template>
<script>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import Welcome from '../components/Welcome.vue';

export default {
  components: { Welcome },
  setup() {
    const errors = ref();
    let router = useRouter();
    const form = reactive({
      name: "",
      email: "",
      password: "",
    });
    const handleSubmit = async (evt) => {
      evt.preventDefault();
      fetch('/api/auth/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(form),
      })
        .then((response) => {
          if(response.ok) {
            return response.json();
          } else {
            return response.json().then((data) => {
              throw data;
            });
          }
        })
        .then((result) => {
          if (result.token) {
            localStorage.setItem('APP_DEMO_USER_TOKEN', result.token);
            router.push('home');
          }
        })
        .catch((error) => {
          if (error.errors) {
            errors.value = error.errors;
            console.log(errors.value);
          }
          console.error('Error:', error);
        });
    };

    return {
      form,
      errors,
      handleSubmit,
    };
  },
};
</script>
<style scoped>
.registercard {
  background-color: #f3f3f6;
  padding: 30px;
  border-radius: 20px;
  box-shadow: 3px 3px 8px rgb(109, 106, 106);
  color: black;
  font-family: Arial, Helvetica, sans-serif;
  width: 400px;
}

.registercard form .registerbody {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-top: 30px;
}

.registercard form .registerbody .inputbox {
  display: flex;
  flex-direction: column;
  gap: 1px;
}

.registercard form .registerbody .inputbox input[type="text"],
.registercard form .registerbody .inputbox input[type="password"] {
  border: 0;
  outline: 0;
  border-radius: 10px;
  box-shadow: 1px 1px 2px white;
  padding: 10px;
}

button[type="submit"] {
  border-radius: 8px;
  font-family: "Montserrat", sans-serif;
  color: #fafafa;
  font-size: 20px;
  background-color: #00a400;
  display: block;
  margin: 0 auto;
  width: 100%;
  padding: 10px;
  box-sizing: border-box;
  border: 0;
  margin-top: 20px;
  text-align: center;
}

span {
  color: red;
  font-size: x-small;
}
.mainregister {
  background-color: #d0e7d2;
  width: 100vw;
  height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
#signin {
  color: #1877f2;
  text-decoration: underline;
  font-size: smaller;
  text-align: center;
  cursor: pointer;
}
</style>
