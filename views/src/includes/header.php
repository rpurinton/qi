<?php
$header = function ($title = "𝗤𝘂𝗮𝗻𝘁𝘂𝗺 𝗜𝗻𝗻𝗼𝘃𝗮𝘁𝗶𝗼𝗻𝘀", $side_nav = true) {
?>
  <!DOCTYPE html>
  <html lang="en-US" itemscope itemtype="https://schema.org/WebPage" style='background-color: #111111;'>

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1">
    <meta name="robots" content="max-image-preview:large" />
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="/assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/highlight.css">

    <link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="/assets/favicon/safari-pinned-tab.svg" color="#111111">
    <link rel="shortcut icon" href="/assets/favicon/favicon.ico">
    <meta name="msapplication-TileColor" content="#111111">
    <meta name="msapplication-config" content="/assets/favicon/browserconfig.xml">
    <meta name="theme-color" content="#111111">
  </head>

  <body class="flex sidebar-icon-only" style='background-color: #111111;'>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <div class="container-scroller">
    <?php
    if ($side_nav) require_once(__DIR__ . "/sidebar.php");
  };
