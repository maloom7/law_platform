<template>
    <v-container>
      <v-card>
        <v-card-title>إدارة القضايا</v-card-title>
        <v-card-text>
          <v-btn color="primary" @click="openCreateDialog">إضافة قضية</v-btn>
          <v-data-table
            :headers="headers"
            :items="legalCases"
            :items-per-page="10"
            class="elevation-1"
          >
            <template v-slot:item.actions="{ item }">
              <v-icon small @click="editLegalCase(item)">mdi-pencil</v-icon>
              <v-icon small @click="deleteLegalCase(item)">mdi-delete</v-icon>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
  
      <!-- نموذج إنشاء/تعديل القضية -->
      <v-dialog v-model="dialog" max-width="500px">
        <v-card>
          <v-card-title>{{ formTitle }}</v-card-title>
          <v-card-text>
            <v-form ref="form">
              <v-text-field v-model="editedItem.case_number" label="رقم القضية" required></v-text-field>
              <v-text-field v-model="editedItem.internal_file_number" label="رقم الملف الداخلي" required></v-text-field>
              <v-text-field v-model="editedItem.start_date" label="تاريخ البدء" type="date" required></v-text-field>
              <v-text-field v-model="editedItem.case_type" label="نوع القضية" required></v-text-field>
              <v-text-field v-model="editedItem.court" label="المحكمة" required></v-text-field>
              <v-select v-model="editedItem.client_id" :items="clients" label="العميل" item-text="name" item-value="id" required></v-select>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="blue darken-1" text @click="closeDialog">إلغاء</v-btn>
            <v-btn color="blue darken-1" text @click="saveLegalCase">حفظ</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </template>
  
  <script>
  export default {
    data() {
      return {
        legalCases: [],
        clients: [],
        headers: [
          { text: 'رقم القضية', value: 'case_number' },
          { text: 'نوع القضية', value: 'case_type' },
          { text: 'المحكمة', value: 'court' },
          { text: 'الإجراءات', value: 'actions' },
        ],
        dialog: false,
        editedItem: {
          id: null,
          case_number: '',
          internal_file_number: '',
          start_date: '',
          case_type: '',
          court: '',
          client_id: null,
        },
        defaultItem: {
          id: null,
          case_number: '',
          internal_file_number: '',
          start_date: '',
          case_type: '',
          court: '',
          client_id: null,
        },
      };
    },
    computed: {
      formTitle() {
        return this.editedItem.id ? 'تعديل القضية' : 'إضافة قضية';
      },
    },
    async created() {
      this.fetchLegalCases();
      this.fetchClients();
    },
    methods: {
      async fetchLegalCases() {
        const response = await axios.get('/api/legal-cases');
        this.legalCases = response.data;
      },
      async fetchClients() {
        const response = await axios.get('/api/clients');
        this.clients = response.data;
      },
      openCreateDialog() {
        this.editedItem = { ...this.defaultItem };
        this.dialog = true;
      },
      editLegalCase(item) {
        this.editedItem = { ...item };
        this.dialog = true;
      },
      async deleteLegalCase(item) {
        if (confirm('هل أنت متأكد من حذف هذه القضية؟')) {
          await axios.delete(`/api/legal-cases/${item.id}`);
          this.fetchLegalCases();
        }
      },
      closeDialog() {
        this.dialog = false;
        this.editedItem = { ...this.defaultItem };
      },
      async saveLegalCase() {
        if (this.editedItem.id) {
          await axios.put(`/api/legal-cases/${this.editedItem.id}`, this.editedItem);
        } else {
          await axios.post('/api/legal-cases', this.editedItem);
        }
        this.fetchLegalCases();
        this.closeDialog();
      },
    },
  };
  </script>