import './md-button.js';

import { schemaToForm } from '../helpers/schema-to-form.js';
import { html, linkStylesheet } from '../helpers/utils.js';

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
        if (new URLSearchParams(window.location.search).size > 1) {
            return;
        }

        const response = await fetch(this.getAttribute('action-url') + '/' + this.getAttribute('entity-id'));
        const product = await response.json();

        return product.data;
    }

    fillForm(product) {
        const form = this.sDOM.querySelector('form');

        if (!form) {
            return;
        }

        const formData = new FormData(form);

        formData.forEach((value, key) => {
            form.querySelector(`[name="${key}"]`).value = product[key];
        });

        if (form.querySelector('input[name="id"]')) {
            form.querySelector('input[name="id"]').value = product.id;
        } else {
            form.innerHTML += html`<input type="hidden" name="id" value="${product.id}">`;
        }
    }

    async fetchSchema() {
        const response = await fetch(this.getAttribute('schema-url'));
        const schema = await response.json();

        return schema;
    }

    async render() {
        this.sDOM = this.attachShadow({ mode: 'closed' });

        this.sDOM.innerHTML = html`
            ${linkStylesheet(import.meta.url)}
            ${schemaToForm(this.schema)}
        `;

        if (this.getAttribute('entity-id') !== "null") {
            const product = await this.fetchProduct();
            this.fillForm(product);
        }
    }
}

customElements.define('edit-form', EditForm);