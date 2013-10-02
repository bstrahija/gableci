App = Ember.Application.create();

// Define all my routes
App.Router.map(function() {
	this.route("reservations", { path: "/reservations" });
	this.route("dishes",       { path: "/dishes" });
});

// Bind model to reservation route
App.IndexRoute = Ember.Route.extend({
	model: function() {
		return Ember.$.getJSON('http://gableci.dev/api/reservations/home');
	}
});
