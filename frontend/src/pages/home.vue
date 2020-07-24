

<template>
  <v-container>
    <my-WithdrawingDialog
      :openWithdrawing="openWithdrawing"
      v-on:closeDialog="closeDialog"
      :balance="formatter.format(parseFloat(user.balance))"
    ></my-WithdrawingDialog>
    <my-DepositDialog
      :openDeposit="openDeposit"
      v-on:closeDialog="closeDialog"
      :balance="formatter.format(parseFloat(user.balance))"
    ></my-DepositDialog>
    <v-col class="d-flex flex-row">
      <v-img :src="logo" width="64" height="64"></v-img>
      <v-list-item-title class="headline font-weight-black">Capgemini Bank</v-list-item-title>
      <v-col>
        <v-icon size="32" @click="signout()">mdi-power</v-icon>
      </v-col>
    </v-col>
    <v-col class="d-flex justify-center">
      <v-card max-width="400" elevation="0">
        <v-col class="d-flex flex-column align-center">
          <v-img :src="logo" width="120px" height="120px"></v-img>
        </v-col>
        <v-list-item two-line>
          <v-list-item-content align="center" v-if="!user.name">
            <v-progress-circular :value="20" indeterminate color="primary"></v-progress-circular>
          </v-list-item-content>
          <v-list-item-content align="center" v-if="user.name">
            <v-list-item-title class="headline">{{user.name}}</v-list-item-title>
            <v-list-item-subtitle>Agência: {{user.agency}} | Conta: {{user.account_number}}</v-list-item-subtitle>
            <v-list-item-subtitle>Saldo atual: {{formatter.format(parseFloat(user.balance))}}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-card>
    </v-col>
    <v-container lg="12" class="d-flex justify-center">
      <v-card
        max-width="300"
        elevation="0"
        class="m-1 cursor"
        v-on:click="openWithdrawing = !openWithdrawing"
      >
        <v-col class="d-flex flex-column justify-center">
          <v-icon size="64px">mdi-bank</v-icon>
          <v-list-item-title>Sacar</v-list-item-title>
        </v-col>
      </v-card>

      <v-card
        max-width="300"
        elevation="0"
        class="m-1 cursor"
        v-on:click="openDeposit = !openDeposit"
      >
        <v-col class="d-flex flex-column justify-center">
          <v-icon size="64px">mdi-piggy-bank</v-icon>
          <v-list-item-title>Depositar</v-list-item-title>
        </v-col>
      </v-card>
    </v-container>
  </v-container>
</template>

<script>
import api from "../config/api";
import logo from "@/assets/logo.png";
import WithdrawingDialog from "../components/WithdrawingDialog";
import DepositDialog from "../components/DepositDialog";

export default {
  components: {
    "my-WithdrawingDialog": WithdrawingDialog,
    "my-DepositDialog": DepositDialog,
  },
  data: () => ({
    logo,
    user: {},
    openWithdrawing: false,
    openDeposit: false,
    formatter: new Intl.NumberFormat([], {
      style: "currency",
      currency: "BRL",
    }),
  }),
  created() {
    this.profile();
  },
  methods: {
    closeDialog() {
      this.openWithdrawing = false;
      this.openDeposit = false;
      this.profile();
    },
    profile() {
      if (!localStorage.getItem("user")) {
        location.hash = "/signin";
      }
      const { token } = JSON.parse(localStorage.getItem("user"));
      api
        .get("/session/profile", {
          headers: { Authorization: `Bearer ${token}` },
        })
        .then((response) => {
          this.user = response.data;
        });
    },
    signout() {
      const { token } = JSON.parse(localStorage.getItem("user"));
      api
        .get("/session/signout", {
          headers: { Authorization: `Bearer ${token}` },
        })
        .then(() => {
          localStorage.removeItem("user");
          location.hash = "/signin";
        });
    },
  },
};
</script>

<style scoped>
.cursor {
  cursor: pointer;
}
</style>