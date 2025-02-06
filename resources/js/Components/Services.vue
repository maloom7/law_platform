<template>
    <v-container>
      <v-card>
        <v-card-title>إدارة الخدمات</v-card-title>
        <v-card-text>
          <v-btn color="primary" @click="openCreateDialog">إضافة خدمة</v-btn>
          <v-data-table
            :headers="headers"
            :items="services"
            :items-per-page="10"
            class="elevation-1"
          >
            <template v-slot:item.actions="{ item }">
              <v-icon small @click="editService(item)">mdi-pencil</v-icon>
              <v-icon small @click="deleteService(item)">mdi-delete</v-icon>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
  
      <!-- نموذج إنشاء/تعديل الخدمة -->
      <v-dialog v-model="dialog" max-width="500px">
        <v-card>
          <v-card-title>{{ formTitle }}</v-card-title>
          <v-card-text>
            <v-form ref="form">
              <v-text-field v-model="editedItem.name" label="اسم الخدمة" required></v-text-field>
              <v-textarea v-model="editedItem.description" label="وصف الخدمة"></v-textarea>
              <v-text-field v-model="editedItem.price" label="السعر" type="number" required></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="blue darken-1" text @click="closeDialog">إلغاء</v-btn>
            <v-btn color="blue darken-1" text @click="saveService">حفظ</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </template>
  
  <script>
  export default {
    data() {
      return {
        services: [],
        headers: [
          { text: 'اسم الخدمة', value: 'name' },
          { text: 'الوصف', value: 'description' },
          { text: 'السعر', value: 'price' },
          { text: 'الإجراءات', value: 'actions' },
        ],
        dialog: false,
        editedItem: {
          id: null,
          name: '',
          description: '',
          price: 0,
        },
        defaultItem: {
          id: null,
          name: '',
          description: '',
          price: 0,
        },
      };
    },
    computed: {
      formTitle() {
        return this.editedItem.id ? 'تعديل الخدمة' : 'إضافة خدمة';
      },
    },
    async created() {
      this.fetchServices();
    },
    methods: {
      async fetchServices() {
        const response = await axios.get('/api/services');
        this.services = response.data;
      },
      openCreateDialog() {
        this.editedItem = { ...this.defaultItem };
        this.dialog = true;
      },
      editService(item) {
        this.editedItem = { ...item };
        this.dialog = true;
      },
      async deleteService(item) {
        if (confirm('هل أنت متأكد من حذف هذه الخدمة؟')) {
          await axios.delete(`/api/services/${item.id}`);
          this.fetchServices();
        }
      },
      closeDialog() {
        this.dialog = false;
        this.editedItem = { ...this.defaultItem };
      },
      async saveService() {
        if (this.editedItem.id) {
          await axios.put(`/api/services/${this.editedItem.id}`, this.editedItem);
        } else {
          await axios.post('/api/services', this.editedItem);
        }
        this.fetchServices();
        this.closeDialog();
      },
    },
  };
  </script>