
<div class="btn-group" id="export-box" data-toggle="buttons">
    <div class="btn-group pull-right" style="margin-right: 10px">
        <a id="print-qr" target="_blank"
           class="btn btn-sm btn-twitter" title="导出"><i class="fa fa-download"></i><span
                    class="hidden-xs"> 打印二维码</span></a>
    </div>
</div>
<script>
    $("#print-qr").click(function () {

        $.ajax({
            method: 'post',
            url: '/admin/qr-code',
            data: {
                "_token": "{{csrf_token()}}",
                ids: $.admin.grid.selected().join(),
                date: "{{$date}}"
            },
            success: function (data) {
                window.open('/html/qr-print.html?pdfUrl=' + data.merge_file_path)
            }
        });
    })
</script>