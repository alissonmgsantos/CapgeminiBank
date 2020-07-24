<template>
  <div class="text-center">
    <v-dialog v-model="openWithdrawing" width="300">
      <v-card>
        <v-card-title class="headline">Sacar</v-card-title>
        <v-card-text>
          <h4>Saldo atual: {{balance}}</h4>
        </v-card-text>
        <v-container>
          <v-text-field name="value" v-model="value" label="Valor" outlined></v-text-field>
        </v-container>
        <v-card-actions>
          <v-spacer></v-spacer>
             <v-btn color="error" text @click="closeDialog()">Cancelar</v-btn>
          <v-btn color="primary" text @click="withDrawing(value)">Sacar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import api from '../config/api';
export default {
  props: ["balance", "openWithdrawing"],
  data: () => ({
    value: null
  }),
  methods: {
    closeDialog() {
      this.$emit("closeDialog");
    },
      withDrawing(value) {
      const { token } = JSON.parse(localStorage.getItem("user"));
      api.post('/account/withDrawing', {value}, {
        headers: { Authorization: `Bearer ${token}` },
      })
         .then(() => this.$emit("closeDialog"))
    }
  },
};
</script>
