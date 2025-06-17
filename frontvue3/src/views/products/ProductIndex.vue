<script setup>
import { ref, onMounted } from 'vue'
import { apiClient } from '../../utils/api.js'

const products = ref([])
const loading = ref(true)
const error = ref(null)

const loadProducts = async () => {
  try {
    loading.value = true
    error.value = null
    const response = await apiClient.get('/products')
    products.value = response.data || response
  } catch (err) {
    console.error('商品の取得に失敗:', err)
    error.value = '商品の取得に失敗しました'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadProducts()
})
</script>

<template>
  <div>
    <h1>商品一覧</h1>
    <RouterLink to="/products/create">新規作成</RouterLink>
    
    <div v-if="loading">
      読み込み中...
    </div>
    
    <div v-else-if="error">
      エラー: {{ error }}
    </div>
    
    <div v-else>
      <div v-for="product in products" :key="product.id">
        <hr>
        <h3>{{ product.name }}</h3>
        <p>価格: ¥{{ product.price?.toLocaleString() }}</p>
        <p>説明: {{ product.description }}</p>
        <p>
          <RouterLink :to="`/products/${product.id}`">詳細</RouterLink> |
          <RouterLink :to="`/products/${product.id}/edit`">編集</RouterLink>
        </p>
      </div>
    </div>
    
    <div v-if="!loading && products.length === 0">
      商品が見つかりません
    </div>
  </div>
</template>

