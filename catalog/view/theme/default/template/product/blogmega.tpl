<?php echo $header; ?>

<div class="container">

  <ul class="breadcrumb">

    <?php foreach ($breadcrumbs as $breadcrumb) { ?>

    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>

    <?php } ?>

  </ul>

  <div class="row"><?php echo $column_left; ?>

    <?php if ($column_left && $column_right) { ?>

    <?php $class = 'col-sm-6'; ?>

    <?php } elseif ($column_left || $column_right) { ?>

    <?php $class = 'col-sm-9'; ?>

    <?php } else { ?>

    <?php $class = 'col-sm-12'; ?>

    <?php } ?>

    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>

      <div class="row">

        <div>
          <h1><?php echo $heading_title; ?></h1>

          <ul class="list-unstyled">

            <?php if ($manufacturer) { ?>

            <li><?php echo $text_manufacturer; ?> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></li>

            <?php } ?>
          </ul>
            <div class="tab-pane active" id="tab-description"><?php echo $description; ?></div>
     

          <div>

            

            <?php if ($attribute_groups) { ?>

            <div class="tab-pane" id="tab-specification">

              <table class="table table-bordered">

                <?php foreach ($attribute_groups as $attribute_group) { ?>

                <thead>

                  <tr>

                    <td colspan="2"><strong><?php echo $attribute_group['name']; ?></strong></td>

                  </tr>

                </thead>

                <tbody>

                  <?php foreach ($attribute_group['attribute'] as $attribute) { ?>

                  <tr>

                    <td><?php echo $attribute['name']; ?></td>

                    <td><?php echo $attribute['text']; ?></td>

                  </tr>

                  <?php } ?>

                </tbody>

                <?php } ?>

              </table>

            </div>

            <?php } ?>

            <?php if ($review_status) { ?>

            <div class="tab-pane" id="tab-review">

              <form class="form-horizontal" id="form-review">

                <div id="review"></div>

                <h2><?php echo $text_write; ?></h2>

                <?php if ($review_guest) { ?>

                <div class="form-group required">

                  <div class="col-sm-12">

                    <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>

                    <input type="text" name="name" value="<?php echo $customer_name; ?>" id="input-name" class="form-control" />

                  </div>

                </div>

                <div class="form-group required">

                  <div class="col-sm-12">

                    <label class="control-label" for="input-review"><?php echo $entry_review; ?></label>

                    <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>

                    <div class="help-block"><?php echo $text_note; ?></div>

                  </div>

                </div>

                <div class="form-group required">

                  <div class="col-sm-12">

                    <label class="control-label"><?php echo $entry_rating; ?></label>

                    &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;

                    <input type="radio" name="rating" value="1" />

                    &nbsp;

                    <input type="radio" name="rating" value="2" />

                    &nbsp;

                    <input type="radio" name="rating" value="3" />

                    &nbsp;

                    <input type="radio" name="rating" value="4" />

                    &nbsp;

                    <input type="radio" name="rating" value="5" />

                    &nbsp;<?php echo $entry_good; ?></div>

                </div>

                <?php echo $captcha; ?>

                <div class="buttons clearfix">

                  <div class="pull-right">

                    <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>

                  </div>

                </div>

                <?php } else { ?>

                <?php echo $text_login; ?>

                <?php } ?>

              </form>

            </div>

            <?php } ?>

          </div>

        </div>



        

      </div>

      <?php if ($blogmegas) { ?>

      <h3><?php echo $text_related; ?></h3>

      <div class="row">

        <?php $i = 0; ?>

        <?php foreach ($blogmegas as $blogmega) { ?>

        <?php if ($column_left && $column_right) { ?>

        <?php $class = 'col-xs-8 col-sm-6'; ?>

        <?php } elseif ($column_left || $column_right) { ?>

        <?php $class = 'col-xs-6 col-md-4'; ?>

        <?php } else { ?>

        <?php $class = 'col-xs-6 col-sm-3'; ?>

        <?php } ?>

        <div class="<?php echo $class; ?>">

          <div class="blogmega-thumb transition">

            <div class="image"><a href="<?php echo $blogmega['href']; ?>"><img src="<?php echo $blogmega['thumb']; ?>" alt="<?php echo $blogmega['name']; ?>" title="<?php echo $blogmega['name']; ?>" class="img-responsive" /></a></div>

            <div class="caption">

              <h4><a href="<?php echo $blogmega['href']; ?>"><?php echo $blogmega['name']; ?></a></h4>

              <p><?php echo $blogmega['description']; ?></p>

              <?php if ($blogmega['rating']) { ?>

              <div class="rating">

                <?php for ($j = 1; $j <= 5; $j++) { ?>

                <?php if ($blogmega['rating'] < $j) { ?>

                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>

                <?php } else { ?>

                <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i class="fa fa-star-o fa-stack-1x"></i></span>

                <?php } ?>

                <?php } ?>

              </div>

              <?php } ?>

              <?php if ($blogmega['price']) { ?>

              <p class="price">

                <?php if (!$blogmega['special']) { ?>

                <?php echo $blogmega['price']; ?>

                <?php } else { ?>

                <span class="price-new"><?php echo $blogmega['special']; ?></span> <span class="price-old"><?php echo $blogmega['price']; ?></span>

                <?php } ?>

                <?php if ($blogmega['tax']) { ?>

                <span class="price-tax"><?php echo $text_tax; ?> <?php echo $blogmega['tax']; ?></span>

                <?php } ?>

              </p>

              <?php } ?>

            </div>

            <div class="button-group">

              <button type="button" onclick="cart.add('<?php echo $blogmega['blogmega_id']; ?>', '<?php echo $blogmega['minimum']; ?>');"><span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span> <i class="fa fa-shopping-cart"></i></button>

              <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $blogmega['blogmega_id']; ?>');"><i class="fa fa-heart"></i></button>

              <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $blogmega['blogmega_id']; ?>');"><i class="fa fa-exchange"></i></button>

            </div>

          </div>

        </div>

        <?php if (($column_left && $column_right) && (($i+1) % 2 == 0)) { ?>

        <div class="clearfix visible-md visible-sm"></div>

        <?php } elseif (($column_left || $column_right) && (($i+1) % 3 == 0)) { ?>

        <div class="clearfix visible-md"></div>

        <?php } elseif (($i+1) % 4 == 0) { ?>

        <div class="clearfix visible-md"></div>

        <?php } ?>

        <?php $i++; ?>

        <?php } ?>

      </div>

      <?php } ?>

      <?php if ($tags) { ?>

      <p><?php echo $text_tags; ?>

        <?php for ($i = 0; $i < count($tags); $i++) { ?>

        <?php if ($i < (count($tags) - 1)) { ?>

        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,

        <?php } else { ?>

        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>

        <?php } ?>

        <?php } ?>

      </p>

      <?php } ?>

      <?php echo $content_bottom; ?></div>

    <?php echo $column_right; ?></div>

</div>


<script type="text/javascript"><!--


//--></script>

<script type="text/javascript"><!--




//--></script>

<script type="text/javascript"><!--

$('#review').delegate('.pagination a', 'click', function(e) {

    e.preventDefault();



    $('#review').fadeOut('slow');



    $('#review').load(this.href);



    $('#review').fadeIn('slow');

});



$('#review').load('index.php?route=product/blogmega/review&blogmega_id=<?php echo $blogmega_id; ?>');



$('#button-review').on('click', function() {

	$.ajax({

		url: 'index.php?route=product/blogmega/write&blogmega_id=<?php echo $blogmega_id; ?>',

		type: 'post',

		dataType: 'json',

		data: $("#form-review").serialize(),

		beforeSend: function() {

			$('#button-review').button('loading');

		},

		complete: function() {

			$('#button-review').button('reset');

		},

		success: function(json) {

			$('.alert-success, .alert-danger').remove();



			if (json['error']) {

				$('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');

			}



			if (json['success']) {

				$('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');



				$('input[name=\'name\']').val('');

				$('textarea[name=\'text\']').val('');

				$('input[name=\'rating\']:checked').prop('checked', false);

			}

		}

	});

});


//--></script>

<?php echo $footer; ?>

