
<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { apiClient } from '@/utils/api.js'

// Vue Router
const router = useRouter()

// リアクティブデータ
const isCompleted = ref(false)
const isLoading = ref(false)

const formData = reactive({
  name: '',
  email: '',
  password: '',
  confirmPassword: ''
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

// パスワード確認バリデーション
const validatePasswords = () => {
  if (formData.password !== formData.confirmPassword) {
    showMessage('パスワードが一致しません', true)
    return false
  }
  return true
}

// 登録完了後の画面表示
const showCompletedState = () => {
  isCompleted.value = true
}

// フォーム送信処理
const handleSubmit = async () => {
  if (!validatePasswords()) {
    return
  }

  isLoading.value = true

  const registerData = {
    name: formData.name,
    email: formData.email,
    password: formData.password
  }

  try {
    const data = await apiClient.post('/register', registerData)
    
    showMessage('登録が完了しました！', false)

    // トークンを保存
    if (data.token) {
      localStorage.setItem('token', data.token)
    }
    if (data.user) {
      localStorage.setItem('user', JSON.stringify(data.user))
    }

    // 少し待ってから完了画面を表示
    setTimeout(() => {
      showCompletedState()
    }, 1000)

  } catch (error) {
    console.error('登録エラー:', error)
    
    // Laravel のバリデーションエラーを表示
    if (error.errors) {
      const errors = Object.values(error.errors).flat()
      showMessage(errors.join(' / '), true)
    } else {
      showMessage(error.message || '登録に失敗しました', true)
    }
  } finally {
    isLoading.value = false
  }
}

// ログアウト処理
const handleLogout = async () => {
  try {
    // APIでログアウト（トークンの無効化）
    await apiClient.post('/logout', {})
  } catch (error) {
    console.error('ログアウトAPIエラー:', error)
    // エラーでも続行
  } finally {
    // ローカルストレージをクリア
    localStorage.removeItem('token')
    localStorage.removeItem('user')

    // ログイン画面にリダイレクト
    router.push('/login')
  }
}

// ページ読み込み時にトークンがあるかチェック
onMounted(() => {
  const token = localStorage.getItem('token')
  if (token) {
    // 既にログインしている場合は完了画面を表示
    showCompletedState()
  }
})
</script>

<template>
  <div>
    <h1>ユーザー登録</h1>
    
    <!-- 登録フォーム -->
    <div v-show="!isCompleted">
      <form @submit.prevent="handleSubmit">
        <div>
          <label for="name">お名前:</label>
          <input 
            id="name" 
            v-model="formData.name" 
            type="text" 
            required
          >
        </div>
        
        <div>
          <label for="email">メールアドレス:</label>
          <input 
            id="email" 
            v-model="formData.email" 
            type="email" 
            required
          >
        </div>
        
        <div>
          <label for="password">パスワード:</label>
          <input 
            id="password" 
            v-model="formData.password" 
            type="password" 
            required 
            minlength="6"
          >
        </div>
        
        <div>
          <label for="confirmPassword">パスワード確認:</label>
          <input 
            id="confirmPassword" 
            v-model="formData.confirmPassword" 
            type="password" 
            required
          >
        </div>
        
        <button type="submit" :disabled="isLoading">
          {{ isLoading ? '登録中...' : '登録する' }}
        </button>
      </form>
      
      <div 
        v-if="message.text" 
        :style="{ color: message.isError ? 'red' : 'green' }"
      >
        {{ message.text }}
      </div>
      
      <p>
        <router-link to="/login">既にアカウントをお持ちですか？ログインはこちら</router-link>
      </p>
    </div>

    <!-- 登録完了画面 -->
    <div v-show="isCompleted">
      <h2>登録完了！</h2>
      <p>ユーザー登録が完了しました。</p>
      <button @click="handleLogout">ログアウト</button>
    </div>
  </div>
</template>
