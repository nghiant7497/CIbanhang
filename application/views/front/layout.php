<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"><!--<![endif]-->
<head>
    <?php $this->load->view('front/head');?>
</head>
<body class="header_sticky">
	<div class="boxed">

		<div class="overlay"></div>

		<!-- Preloader -->
		<div class="preloader">
			<div class="clear-loading loading-effect-2">
				<span></span>
			</div>
		</div><!-- /.preloader -->

        <?php $this->load->view('front/header', $this->data); ?>

        <!-- Content -->
        <?php  $this->load->view($template, $this->data);?>
        <!-- End content -->

        <?php $this->load->view('front/footer')?>


	</div><!-- /.boxed -->

		<!-- Javascript -->

		<script type="text/javascript" src="<?php echo public_url()?>/front/javascript/tether.min.js"></script>
		<script type="text/javascript" src="<?php echo public_url()?>/front/javascript/bootstrap.min.js"></script>
		<script type="text/javascript" src="<?php echo public_url()?>/front/javascript/waypoints.min.js"></script>
		<!-- <script type="text/javascript" src="javascript/jquery.circlechart.js"></script> -->
		<script type="text/javascript" src="<?php echo public_url()?>/front/javascript/easing.js"></script>
		<script type="text/javascript" src="<?php echo public_url()?>/front/javascript/jquery.flexslider-min.js"></script>
		<script type="text/javascript" src="<?php echo public_url()?>/front/javascript/owl.carousel.js"></script>
		<script type="text/javascript" src="<?php echo public_url()?>/front/javascript/smoothscroll.js"></script>
		<!-- <script type="text/javascript" src="javascript/jquery-ui.js"></script> -->
		<script type="text/javascript" src="<?php echo public_url()?>/front/javascript/jquery.mCustomScrollbar.js"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtRmXKclfDp20TvfQnpgXSDPjut14x5wk&region=GB"></script>
	   	<script type="text/javascript" src="<?php echo public_url()?>/front/javascript/gmap3.min.js"></script>
	   	<script type="text/javascript" src="<?php echo public_url()?>/front/javascript/waves.min.js"></script>
		<script type="text/javascript" src="<?php echo public_url()?>/front/javascript/jquery.countdown.js"></script>

		<script type="text/javascript" src="<?php echo public_url()?>/front/javascript/main.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                $('.btn-add-cart').click(function(){
                    var product_id = $(this).data("productid");
                    $.ajax({
                        url : "<?php echo base_url('cart/add') ?>",
                        method : "POST",
                        data : {product_id: product_id},
                        success: function(data){
                            $('#box-cart').html(data);
                            alert('Thêm sản phẩm vào giỏ hàng thành công.');
                        }
                    });
                });


                $('#box-cart').load("<?php echo site_url('cart/load_cart');?>");


                $(document).on('click','.romove_cart',function(){
                    var row_id=$(this).attr("id");
                    $.ajax({
                        url : "<?php echo site_url('product/delete_cart');?>",
                        method : "POST",
                        data : {row_id : row_id},
                        success :function(data){
                            $('#detail_cart').html(data);
                        }
                    });
                });
            });
        </script>
    <!-- Load Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js#xfbml=1&version=v2.12&autoLogAppEvents=1';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    <!-- Your customer chat code -->
    <div class="fb-customerchat"
         attribution=setup_tool
         page_id="571694666603102">
    </div>
</body>

</html>