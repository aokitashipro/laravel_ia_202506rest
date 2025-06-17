<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { apiClient } from '../../utils/api.js'

const route = useRoute()
const router = useRouter()

const form = ref({
  title: '',
  price: ''
})

const loading = ref(true)
const updating = ref(false)
const error = ref(null)
const errors = ref({})

const loadBook = async () => {
  try {
    loading.value = true
    error.value = null
    
    const bookId = route.params.id
    const response = await apiClient.get(`/books/${bookId}`)
    const book = response.data || response
    
    console.log('編集対象書籍:', book)
    
    // フォームに既存データを設定
    form.value.title = book.title
    form.value.price = book.price.toString()
    
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

const handleSubmit = async () => {
  try {
    updating.value = true
    errors.value = {}
    
    const bookData = {
      title: form.value.title,
      price: parseInt(form.value.price)
    }
    
    console.log('更新データ:', bookData)
    
    const bookId = route.params.id
    const response = await apiClient.put(`/books/${bookId}`, bookData)
    
    console.log('更新成功:', response)
    
    alert('書籍を更新しました！')
    router.push(`/books/${bookId}`)
    
  } catch (error) {
    console.error('書籍更新に失敗:', error)
    
    if (error.errors) {
      errors.value = error.errors
    } else {
      alert('書籍更新に失敗しました。')
    }
  } finally {
    updating.value = false
  }
}

const goBack = () => {
  const bookId = route.params.id
  router.push(`/books/${bookId}`)
}

onMounted(() => {
  loadBook()
})
</script>

<template>
  <div>
    <h1>書籍編集</h1>
    
    <div v-if="loading">
      読み込み中...
    </div>
    
    <div v-else-if="error">
      エラー: {{ error }}
    </div>
    
    <div v-else>
      <!-- エラーメッセージ表示 -->
      <div v-if="Object.keys(errors).length > 0" class="error-messages">
        <div v-for="(fieldErrors, field) in errors" :key="field">
          <div v-for="error in fieldErrors" :key="error">
            {{ error }}
          </div>
        </div>
      </div>

      <form @submit.prevent="handleSubmit">
        <div>
          <label for="title">書籍名:</label>
          <input 
            type="text" 
            id="title" 
            v-model="form.title" 
            required
            :disabled="updating"
          >
        </div>
        
        <div>
          <label for="price">価格:</label>
          <input 
            type="number" 
            id="price" 
            v-model="form.price" 
            min="0" 
            required
            :disabled="updating"
          >
        </div>
        
        <div>
          <button type="submit" :disabled="updating">
            {{ updating ? '更新中...' : '更新' }}
          </button>
          <button type="button" @click="goBack" :disabled="updating">
            キャンセル
          </button>
        </div>
      </form>
    </div>
  </div>
</template>