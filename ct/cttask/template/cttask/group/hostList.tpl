<script>
    $(function(){
        $('.form-inline').ajaxForm({
            success: function(result) {
                $("#hostList").html(result);
            }
        });

        //全选
        $("#checkAll").click(function() {
            $('input[name="host_id"]').prop("checked",this.checked);
        });
        var $subBox = $("input[name='host_id']");
        $subBox.click(function(){
            if($subBox.length == $("input[name='host_id']:checked").length ){
                $("#checkAll").prop("checked",true);
            }else {
                $("#checkAll").removeAttr("checked",false);
            }
        });
    });
    function hostReload(page) {
        $("#page").val(page);
        $(".form-inline").submit();
    }

</script>
<div id="mis_content">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <form class="form-inline" action="/cttask/host/index?type=select_list" method="GET">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <p>
                        <div class="form-group">
                            <label for="search-name">机器名称</label>
                            <input type="text" name="host_name" class="form-control" value="{%$query.host_name%}">
                        </div>
                        <div class="form-group">
                            <label>机器ip</label>
                            <input type="text" name="ip" class="form-control" value="{%$query.ip%}">
                        </div>
                        <input type="hidden" id="page" name="page" value="1">
                        <button type="submit" id="submitSave" class="btn btn-success btn-bigger">查询</button>
                        </p>
                    </div>
                    <div class="panel-body">
                        <div class="dataTable_wrapper" style="height:250px;OVERFLOW-Y: auto; OVERFLOW-X:hidden;">
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll" value="">全选</th>
                                    <th>ID</th>
                                    <th>机器名称</th>
                                    <th>IP</th>
                                </tr>
                                </thead>
                                <tbody>
                                {%foreach $list as $task%}
                                <tr>
                                    <td>
                                        <input type="checkbox" name="host_id" value='{%$task|json_str%}'>
                                    </td>
                                    <td>{%$task.id%}</td>
                                    <td>{%$task.host_name%}</td>
                                    <td>{%$task.ip%}</td>
                                </tr>
                                {%/foreach%}
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
            </form>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>