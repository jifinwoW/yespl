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
		this.registerGyroEvent(container);
	},

	registerEngineerSelectionEvent: function(container) {
		var thisInstance = this;
		container.find('input[name="engineer_id"]').on(Vtiger_Edit_Js.referenceSelectionEvent, function(e, data) {
			thisInstance.populateEngineerMobileNo(data, container);
		});
		container.find('input[name="engineer_id"]').on(Vtiger_Edit_Js.referenceDeSelectionEvent, function() {
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
				source_module: data.source_module
			}
		}).then(function(error, response) {
			if (error !== null || !response || response.success !== true) {
				return;
			}
			var mobileField = container.find('[name="eng_mobileno"]');
			if (mobileField.length) {
				mobileField.val(response.result.mobile_no).trigger('change');
			}
		}, function() {});
	},

	clearEngineerMobileNo: function(container) {
		var mobileField = container.find('[name="eng_mobileno"]');
		if (!mobileField.length) {
			return;
		}
		mobileField.val('').trigger('change');
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

		var gyroRow = container.find('[name="gyro_serialno"]').closest('tr');

		// Initially hide the row
		gyroRow.hide();

		gyroField.on('change', function() {
			var value = jQuery(this).val();
			if (value === 'Yes') {
				gyroRow.show();
			} else {
				gyroRow.hide();
			}
		});

		gyroField.trigger('change');
	}
});
