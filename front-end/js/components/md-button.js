import { html, linkStylesheet } from '../helpers/utils.js';

export class MdButton extends HTMLElement {
    connectedCallback() {
        this.sDOM = this.attachShadow({ mode: 'open' });
        this.sDOM.innerHTML = html`
            ${linkStylesheet(import.meta.url)}
            <slot></slot>
        `;
    }
}

customElements.define('md-button', MdButton);