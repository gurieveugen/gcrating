function resetRatings()
{
	var result = confirm("Are you sure you want to reset all counts?");
	if (result == true) 
	{
		jQuery.ajax({
			type: "POST",
			url: gcrating_object.ajaxurl + '?action=resetratings',
			dataType: 'json',
			success: function(data)
			{
				if(data.empty == true)
				{
					location.reload(true);
				}
			}
		});    
	}
}
