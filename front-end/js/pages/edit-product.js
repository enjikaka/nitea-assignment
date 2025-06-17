import '../components/edit-form.js';

const qp = new URLSearchParams(document.location.search);

const id = qp.get('productId');

const editForm = document.querySelector('edit-form');

editForm.setAttribute('entity-id', id);
