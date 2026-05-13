import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import './style.css'
import App from './App.vue'
import ChildrenView from './views/ChildrenView.vue'
import GroupsView from './views/GroupsView.vue'
import CreateChildView from './views/CreateChildView.vue'
import CreateGroupView from './views/CreateGroupView.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', redirect: '/children' },
    { path: '/children', component: ChildrenView },
    { path: '/groups', component: GroupsView },
    { path: '/children/create', component: CreateChildView },
    { path: '/groups/create', component: CreateGroupView }
  ]
})

createApp(App).use(router).mount('#app')
