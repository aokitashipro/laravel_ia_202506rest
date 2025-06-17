<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { apiClient } from '../../utils/api.js'

const route = useRoute()
const router = useRouter()

const book = ref(null)
const loading = ref(true)
const error = ref(null)

const loadBook = async () => {
  try {
    loading.value = true
    error.value = null
    
    const bookId = route.params.id
    const response = await apiClient.get(`/books/${bookId}`)
    book.value = response.data || response
    
    console.log('書籍詳細:', book.value)
    
  } catch (err) {
    console.error('書籍詳細の取得に失敗:', err)
    
    if (err.status === 404) {
      error.value = '書籍が見つかりません'
    } else {
      error.value = '書籍詳細の取得に失敗しました'
    }
  } finally {
    loading.value = false
  }
}

const deleteBook = async () => {
  if (!confirm('本当にこの書籍を削除しますか？')) {
    return
  }
  
  try {
    const bookId = route.params.id
    await apiClient.delete(`/books/${bookId}`)
    
    alert('書籍を削除しました')
    router.push('/books')
    
  } catch (error) {
    console.error('削除エラー:', error)
    alert('削除に失敗しました')
  }
}

const goToEdit = () => {
  router.push(`/books/${route.params.id}/edit`)
}

const goBack = () => {
  router.push('/books')
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleString('ja-JP')
}

onMounted(() => {
  loadBook()
})
</script>

<template>
  <div>
    <h1>書籍詳細</h1>
    
    <div v-if="loading">
      読み込み中...
    </div>
    
    <div v-else-if="error">
      エラー: {{ error }}
    </div>
    
    <div v-else-if="book">
      <div class="book-info">
        <div class="info-row">
          <label>ID:</label>
          <span>{{ book.id }}</span>
        </div>
        
        <div class="info-row">
          <label>書籍名:</label>
          <span>{{ book.title }}</span>
        </div>
        
        <div class="info-row">
          <label>価格:</label>
          <span>¥{{ book.price?.toLocaleString() }}</span>
        </div>
        
        <div class="info-row">
          <label>登録日:</label>
          <span>{{ formatDate(book.created_at) }}</span>
        </div>
        
        <div class="info-row">
          <label>更新日:</label>
          <span>{{ formatDate(book.updated_at) }}</span>
        </div>
      </div>
      
      <div class="action-buttons">
        <button @click="goToEdit" class="btn btn-primary">編集</button>
        <button @click="deleteBook" class="btn btn-danger">削除</button>
        <button @click="goBack" class="btn btn-secondary">一覧に戻る</button>
      </div>
    </div>
  </div>
</template>