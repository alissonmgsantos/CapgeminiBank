<template>
  <v-col>
    <v-card max-width="344" class="mx-auto">
      <v-form>
        <v-container>
          <v-col class="d-flex flex-column align-center">
            <v-img :src="logo" width="120px" height="120px"></v-img>
            <v-list-item-title class="headline font-weight-black">Capgemini Bank</v-list-item-title>
            <v-list-item-subtitle>Cadastre-se</v-list-item-subtitle>
          </v-col>
          <v-col>
            <v-text-field label="Nome" v-model="user.name"  outlined ></v-text-field>

            <v-text-field label="E-mail" v-model="user.email" outlined></v-text-field>

            <v-text-field
              label="Senha"
              v-model="user.password"
              :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
              :type="showPassword ? 'text' : 'password'"
              @click:append="showPassword = !showPassword"
              outlined
            ></v-text-field>
            <v-col class="d-flex flex-column pt-0">
              <v-btn text color="primary" @click="signup(user)">Cadastrar</v-btn>
              <v-btn text color="primary" to="/signin">Acessar conta</v-btn>
            </v-col>
          </v-col>
        </v-container>
      </v-form>
    </v-card>
    <v-snackbar v-model="snackbar">{{ text }}</v-snackbar>
  </v-col>
</template>

<script>
import api from '../config/api';
import logo from "@/assets/logo.png";
export default {
  data: () => ({
    user: { name: "", email: "", password: "" },
    logo,
    showPassword: false,
    snackbar: false,
    text: '',
  }),
   created() {
    if (localStorage.getItem("user")) {
      return (location.hash = "/home");
    }
  },
   methods: {
     signup({ name, email, password }) {
       api.post("/session/signup", { name, email, password })
       .then(() =>  location.hash = '/signin')
       .catch(error => this.text = error.response.data. message)
       .finally(() => this.snackbar = true);
    },
  },
};
</script>
