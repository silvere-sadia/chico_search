document.querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
});

document
    .querySelectorAll('ul.criterias li')
    .forEach((criteria) => {
        addTagFormDeleteLink(criteria)
})

function addFormToCollection(e) {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');
    item.classList.add('border', 'border-light-subtle', 'rounded', 'p-2', 'm-1');

    item.innerHTML = collectionHolder
      .dataset
      .prototype
      .replace(
        /__name__/g,
        collectionHolder.dataset.index
      );

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    addTagFormDeleteLink(item);
};

function addTagFormDeleteLink(item) {
    const removeFormButton = document.createElement('button');
    removeFormButton.classList.add('btn', 'btn-danger', 'text-end');
    removeFormButton.innerText = 'Delete this criteria';

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });
}