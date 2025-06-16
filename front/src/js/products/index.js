import { apiClient } from '../utils/api.js'

document.addEventListener('DOMContentLoaded', async () => {
    await loadProducts()
})

async function loadProducts() {
    try {
        const response = await apiClient.get('/products')
        console.log('Full response:', response) // デバッグ用

        const products = response.data
        
        const listElement = document.getElementById('products-index')
        listElement.innerHTML = products.map(product => `
            <div class="product-card">
                商品名: ${product.name} 価格: ¥${product.price}
                <a href="/products/show.html?id=${product.id}">詳細</a>
                <a href="/products/edit.html?id=${product.id}">編集</a>
            </div>
        `).join('')
        
    } catch (error) {
        console.error('商品の取得に失敗:', error)
    }
}