$(function() {

	$("a.lightbox").fancybox();


	var reservationRefreshInterval = setInterval(function() {
		$.ajax({
			url: APP_URL,
			success: function(data) {
				$("#reservation-reload").html(data);
				//console.log("Reservations reloaded...");
			},
			error: function(e) {
				//console.error(e);
			}
		});
	}, 20000);

});
