jQuery(document).ready(function(){
	jQuery('#star-rating li').click(function(){
		var li_index = parseInt(jQuery(this).attr('data-index'));
		jQuery('#star-rating').attr('data-star', li_index);
		jQuery('#star-rating li').each(function(index){
			setActiveStar(jQuery(this), index, li_index);
		});
	});

	jQuery('#star-rating li').hover(function(){
		var li_index = parseInt(jQuery(this).attr('data-index'));		
		jQuery('#star-rating li').each(function(index){
			setActiveStar(jQuery(this), index, li_index);
		});
	});

	jQuery('#star-rating').mouseleave(function() {
		var li_index = parseInt(jQuery('#star-rating').attr('data-star'));
		jQuery('#star-rating li').each(function(index){
			setActiveStar(jQuery(this), index, li_index);
		});
	});

	jQuery('#rate-button').click(function(){ rate(); });
});

/**
 * Set active star
 * @param object  obj 
 * @param integer i   
 * @param integer x   
 */
function setActiveStar(obj, i, x)
{
	if(i <= x - 1)
	{
		if(!obj.hasClass('active')) obj.addClass('active');
	}
	else
	{
		if(obj.hasClass('active'))	obj.removeClass('active');
	}
}

/**
 * Rate this post [AJAX] 
 */
function rate()
{
	var rate = parseInt(jQuery('#star-rating').attr('data-star'));
	var id   = parseInt(jQuery('#rate-block').attr('data-id'));
	var ip   = jQuery('#rate-block').attr('data-ip');

	jQuery.ajax({
		type: "POST",
		url: '/wp-admin/admin-ajax.php?action=rate',
		data: {
			rate : rate,			
			id   : id,
			ip   : ip},
		dataType: 'json',
		success: function(data)
		{
			if(data.msg == 'OK')
			{
				alert('Congratulations! You set a rating ' + data.rate + '.');
			}
			else
			{
				alert('You have already rated this post!');				
			}
		}
	});
}