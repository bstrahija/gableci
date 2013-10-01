$(function() {

	// Detect iOS
	var iOS = ( navigator.userAgent.match(/(iPad|iPhone|iPod)/g) ? true : false );

	// Detect fullscreen app
	if (window.navigator.standalone) {
		$("body").addClass("fullscreen");
	}

	// Fix some focus issues with fixed elements on iOS
	if (iOS) {
		$(document).on('focus', 'input, textarea, select', function() {
			$("header, footer").hide();
		});
		$(document).on('blur', 'input, textarea, select', function() {
			$("header, footer").fadeIn(250);
		});
	}

	// Init lightbox images
	$("a.lightbox").fancybox({
		padding: 0,
		margin: 5,
		closeClick: true,
		closeBtn: false,
		openSpeed: 200,
		closeSpeed: 100
	});

	// Auto refresh reservations
	if (APP_ROUTE == 'reservations') {
		var reservationRefreshInterval = setInterval(function() {
			$.ajax({
				url: APP_URL + 'reservations/overview',
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

	// Stats
	$("#spent-range").change(function() {
		var range = $(this).val();

		if (range) document.location = APP_URL + 'stats?range=' + range;
		else       document.location = APP_URL + 'stats';
	});

});
