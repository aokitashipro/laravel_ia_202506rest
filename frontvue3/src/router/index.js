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
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
    { path: '/books', component: BookIndex },           // 一覧
    { path: '/books/create', component: BookCreate },   // 新規作成
    { path: '/books/:id', component: BookShow },        // 詳細
    { path: '/books/:id/edit', component: BookEdit }    // 編集
  ],
})

export default router
