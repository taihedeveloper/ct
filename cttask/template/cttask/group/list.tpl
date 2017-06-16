{%extends file="`$tplDir`/default.tpl"%}
{%block name="head" append%}
<!--<script type="text/javascript" src=""></script>-->
<script type="text/javascript">
    (function() {
        $(function() {
            var operate;
            $('.delete').click(function() {
                return operate($(this), 'destroy');
            });
            return operate = function(opObj, method) {
                var param, tpl;
                param = opObj.data('param');
                tpl = '';
                tpl += '<div style="padding:5px 20px">';
                tpl += '<form method="POST" action="/cttask/group/delete">';
                tpl += '<div class="well">';
                tpl += '<p>确认 ' + opObj.text() + '? </p>';
                tpl += '</div>';
                tpl += '<input type="hidden" value="' + param.id + '" name="id"/>';
                tpl += '</form>';
                tpl += '</div>';
                return apollo.formDialog(tpl, {
                    dialog: {
                        title: opObj.text()
                    }
                });
            };
        });
    }).call(this);
</script>
{%/block%}
{%block name="content"%}
<div id="mis_content">
    <div class="row bread-row">
        <div class="col-lg-12">
            <h4 class="page-header">CT任务中心 > 机器组列表</h4>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p>
                        <a class="btn btn-success btn-bigger" href="/cttask/group/create">添加组</a>
                    </p>
                    <form class="form-inline" action="/cttask/group/index" method="GET">
                        <p>
                        <div class="form-group">
                            <label for="search-name">组名</label>
                            <input type="text" name="group_name" class="form-control" value="{%$query.group_name%}">
                        </div>
                        <div class="form-group">
                            <label>机器ip</label>
                            <input type="text" name="ip" class="form-control" value="{%$query.ip%}">
                        </div>
                        <button type="submit" id="submitSave" class="btn btn-success btn-bigger">查询</button>
                        <button type="button" onclick="window.location.href='/cttask/group/index';" class="btn btn-success btn-bigger">全部</button>
                        </p>
                    </form>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>组名</th>
                            <th>机器</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {%foreach $list as $task%}
                        <tr>
                            <td width="25%">{%$task['id']%}</td>
                            <td width="25%">{%$task['group_name']%}</td>
                            <td width="25%">{%$task['host_name']%}</td>
                            <td width="25%">
                                <a href="/cttask/group/edit?id={%$task.id%}" class="btn btn-success">编辑</a>
                                <button type="button" class="btn btn-warning delete" data-param='{"id": {%$task.id%}}'>删除</button>
                            </td>
                        </tr>
                        {%/foreach%}
                        </tbody>
                    </table>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            {%$pagination|pagination%}
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
{%/block%}
