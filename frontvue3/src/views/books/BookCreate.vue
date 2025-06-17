<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { apiClient } from '../../utils/api.js'

const router = useRouter()

const form = ref({
  title: '',
  price: ''
})

const loading = ref(false)
const errors = ref({})

const handleSubmit = async () => {
  try {
    loading.value = true
    errors.value = {}
    
    const bookData = {
      title: form.value.title,
      price: parseInt(form.value.price)
    }
    
    console.log('送信データ:', bookData)
    
    const response = await apiClient.post('/books', bookData)
    console.log('作成成功:', response)
    
    alert('書籍を登録しました！')
    router.push('/books')
    
  } catch (error) {
    console.error('書籍登録に失敗:', error)
    
    if (error.errors) {
      errors.value = error.errors
    } else {
      alert('書籍登録に失敗しました。')
    }
  } finally {
    loading.value = false
  }
}

const goBack = () => {
  router.push('/books')
}
</script>

<template>
  <div>
    <h1>書籍新規登録</h1>
    
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
          :disabled="loading"
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
          :disabled="loading"
        >
      </div>
      
      <div>
        <button type="submit" :disabled="loading">
          {{ loading ? '登録中...' : '登録' }}
        </button>
        <button type="button" @click="goBack" :disabled="loading">
          キャンセル
        </button>
      </div>
    </form>
  </div>
</template>