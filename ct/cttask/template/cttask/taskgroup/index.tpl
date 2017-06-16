{%extends file="`$tplDir`/default.tpl"%}
{%block name="head" append%}
<script type="text/javascript" src="/static/js/page/taskgroup/index.min.js"></script>
<script type="text/javascript">

</script>
{%/block%}
{%block name="content"%}
<div id="mis_content">
    <div class="row bread-row">
        <div class="col-lg-12">
            <h4 class="page-header">CT任务中心 > 分组列表</h4>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p>
                        <!--<a class="btn btn-success btn-bigger" href="/cttask/taskgroup/create">添加新分组</a>-->
                        <a class="btn btn-success btn-bigger add"
                           data-api="/cttask/taskgroup/store"
                           href="javascript:void(0);">添加分组</a>
                    </p>
                    <form class="form-inline" action="/cttask/taskgroup/index" method="GET">
                        <p>
                        <div class="form-group">
                            <label for="search-name">名称</label>
                            <input type="text" name="group_name" class="form-control" value="{%$query.name%}">
                        </div>
                        <button type="submit" id="submitSave" class="btn btn-success btn-bigger">查询</button>
                        <button type="button" onclick="window.location.href='/cttask/taskgroup/index';" class="btn btn-success btn-bigger">全部</button>
                        </p>
                    </form>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>名称</th>
                            <th>创建人</th>
                            <th>更新时间</th>
                            <th>描述</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {%foreach $list as $item%}
                        <tr>
                            <td width="8%">{%$item['id']%}</td>
                            <td width="12%">{%$item['group_name']%}</td>
                            <td width="10%">{%$item['create_user']%}</td>
                            <td width="22%">{%$item['update_time']%}</td>
                            <td width="7%">{%$item['group_desc']%}</td>
                            <td width="16%">
                                <a class="btn btn-success edit"
                                   data-api="/cttask/taskgroup/update"
                                   data-param='{"id":{%$item.id%}}' href="javascript:void(0);">编辑</a>
                                <!--<button type="button" class="btn btn-warning delete" data-param='{"id": {%$item.id%}}'>删除</button>-->
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