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

	if (APP_ROUTE == 'reservations') {
		var reservationRefreshInterval = setInterval(function() {
			$.ajax({
				url: APP_URL,
				success: function(data) {
					$("#reservation-reload").html(data);
					//console.log("Reservations reloaded...");
				},
				error: function(e) {
					if (e.status == 401) {
						alert("You are not logged in!");
						document.location = APP_URL + "login";
					} else {
						console.error("Error when refreshing reservations!");
					}
				}
			});
		}, 20000);
	}

});
