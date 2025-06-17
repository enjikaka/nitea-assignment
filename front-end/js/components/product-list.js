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
        this.innerHTML = `
            <ul>
                ${this.products.map(product => `<li>${product.name}</li>`).join('')}
            </ul>
        `;
    }
}

customElements.define('product-list', ProductList);