Vtiger_Edit_Js("SparePartsAssignment_Edit_Js", {}, {

    registerAssignRoleToggle: function () {

        const engineerField = jQuery('[name="spa_engineer_id"]')
            .closest('td')
            .prev('td')
            .addBack();

        const scField = jQuery('[name="spa_sc_id"]')
            .closest('td')
            .prev('td')
            .addBack();

        // Hide initially before logic
        engineerField.hide();
        scField.hide();

        const toggleFields = function () {

            const role = jQuery('[name="spa_assign_role"]').val();

            // Hide both first
            engineerField.hide();
            scField.hide();

            if (role === 'Engineer') {
                engineerField.show();
            } 
            else if (role === 'Service Cordinator') {
                scField.show();
            }
        };

        // Initial load
        toggleFields();

        // On change
        jQuery('[name="spa_assign_role"]').on('change', function () {
            toggleFields();
        });
    },

    registerEvents: function () {
        this._super();
        this.registerAssignRoleToggle();
    }
});