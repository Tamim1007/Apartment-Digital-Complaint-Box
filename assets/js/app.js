// assets/js/app.js
document.addEventListener('click', (e)=>{
  const el = e.target.closest('[data-confirm]');
  if(el){
    if(!confirm(el.getAttribute('data-confirm'))){
      e.preventDefault();
    }
  }
});
