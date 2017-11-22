var AJAX_BASE_PATH = 'http://localhost/api/admin/'
var indexModule = new Vue({
	el:'#index-module',
	data: {
		currentCard: {},
		listCards: [], 
		listFields: []
	},
	mounted: function () {
		this.loadCards();
		this.loadFields();
	},
	methods: {
		loadCards: function () {
			$.get(AJAX_BASE_PATH + 'card', 
				function (response) {
					this.listCards = response;
					console.log(response);
				}.bind(this));
		},
		loadFields: function () {
			$.get(AJAX_BASE_PATH + 'field', 
				function (response) {
					this.listFields = response;
					console.log(response);
				}.bind(this));
		},
		loadCard: function (card) {
			$.get(AJAX_BASE_PATH + 'card/' + card.id, { scope: "fields" }, 
				function (response) {
					this.currentCard = response;
					console.log(response);
				}.bind(this));
		}
	}
});