<template>
    <v-container>
      <v-card>
        <v-card-title>إدارة العملاء</v-card-title>
        <v-card-text>
          <v-btn color="primary" @click="openCreateDialog">إضافة عميل</v-btn>
          <v-data-table
            :headers="headers"
            :items="clients"
            :items-per-page="10"
            class="elevation-1"
          >
            <template v-slot:item.actions="{ item }">
              <v-icon small @click="editClient(item)">mdi-pencil</v-icon>
              <v-icon small @click="deleteClient(item)">mdi-delete</v-icon>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
  
      <!-- نموذج إنشاء/تعديل العميل -->
      <v-dialog v-model="dialog" max-width="500px">
        <v-card>
          <v-card-title>{{ formTitle }}</v-card-title>
          <v-card-text>
            <v-form ref="form">
              <v-text-field v-model="editedItem.name" label="اسم العميل" required></v-text-field>
              <v-text-field v-model="editedItem.email" label="البريد الإلكتروني" required></v-text-field>
              <v-text-field v-model="editedItem.phone" label="رقم الهاتف" required></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="blue darken-1" text @click="closeDialog">إلغاء</v-btn>
            <v-btn color="blue darken-1" text @click="saveClient">حفظ</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </template>
  
  <script>
  export default {
    data() {
      return {
        clients: [],
        headers: [
          { text: 'اسم العميل', value: 'name' },
          { text: 'البريد الإلكتروني', value: 'email' },
          { text: 'رقم الهاتف', value: 'phone' },
          { text: 'الإجراءات', value: 'actions' },
        ],
        dialog: false,
        editedItem: {
          id: null,
          name: '',
          email: '',
          phone: '',
        },
        defaultItem: {
          id: null,
          name: '',
          email: '',
          phone: '',
        },
      };
    },
    computed: {
      formTitle() {
        return this.editedItem.id ? 'تعديل العميل' : 'إضافة عميل';
      },
    },
    async created() {
      this.fetchClients();
    },
    methods: {
      async fetchClients() {
        const response = await axios.get('/api/clients');
        this.clients = response.data;
      },
      openCreateDialog() {
        this.editedItem = { ...this.defaultItem };
        this.dialog = true;
      },
      editClient(item) {
        this.editedItem = { ...item };
        this.dialog = true;
      },
      async deleteClient(item) {
        if (confirm('هل أنت متأكد من حذف هذا العميل؟')) {
          await axios.delete(`/api/clients/${item.id}`);
          this.fetchClients();
        }
      },
      closeDialog() {
        this.dialog = false;
        this.editedItem = { ...this.defaultItem };
      },
      async saveClient() {
        if (this.editedItem.id) {
          await axios.put(`/api/clients/${this.editedItem.id}`, this.editedItem);
        } else {
          await axios.post('/api/clients', this.editedItem);
        }
        this.fetchClients();
        this.closeDialog();
      },
    },
  };
  </script>