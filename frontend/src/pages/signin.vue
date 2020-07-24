<template>
  <v-card max-width="344" class="mx-auto">
    <v-form>
      <v-container>
        <v-col class="d-flex flex-column align-center">
          <v-img :src="logo" width="120px" height="120px"></v-img>
          <v-list-item-title class="headline font-weight-black">Capgemini Bank</v-list-item-title>
          <v-list-item-subtitle>Acessar conta</v-list-item-subtitle>
        </v-col>
        <v-col>
          <v-text-field label="E-mail" name="email" v-model="user.email" outlined></v-text-field>

          <v-text-field
            label="Senha"
            name="password"
            v-model="user.password"
            :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
            :type="showPassword ? 'text' : 'password'"
            @click:append="showPassword = !showPassword"
            outlined
          ></v-text-field>
          <v-col class="d-flex flex-column pt-0">
            <v-btn text color="primary" @click="signin(user)">Entrar</v-btn>
            <v-btn text color="primary" to="/signup">Cadastre-se</v-btn>
          </v-col>
        </v-col>
      </v-container>
    </v-form>
  </v-card>
</template>

<script>
import api from "../config/api";
import logo from "@/assets/logo.png";
export default {
  data: () => ({
    user: { email: "", password: "" },
    logo,
    showPassword: false,
  }),
  created() {
    if (localStorage.getItem("user")) {
      return (location.hash = "/home");
    }
  },
  methods: {
    async signin({ email, password }) {
      const result = await api.post("/session/signin", { email, password });
      await localStorage.setItem(
        "user",
        JSON.stringify({
          token: result.data.access_token,
          expires_at: result.data.expires_at,
        })
      );
      location.hash = "/home";
    },
  },
};
</script>
