Vtiger_Edit_Js("ServiceCordinator_Edit_Js", {}, {

	registerBasicEvents: function (container) {
		this._super(container);
		//this.hide();
	
	},
	hide: function () {
		$("#ServiceCordinator_editView_fieldName_rejection_reason").parent().parent().parent().parent().addClass("hide");

		$('select').on('change', function () {
			var name = this.value;
			if (name == "Rejected") {
				$("#ServiceCordinator_editView_fieldName_rejection_reason").parent().parent().parent().parent().removeClass("hide");

			}
			if (name == "Accepted") {
				$("#ServiceCordinator_editView_fieldName_rejection_reason").parent().parent().parent().parent().addClass("hide");

			}
			if (name == "Pending") {
				$("#ServiceCordinator_editView_fieldName_rejection_reason").parent().parent().parent().parent().addClass("hide");

			}
		})
	},
});

