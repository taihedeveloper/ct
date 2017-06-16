{%extends file="`$tplDir`/default.tpl"%}
{%block name="head" append%}
<script type="text/javascript" src="/static/js/page/host/host.min.js"></script>
<script type="text/javascript">

</script>
{%/block%}
{%block name="content"%}
<div id="mis_content">
    <div class="row bread-row">
        <div class="col-lg-12">
            <h4 class="page-header">CT任务中心 > 机器列表</h4>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p>
                        <a class="btn btn-success btn-bigger add" href="/cttask/host/create">添加机器</a>
                    </p>
                    <form class="form-inline" action="/cttask/host/index" method="GET">
                        <p>
                        <div class="form-group">
                            <label for="search-name">机器名称</label>
                            <input type="text" name="host_name" class="form-control" value="{%$query.host_name%}">
                        </div>
                        <div class="form-group">
                            <label>机器ip</label>
                            <input type="text" name="ip" class="form-control" value="{%$query.ip%}">
                        </div>
                        <button type="submit" id="submitSave" class="btn btn-success btn-bigger">查询</button>
                        <button type="button" onclick="window.location.href='/cttask/host/index';" class="btn btn-success btn-bigger">全部</button>
                        </p>
                    </form>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>机器名称</th>
                            <th>IP地址</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {%foreach $list as $task%}
                        <tr id="tr-{%$task['id']%}">
                            <td width="25%">{%$task['id']%}</td>
                            <td width="25%">{%$task['host_name']%}</td>
                            <td width="25%">{%$task['ip']%}</td>
                            <td width="25%">
                                <a href="/cttask/host/edit?id={%$task.id%}" class="btn btn-success">编辑</a>
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
