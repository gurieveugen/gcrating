jQuery(document).ready(function(){
	jQuery('#star-rating li').click(function(){
		var li_index = parseInt(jQuery(this).attr('data-index'));
		jQuery('#star-rating').attr('data-star', li_index);
		setActiveStar('#star-rating li', li_index);
	});

	jQuery('#star-rating li').hover(function(){
		var li_index = parseInt(jQuery(this).attr('data-index'));		
		setActiveStar('#star-rating li', li_index);
	});

	jQuery('#star-rating').mouseleave(function() {
		var li_index = parseInt(jQuery('#star-rating').attr('data-star'));
		setActiveStar('#star-rating li', li_index);
	});

	jQuery('#rate-button').click(function(){ rate(); });
});

/**
 * Set active star
 * @param string  dom
 * @param integer index   
 */
function setActiveStar(dom, index)
{
	jQuery(dom).each(function(i){
		if(i <= index - 1)
		{
			if(!jQuery(this).hasClass('active')) jQuery(this).addClass('active');
		}
		else
		{
			if(jQuery(this).hasClass('active'))	jQuery(this).removeClass('active');
		}
	});
}

/**
 * Rate this post [AJAX] 
 */
function rate()
{
	var rate     = parseInt(jQuery('#star-rating').attr('data-star'));
	var rate_old = parseInt(jQuery('#star-rating').attr('data-star-old'));
	var id       = parseInt(jQuery('#rate-block').attr('data-id'));
	var ip       = jQuery('#rate-block').attr('data-ip');

	jQuery.ajax({
		type: "POST",
		url: getSiteFolder() + '/wp-admin/admin-ajax.php?action=rate',
		data: {
			rate : rate,			
			id   : id,
			ip   : ip},
		dataType: 'json',
		success: function(data)
		{
			if(data.msg == 'OK')
			{
				var li_index = parseInt(data.rate);
				jQuery('#star-rating').attr('data-star', li_index);
				setActiveStar('#star-rating li', li_index);

				alert('Thanks for the feedback! You set a rating ' + data.rate + '.');
			}
			else if(data.msg == 'exist')
			{
				alert('You have already rated this post!');				
				setActiveStar('#star-rating li', rate_old);
			}
			else
			{
				alert('Please login first.');	
			}
		}
	});
}

function getSiteFolder()
{	
	if(typeof(SITE_FOLDER) != 'undefined') return SITE_FOLDER;
	return '';
}