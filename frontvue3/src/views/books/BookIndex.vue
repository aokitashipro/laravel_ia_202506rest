<script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '@/utils/api.js'

const books = ref([])
const loading = ref(true)
const error = ref(null)

const loadBooks = async () => {
  try {
    loading.value = true
    error.value = null
    const response = await apiClient.get('/books')
    books.value = response.data || response
  } catch (err) {
    console.error('書籍の取得に失敗:', err)
    error.value = '書籍の取得に失敗しました'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadBooks()
})
</script>

<template>
  <div>
    <h1>書籍一覧</h1>
    <RouterLink to="/books/create">新規登録</RouterLink>
    
    <div v-if="loading">
      読み込み中...
    </div>
    
    <div v-else-if="error">
      エラー: {{ error }}
    </div>
    
    <div v-else>
      <div v-for="book in books" :key="book.id">
        <hr>
        <h3>{{ book.title }}</h3>
        <p>価格: ¥{{ book.price?.toLocaleString() }}</p>
        <p>
          <RouterLink :to="`/books/${book.id}`">詳細</RouterLink> |
          <RouterLink :to="`/books/${book.id}/edit`">編集</RouterLink>
        </p>
      </div>
    </div>
    
    <div v-if="!loading && books.length === 0">
      書籍が見つかりません
    </div>
  </div>
</template>