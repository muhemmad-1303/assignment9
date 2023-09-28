<template>
  <div class="mainlogin">
    <div class="logincard">
      <form method="post" @submit.prevent="handleLogin">
        <div class="inputbox">
          <label>User Name:</label>
          <input type="text" v-model="form.name" />
        </div>
        <div class="inputbox">
          <label>Password:</label>
          <input type="password" v-model="form.password" />
        </div>
        <button type="submit">Sign In</button>
        <div class="createMe">
          <router-link to="/register">Create Account</router-link>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
export default {
  setup() {
    const errors = ref();
    const router = useRouter();
    const form = reactive({
      name: "",
      password: "",
    });
    const handleLogin = (evnt) => {
      evnt.preventDefault();
      fetch("/api/auth/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(form),
      })
        .then((response) => {
          if (response.ok) {
            return response.json();
          } else {
            return response.json().then((data) => {
              throw data;
            });
          }
        })
        .then((result) => {
          if (result.token) {
            localStorage.setItem("APP_DEMO_USER_TOKEN", result.token);
            router.push("home");
          }
        })
        .catch((error) => {
          if (error.errors) {
            errors.value = Object.values(error.errors);
          }
          console.error("Error:", error);
        });
    };

    return {
      form,
      errors,
      handleLogin,
    };
  },
};
</script>

<style scoped>
.mainlogin {
  background-color: #d0e7d2;
  width: 100vw;
  height: 100vh;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.logincard {
  background-color: #f3f3f6;
  padding: 30px;
  border-radius: 20px;
  box-shadow: 2px 2px 8px #d8d7e1;
  color: rgb(0, 0, 0);
  font-family: Arial, Helvetica, sans-serif;
  width: 300px;
  display: flex;
  flex-direction: column;
  gap: 20px;
  padding-bottom: 50px;
}

.logincard form .inputbox {
  display: flex;
  flex-direction: column;
  gap: 1px;
  margin-bottom: 10px;
}

.logincard form .inputbox input[type="text"],
.logincard form .inputbox input[type="password"] {
  outline: 0;
  border: 0;
  border-radius: 10px;
  box-shadow: 1px 1px 2px white;
  padding: 10px;
}
.logincard button[type="submit"] {
  border-radius: 8px;
  font-family: "Montserrat", sans-serif;
  color: #fafafa;
  font-size: 20px;
  background-color: #1877f2;
  display: block;
  width: 100%;
  padding: 10px;
  box-sizing: border-box;
  border: 0;
  margin: 20px auto;
  text-align: center;
}
a {
  all: unset;
}
.createMe {
  background-color: #00a400;
  border-radius: 8px;
  font-family: "Montserrat", sans-serif;
  color: #fafafa;
  font-size: 16px;
  display: block;
  margin: 0px auto 0px auto;
  width: 80%;
  padding: 10px;
  text-align: center;
  border: 0;
}
</style>