<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
       <link rel="stylesheet" href="css/perfil.css" >
       <link rel="stylesheet" href="css/home.css" >
    <link rel="stylesheet" href="css/all.min.css">
    <title>Document</title>
</head>

      
  
<body>
  
<header>
  <img class="logo" src="img/lofofinall.png" alt="logo">

  <div class="middle-and-right">
    <input type="text" class="search" placeholder="Buscar...">

    <div class="right-group">
      <div class="icons">
        <a href="#"><i class="fa-solid fa-house"></i></a>
        <a href="#"><i class="fa-solid fa-envelope"></i></a>
        <a href="#"><i class="fa-solid fa-right-to-bracket"></i></a>
        <a href="#"><i class="fa-solid fa-bell"></i></a>
      </div>
      <div class="perfil">
          <img src="https://i.pravatar.cc/100" class="rs-avatar" alt="perfil">
        <span>Jorge Quiroz</span>
      </div>
    </div>
  </div>

  <div class="hamburger">☰</div>
</header>
<script>
const hamburger = document.querySelector('.hamburger');
const menu = document.querySelector('.middle-and-right');

hamburger.addEventListener('click', () => {
  menu.classList.toggle('open');
});

</script>


<div class="rs-container">

  <!-- CAJA DE TEXTO -->
  <div class="rs-box">
    <img src="https://i.pravatar.cc/100" class="rs-avatar" alt="perfil">

    <div class="rs-input-area">
      <textarea placeholder="¿Qué estás pensando?"></textarea>

      <div class="rs-actions">
     <label class="rs-photo">
 <i class="tama fa-regular fa-image"></i>
  <input type="file" accept="image/*" hidden>
</label>
        <button class="rs-publish">Publicar</button>
      </div>
    </div>
  </div>

  <!-- PUBLICACIÓN -->
  <div class="rs-post">
    <div class="rs-post-header">
      <img src="https://i.pravatar.cc/100" class="rs-avatar" alt="perfil">
      <div>
        <strong>Jorge Andrés</strong>
        <p>The Beatles xs</p>
        
      </div>
    </div>
    <div class="publica">
      <img  src="img/publi.jpg" alt="user"></div>
   
  

    <div class="rs-reactions">
      <button><i class="fa-solid fa-heart"></i>  75</button>
      <button><i class="fa-solid fa-heart-crack"></i>  30</button>
    </div>
  </div>

</div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
</body>

</html>