export function indexProducts(apiUrl, elementId) {
  fetch(apiUrl)
    .then(res => res.json())
    .then(json => {
      const items = json.data
      document.getElementById(elementId).innerHTML =
        items.map(item => `<li>${item.name}</li>`).join('');
    });
}
