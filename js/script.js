const deleteForm = document.querySelector('#delete-form')
const deleteBtn = document.querySelector('button[name=delete]')

deleteForm.addEventListener('submit', e => {
  e.preventDefault()
  if( confirm('本当に削除しますか？') ) {
    e.target.submit()
  }
})