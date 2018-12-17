define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'find/special/index',
                    add_url: 'find/special/add',
                    edit_url: 'find/special/edit',
                    del_url: 'find/special/del',
                    multi_url: 'find/special/multi',
                    table: 'special',
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
                        { field: 'title', title: __('标题'), align: 'left' },
                        { field: 'image', title: __('图片'), operate: false, formatter: Table.api.formatter.image },
                        { field: 'status', title: __('Status'), operate: false, formatter: Table.api.formatter.status },
                        { field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }
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