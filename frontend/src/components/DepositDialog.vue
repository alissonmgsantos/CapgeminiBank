<template>
  <div class="text-center">
    <v-dialog v-model="openDeposit" width="300">
      <v-card>
        <v-card-title class="headline">Depositar</v-card-title>
        <v-card-text>
          <h4>Saldo atual: {{balance}}</h4>
        </v-card-text>
        <v-container>
          <v-text-field name="agency" v-model="depositAccount.agency" label="Agência" outlined   type="number"></v-text-field>
          <v-text-field
            name="account_number"
            v-model="depositAccount.account_number"
            label="Número da conta"
            outlined
            type="number"
          ></v-text-field>
          <v-text-field
            name="value"
            v-model="depositAccount.value"
            label="Valor da transferência"
            outlined
              type="number"
          ></v-text-field>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="error" text @click="closeDialog()">Cancelar</v-btn>
          <v-btn color="primary" text @click="deposit(depositAccount)">Depositar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar v-model="snackbar">{{ text }}</v-snackbar>
  </div>
</template>

<script>
import api from "../config/api";
export default {
  props: ["balance", "openDeposit"],
  data: () => ({
    depositAccount: { agency: null, account_number: null, value: null },
    snackbar: false,
    text: null,
  }),
  methods: {
    closeDialog() {
      this.$emit("closeDialog");
    },
    deposit({ agency, account_number, value }) {
      const { token } = JSON.parse(localStorage.getItem("user"));
      api
        .post(
          "/account/deposit",
          { agency, account_number, value },
          {
            headers: { Authorization: `Bearer ${token}` },
          }
        )
        .then((response) => {
          this.text = response.data.message;
          this.$emit("closeDialog");
        })
        .catch(() => this.text =  'Operação inválida.')
        .finally(() =>  this.snackbar = true);
    },
  },
};
</script>
