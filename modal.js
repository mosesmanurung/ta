// mengambil elemen div id modal

var modal = document.getElementById('simpleModal');
// mengambil elemen button id modal

var modalBtn = document.getElementById('modalBtn');
// mengambil class closeBtn

var closeBtn = document.getElementsByClassName('closeBtn')[0];
 
// jika tombol di klik maka akan memanggil function open modal

modalBtn.addEventListener('click', openModal);
// jika tombol di klik maka akan memanggil function close modal

closeBtn.addEventListener('click', closeModal);
// jika tombol di klik maka akan memanggil function outside click

window.addEventListener('click', outsideClick);
 
// Function untuk open modal

function openModal(){

  modal.style.display = 'block';

}
 
// Function untuk close modal

function closeModal(){

  modal.style.display = 'none';

}
 
// Function untuk close modal jika di klik diluar modal (outside click)

function outsideClick(e){

  if(e.target == modal){

    modal.style.display = 'none';

  }

}