<!DOCTYPE html>

<!--[if IE]><![endif]-->

<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->

<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->

<!--[if (gt IE 9)|!(IE)]><!-->

<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">

<!--<![endif]-->

<head>

<meta charset="UTF-8" />

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<title><?php echo $title; ?></title>

<base href="<?php echo $base; ?>" />

<?php if ($description) { ?>

<meta name="description" content="<?php echo $description; ?>" />

<?php } ?>

<?php if ($keywords) { ?>

<meta name="keywords" content= "<?php echo $keywords; ?>" />

<?php } ?>

<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>


<script src="catalog/view/theme/default/js/main.js" type="text/javascript"></script>


<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />

<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />


<link href="catalog/view/javascript/jquery/owl-carousel/owl.carousel.css" type="text/css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/jquery/owl-carousel/owl.carousel.min.js" type="text/javascript"></script>

<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">

<?php foreach ($styles as $style) { ?>

<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />

<?php } ?>

<script src="catalog/view/javascript/common.js" type="text/javascript"></script>

<?php foreach ($links as $link) { ?>

<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />

<?php } ?>

<?php foreach ($scripts as $script) { ?>

<script src="<?php echo $script; ?>" type="text/javascript"></script>

<?php } ?>

<?php foreach ($analytics as $analytic) { ?>

<?php echo $analytic; ?>

<?php } ?>

</head>

<body class="<?php echo $class; ?>">



<header>

  <div class="container">

    <div class="row">
    <div class="header_header">
     <div class="col-sm-4">

             <ul class="list-inline">

             <li class="cart_ms"> <?php echo $cart; ?></li>
        <li class="dropdown account"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>

          <ul class="dropdown-menu dropdown-menu-right">

            <?php if ($logged) { ?>

            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>

            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>

            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>

            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>

            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>

            <?php } else { ?>

            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>

            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>

            <?php } ?>

          </ul>

        </li>


      </ul>

     </div>

      <div class="col-sm-5">

        <div id="logo">

          <?php if ($logo) { ?>

          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>

          <?php } else { ?>

          <h1><a href="<?php echo $home; ?>"><?php echo $naaccme; ?></a></h1>

          <?php } ?>

        </div>

      </div>

      <div class="col-sm-3"><?php echo $search; ?>

      </div>
      </div>
    

     

    </div>

  </div>

</header>



<?php if ($categories) { ?>
<div class="menu_main">
<div class="container">

  <nav id="menu" class="navbar">

    <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>

      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>

    </div>

    <div class="collapse navbar-collapse navbar-ex1-collapse">

      <ul class="nav navbar-nav">
        <li><a href="#">Trang chu</a></li>
        <?php foreach ($categories as $category) { ?>

        <?php if ($category['children']) { ?>
        <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>

          <div class="dropdown-menu">

            <div class="dropdown-inner">

              <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>

              <ul class="list-unstyled">

                <?php foreach ($children as $child) { ?>

                <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>

                <?php } ?>

              </ul>

              <?php } ?>

            </div>

            <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a> </div>

        </li>

        <?php } else { ?>
        
        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>

        <?php } ?>

        <?php } ?>
        
      </ul>

    </div>

  </nav>

</div>
</div>
<?php
 $less = "lessc.inc.php";
if(!file_exists($less)) {
    echo "khong tim thay file";
    }
else{
    require 'lessc.inc.php';
    $lessc = new lessc();
    $lessc->compileFile("catalog/view/theme/default/stylesheet/stylesheet.less","catalog/view/theme/default/stylesheet/stylesheet.css");
}

 ?>
<?php } ?>
 <!-- by T.A-->
 <?php echo $content_slide;

  ?>
  <!-- end by T.A-->

