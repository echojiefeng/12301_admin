define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'aim/product/index',
                    add_url: 'aim/product/add',
                    edit_url: 'aim/product/edit',
                    del_url: 'aim/product/del',
                    multi_url: 'aim/product/multi',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'id',
                pagination: false,
                commonSearch: false,
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        {
                            field: 'image',
                            title: __('图片'),
                            formatter: Table.api.formatter.image,
                            operate: false
                        },
                        {
                            field: 'title',
                            title: __('标题')
                        },
                        {
                            field: 'type',
                            title: __('分类')
                        },
                        {
                            field: 'ticket',
                            title: __('门票')
                        },
                        {
                            field: 'address',
                            title: __('地址')
                        },
                        {
                            field: 'status',
                            title: __('Status'),
                            operate: false,
                            formatter: Table.api.formatter.status
                        },
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function() {
            Controller.api.bindevent();
        },
        edit: function() {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function() {
                $(document).on("change", "#c-type", function() {
                    $("#c-pid option[data-type='all']").prop("selected", true);
                    $("#c-pid option").removeClass("hide");
                    $("#c-pid option[data-type!='" + $(this).val() + "'][data-type!='all']").addClass("hide");
                    $("#c-pid").selectpicker("refresh");
                });
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});