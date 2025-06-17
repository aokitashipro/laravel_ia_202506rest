<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { apiClient } from '@/api/apiClient'

const router = useRouter()

const form = reactive({
  name: '',
  price: 0
})

const errorMessage = ref('')

const handleSubmit = async () => {
  try {
    await apiClient.post('/products', form)
    router.push('/products/')
  } catch (error) {
    errorMessage.value = error.message || '登録に失敗しました'
  }
}
</script>

<template>
  <div>
    <nav>
      <router-link to="/">ホーム</router-link>
      <router-link to="/products/">商品一覧</router-link>
      <router-link to="/products/create">新規登録</router-link>
    </nav>
    
    <main>
      <h1>商品新規登録</h1>
      <div v-if="errorMessage" id="error-message">{{ errorMessage }}</div>

      <form @submit.prevent="handleSubmit">
        <div>
          <label for="name">商品名:</label>
          <input type="text" id="name" v-model="form.name" required>
        </div>
        
        <div>
          <label for="price">価格:</label>
          <input type="number" id="price" v-model.number="form.price" min="0" required>
        </div>
        
        <div>
          <button type="submit">登録</button>
          <router-link to="/products/">キャンセル</router-link>
        </div>
      </form>
    </main>
  </div>
</template>

<style scoped>
/* 元のCSSファイルを読み込むか、必要最小限のスタイル */
</style>