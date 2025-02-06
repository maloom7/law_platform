<template>
    <v-container>
      <v-card>
        <v-card-title>إدارة الفواتير</v-card-title>
        <v-card-text>
          <v-btn color="primary" @click="openCreateDialog">إضافة فاتورة</v-btn>
          <v-data-table
            :headers="headers"
            :items="invoices"
            :items-per-page="10"
            class="elevation-1"
          >
            <template v-slot:item.actions="{ item }">
              <v-icon small @click="editInvoice(item)">mdi-pencil</v-icon>
              <v-icon small @click="deleteInvoice(item)">mdi-delete</v-icon>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
  
      <!-- نموذج إنشاء/تعديل الفاتورة -->
      <v-dialog v-model="dialog" max-width="500px">
        <v-card>
          <v-card-title>{{ formTitle }}</v-card-title>
          <v-card-text>
            <v-form ref="form">
              <v-text-field v-model="editedItem.invoice_number" label="رقم الفاتورة" required></v-text-field>
              <v-text-field v-model="editedItem.invoice_date" label="تاريخ الفاتورة" type="date" required></v-text-field>
              <v-text-field v-model="editedItem.amount" label="المبلغ" type="number" required></v-text-field>
              <v-select v-model="editedItem.status" :items="statuses" label="حالة الفاتورة" required></v-select>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="blue darken-1" text @click="closeDialog">إلغاء</v-btn>
            <v-btn color="blue darken-1" text @click="saveInvoice">حفظ</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </template>
  
  <script>
  export default {
    data() {
      return {
        invoices: [],
        statuses: ['مدفوعة', 'غير مدفوعة', 'متأخرة'],
        headers: [
          { text: 'رقم الفاتورة', value: 'invoice_number' },
          { text: 'تاريخ الفاتورة', value: 'invoice_date' },
          { text: 'المبلغ', value: 'amount' },
          { text: 'حالة الفاتورة', value: 'status' },
          { text: 'الإجراءات', value: 'actions' },
        ],
        dialog: false,
        editedItem: {
          id: null,
          invoice_number: '',
          invoice_date: '',
          amount: 0,
          status: '',
        },
        defaultItem: {
          id: null,
          invoice_number: '',
          invoice_date: '',
          amount: 0,
          status: '',
        },
      };
    },
    computed: {
      formTitle() {
        return this.editedItem.id ? 'تعديل الفاتورة' : 'إضافة فاتورة';
      },
    },
    async created() {
      this.fetchInvoices();
    },
    methods: {
      async fetchInvoices() {
        const response = await axios.get('/api/invoices');
        this.invoices = response.data;
      },
      openCreateDialog() {
        this.editedItem = { ...this.defaultItem };
        this.dialog = true;
      },
      editInvoice(item) {
        this.editedItem = { ...item };
        this.dialog = true;
      },
      async deleteInvoice(item) {
        if (confirm('هل أنت متأكد من حذف هذه الفاتورة؟')) {
          await axios.delete(`/api/invoices/${item.id}`);
          this.fetchInvoices();
        }
      },
      closeDialog() {
        this.dialog = false;
        this.editedItem = { ...this.defaultItem };
      },
      async saveInvoice() {
        if (this.editedItem.id) {
          await axios.put(`/api/invoices/${this.editedItem.id}`, this.editedItem);
        } else {
          await axios.post('/api/invoices', this.editedItem);
        }
        this.fetchInvoices();
        this.closeDialog();
      },
    },
  };
  </script>