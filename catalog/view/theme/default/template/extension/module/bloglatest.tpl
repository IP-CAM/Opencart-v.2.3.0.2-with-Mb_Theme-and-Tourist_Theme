<h3 class="title_main"><?php echo $heading_title; ?></h3>

<div class="row">

  <?php foreach ($blogmegas as $blogmega) { ?>

  <div class="blogmega-layout col-lg-4 col-md-4 col-sm-6 col-xs-12">

    <div class="blogmega-thumb transition">

      <div class="image"><a href="<?php echo $blogmega['href']; ?>"><img src="<?php echo $blogmega['thumb']; ?>" alt="<?php echo $blogmega['name']; ?>" title="<?php echo $blogmega['name']; ?>" class="img-responsive" /></a></div>

      <div class="caption">

        <h4 class="blog-title"><a href="<?php echo $blogmega['href']; ?>"><?php echo $blogmega['name']; ?></a></h4>
        <li><?php echo utf8_substr(strip_tags(html_entity_decode($blogmega['description'])),0,200); ?>...</li>



      </div>

    </div>

  </div>

  <?php } ?>

</div>

