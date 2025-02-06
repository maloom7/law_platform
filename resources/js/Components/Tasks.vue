<template>
    <v-container>
      <v-card>
        <v-card-title>إدارة المهام</v-card-title>
        <v-card-text>
          <v-btn color="primary" @click="openCreateDialog">إضافة مهمة</v-btn>
          <v-data-table
            :headers="headers"
            :items="tasks"
            :items-per-page="10"
            class="elevation-1"
          >
            <template v-slot:item.actions="{ item }">
              <v-icon small @click="editTask(item)">mdi-pencil</v-icon>
              <v-icon small @click="deleteTask(item)">mdi-delete</v-icon>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
  
      <!-- نموذج إنشاء/تعديل المهمة -->
      <v-dialog v-model="dialog" max-width="500px">
        <v-card>
          <v-card-title>{{ formTitle }}</v-card-title>
          <v-card-text>
            <v-form ref="form">
              <v-text-field v-model="editedItem.description" label="وصف المهمة" required></v-text-field>
              <v-text-field v-model="editedItem.due_date" label="تاريخ الاستحقاق" type="date" required></v-text-field>
              <v-select v-model="editedItem.priority" :items="priorities" label="الأولوية" required></v-select>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="blue darken-1" text @click="closeDialog">إلغاء</v-btn>
            <v-btn color="blue darken-1" text @click="saveTask">حفظ</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </template>
  
  <script>
  export default {
    data() {
      return {
        tasks: [],
        priorities: ['عالية', 'متوسطة', 'منخفضة'],
        headers: [
          { text: 'وصف المهمة', value: 'description' },
          { text: 'تاريخ الاستحقاق', value: 'due_date' },
          { text: 'الأولوية', value: 'priority' },
          { text: 'الإجراءات', value: 'actions' },
        ],
        dialog: false,
        editedItem: {
          id: null,
          description: '',
          due_date: '',
          priority: '',
        },
        defaultItem: {
          id: null,
          description: '',
          due_date: '',
          priority: '',
        },
      };
    },
    computed: {
      formTitle() {
        return this.editedItem.id ? 'تعديل المهمة' : 'إضافة مهمة';
      },
    },
    async created() {
      this.fetchTasks();
    },
    methods: {
      async fetchTasks() {
        const response = await axios.get('/api/tasks');
        this.tasks = response.data;
      },
      openCreateDialog() {
        this.editedItem = { ...this.defaultItem };
        this.dialog = true;
      },
      editTask(item) {
        this.editedItem = { ...item };
        this.dialog = true;
      },
      async deleteTask(item) {
        if (confirm('هل أنت متأكد من حذف هذه المهمة؟')) {
          await axios.delete(`/api/tasks/${item.id}`);
          this.fetchTasks();
        }
      },
      closeDialog() {
        this.dialog = false;
        this.editedItem = { ...this.defaultItem };
      },
      async saveTask() {
        if (this.editedItem.id) {
          await axios.put(`/api/tasks/${this.editedItem.id}`, this.editedItem);
        } else {
          await axios.post('/api/tasks', this.editedItem);
        }
        this.fetchTasks();
        this.closeDialog();
      },
    },
  };
  </script>