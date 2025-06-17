import { apiClient } from '../utils/api.js'

// HTMLが読み込まれたら実行
document.addEventListener('DOMContentLoaded', () => {
    // フォームの情報取得
    const form = document.getElementById('book-form')
    // submit押されたら実行
    form.addEventListener('submit', handleSubmit)
})

//e ・・イベントオブジェクト
async function handleSubmit(e) {
    // formの読み込みをなくす
    e.preventDefault()
    
    // formData フォーム情報を設定
    const form = e.target
    const formData = new FormData(form)
    
    // エラーメッセージをクリア
    document.getElementById('error-message').style.display = 'none'
    
    // formDataに追加
    const bookData = {
        title: formData.get('title'),
        price: parseInt(formData.get('price')),
    }
    
    try {
        // APIに接続
        const response = await apiClient.post('/books', bookData)
        
        console.log('成功レスポンス:', response)
        alert('商品を登録しました！')
        window.location.href = '/books/'
        
    } catch (error) {
        console.error('商品登録に失敗:', error)
        
        if (error.errors) {
            // バリデーションエラーを表示
            // オブジェクトの値（配列）だけを取得 Object.values(error.errors)
            // 二次元配列を一次元配列に平坦化 flat()
            // 配列を HTML の改行タグで結合して文字列に join('<br>')
            const errorMessages = Object.values(error.errors).flat().join('<br>')

            // id指定して文字設定
            const errorDiv = document.getElementById('error-message')
            errorDiv.innerHTML = errorMessages
            errorDiv.style.display = 'block'
        } else {
            alert('商品登録に失敗しました。')
        }
    }
}
