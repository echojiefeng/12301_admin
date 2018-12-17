define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'aim/travel/index',
                    add_url: 'aim/travel/add',
                    edit_url: 'aim/travel/edit',
                    del_url: 'aim/travel/del',
                    multi_url: 'aim/travel/multi',
                    dragsort_url: '',
                    table: 'strategy',
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
                        { field: 'product_id', title: __('商品') },
                        { field: 'user_name', title: __('用户名')},
                        { field: 'user_image', title: __('用户头像'), operate: false, formatter: Table.api.formatter.image },
                        { field: 'type', title: __('文章分类') },
                        { field: 'title', title: __('主标题') },
                        { field: 'second_title', title: __('二级标题') },
                        { field: 'image', title: __('用户头像'), operate: false, formatter: Table.api.formatter.image },
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