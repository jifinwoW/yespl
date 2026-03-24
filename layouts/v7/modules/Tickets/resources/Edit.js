Vtiger_Edit_Js("Tickets_Edit_Js", {}, {

	amcFieldMappings: {
		boid: 'boid',
		sapcode: 'sapcode',
		custcode: 'custcode',
		depocode: 'depocode',
		zone: 'zone',
		serviceengineer: 'servicecoordintor',
		typeofmc: 'typeofmc',
		model: 'model',
		serialno: 'serialno',
		doi: 'doi',
		vendor_id: 'vendor_id',
		service_location: 'service_location',
		connectivity: 'connectivity',
		device_details: 'device_details',
		gyro_type: 'gyro_type',
		gyro_model: 'gyro_model',
		gyro_serialno: 'gyro_serialno',
		ups_details: 'ups_details',
		ups_serialno: 'ups_serialno',
		warranty_month: 'warranty_month',
		warranty_start_date: 'warranty_start_date',
		warranty_end_date: 'warranty_end_date',
		engineer_id: 'engineer_id',
		contact_mobileno: 'contact_mobileno',
		address: 'address',
		city: 'city',
		state: 'state',
		pincode: 'pincode',
		parent_id: 'account_id',
		location_type: 'location_type'
	},

	registerBasicEvents: function(container) {
		this._super(container);
		this.registerAmcSelectionEvent(container);
		this.registerAmcDeselectionEvent(container);
	},

	registerAmcSelectionEvent: function(container) {
		var thisInstance = this;
		container.find('input[name="amc_id"]').on(Vtiger_Edit_Js.referenceSelectionEvent, function(e, data) {
			thisInstance.populateFieldsFromAmc(data, container);
		});
	},

	registerAmcDeselectionEvent: function(container) {
		var thisInstance = this;
		container.find('input[name="amc_id"]').on(Vtiger_Edit_Js.referenceDeSelectionEvent, function() {
			thisInstance.clearMappedFields(container);
		});
	},

	populateFieldsFromAmc: function(data, container) {
		var thisInstance = this;
		app.request.get({
			url: 'index.php',
			data: {
				module: 'Tickets',
				action: 'GetAmcData',
				record: data.record,
				source_module: data.source_module
			}
		}).then(function(error, response) {
			if (error !== null || !response || response.success !== true) {
				return;
			}

			thisInstance.applyAmcDataToForm(response.data, container);
		}, function() {});
	},

	applyAmcDataToForm: function(fieldData, container) {
		var thisInstance = this;
		jQuery.each(fieldData, function(targetFieldName, fieldMeta) {
			thisInstance.setFieldValue(container, targetFieldName, fieldMeta);
		});
	},

	setFieldValue: function(container, fieldName, fieldMeta) {
		var fieldElement = container.find('[name="' + fieldName + '"]');
		if (!fieldElement.length) {
			return;
		}

		if (fieldMeta.isReference) {
			this.setReferenceValue(fieldElement, fieldMeta);
			return;
		}

		if (fieldElement.is('select')) {
			fieldElement.val(fieldMeta.value).trigger('change');
			return;
		}

		if (fieldElement.hasClass('dateField')) {
			fieldElement.val(this.getFormattedDateValue(fieldElement, fieldMeta.value)).trigger('change');
			return;
		}

		fieldElement.val(fieldMeta.value).trigger('change');
	},

	getFormattedDateValue: function(fieldElement, fieldValue) {
		if (!fieldValue) {
			return '';
		}

		var dateInstance = moment(fieldValue, 'YYYY-MM-DD', true);
		if (!dateInstance.isValid()) {
			return fieldValue;
		}

		return app.getDateInVtigerFormat(fieldElement.data('dateFormat') || app.getDateFormat(), dateInstance.toDate());
	},

	setReferenceValue: function(fieldElement, fieldMeta) {
		var fieldContainer = fieldElement.closest('td');
		if (!fieldContainer.length) {
			fieldContainer = fieldElement.closest('.fieldValue');
		}

		this.setReferenceFieldValue(fieldContainer, {
			id: fieldMeta.value,
			name: fieldMeta.displayValue
		});
	},

	clearMappedFields: function(container) {
		var thisInstance = this;
		jQuery.each(this.amcFieldMappings, function(sourceFieldName, targetFieldName) {
			var fieldElement = container.find('[name="' + targetFieldName + '"]');
			if (!fieldElement.length) {
				return;
			}

			if (fieldElement.hasClass('sourceField')) {
				fieldElement.closest('.referencefield-wrapper').find('.clearReferenceSelection').trigger('click');
				return;
			}

			if (fieldElement.is('select')) {
				fieldElement.val('').trigger('change');
				return;
			}

			fieldElement.val('').trigger('change');
		});
	}
});