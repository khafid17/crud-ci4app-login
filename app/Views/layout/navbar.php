<nav class="navbar navbar-expand-lg navbar-light bg-light">

  <div class="container">
    <a class="navbar-brand" href="#">Zanpakuto</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/pages/about">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/pages/contact">Contact Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/komik">Komik</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/orang">Orang</a>
        </li>
      </ul>
    </div>
  <?php if(logged_in()) : ?>
      <a class="nav-link" href="/logout">Logout</a>
  <?php else : ?>
      <a class="nav-link" href="/login">login</a>
  <?php endif ; ?>
  </div>
</nav>