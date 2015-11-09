/**
 * Created by David Munroe on 8/11/2015.
 */
Ext.onReady(function()
{
    var pageUrl = intelli.config.admin_url + '/blacklist/ipadresses/';

    if (Ext.get('js-grid-placeholder'))
    {
        var urlParam = intelli.urlVal('status');

        intelli.blacklist =
        {
            columns: [
                {name: 'ip', title: _t('ip'), width: 2, editor: 'text'},
                {name: 'note', title: _t('note'), width: 2, editor: 'text'},
                {name: 'date', title: _t('date'), width: 200, editor: 'date'},
                'delete',
                'selection'
            ],
            sorters: [{property: 'date', direction: 'DESC'}],
            storeParams: urlParam ? {status: urlParam} : null,
            url: pageUrl
        };

        intelli.blacklist = new IntelliGrid(intelli.blacklist, false);
        intelli.blacklist.toolbar = Ext.create('Ext.Toolbar', {items:[
            {
                emptyText: _t('text'),
                name: 'ip',
                listeners: intelli.gridHelper.listener.specialKey,
                width: 275,
                xtype: 'textfield'
            },{
                displayField: 'ip',
                editable: false,
                emptyText: _t('status'),
                id: 'fltStatus',
                name: 'status',
                store: intelli.blacklist.stores.statuses,
                typeAhead: true,
                valueField: 'value',
                xtype: 'combo'
            },{
                handler: function(){intelli.gridHelper.search(intelli.blacklist);},
                id: 'fltBtn',
                text: '<i class="i-search"></i> ' + _t('search')
            },{
                handler: function(){intelli.gridHelper.search(intelli.blacklist, true);},
                text: '<i class="i-close"></i> ' + _t('reset')
            }]});

        if (urlParam)
        {
            Ext.getCmp('fltStatus').setValue(urlParam);
        }

        intelli.blacklist.init();
    }
});