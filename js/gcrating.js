jQuery(document).ready(function(){
	jQuery('#star-rating li').click(function(){
		var li_index = parseInt(jQuery(this).attr('data-index'));
		jQuery('#star-rating').attr('data-star', li_index);
		jQuery('#star-rating li').each(function(index){
			if(index <= li_index-1)
			{
				if(!jQuery(this).hasClass('active')) jQuery(this).addClass('active');
			}
			else
			{
				if(jQuery(this).hasClass('active'))	jQuery(this).removeClass('active');
			}
		});
	});
});