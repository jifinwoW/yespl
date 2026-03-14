<?php
/* Smarty version 4.5.4, created on 2025-03-10 11:50:18
  from '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Mobile/simple/Vtiger/List.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_67ced1faa23317_33126459',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '29bfe453118375175cd778968d8e2aae06d6c52d' => 
    array (
      0 => '/home/yespl/htdocs/www.yespl.info/layouts/v7/modules/Mobile/simple/Vtiger/List.tpl',
      1 => 1727629512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../Header.tpl' => 1,
    'file:../Vtiger/Toolbar.tpl' => 1,
    'file:../Vtiger/SideMenu.tpl' => 1,
    'file:../Footer.tpl' => 1,
  ),
),false)) {
function content_67ced1faa23317_33126459 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../Header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('scripts'=>$_smarty_tpl->tpl_vars['_scripts']->value), 0, false);
?>

<section layout="row" flex class="content-section" ng-controller="<?php echo $_smarty_tpl->tpl_vars['_controller']->value;?>
">
    <?php $_smarty_tpl->_subTemplateRender("file:../Vtiger/Toolbar.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    <?php $_smarty_tpl->_subTemplateRender("file:../Vtiger/SideMenu.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
    
        <md-button ng-click="listViewCreateEvent()" class="md-fab md-primary float-button md-fab-bottom-right" aria-label="addnew">
            <i class="mdi mdi-plus"></i>
        </md-button>
        <div flex class="list-content">
            <div class="list-filters" layout="row" flex>
                <div flex="100" class="change-filter">
                    <md-button class="filter-btn" aria-label="notifications">
                        <i class="mdi mdi-filter-outline"></i>
                    </md-button>
                    <md-input-container class="current-filter">
                        <md-select ng-model="selectedFilter" aria-label="filter" ng-change="changeFilter()">
                            <md-optgroup label="Mine" aria-label="Mine">
                                <md-option ng-repeat="filter in filters.Mine track by filter.id" ng-value="filter.id" aria-label="{{filter.name}}">{{filter.name}}</md-option>
                            </md-optgroup>
                            <md-optgroup label="Shared" aria-label="Shared">
                                <md-option ng-repeat="filter in filters.Shared track by filter.id" ng-value="filter.id" aria-label="{{filter.name}}">{{filter.name}}</md-option>
                            </md-optgroup>
                        </md-select>
                    </md-input-container>
                </div>
                <!--div flex="50" class="sort-filter" ng-if="records.length">
                    <md-button class="filter-btn" aria-label="notifications">
                        <i class="mdi mdi-sort"></i>
                    </md-button>
                    <md-input-container class="current-sort-field">
                        <md-select ng-model="orderBy" aria-label="sortfield" placeholder="Sort" ng-change="changeSort(orderBy)">
                            <md-option ng-repeat="nameField in nameFields track by $index" ng-value="nameField.name" aria-label="nameField.name">{{nameField.label}}</md-option>
                            <md-option ng-repeat="header in headers track by $index" ng-value="header.name" aria-label="nameField.name">{{header.label}}</md-option>
                        </md-select>
                    </md-input-container>
                </div>-->
            </div>
            <div layout="column" layout-fill layout-align="top center" ng-if="records.length">
                <md-list class="records-list">
                    <md-list-item class="md-3-line" data-record-id="{{record.id}}" aria-label="row+{{record.id}}" ng-model="showActions" md-swipe-right="showActions=false;$event.stopPropagation();" md-swipe-left="showActions=true;$event.stopPropagation();" ng-click="gotoDetailView(record.id)" ng-repeat="record in records">
                        <div class="md-list-item-text">
                            <h3>
                                <span ng-repeat="label in headers">
                                    <span  ng-repeat="name in nameFields" ng-if="label.name === name">{{record[label.name] + " "}}</span>
                                </span>
                            </h3>
                            <p class="header-fields" ng-repeat="header in headers" ng-if="headerIndex(nameFields,header.name)== -1">
                                {{record[header.name]}}
                            </p>  
                        </div>
                        <div class="actions-slider animate-show" ng-show="showActions" ng-swipe-right="hideRecordActions();" ng-animate="{enter: 'animate-enter', leave: 'animate-leave'}">
                            <div class="button-wrap" flex layout="row">
                                <div flex layout='column'>
                                    <md-button class="list-action-edit md-icon-button"  aria-label="list-action-edit" ng-click="listViewEditEvent($event, record.id);$event.stopPropagation();">
                                        <span><i class="mdi mdi-pencil"></i></span>
                                    </md-button>
                                </div>
                                <div flex layout='column'>
                                    <md-button class="list-action-delete md-icon-button"  aria-label="list-action-delete" ng-click="showConfirmDelete($event, record.id);$event.stopPropagation();">
                                        <span><i class="mdi mdi-delete"></i></span>
                                    </md-button>
                                </div>
                            </div>
                        </div>
                        <md-divider ></md-divider>
                    </md-list-item>
                    <md-list-item class="md-1-line load-more-link" >
                        <div ng-click="loadMoreRecords()" ng-show="moreRecordsExists">
                            Load more
                        </div>
                    </md-list-item>
                </md-list>

            </div>
            <div class="no-records-message" ng-if="!records.length">
                <div class="no-records">No Records Found</div>
            </div>
            <div flex></div>
        </div>
    </section>

<?php $_smarty_tpl->_subTemplateRender("file:../Footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
