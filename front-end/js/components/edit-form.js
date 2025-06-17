import { schemaToForm } from '../helpers/schema-to-form.js';

const html = String.raw;

class EditForm extends HTMLElement {
    constructor() {
        super();
        this.schema = null;
    }

    async connectedCallback() {
        this.schema = await this.fetchSchema();
        this.render();
    }

    async attributeChangedCallback(name, oldValue, newValue) {
        if (name === 'entity-id') {
            const product = await this.fetchProduct();
            this.fillForm(product);
        }
    }

    async fetchProduct() {
        const response = await fetch(this.getAttribute('action-url') + '/' + this.getAttribute('entity-id'));
        const product = await response.json();

        return product.data;
    }

    async fetchSchema() {
        const response = await fetch(this.getAttribute('schema-url'));
        const schema = await response.json();

        return schema;
    }

    async render() {
        this.sDOM = this.attachShadow({ mode: 'closed' });

        this.sDOM.innerHTML = html`
            <style>
                :root {
                    display: block;
                    width: 100%;
                    height: auto;
                }

                :host(:not([entity-id])) {
                    background-color: red;
                }
            </style>
        `;

        this.sDOM.innerHTML += schemaToForm(this.schema);

        if (this.getAttribute('entity-id') !== "null") {
            const product = await this.fetchProduct();
            this.fillForm(product);
        }
    }

    fillForm(product) {
        const form = this.sDOM.querySelector('form');
        const formData = new FormData(form);

        console.log(product);

        formData.forEach((value, key) => {
            form.querySelector(`[name="${key}"]`).value = product[key];
        });
    }
}

customElements.define('edit-form', EditForm);