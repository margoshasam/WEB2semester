<template>
   <div>
      <div class="d-flex justify-content-between align-items-center mb-4">
         <h2>Список детей</h2>
         <RouterLink to="/children/create" class="btn btn-success"
            >+ Добавить ребенка</RouterLink
         >
      </div>

      <div class="filter-section">
         <div class="row">
            <div class="col-md-4">
               <label class="form-label">Фильтр по группе:</label>
               <select
                  class="form-select"
                  v-model="selectedGroup"
                  @change="filterChildren"
               >
                  <option value="all">Все группы</option>
                  <option
                     v-for="group in groups"
                     :key="group.id"
                     :value="group.id"
                  >
                     {{ group.name_group }}
                  </option>
               </select>
            </div>
         </div>
      </div>

      <div class="table-responsive">
         <table class="table table-striped table-bordered">
            <thead class="table-dark">
               <tr>
                  <th>ID</th>
                  <th>Фото</th>
                  <th>Имя</th>
                  <th>Группа</th>
                  <th>Год рождения</th>
                  <th>Биография</th>
                  <th>Действия</th>
               </tr>
            </thead>
            <tbody>
               <tr v-for="child in filteredChildren" :key="child.id">
                  <td>{{ child.id }}</td>
                  <td>
                     <img
                        :src="formatImagePath(child.img_path)"
                        :alt="child.name"
                        style="width: 50px; height: 50px; object-fit: cover"
                     />
                  </td>
                  <td>{{ child.name }}</td>
                  <td>{{ getGroupName(child.id_group) }}</td>
                  <td>{{ child.year_of_birth }}</td>
                  <td>{{ child.bio.substring(0, 50) }}...</td>
                  <td>
                     <button
                        class="btn btn-sm btn-warning me-2"
                        @click="openEditModal(child)"
                     >
                        Редактировать
                     </button>
                     <button
                        class="btn btn-sm btn-danger"
                        @click="deleteItem(child.id)"
                     >
                        Удалить
                     </button>
                  </td>
               </tr>
               <tr v-if="filteredChildren.length === 0">
                  <td colspan="7" class="text-center">Нет данных</td>
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
                  <h5 class="modal-title">Редактирование ребенка</h5>
                  <button
                     type="button"
                     class="btn-close"
                     @click="closeEditModal"
                  ></button>
               </div>
               <div class="modal-body">
                  <form @submit.prevent="updateChild">
                     <div class="mb-3">
                        <label class="form-label">Имя *</label>
                        <input
                           type="text"
                           class="form-control"
                           v-model="editForm.name"
                           :class="{ 'is-invalid': editErrors.name }"
                        />
                        <div class="error-text" v-if="editErrors.name">
                           {{ editErrors.name }}
                        </div>
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Год рождения *</label>
                        <input
                           type="text"
                           class="form-control"
                           v-model="editForm.year_of_birth"
                           :class="{ 'is-invalid': editErrors.year_of_birth }"
                        />
                        <div class="error-text" v-if="editErrors.year_of_birth">
                           {{ editErrors.year_of_birth }}
                        </div>
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Группа *</label>
                        <select
                           class="form-select"
                           v-model="editForm.id_group"
                           :class="{ 'is-invalid': editErrors.id_group }"
                        >
                           <option value="">Выберите группу</option>
                           <option
                              v-for="group in groups"
                              :key="group.id"
                              :value="group.id"
                           >
                              {{ group.name_group }}
                           </option>
                        </select>
                        <div class="error-text" v-if="editErrors.id_group">
                           {{ editErrors.id_group }}
                        </div>
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Биография</label>
                        <textarea
                           class="form-control"
                           v-model="editForm.bio"
                           rows="3"
                        ></textarea>
                     </div>
                     <div class="mb-3">
                        <label class="form-label">Путь к фото</label>
                        <input
                           type="text"
                           class="form-control"
                           v-model="editForm.img_path"
                        />
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
import { onMounted, reactive, ref } from "vue";
import { RouterLink } from "vue-router";
import childrenApi from "../store/children/api";
import groupsApi from "../store/groups/api";

const children = ref([]);
const groups = ref([]);
const filteredChildren = ref([]);
const selectedGroup = ref("all");
const showEditModal = ref(false);
const editErrors = ref({});
const editForm = reactive({
   id: null,
   name: "",
   year_of_birth: "",
   id_group: "",
   bio: "",
   img_path: "",
});

const loadGroups = async () => {
   const response = await groupsApi.list();

   groups.value = response;
};

const loadChildren = async () => {
   const response = await childrenApi.list();

   children.value = response;
   filteredChildren.value = [...children.value];
};

const filterChildren = async () => {
   if (selectedGroup.value === "all") {
      const response = await childrenApi.list();
      filteredChildren.value = response;
      return;
   }
   const response = await childrenApi.filter();
   console.log(response);
   filteredChildren.value = response;
};

const getGroupName = (groupId) => {
   const group = groups.value.find((g) => g.id == groupId);
   return group ? group.name_group : "Не указана";
};

const openEditModal = (child) => {
   editForm.id = child.id;
   editForm.name = child.name;
   editForm.year_of_birth = child.year_of_birth;
   editForm.id_group = child.id_group;
   editForm.bio = child.bio;
   editForm.img_path = child.img_path;
   editErrors.value = {};
   showEditModal.value = true;
};

const closeEditModal = () => {
   showEditModal.value = false;
   editForm.id = null;
   editForm.name = "";
   editForm.year_of_birth = "";
   editForm.id_group = "";
   editForm.bio = "";
   editForm.img_path = "";
};

const validateEditForm = () => {
   const errors = {};
   if (!editForm.name.trim()) {
      errors.name = "Имя обязательно";
   }
   if (!editForm.year_of_birth.trim()) {
      errors.year_of_birth = "Год рождения обязателен";
   } else if (!/^\d{4}$/.test(editForm.year_of_birth)) {
      errors.year_of_birth = "Введите корректный год (4 цифры)";
   }
   if (!editForm.id_group) {
      errors.id_group = "Выберите группу";
   }
   editErrors.value = errors;
   return Object.keys(errors).length === 0;
};

const updateChild = async () => {
   if (!validateEditForm()) {
      return;
   }

   await childrenApi.update(editForm);
   alert("Заглушка: данные не были сохранены (демо-режим)");
   closeEditModal();
};

const deleteItem = async (id) => {
   const approved = confirm(
      "Вы уверены, что хотите удалить этого ребенка? (Заглушка - удаление не произойдет)",
   );
   if (!approved) {
      return;
   }
   await childrenApi.delete(id);
   alert("Заглушка: запись не была удалена (демо-режим)");
};

const formatImagePath = (imgPath) => {
   if (!imgPath) {
      return "";
   }
   if (imgPath.startsWith("http") || imgPath.startsWith("/")) {
      return imgPath;
   }
   return `/${imgPath}`;
};

onMounted(async () => {
   await Promise.all([loadGroups(), loadChildren()]);
});
</script>
