import { html } from '../helpers/utils.js';

export function renderProductItemElement(product) {
    return html`
        <product-item product-id="${product.id}">
            <h2 slot="name">${product.name}</h2>
            <p slot="price">${product.price}</p>
            <p slot="categories">${product.categories}</p>
            <img slot="image" src="${product.image}" alt="${product.name}">
        </product-item>
    `;
}
