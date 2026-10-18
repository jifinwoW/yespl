/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

Vtiger_Edit_Js("HelpDesk_Edit_Js", {}, {

	registerBasicEvents: function(container) {
		this._super(container);
		this.registerInstallationDateAutoPopulateEvent(container);
		this.registerEngineerSelectionEvent(container);
		this.registerCustomerSelectionEvent(container);
		this.registerGyroEvent(container);
		this.registerUpsEvent(container);
	},

	registerCustomerSelectionEvent: function(container) {
		var thisInstance = this;
		// Active form field is contact_id (customer_id is presence=2 / hidden)
		var customerRefFields = container.find('input[name="contact_id"], input[name="customer_id"]');
		customerRefFields.on(Vtiger_Edit_Js.referenceSelectionEvent, function(e, data) {
			thisInstance.populateCustomerPhone(data, container);
		});
		customerRefFields.on(Vtiger_Edit_Js.referenceDeSelectionEvent, function() {
			thisInstance.clearCustomerPhone(container);
		});
	},

	populateCustomerPhone: function(data, container) {
		var thisInstance = this;
		if (!data || !data.record) {
			return;
		}

		app.request.get({
			url: 'index.php',
			data: {
				module: 'HelpDesk',
				action: 'GetContactPhone',
				record: data.record,
				source_module: data.source_module || 'Contacts'
			}
		}).then(function(error, response) {
			if (error !== null || !response || response.success !== true) {
				return;
			}
			var phone = (response.result && response.result.phone) ? response.result.phone : '';
			// Active field is contact_mobileno; customer_number is presence=2 / hidden
			var phoneFields = container.find('[name="contact_mobileno"], [name="customer_number"]');
			if (phoneFields.length) {
				phoneFields.val(phone).trigger('change');
			}
		}, function() {});
	},

	clearCustomerPhone: function(container) {
		var phoneFields = container.find('[name="contact_mobileno"], [name="customer_number"]');
		if (!phoneFields.length) {
			return;
		}
		phoneFields.val('').trigger('change');
	},

	registerEngineerSelectionEvent: function(container) {
		var thisInstance = this;
		// Active form field is engineer_id (engineer_name is presence=2 / hidden)
		var engineerRefFields = container.find('input[name="engineer_id"], input[name="engineer_name"]');
		engineerRefFields.on(Vtiger_Edit_Js.referenceSelectionEvent, function(e, data) {
			thisInstance.populateEngineerMobileNo(data, container);
		});
		engineerRefFields.on(Vtiger_Edit_Js.referenceDeSelectionEvent, function() {
			thisInstance.clearEngineerMobileNo(container);
		});
	},

	populateEngineerMobileNo: function(data, container) {
		var thisInstance = this;
		if (!data || !data.record) {
			return;
		}

		app.request.get({
			url: 'index.php',
			data: {
				module: 'HelpDesk',
				action: 'GetEngineerMobileNo',
				record: data.record,
				source_module: data.source_module || 'Engineer'
			}
		}).then(function(error, response) {
			if (error !== null || !response || response.success !== true) {
				return;
			}
			var result = response.result || {};
			var mobileNo = result.mobile_no ? result.mobile_no : '';
			var state = result.state ? result.state : '';
			// Active field is eng_mobileno; engineer_number is presence=2 / hidden
			var mobileFields = container.find('[name="eng_mobileno"], [name="engineer_number"]');
			if (mobileFields.length) {
				mobileFields.val(mobileNo).trigger('change');
			}
			var locationFields = container.find('[name="engineer_service_location"]');
			if (locationFields.length) {
				locationFields.val(state).trigger('change');
			}
		}, function() {});
	},

	clearEngineerMobileNo: function(container) {
		var mobileFields = container.find('[name="eng_mobileno"], [name="engineer_number"]');
		if (mobileFields.length) {
			mobileFields.val('').trigger('change');
		}
		var locationFields = container.find('[name="engineer_service_location"]');
		if (locationFields.length) {
			locationFields.val('').trigger('change');
		}
	},

	registerInstallationDateAutoPopulateEvent: function(container) {
		var thisInstance = this;
		var installationField = thisInstance.getFieldElement(container, ['doi', 'installation_date', 'intallation_date']);
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
		var installationField = this.getFieldElement(container, ['doi', 'installation_date', 'intallation_date']);
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

	registerGyroEvent: function(container) {
		var gyroField = container.find('[name="gyro"]');
		if (!gyroField.length) return;
		var gyroBlock = container.find('[data-block="Gyro Details"]');
		var gyroRow = container.find('[name="gyro_serialno"]').closest('tr');

		// Initially hide the row
		gyroRow.hide();
		gyroBlock.hide();

		gyroField.on('change', function() {
			var value = jQuery(this).val();
			if (value === 'Yes') {
				gyroRow.show();
				gyroBlock.show();
			} else {
				gyroRow.hide();
				gyroBlock.hide();
			}
		});

		gyroField.trigger('change');
	},

	registerUpsEvent: function(container) {
		var upsField = container.find('[name="ups"]');
		if (!upsField.length) return;

		var upsRowTd = container.find('[name="ups_serialno"]').closest('tr');
		var UpsBlock = container.find('[data-block="UPS Details"]');
		
		
		// Initially hide the row and the td above
		upsRowTd.hide();
		UpsBlock.hide();
		
		upsField.on('change', function() {
			var value = jQuery(this).val();
			if (value === 'Yes') {
				upsRowTd.show();
				UpsBlock.show();	
			} else {
				upsRowTd.hide();
				UpsBlock.hide();
			}
		});

		upsField.trigger('change');
	}
});
