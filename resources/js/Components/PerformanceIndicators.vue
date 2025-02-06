<template>
    <v-container>
      <v-card>
        <v-card-title>مؤشرات الأداء</v-card-title>
        <v-card-text>
          <v-row>
            <v-col cols="12" md="4">
              <v-card>
                <v-card-title>القضايا المفتوحة</v-card-title>
                <v-card-text>{{ openLegalCasesCount }}</v-card-text>
              </v-card>
            </v-col>
            <v-col cols="12" md="4">
              <v-card>
                <v-card-title>المهام المعلقة</v-card-title>
                <v-card-text>{{ pendingTasksCount }}</v-card-text>
              </v-card>
            </v-col>
            <v-col cols="12" md="4">
              <v-card>
                <v-card-title>الفواتير المتأخرة</v-card-title>
                <v-card-text>{{ overdueInvoicesCount }}</v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-container>
  </template>
  
  <script>
  export default {
    data() {
      return {
        openLegalCasesCount: 0,
        pendingTasksCount: 0,
        overdueInvoicesCount: 0,
      };
    },
    async created() {
      const response = await axios.get('/api/performance-indicators');
      this.openLegalCasesCount = response.data.openLegalCasesCount;
      this.pendingTasksCount = response.data.pendingTasksCount;
      this.overdueInvoicesCount = response.data.overdueInvoicesCount;
    },
  };
  </script>