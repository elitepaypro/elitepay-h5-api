
<div class="btn-group" id="export-box" data-toggle="buttons">
    <div class="btn-group pull-right" style="margin-right: 10px">
        <a id="print-pdf" href="/admin/export-zip?date={{$date}}" target="_blank"
           class="btn btn-sm btn-twitter" title="导出"><i class="fa fa-download"></i><span
                    class="hidden-xs"> 打印面单</span></a>
    </div>
</div>
<script>
    $("#print-pdf").click(function () {

        $.ajax({
            method: 'post',
            url: '/admin/pdf',
            data: {
                "_token": "{{csrf_token()}}",
                ids: $.admin.grid.selected().join(),
                date: "{{$date}}"
            },
            success: function (data) {
                url = 'http://114.132.64.96' + data.merge_file_path.replace('https', 'http')
                // console.log(url, data.merge_file_path.replace('https', 'http'))
                window.open('/html/pdf-print.html?pdfUrl=' + url)
            }
        });
    })
</script>
