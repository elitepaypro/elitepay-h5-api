<div class="btn-group" id="export-box" data-toggle="buttons">
    <div class="btn-group pull-right" style="margin-right: 10px">
        <a id="export-zip" href="/admin/export-zip?date={{$date}}" target="_blank"
           class="btn btn-sm btn-twitter" title="导出"><i class="fa fa-download"></i><span
                    class="hidden-xs"> 导出zip</span></a>
    </div>
</div>
<script>
    $("#export-zip").click(function () {
        window.open("/admin/export-zip?date={{$date}}");
    })
</script>