<template>
   <div>
      <div class="d-flex justify-content-between align-items-center mb-4">
         <h2>Список групп</h2>
         <RouterLink to="/groups/create" class="btn btn-success"
            >+ Добавить группу</RouterLink
         >
      </div>

      <div class="table-responsive">
         <table class="table table-striped table-bordered">
            <thead class="table-dark">
               <tr>
                  <th>ID</th>
                  <th>Название группы</th>
                  <th>Количество детей</th>
                  <th>Действия</th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="group in groups" :key="group.id">
                  <td>{{ group.id }}</td>
                  <td>{{ group.name_group }}</td>
                  <td>{{ getChildrenCount(group.id) }}</td>
                  <td>
                     <button
                        class="btn btn-sm btn-warning me-2"
                        @click="openEditModal(group)"
                     >
                        Редактировать
                     </button>
                     <button
                        class="btn btn-sm btn-danger"
                        @click="deleteItem(group.id)"
                     >
                        Удалить
                     </button>
                  </td>
               </tr>
               <tr v-if="groups.length === 0">
                  <td colspan="4" class="text-center">Нет данных</td>
               </tr>
            </tbody>
         </table>
      </div>

      <div
         class="modal"
         :class="{ 'd-block': showEditModal }"
         tabindex="-1"
         v-if="showEditModal"
      >
         <div class="modal-backdrop" @click="closeEditModal"></div>
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title">Редактирование группы</h5>
                  <button
                     type="button"
                     class="btn-close"
                     @click="closeEditModal"
                  ></button>
               </div>
               <div class="modal-body">
                  <form @submit.prevent="updateGroup">
                     <div class="mb-3">
                        <label class="form-label">Название группы *</label>
                        <input
                           type="text"
                           class="form-control"
                           v-model="editForm.name_group"
                           :class="{ 'is-invalid': editErrors.name_group }"
                        />
                        <div class="error-text" v-if="editErrors.name_group">
                           {{ editErrors.name_group }}
                        </div>
                     </div>
                     <div class="modal-footer">
                        <button
                           type="button"
                           class="btn btn-secondary"
                           @click="closeEditModal"
                        >
                           Отмена
                        </button>
                        <button type="submit" class="btn btn-primary">
                           Сохранить
                        </button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script setup>
import axios from "axios";
import { onMounted, reactive, ref } from "vue";
import { RouterLink } from "vue-router";
import groupsApi from "../store/groups/api";

const groups = ref([]);
const children = ref([]);
const showEditModal = ref(false);
const editErrors = ref({});
const editForm = reactive({
   id: null,
   name_group: "",
});

const loadGroups = async () => {
   const response = await axios.get("/inc/groups.json");
   groups.value = response.data;
};

const loadChildren = async () => {
   const response = await axios.get("/inc/children.json");
   children.value = response.data;
};

const getChildrenCount = (groupId) =>
   children.value.filter((child) => child.id_group == groupId).length;

const openEditModal = (group) => {
   editForm.id = group.id;
   editForm.name_group = group.name_group;
   editErrors.value = {};
   showEditModal.value = true;
};

const closeEditModal = () => {
   showEditModal.value = false;
};

const validateEditForm = () => {
   const errors = {};
   if (!editForm.name_group.trim()) {
      errors.name_group = "Название группы обязательно";
   }
   editErrors.value = errors;
   return Object.keys(errors).length === 0;
};

const updateGroup = async () => {
   if (!validateEditForm()) {
      return;
   }
   await groupsApi.update(editForm);
   alert("Заглушка: данные не были сохранены (демо-режим)");
   closeEditModal();
};

const deleteItem = async (id) => {
   const approved = confirm(
      "Вы уверены, что хотите удалить эту группу? (Заглушка - удаление не произойдет)",
   );
   if (!approved) {
      return;
   }
   await groupsApi.delete(id);
   alert("Заглушка: запись не была удалена (демо-режим)");
};

onMounted(async () => {
   await Promise.all([loadGroups(), loadChildren()]);
});
</script>
