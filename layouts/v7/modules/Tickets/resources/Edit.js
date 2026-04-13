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
		this.registerInstallationDateAutoPopulateEvent(container);
	},

	registerInstallationDateAutoPopulateEvent: function(container) {
		var thisInstance = this;
		var installationField = thisInstance.getFieldElement(container, ['doi']);
		if (!installationField.length) {
			return;
		}

		installationField.on('change', function() {
			thisInstance.populateWarrantyAndAmcDates(container);
		});

		var warrantyMonthsField = thisInstance.getFieldElement(container, ['warranty_month']);
		if (warrantyMonthsField.length) {
			warrantyMonthsField.on('change', function() {
				thisInstance.populateWarrantyAndAmcDates(container);
			});
		}

		thisInstance.populateWarrantyAndAmcDates(container);
	},

	getFieldElement: function(container, candidateNames) {
		var element = jQuery();
		jQuery.each(candidateNames, function(index, fieldName) {
			var field = container.find('[name="' + fieldName + '"]');
			if (field.length) {
				element = field;
				return false;
			}
		});
		return element;
	},

	parseUserDate: function(dateString) {
		if (!dateString) {
			return null;
		}

		var userDateFormat = app.getDateFormat() || 'dd-mm-yyyy';
		var momentFormat = userDateFormat.toUpperCase();
		var parsedDate = moment(dateString, momentFormat, true);
		if (!parsedDate.isValid()) {
			return null;
		}

		return parsedDate;
	},

	formatUserDate: function(dateMoment) {
		if (!dateMoment || !dateMoment.isValid()) {
			return '';
		}

		var userDateFormat = app.getDateFormat() || 'dd-mm-yyyy';
		return dateMoment.format(userDateFormat.toUpperCase());
	},

	setDateFieldValue: function(container, candidateNames, dateMoment) {
		var fieldElement = this.getFieldElement(container, candidateNames);
		if (!fieldElement.length) {
			return;
		}

		fieldElement.val(this.formatUserDate(dateMoment)).trigger('change');
	},

	getWarrantyMonths: function(container) {
		var warrantyMonthsField = this.getFieldElement(container, ['warranty_month']);
		var months = parseInt(warrantyMonthsField.val(), 10);
		if (isNaN(months) || months <= 0) {
			months = 12;
		}
		return months;
	},

	populateWarrantyAndAmcDates: function(container) {
		var installationField = this.getFieldElement(container, ['doi']);
		if (!installationField.length) {
			return;
		}

		var installationDate = this.parseUserDate(installationField.val());
		if (!installationDate) {
			return;
		}

		var warrantyMonths = this.getWarrantyMonths(container);
		var warrantyStartDate = installationDate.clone();
		var warrantyEndDate = installationDate.clone().add(warrantyMonths, 'months').subtract(1, 'days');
		var amcStartDate = warrantyEndDate.clone().add(1, 'days');
		var amcEndDate = amcStartDate.clone().add(12, 'months').subtract(1, 'days');

		this.setDateFieldValue(container, ['warranty_start_date', 'warranty_date'], warrantyStartDate);
		this.setDateFieldValue(container, ['warranty_end_date', 'warranty_expire_date'], warrantyEndDate);
		this.setDateFieldValue(container, ['amc_start_date', 'amcstartdate'], amcStartDate);
		this.setDateFieldValue(container, ['amc_end_date', 'amcenddate'], amcEndDate);
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