import { apiClient } from '../utils/api.js'

document.addEventListener('DOMContentLoaded', async () => {
    // URLからIDを取得
    const urlParams = new URLSearchParams(window.location.search)
    const bookId = urlParams.get('id')
    
    if (!bookId) {
        document.getElementById('error-message').textContent = '書籍IDが指定されていません'
        document.getElementById('error-message').style.display = 'block'
        return
    }
    
    try {
        // 書籍詳細を取得
        const response = await apiClient.get(`/books/${bookId}`)
        const book = response.data || response
        
        console.log('編集対象書籍:', book)
        
        // 編集フォームを生成
        document.getElementById('edit-form-container').innerHTML = `
            <form id="book-form">
                <div>
                    <label for="title">書籍名:</label>
                    <input type="text" id="title" name="title" value="${book.title}" required>
                </div>
                
                <div>
                    <label for="price">価格:</label>
                    <input type="number" id="price" name="price" value="${book.price}" min="0" required>
                </div>
                
                <div>
                    <button type="submit">更新</button>
                    <a href="/books/show.html?id=${bookId}">キャンセル</a>
                </div>
            </form>
        `
        
        // フォーム送信イベントを設定
        document.getElementById('book-form').addEventListener('submit', async (e) => {
            e.preventDefault()
            
            const form = e.target
            const formData = new FormData(form)
            
            // エラーメッセージをクリア
            document.getElementById('error-message').style.display = 'none'
            
            // 更新データを準備
            const bookData = {
                title: formData.get('title'),
                price: parseInt(formData.get('price')),
            }
            
            try {
                // APIで更新
                const response = await apiClient.put(`/books/${bookId}`, bookData)
                
                console.log('更新成功:', response)
                alert('書籍を更新しました！')
                window.location.href = `/books/show.html?id=${bookId}`
                
            } catch (error) {
                console.error('書籍更新に失敗:', error)
                
                if (error.errors) {
                    // バリデーションエラーを表示
                    const errorMessages = Object.values(error.errors).flat().join('<br>')
                    const errorDiv = document.getElementById('error-message')
                    errorDiv.innerHTML = errorMessages
                    errorDiv.style.display = 'block'
                } else {
                    alert('書籍更新に失敗しました。')
                }
            }
        })
        
        document.getElementById('loading-message').style.display = 'none'
        
    } catch (error) {
        console.error('書籍詳細の取得に失敗:', error)
        
        document.getElementById('loading-message').style.display = 'none'
        const errorDiv = document.getElementById('error-message')
        errorDiv.textContent = error.status === 404 ? '書籍が見つかりません' : '書籍詳細の取得に失敗しました'
        errorDiv.style.display = 'block'
    }
})