export function showProduct(apiUrl, elementId) {
  fetch(apiUrl)
    .then(res => res.json())
    .then(json => {
      const product = json.data;
      document.getElementById(elementId).innerHTML = `
        <h2>${product.name}</h2>
        <p>価格: ¥${product.price}</p>
        <p>登録日: ${product.created_at}</p>
      `;
    });
}
