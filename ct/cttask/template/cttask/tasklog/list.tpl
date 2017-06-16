{%extends file="`$tplDir`/default.tpl"%}
{%block name="head" append%}
<script type="text/javascript" src="/static/js/page/tasklog/index.min.js"></script>
<script type="text/javascript">

</script>
{%/block%}
{%block name="content"%}
<div id="mis_content">
    <div class="row bread-row">
        <div class="col-lg-12">
            <h4 class="page-header">CT任务中心 > 日志列表</h4>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form class="form-inline" action="/cttask/tasklog/index" method="GET">
                        <p>
                        <div class="form-group">
                            <label for="search-name">任务ID</label>
                            <input type="text" name="task_id" class="form-control" value="{%$query.task_id%}">
                        </div>
                        <div class="form-group">
                            <label for="search-name">任务名称</label>
                            <input type="text" name="task_name" class="form-control" value="{%$query.task_name%}">
                        </div>
                        <div class="form-group">
                            <label>机器ip</label>
                            <input type="text" name="host_name" class="form-control" value="{%$query.host_name%}">
                        </div>
                        </p>
                        <p>
                        <div class="form-group">
                            <label>开始结束时间</label>
                            <input id="begin_time" readonly="readonly" class="form-control datepicker" date-format="yy-mm-dd"  name="begin_time" value="{%$query.begin_time%}"/> -
                            <input id="end_time" readonly="readonly" class="form-control datepicker" date-format="yy-mm-dd"  name="end_time" value="{%$query.end_time%}"/>
                        </div>
                        <button type="submit" id = "submitSave" class="btn btn-success btn-bigger">查询</button>
                        <button type="button" onclick="window.location.href='/cttask/tasklog/index';" class="btn btn-success btn-bigger">全部</button>
                        </p>
                    </form>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>任务ID</th>
                            <th>任务名称</th>
                            <th>机器名称</th>
                            <th>开始时间</th>
                            <th>结束时间</th>
                            <th>运行耗时(秒)</th>
                            <th>命令运行结果</th>
                            <th>CT运行结果</th>
                        </tr>
                        </thead>
                        <tbody>
                        {%foreach $list as $task%}
                        <tr>
                            <td width="8%">{%$task['task_id']%}</td>
                            <td width="12%">{%$task['task_name']%}</td>
                            <td width="12%">{%$task['host_name']%}</td>
                            <td width="12%">{%$task['begin_time']%}</td>
                            <td width="12%">{%$task['end_time']%}</td>
                            <td width="12%">{%$task['run_time']%}</td>
                            <td width="12%">{%$task['return_info']%}</td>
                            <td width="8%">
                                {%if $task['run_status'] == 0%}
                                success
                                {%else%}
                                failed
                                {%/if%}
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
