const toast = document.querySelector(".toast");
const closeIcon = document.querySelector(".close");

closeIcon.addEventListener("click", function() {
  toast.classList.toggle("tutup");
});

$(document).ready(function(){
    $("#postingan").load("index.php #postingan");
   setInterval(function() {
       $("#postingan").load("index.php #postingan");
   }, 2000);
});




