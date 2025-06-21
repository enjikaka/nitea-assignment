import '../components/app-header.js';

import { hydrateProductItem } from '../helpers/hydrate-product-item.js';

const qp = new URLSearchParams(document.location.search);
const $editForm = document.querySelector('edit-form');

async function submitForm() {
    $editForm.remove();
    const $output = document.querySelector('output');
    const $productItem = document.querySelector('product-item');

    const response = await fetch('http://localhost:8080/products', {
        method: 'POST',
        body: JSON.stringify({
            name: qp.get('name'),
            price: qp.get('price'),
            categories: qp.get('categories'),
            image: qp.get('image'),
        }),
        headers: {
            'Content-Type': 'application/json',
        },
    });

    const json = await response.json();

    if (response.ok) {
        $output.textContent = 'Produkten har lagts till!';
        const $newProductItem = await hydrateProductItem(json.data[0].id);
        $productItem.parentElement.replaceChild($newProductItem, $productItem);
    } else {
        $output.textContent = 'Något gick fel!';
    }
}

if (qp.size === 4) {
    import('../components/product-item.js');
    submitForm();
} else {
    import('../components/edit-form.js');
}