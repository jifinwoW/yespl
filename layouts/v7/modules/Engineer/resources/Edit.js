Vtiger_Edit_Js("Engineer_Edit_Js", {}, {

    registerRejectReasonToggle : function() {

        const toggleField = function () {

            const status = jQuery('[name="eng_status"]').val();
            const rejectField = jQuery('[name="rejection_reason"]');

            const rejectFieldRow = rejectField.closest('td').closest('tr');

            if (status === 'Rejected') {
                rejectFieldRow.show();
            } else {
                rejectFieldRow.hide();
                rejectField.val('');
            }
        };

        toggleField();

          jQuery(document).on('change', '[name="eng_status"]', function () {
        toggleField();
    });
    },

    registerEvents : function() {
        this._super();
        this.registerRejectReasonToggle();
    }
});