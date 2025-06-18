import { html } from '../helpers/utils.js';

export function renderProductItemElement(product) {
    return html`
        <product-item product-id="${product.id}">
            <span slot="name">${product.name}</span>
            <span slot="price">${product.price}</span>
            <span slot="categories">${product.categories}</span>
            <img slot="image" src="${product.image}" alt="${product.name}">
        </product-item>
    `;
}
