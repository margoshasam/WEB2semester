<template>
  <div class="card">
    <div class="card-header">
      <h3>Добавление нового ребенка</h3>
    </div>
    <div class="card-body">
      <form @submit.prevent="submitForm" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Фото ребенка</label>
          <input type="file" class="form-control" @change="handleFileUpload" accept="image/*" :class="{ 'is-invalid': errors.img_path }" />
          <small class="text-muted">Поддерживаются форматы: JPG, PNG, GIF, WEBP</small>
          <div class="error-text" v-if="errors.img_path">{{ errors.img_path }}</div>
          <div class="mt-2" v-if="imagePreview">
            <img :src="imagePreview" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 8px" />
            <button type="button" class="btn btn-sm btn-danger mt-1" @click="removeImage">Удалить фото</button>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Имя *</label>
          <input type="text" class="form-control" v-model="form.name" :class="{ 'is-invalid': errors.name }" />
          <div class="error-text" v-if="errors.name">{{ errors.name }}</div>
        </div>

        <div class="mb-3">
          <label class="form-label">Год рождения *</label>
          <input type="text" class="form-control" v-model="form.year_of_birth" placeholder="2020" :class="{ 'is-invalid': errors.year_of_birth }" />
          <div class="error-text" v-if="errors.year_of_birth">{{ errors.year_of_birth }}</div>
        </div>

        <div class="mb-3">
          <label class="form-label">Группа *</label>
          <select class="form-select" v-model="form.id_group" :class="{ 'is-invalid': errors.id_group }">
            <option value="">Выберите группу</option>
            <option v-for="group in groups" :key="group.id" :value="group.id">{{ group.name_group }}</option>
          </select>
          <div class="error-text" v-if="errors.id_group">{{ errors.id_group }}</div>
        </div>

        <div class="mb-3">
          <label class="form-label">Биография</label>
          <textarea class="form-control" v-model="form.bio" rows="4" placeholder="Информация о ребенке..."></textarea>
        </div>

        <div class="d-flex justify-content-between">
          <RouterLink to="/children" class="btn btn-secondary">Отмена</RouterLink>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            <span v-if="loading" class="spinner-border spinner-border-sm me-1"></span>
            {{ loading ? 'Сохранение...' : 'Сохранить' }}
          </button>
        </div>
      </form>
    </div>

    <div class="alert mt-3 mx-3" :class="messageType" v-if="showMessage">
      {{ message }}
      <button type="button" class="btn-close float-end" @click="showMessage = false"></button>
    </div>
  </div>
</template>

<script setup>
import axios from 'axios'
import { onMounted, reactive, ref } from 'vue'
import { RouterLink } from 'vue-router'
import childrenApi from '../store/children/api'

const groups = ref([])
const showMessage = ref(false)
const message = ref('')
const messageType = ref('alert-info')
const loading = ref(false)
const imagePreview = ref(null)

const form = reactive({
  name: '',
  year_of_birth: '',
  id_group: '',
  bio: '',
  img_path: '',
  img_file: null
})

const errors = reactive({
  name: '',
  year_of_birth: '',
  id_group: '',
  img_path: ''
})

const loadGroups = async () => {
  const response = await axios.get('/inc/groups.json')
  groups.value = response.data
}

const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (!file) {
    return
  }

  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp']
  if (!allowedTypes.includes(file.type)) {
    errors.img_path = 'Пожалуйста, выберите файл изображения (JPG, PNG, GIF, WEBP)'
    form.img_file = null
    imagePreview.value = null
    return
  }

  if (file.size > 5 * 1024 * 1024) {
    errors.img_path = 'Размер файла не должен превышать 5MB'
    form.img_file = null
    imagePreview.value = null
    return
  }

  errors.img_path = ''
  form.img_file = file

  const reader = new FileReader()
  reader.onload = (e) => {
    imagePreview.value = e.target.result
  }
  reader.readAsDataURL(file)
}

const removeImage = () => {
  form.img_file = null
  imagePreview.value = null
  errors.img_path = ''
  const fileInput = document.querySelector('input[type="file"]')
  if (fileInput) {
    fileInput.value = ''
  }
}

const validateForm = () => {
  errors.name = ''
  errors.year_of_birth = ''
  errors.id_group = ''

  if (!form.name.trim()) {
    errors.name = 'Имя обязательно для заполнения'
  }
  if (!form.year_of_birth.trim()) {
    errors.year_of_birth = 'Год рождения обязателен для заполнения'
  } else if (!/^\d{4}$/.test(form.year_of_birth)) {
    errors.year_of_birth = 'Введите корректный год (4 цифры)'
  }
  if (!form.id_group) {
    errors.id_group = 'Выберите группу'
  }

  return !errors.name && !errors.year_of_birth && !errors.id_group && !errors.img_path
}

const resetForm = () => {
  form.name = ''
  form.year_of_birth = ''
  form.id_group = ''
  form.bio = ''
  form.img_path = ''
  form.img_file = null
  imagePreview.value = null
  errors.name = ''
  errors.year_of_birth = ''
  errors.id_group = ''
  errors.img_path = ''
  const fileInput = document.querySelector('input[type="file"]')
  if (fileInput) {
    fileInput.value = ''
  }
}

const submitForm = async () => {
  if (!validateForm()) {
    return
  }

  loading.value = true

  try {
    await childrenApi.create({
      name: form.name,
      year_of_birth: form.year_of_birth,
      id_group: form.id_group,
      bio: form.bio,
      img_path: form.img_file ? form.img_file.name : form.img_path
    })
    message.value = `Заглушка: запись не была создана (демо-режим). ${form.img_file ? `Файл "${form.img_file.name}" не был загружен.` : ''}`
    messageType.value = 'alert-info'
    showMessage.value = true
    resetForm()
    setTimeout(() => {
      showMessage.value = false
    }, 4000)
  } catch (error) {
    message.value = 'Ошибка при создании записи'
    messageType.value = 'alert-danger'
    showMessage.value = true
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await loadGroups()
})
</script>
