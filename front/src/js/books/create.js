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
    const formData = new FormData(form) // フォームをまとめて扱える・画像・ファイル
    
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
        alert('書籍を登録しました！') // 商品→書籍に変更
        window.location.href = '/books/'
        
    } catch (error) {
        console.error('書籍登録に失敗:', error) // 商品→書籍に変更
        
        // APIクライアントの実装に合わせてエラーアクセス方法を調整
        let errors = null
        if (error.data && error.data.errors) {
            errors = error.data.errors  // APIクライアントでthrow errorの場合
        } else if (error.errors) {
            errors = error.errors       // APIクライアントでthrow responseDataの場合
        }
        
        if (errors) {
            // バリデーションエラーを表示
            const errorMessages = Object.values(errors).flat().join('<br>')
            const errorDiv = document.getElementById('error-message')
            errorDiv.innerHTML = errorMessages
            errorDiv.style.display = 'block'
        } else {
            alert('書籍登録に失敗しました。') // 商品→書籍に変更
        }
    }
}