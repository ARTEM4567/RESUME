jQuery(function($) {
	$('#wbcr-factory-subscribe-widget__subscribe-form').submit(function(e) {
		e.preventDefault();
		var agree = $(this).find('[name=agree_terms]:checked');
		if( agree.length === 0 ) {
			return;
		}

		$.ajax({
			method: "POST",
			url: "https://clearfy.pro/wp-json/mailerlite/v1/subscribe/",
			data: {
				email: $('#wbcr-factory-subscribe-widget__email').val(),
				group_id: $('#wbcr-factory-subscribe-widget__group-id').val(),
			},
			success: function(data) {
				if( !data.message ) {
					if( data.subscribed ) {
						$(".wbcr-factory-subscribe-widget__text--success").show();
					} else {
						$(".wbcr-factory-subscribe-widget__text--success2").show();
					}
				} else {
					console.log(data.message);
					var noticeId = $.wbcr_factory_clearfy_230.app.showNotice('Error: [' + data.message + ']', 'danger');
					setTimeout(function() {
						$.wbcr_factory_clearfy_230.app.hideNotice(noticeId);
					}, 5000);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {

				console.log(xhr.status);
				console.log(xhr.responseText);
				console.log(thrownError);

				var noticeId = $.wbcr_factory_clearfy_230.app.showNotice('Error: [' + thrownError + '] Status: [' + xhr.status + '] Error massage: [' + xhr.responseText + ']', 'danger');
				setTimeout(function() {
					$.wbcr_factory_clearfy_230.app.hideNotice(noticeId);
				}, 5000);
			}
		});
	});
});