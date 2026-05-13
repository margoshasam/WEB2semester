<template>
  <div class="header">
    <div class="container py-2">
      <div class="row d-flex">
        <div class="col-12 col-lg-10 d-flex flex-wrap justify-content-start align-items-center">
          <div class="p-1"><a href="https://sunschool.ru/#map" class="btn active opacity-50 fs12">Детские сады</a></div>
          <div class="p-1"><a href="https://primary.sunschool.ru/" class="btn active opacity-50 fs12">Начальные школы</a></div>
          <div class="p-1"><a href="https://fr.sunschool.ru/" class="btn active opacity-50 fs12">Франшиза детского сада</a></div>
          <div class="p-1"><a href="https://fr.primary.sunschool.ru/" class="btn active opacity-50 fs12">Франшиза школы</a></div>
          <div class="p-1"><a href="https://blog.sunschool.ru/" class="btn active opacity-50 fs12">Блог</a></div>
          <div class="p-1"><a href="https://sunschool.ru/about/" class="btn active opacity-50 fs12">О компании</a></div>
          <div class="p-1"><a href="https://sunschool.ru/concept/" class="btn active opacity-50 fs12">Концепция</a></div>
          <div class="p-1"><a href="https://sunschool.ru/charity-projects/" class="btn active opacity-50 fs12">Благотворительность</a></div>
          <div class="p-1"><a href="https://sunschool.ru/career/" class="btn active opacity-50 fs12">Карьера</a></div>
        </div>
        <div class="col-12 col-lg-2 d-flex justify-content-lg-end align-items-center">
          <div class="p-1 small">VK</div>
          <div class="p-1 small">YouTube</div>
          <div class="p-1 small">Telegram</div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-lg-7 d-flex justify-content-lg-between align-items-lg-center">
          <div class="p-1">
            <a class="fs-3 header-phone" href="tel:88007756047">8 (800) 775-60-47</a>
            <p class="fs-6 opacity-75 mb-0">Звонок по России бесплатный</p>
          </div>
          <div class="p-1">
            <button type="button" class="btn pink-btn">Заказать звонок</button>
          </div>
        </div>
        <div class="col-12 col-lg-5 d-flex justify-content-lg-end align-items-center">
          <div class="p-1">
            <button type="button" class="btn pink-btn">Оставить отзыв</button>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col">
          <RouterLink class="btn btn-primary me-2" to="/children">Дети</RouterLink>
          <RouterLink class="btn btn-primary" to="/groups">Группы</RouterLink>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { RouterLink } from 'vue-router'
</script>
