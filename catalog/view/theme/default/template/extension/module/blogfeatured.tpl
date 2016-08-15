<h3 class=""><?php echo $heading_title; ?></h3>

<div class="blogfeatured">
  <?php foreach ($blogmegas as $blogmega) { ?>


    <div class="blogmega-thumb transition">

      <div class="image"><a href="<?php echo $blogmega['href']; ?>"><img src="<?php echo $blogmega['thumb']; ?>" alt="<?php echo $blogmega['name']; ?>" title="<?php echo $blogmega['name']; ?>" class="img-responsive" /></a></div>

      <div class="caption">

        <h4 class="blog-title"><a href="<?php echo $blogmega['href']; ?>"><?php echo $blogmega['name']; ?></a></h4>
        <li><?php echo utf8_substr(strip_tags(html_entity_decode($blogmega['description'])),0,100); ?>...</li>



      </div>

    </div>


  

  <?php } ?>

    </div>
<script type="text/javascript">
  $(document).ready(function(){
    $('.blogfeatured').owlCarousel({
      items: 1,
      pagination: false,
      navigation: false,
    });
  });

</script>