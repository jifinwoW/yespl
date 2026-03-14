/* * *******************************************************************************
 * The content of this file is subject to the ITS4You license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is IT-Solutions4You s.r.o.
 * Portions created by IT-Solutions4You s.r.o. are Copyright(C) IT-Solutions4You s.r.o.
 * All Rights Reserved.
 * ****************************************************************************** */
/** @var ITS4YouItemsWidget_ItemsWidget_Js */
jQuery.Class('ITS4YouItemsWidget_ItemsWidget_Js', {
    getInstance: function () {
        if (!this.instance) {
            this.instance = new ITS4YouItemsWidget_ItemsWidget_Js();
        }

        return this.instance;
    },
}, {
    getDetailViewContainer: function () {
        return jQuery('.detailViewContainer');
    },

    getActiveTabName: function () {
        const self = this;
        let activeTabItem = jQuery('.related-tabs', self.getDetailViewContainer()).find('li.tab-item').filter('.active');

        if (!activeTabItem.length) {
            activeTabItem = jQuery('.related-tabs', self.getDetailViewContainer()).find('li.tab-item:first');
        }

        return activeTabItem.data('link-key');
    },

    registerEvents: function () {
        const self = this;

        app.event.on('post.relatedListLoad.click', function () {
            self.callWidget();
        });

        self.callWidget();
    },

    callWidget: function () {
        const self = this;
        const recordId = app.getRecordId();

        //post.relatedListLoad.click
        if ('Detail' !== app.view() || 'LBL_RECORD_SUMMARY' !== self.getActiveTabName() || !parseInt(recordId)) {
            return false;
        }

        const params = {
            module: 'ITS4YouItemsWidget',
            view: 'ItemsWidgetAjax',
            mode: 'show',
            record: recordId,
            source_module: app.getModuleName(),
        };

        app.helper.showProgress();
        app.request.post({data: params}).then(function (error, data) {
            app.helper.hideProgress();

            if (!error && data) {
                $('.summaryView').after(data);

                self.registerTaxClickEvent();
                self.registerCustomActions();
            }
        });
    },

    /**
     * Event handler which is invoked on click event happened on inventoryLineItemDetails
     */
    registerTaxClickEvent: function () {
        $('.inventoryLineItemDetails').popover({html: true});
    },
    registerCustomActions: function () {
        if ('function' === typeof ITS4You_GpsSalesOrder_Js) {
            ITS4You_GpsSalesOrder_Js.getInstance().registerGpsSo(app.getModuleName());
        }
    },
});

$(function() {
    ITS4YouItemsWidget_ItemsWidget_Js.getInstance().registerEvents();
})