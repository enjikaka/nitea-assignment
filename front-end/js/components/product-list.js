import './product-item.js';

const html = String.raw;

class ProductList extends HTMLElement {
    constructor() {
        super();
        this.products = [];
    }

    async fetchProducts() {
        return fetch('http://localhost:8080/products')
            .then(response => response.json())
            .then(response => {
                this.products = response.data;
            });
    }

    async connectedCallback() {
        await this.fetchProducts();
        this.render();
    }

    render() {
        const sDOM = this.attachShadow({ mode: 'closed' });

        const productItems = this.products.map(product => html`
            <li>
                <product-item product-id="${product.id}">
                    <h2 slot="name">${product.name}</h2>
                    <p slot="price">${product.price}</p>
                    <p slot="categories">${product.categories}</p>
                    <img slot="image" src="${product.image}" alt="${product.name}">
                </product-item>
            </li>
        `).join('');

        sDOM.innerHTML = html`
            <style>
                ol {
                    list-style: none;
                    display: flex;
                    flex-flow: column nowrap;
                    gap: 1rem;
                    padding: 0 1rem;
                    margin: 0;
                }
            </style>
            <ol>
                ${productItems}
            </ol>
        `;
    }
}

customElements.define('product-list', ProductList);