import { apiClient } from '../utils/api.js'

document.addEventListener('DOMContentLoaded', async () => {
    await loadBooks()
})

async function loadBooks() {
    try {
        const response = await apiClient.get('/books')
        console.log('Full response:', response) // デバッグ用

        const books = response.data
        
        const listElement = document.getElementById('books-index')
        listElement.innerHTML = books.map(book => `
            <div>
                商品名: ${book.title} 価格: ¥${book.price}
                <a href="/books/show.html?id=${book.id}">詳細</a>
                <a href="/books/edit.html?id=${book.id}">編集</a>
            </div>
        `).join('')
        
    } catch (error) {
        console.error('商品の取得に失敗:', error)
    }
}