import { html, linkStylesheet } from '../helpers/utils.js';

class AppHeader extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.sDOM = this.attachShadow({ mode: 'closed' });
        this.sDOM.innerHTML = html`
            ${linkStylesheet(import.meta.url)}
            <header>
                <strong><a href="/">Frukthandlaren</a></strong>
            </header>
        `;
    }
}

customElements.define('app-header', AppHeader);