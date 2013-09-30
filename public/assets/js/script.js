$(function() {

	if (window.navigator.standalone) {
		$("body").addClass("fullscreen");
	}

	$("a.lightbox").fancybox({
		padding: 0,
		margin: 5,
		closeClick: true,
		closeBtn: false,
		openSpeed: 200,
		closeSpeed: 100
	});


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
