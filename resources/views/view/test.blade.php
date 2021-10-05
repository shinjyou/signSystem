<!DOCTYPE html>
<html lang = "ja">
  <head>
    <meta charset = "utf-8">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <title>練習</title>
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}
  </head>

  <style>
.pulse-btn {
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background-color: #1da1f2;
  cursor: pointer;
  transition: box-shadow 0.3s;
}

.pulse-btn img {
  width: 60%;
}

.pulse-btn::before, .pulse-btn::after {
  content: "";
  display: block;
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  margin: auto;
  width: 100%;
  height: 100%;
  border: 1px solid #1da1f2;
  border-radius: 50%;
  box-sizing: border-box;
  pointer-events: none;
}

.pulse-btn:hover {
  box-shadow: 0 0 20px #1da1f2;
}

.pulse-btn:hover::before, 
.pulse-btn:hover::after {
  animation: pulsate 2s linear infinite;
}

.pulse-btn:hover::after {
  animation-delay: 1s;
}

@keyframes pulsate {
  0% {
    transform: scale(1);
    opacity: 1;
  }

  100% {
    transform: scale(2);
    opacity: 0;
  }
}

@media screen and (max-width: 768px) {
  .pulse-btn {
    box-shadow: 0 0 20px #1da1f2;
  }

  .pulse-btn::before, .pulse-btn::after {
    animation: pulsate 2s linear infinite;
  }

  .pulse-btn::after {
    animation: pulsate 2s linear infinite;
  }
}

/* //////// */


.part-delete {
  text-align: center;
  letter-spacing: 0.1em;
  user-select: none;
  font-size: 1.6rem;
  font-weight: 700;
  line-height: 1.5;
  text-decoration:none;
  display: block;
  width: 200px;
  margin: 0 auto;
  padding: 0;
  perspective: 500px;
}

.part-delete:hover .box {
  transform: translateY(-50%) rotateX(90deg);
}

.box {
  position: relative;
  display: block;
  width: 100%;
  height: 100%;
  margin: auto;
  transition: all 0.4s;
  transform: rotateX(0);
  text-decoration: none;
  text-transform: uppercase;
  color: #fff;
  transform-style: preserve-3d;
}

.box-face {
  display: block;
  position: relative;
  width: 100%;
  padding: 1.5rem 0;
  transition: all 0.4s;
  color: #fff;
  backface-visibility: hidden;
}

.front {
  background: #eb6100;
} 
.back {
  position: absolute;
  top: 100%;
  left: 0;
  transform: translateY(-1px) rotateX(-90deg);
  transform-origin: 50% 0;
  background: #dc5b00;
}

/* .fa-position-right {
  position: absolute;
  top: calc(50% - 0.5em);
  right: 1rem;
} */


  </style>

  <body>
    <h1>練習</h1>
    <a class="pulse-btn">
      <img src="/image/twitter_icon.png" alt="">
    </a>

    <a href="" class="part-delete">
      <span class="box">
        <span class="box-face front">表です<i class="fas fa-angle-right fa-position-right"></i></span>
        <span class="box-face back">側面です<i class="fas fa-angle-right fa-position-right"></i></span>
      </span>
    </a>
  </body>
</html>