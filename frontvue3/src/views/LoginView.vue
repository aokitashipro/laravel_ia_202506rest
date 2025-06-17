<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { apiClient } from '@/utils/api.js'

// Vue Router
const router = useRouter()

// リアクティブデータ
const isLoggedIn = ref(false)
const isLoading = ref(false)

const formData = reactive({
  email: '',
  password: ''
})

const message = reactive({
  text: '',
  isError: false
})

// メッセージ表示用関数
const showMessage = (text, isError = false) => {
  message.text = text
  message.isError = isError
}

// ログイン成功後の画面表示
const showLoggedInState = () => {
  isLoggedIn.value = true
  message.text = ''
}

// フォーム送信処理
const handleSubmit = async () => {
  isLoading.value = true

  const loginData = {
    email: formData.email,
    password: formData.password
  }

  try {
    const data = await apiClient.post('/login', loginData)
    
    // トークンを保存
    localStorage.setItem('token', data.token)
    if (data.user) {
      localStorage.setItem('user', JSON.stringify(data.user))
    }

    showMessage('ログイン成功！', false)

    // ログイン成功後の画面を表示
    setTimeout(() => {
      showLoggedInState()
    }, 500)

  } catch (error) {
    console.error('ログインエラー:', error)
    showMessage(error.message || 'ログインに失敗しました', true)
  } finally {
    isLoading.value = false
  }
}

// ログアウト処理
const handleLogout = async () => {
  try {
    // APIでログアウト
    await apiClient.post('/logout', {})
  } catch (error) {
    console.error('ログアウトAPIエラー:', error)
  } finally {
    // ローカルストレージをクリア
    localStorage.removeItem('token')
    localStorage.removeItem('user')

    // フォームを再表示
    isLoggedIn.value = false
    formData.email = ''
    formData.password = ''
    showMessage('ログアウトしました', false)
  }
}

// ページ読み込み時にトークンがあるかチェック
onMounted(() => {
  const token = localStorage.getItem('token')
  if (token) {
    // 既にログインしている場合はログイン済み画面を表示
    showLoggedInState()
  }
})
</script>
<template>
  <div>
    <h1>ログイン</h1>
    
    <!-- ログインフォーム -->
    <div v-show="!isLoggedIn">
      <form @submit.prevent="handleSubmit">
        <div>
          <label for="email">Email:</label>
          <input 
            id="email" 
            v-model="formData.email" 
            type="email" 
            placeholder="email" 
            required
          >
        </div>
        
        <div>
          <label for="password">Password:</label>
          <input 
            id="password" 
            v-model="formData.password" 
            type="password" 
            placeholder="password" 
            required
          >
        </div>
        
        <button type="submit" :disabled="isLoading">
          {{ isLoading ? 'ログイン中...' : 'Login' }}
        </button>
      </form>

      <div 
        v-if="message.text" 
        :style="{ color: message.isError ? 'red' : 'green' }"
      >
        {{ message.text }}
      </div>
      
      <p>
        <router-link to="/register">アカウントをお持ちでない方はこちら</router-link>
      </p>
    </div>

    <!-- ログイン済み画面 -->
    <div v-show="isLoggedIn">
      <h2>ログイン済み</h2>
      <p>ログインが完了しました。</p>
      <button @click="handleLogout">ログアウト</button>
    </div>
  </div>
</template>

