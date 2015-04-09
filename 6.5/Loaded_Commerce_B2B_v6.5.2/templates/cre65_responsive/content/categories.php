<?PHP
$categories = new box_categories();
//echo $categories->categories_string;
?>
<script type="text/javascript">
  $(document).ready(function(){
  	$(".view_more").on('click', function(e) 
	  { 
	  	var rel	=	$(this).attr('rel');
		if(rel==0)
		{
			$(".hidden-div").css("height", "auto");
			$(".hidden-ul").css("height", "auto");
			$(".hidden-ul").css("overflow", "auto");
			$(this).attr('rel','1')
		}
		else
		{
			$(".hidden-div").css("height", "416px");
			$(".hidden-ul").css("height", "365px");
			$(".hidden-ul").css("overflow", "hidden");
			$(this).attr('rel','0')
		}
	  });
   });

</script>
  <h2 class="col-lg-12 gry_box y_clr con_txt">Product List</h2>
  <div class="clearfix"></div>
  <div class="col-lg-12 hidden-div" style="height: 416px;">
  
  <ul class="panel-group hidden-ul" id="accordion" style="height: 365px; overflow: hidden;">
  	  <?php echo $categories->categories_string; ?>
  </ul>      
    <!--<ul class="panel-group" id="accordion">
      <li class="panel panel-default">
        <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> <span>Collapsible Group Item #1</span><i class="fa fa-caret-down pull-right"></i> </a> </h4>
        <div id="collapseOne" class="panel-collapse collapse">
          <div class="panel-body">
            <ul>
              <li><a href="#"><i class="fa fa-caret-right"></i>Home</a></li>
              <li><a href="#"><i class="fa fa-caret-right"></i>Profile</a></li>
              <li><a href="#"><i class="fa fa-caret-right"></i>Messages</a></li>
            </ul>
          </div>
        </div>
      </li>
      <li class="panel panel-default">
        <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"> <span>Collapsible Group Item #1</span><i class="fa fa-caret-down pull-right"></i> </a></h4>
        <div id="collapseTwo" class="panel-collapse collapse">
          <div class="panel-body">
            <ul>
              <li><a href="#"><i class="fa fa-caret-right"></i>Home</a></li>
              <li><a href="#"><i class="fa fa-caret-right"></i>Profile</a></li>
              <li><a href="#"><i class="fa fa-caret-right"></i>Messages</a></li>
            </ul>
          </div>
        </div>
      </li>
      <li class="panel panel-default">
        <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapseTree"> <span>Collapsible Group Item #1</span><i class="fa fa-caret-down pull-right"></i> </a></h4>
        <div id="collapseTree" class="panel-collapse collapse">
          <div class="panel-body">
            <ul>
              <li><a href="#"><i class="fa fa-caret-right"></i>Home</a></li>
              <li><a href="#"><i class="fa fa-caret-right"></i>Profile</a></li>
              <li><a href="#"><i class="fa fa-caret-right"></i>Messages</a></li>
            </ul>
          </div>
        </div>
      </li>
    </ul>-->
    <a href="javascript:void(0)" class="v_more view_more" rel="0">View More<i class="fa fa-angle-right "></i></a> </div>
  <div class="col-lg-12 f-t-shirt">
    <div class="row col-md-12 f-t-txt">
      <div class="col-md-8"><span class="mtop25">FREE T-SHIRT</span></div>
      <div class="col-md-4">
        <div class="row">with<span>$100</span>Order</div>
      </div>
    </div>
  </div>

