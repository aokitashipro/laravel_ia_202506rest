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
        
        console.log('書籍詳細:', book)
        
        // HTML一括設定
        document.getElementById('book-details').innerHTML = `
            <div class="book-info">
                <div class="info-row"><label>ID:</label><span>${book.id}</span></div>
                <div class="info-row"><label>書籍名:</label><span>${book.title}</span></div>
                <div class="info-row"><label>価格:</label><span>¥${book.price.toLocaleString()}</span></div>
                <div class="info-row"><label>登録日:</label><span>${new Date(book.created_at).toLocaleString('ja-JP')}</span></div>
                <div class="info-row"><label>更新日:</label><span>${new Date(book.updated_at).toLocaleString('ja-JP')}</span></div>
            </div>
            <div class="action-buttons">
                <a href="/books/edit.html?id=${bookId}" class="btn btn-primary">編集</a>
                <button type="button" id="delete-btn" class="btn btn-danger">削除</button>
                <a href="/books/" class="btn btn-secondary">一覧に戻る</a>
            </div>
        `
        
        // 削除ボタンにイベントリスナーを追加
        document.getElementById('delete-btn').addEventListener('click', async () => {
            if (confirm('本当にこの書籍を削除しますか？')) {
                try {
                    await apiClient.delete(`/books/${bookId}`)
                    alert('書籍を削除しました')
                    window.location.href = '/books/'
                } catch (error) {
                    console.error('削除エラー:', error)
                    alert('削除に失敗しました')
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