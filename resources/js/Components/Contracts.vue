<template>
    <v-container>
      <v-card>
        <v-card-title>إدارة العقود</v-card-title>
        <v-card-text>
          <v-btn color="primary" @click="openCreateDialog">إضافة عقد</v-btn>
          <v-data-table
            :headers="headers"
            :items="contracts"
            :items-per-page="10"
            class="elevation-1"
          >
            <template v-slot:item.actions="{ item }">
              <v-icon small @click="editContract(item)">mdi-pencil</v-icon>
              <v-icon small @click="deleteContract(item)">mdi-delete</v-icon>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
  
      <!-- نموذج إنشاء/تعديل العقد -->
      <v-dialog v-model="dialog" max-width="500px">
        <v-card>
          <v-card-title>{{ formTitle }}</v-card-title>
          <v-card-text>
            <v-form ref="form">
              <v-text-field v-model="editedItem.contract_number" label="رقم العقد" required></v-text-field>
              <v-text-field v-model="editedItem.contract_date" label="تاريخ العقد" type="date" required></v-text-field>
              <v-text-field v-model="editedItem.end_date" label="تاريخ الانتهاء" type="date" required></v-text-field>
              <v-select v-model="editedItem.client_id" :items="clients" label="العميل" item-text="name" item-value="id" required></v-select>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="blue darken-1" text @click="closeDialog">إلغاء</v-btn>
            <v-btn color="blue darken-1" text @click="saveContract">حفظ</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </template>
  
  <script>
  export default {
    data() {
      return {
        contracts: [],
        clients: [],
        headers: [
          { text: 'رقم العقد', value: 'contract_number' },
          { text: 'تاريخ العقد', value: 'contract_date' },
          { text: 'تاريخ الانتهاء', value: 'end_date' },
          { text: 'الإجراءات', value: 'actions' },
        ],
        dialog: false,
        editedItem: {
          id: null,
          contract_number: '',
          contract_date: '',
          end_date: '',
          client_id: null,
        },
        defaultItem: {
          id: null,
          contract_number: '',
          contract_date: '',
          end_date: '',
          client_id: null,
        },
      };
    },
    computed: {
      formTitle() {
        return this.editedItem.id ? 'تعديل العقد' : 'إضافة عقد';
      },
    },
    async created() {
      this.fetchContracts();
      this.fetchClients();
    },
    methods: {
      async fetchContracts() {
        const response = await axios.get('/api/contracts');
        this.contracts = response.data;
      },
      async fetchClients() {
        const response = await axios.get('/api/clients');
        this.clients = response.data;
      },
      openCreateDialog() {
        this.editedItem = { ...this.defaultItem };
        this.dialog = true;
      },
      editContract(item) {
        this.editedItem = { ...item };
        this.dialog = true;
      },
      async deleteContract(item) {
        if (confirm('هل أنت متأكد من حذف هذا العقد؟')) {
          await axios.delete(`/api/contracts/${item.id}`);
          this.fetchContracts();
        }
      },
      closeDialog() {
        this.dialog = false;
        this.editedItem = { ...this.defaultItem };
      },
      async saveContract() {
        if (this.editedItem.id) {
          await axios.put(`/api/contracts/${this.editedItem.id}`, this.editedItem);
        } else {
          await axios.post('/api/contracts', this.editedItem);
        }
        this.fetchContracts();
        this.closeDialog();
      },
    },
  };
  </script>