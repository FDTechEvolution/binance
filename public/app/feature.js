function setNumberDecimal(id) {
    document.querySelector(`#${id}`).value = parseFloat(document.querySelector(`#${id}`).value).toFixed(5)
}

function confirmDeleteFeature(id, coin) {
    document.querySelector('#delete-coin-name').innerHTML = coin
    document.querySelector('#feature-id-delete').value = id
}

function featureEdit(index) {
    let elem = document.querySelector(`#edit-feature-btn_${index}`)
    document.querySelector('#feature-e-id').value = elem.dataset.id
    document.querySelector('#feature-e-coin').value = elem.dataset.coin
    document.querySelector('#feature-e-type').value = elem.dataset.type
    document.querySelector('#feature-e-stop_loss').value = elem.dataset.stop
    document.querySelector('#feature-e-avg_price').value = elem.dataset.avg
    document.querySelector('#feature-e-usdt_pnl').value = elem.dataset.usdt
    document.querySelector('#feature-e-docdate').value = elem.dataset.docdate
    document.querySelector('#feature-e-status').value = elem.dataset.status
    document.querySelector('#feature-e-description').value = elem.dataset.description
    document.querySelector('#feature-e-entry1').value = elem.dataset.entry1
    document.querySelector('#feature-e-entry2').value = elem.dataset.entry2
    document.querySelector('#feature-e-entry3').value = elem.dataset.entry3
    document.querySelector('#feature-e-target1').value = elem.dataset.target1
    document.querySelector('#feature-e-target2').value = elem.dataset.target2
    document.querySelector('#feature-e-target3').value = elem.dataset.target3
}