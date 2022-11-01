
<div class="btn-group" id="export-box" data-toggle="buttons">
    <div class="btn-group pull-right" style="margin-right: 10px">
        <a id="update-is-dispose" target="_blank"
           class="btn btn-sm btn-twitter" title="导出"><i class="fa fa-download"></i><span
                    class="hidden-xs"> 批量处理单据</span></a>
    </div>
</div>
<script>
    $("#update-is-dispose").click(function () {

        $.ajax({
            method: 'post',
            url: '/admin/orders/update/isDispose',
            data: {
                "_token": "{{csrf_token()}}",
                "ids": $.admin.grid.selected().join(),
            },
            success: function (data) {
                alert('修改成功')
            }
        });
    })
</script>