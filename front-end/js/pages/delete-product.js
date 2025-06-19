import '../components/app-header.js';
import '../components/product-item.js';
import '../components/md-button.js';

import { hydrateProductItem } from '../helpers/hydrate-product-item.js';
import { html } from '../helpers/utils.js';

const qp = new URLSearchParams(document.location.search);

const id = qp.get('productId') ?? qp.get('id');

const $confirmLink = document.querySelector('md-button[href*="action=DELETE"]');
const $productItem = document.querySelector('product-item');

async function hydratePage() {
    console.log('hydratePage', id, $confirmLink);

    if ($confirmLink) {
        $confirmLink.setAttribute('href', $confirmLink.getAttribute('href') + id);
    }

    if ($productItem) {
        hydrateProductItem($productItem, id);
    }
}

async function handleDeleteRequest() {
    const $main = document.querySelector('main');
    $main.innerHTML = html`<output>Tar bort produkt...</output><a href="/">Tillbaka</a>`;

    const response = await fetch('http://localhost:8080/products/' + id, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
        },
    });

    const json = await response.json();

    if (response.ok) {
        $main.innerHTML = html`<output>Produkt borttagen</output>`;
    } else {
        $main.innerHTML = html`<output>Kunde inte ta bort produkt: ${json.message}</output>`;
    }
}

if (id && qp.size === 1) {
    hydratePage();
} else {
    handleDeleteRequest();
}