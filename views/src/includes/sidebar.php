<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
    <a class="sidebar-brand brand-logo" style="padding: 0;" href="/favorites"><img style='margin-left: 60px; height: 35px; vertical-align: top;' src='/assets/images/logo/qi_logo.png'></a>
    <a class="sidebar-brand brand-logo-mini" style="padding: 0;" href="/favorites"><img style='margin-left: 25px; width: 25px; height: 25px;  vertical-align: text-top;' src='/assets/images/icon/qi_icon.png'></a>
  </div>
  <ul class="nav">
    <li class='nav-item menu-items'>
      <a class='nav-link' href='/favorites'>
        <span class='menu-icon'>
          <i style="color: #ff0;" class='mdi mdi-star'></i>
        </span>
        <span class='menu-title' style='margin-left: 5px; font-size: 100%;'><b>Favorites</b></span>
      </a>
    </li>
    <li class='nav-item menu-items'>
      <a class='nav-link' href='/my-ideas'>
        <span class='menu-icon'>
          <i class='mdi mdi-lightbulb-on-outline'></i>
        </span>
        <span class='menu-title' style='margin-left: 5px; font-size: 100%;'><b>My Ideas</b></span>
      </a>
    </li>
    <li class='nav-item menu-items'>
      <a class='nav-link' href='/public-ideas'>
        <span class='menu-icon'>
          <i class='mdi mdi-lightbulb-on-outline'></i>
        </span>
        <span class=' menu-title' style='margin-left: 5px; font-size: 100%;'><b>Public Ideas</b></span>
      </a>
    </li>
  </ul>
</nav>
<div class="container-fluid page-body-wrapper">
  <?php
  require_once(__DIR__ . "/navbar.php");
