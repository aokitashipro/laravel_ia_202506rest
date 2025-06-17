import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from '../views/RegisterView.vue'
import LoginView from '../views/LoginView.vue'
import BookIndex from '@/views/books/BookIndex.vue'
import BookCreate from '@/views/books/BookCreate.vue'
import BookShow from '@/views/books/BookShow.vue'
import BookEdit from '@/views/books/BookEdit.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    { path: '/books', component: BookIndex },           // 一覧
    { path: '/books/create', component: BookCreate },   // 新規作成
    { path: '/books/:id', component: BookShow },        // 詳細
    { path: '/books/:id/edit', component: BookEdit },    // 編集
    {
      path: '/login',
      name: 'Login',
      component: LoginView,
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView,
    },

  ],
})

export default router
