import '../components/app-header.js';
import '../components/edit-form.js';
import '../components/product-item.js';

import { renderProductItemElement } from '../helpers/render-product-item-element.js';
import { html } from '../helpers/utils.js';

const qp = new URLSearchParams(document.location.search);

const id = qp.get('productId') ?? qp.get('id');

const $editForm = document.querySelector('edit-form');
const $productItem = document.querySelector('product-item');

if (qp.size === 1) {
    $editForm.setAttribute('entity-id', id);
    $productItem.setAttribute('product-id', id);

    const response = await fetch('http://localhost:8080/products/' + id);
    const json = await response.json();
    const product = json.data;

    $editForm.fillForm(product);
    $productItem.outerHTML = renderProductItemElement(product);
} else { // Form is submitted
    $editForm.remove();
    $productItem.remove();

    const $main = document.querySelector('main');
    $main.innerHTML += html`<output></output>`;
    const $output = document.querySelector('output');

    $output.textContent = 'Uppdaterar produkt...';

    const response = await fetch('http://localhost:8080/products/' + id, {
        method: 'PATCH',
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
    const updatedProduct = json.data[0];

    $output.textContent = 'Produkt uppdaterat:';
    $main.innerHTML += renderProductItemElement(updatedProduct);
}
