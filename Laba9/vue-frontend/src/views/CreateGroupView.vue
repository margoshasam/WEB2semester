<template>
   <div class="card">
      <div class="card-header">
         <h3>Добавление новой группы</h3>
      </div>
      <div class="card-body">
         <form @submit.prevent="submitForm">
            <div class="mb-3">
               <label class="form-label">Название группы *</label>
               <input
                  type="text"
                  class="form-control"
                  v-model="form.name_group"
                  :class="{ 'is-invalid': errors.name_group }"
               />
               <div class="error-text" v-if="errors.name_group">
                  {{ errors.name_group }}
               </div>
            </div>

            <div class="d-flex justify-content-between">
               <RouterLink to="/groups" class="btn btn-secondary"
                  >Отмена</RouterLink
               >
               <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
         </form>
      </div>

      <div class="alert alert-info mt-3 mx-3" v-if="showMessage">
         {{ message }}
      </div>
   </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { RouterLink } from "vue-router";
import groupsApi from "../store/groups/api";

const form = reactive({
   name_group: "",
});

const errors = reactive({
   name_group: "",
});

const showMessage = ref(false);
const message = ref("");

const validateForm = () => {
   errors.name_group = "";
   if (!form.name_group.trim()) {
      errors.name_group = "Название группы обязательно для заполнения";
   }
   return !errors.name_group;
};

const submitForm = async () => {
   if (!validateForm()) {
      return;
   }

   await groupsApi.create(form);
   message.value = "Заглушка: запись не была создана (демо-режим)";
   showMessage.value = true;
   form.name_group = "";
   setTimeout(() => {
      showMessage.value = false;
   }, 3000);
};
</script>
