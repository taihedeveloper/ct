{%extends file="`$tplDir`/default.tpl"%}
{%block name="head" append%}
<script type="text/javascript" src="/static/js/page/task/index.min.js"></script>
<script type="text/javascript">

</script>
{%/block%}
{%block name="content"%}
<div id="mis_content">
    <div class="row bread-row">
        <div class="col-lg-12">
            <h4 class="page-header">CT任务中心 > 任务列表</h4>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p>
                        <a class="btn btn-success btn-bigger" href="/cttask/task/create">添加新任务</a>
                    </p>
                    <form class="form-inline" action="/cttask/cttask" method="GET">
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
                            <input type="text" name="ip" class="form-control" value="{%$query.ip%}">
                        </div>
                        <div class="form-group">
                            <label>任务状态</label>
                            <select name="status" class="form-control">
                                <option value="" {%if $query['status']%}selected{%/if%}>全部</option>
                                <option value="1" {%if $query['status'] == 1%}selected{%/if%}>上线</option>
                                <option value="2" {%if $query['status'] == 2%}selected{%/if%}>下线</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>审核状态</label>
                            <select name="auth" class="form-control">
                                <option value="" {%if $query['auth']%}selected{%/if%}>全部</option>
                                <option value="1" {%if $query['auth'] == 1%}selected{%/if%}>未审核</option>
                                <option value="2" {%if $query['auth'] == 2%}selected{%/if%}>已审核</option>
                                <option value="3" {%if $query['auth'] == 3%}selected{%/if%}>未通过</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>任务分组</label>
                            <select name="task_group" class="form-control">
                                <option value="" {%if $query['task_group']%}selected{%/if%}>全部</option>
                                {%foreach $task_group_list as $item%}
                                <option value="{%$item.id%}" {%if $query['task_group'] == $item.id%}selected{%/if%}>{%$item.group_name%}</option>
                                {%/foreach%}
                            </select>
                        </div>
                        <button type="submit" id="submitSave" class="btn btn-success btn-bigger">查询</button>
                        <button type="button" onclick="window.location.href='/cttask/cttask';" class="btn btn-success btn-bigger">全部</button>
                        </p>
                    </form>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>任务ID</th>
                            <th>任务名称</th>
                            <th>任务等级</th>
                            <th>任务分组</th>
                            <th>运行时间</th>
                            <th>调用命令</th>
                            <th>部署机器</th>
                            <th>执行账号</th>
                            <th>任务状态</th>
                            <th>审核状态</th>
                            <th>更新时间</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        {%foreach $list as $task%}
                        <tr>
                            <td width="4%">{%$task['id']%}</td>
                            <td width="12%">{%$task['task_name']%}</td>
                            <td width="5%">{%$task['task_level_name']%}</td>
                            <td width="5%">{%$task['task_group_name']%}</td>
                            <td width="9%">{%$task['crontab_time']%}</td>
                            <td width="22%">{%$task['run_command']%}</td>
                            <td width="8%">
                                <a class="btn btn-success detail"
                                   data-api=""
                                   data-param='{"task_id":"{%$task.id%}","host_name": "{%$task.host_name%}"}' href="javascript:void(0);">查看详细</a>
                            </td>
                            <td width="4%">{%$task['run_user']%}</td>
                            <td width="4%">
                                {%if $task['status'] == 1%}
                                上线
                                {%elseif $task['status'] == 2%}
                                下线
                                {%/if%}
                            </td>
                            <td width="4%">
                                {%if $task['auth'] == 1%}
                                未审核
                                {%elseif $task['auth'] == 2%}
                                已审核
                                {%elseif $task['auth'] == 3%}
                                未通过
                                {%/if%}
                            </td>
                            <td width="9%">{%$task['update_time']%}</td>
                            <td width="16%">

                                {%if $is_op && $task.auth == 1 && ($task.task_level == 1 || $task.task_level == 2)%}
                                <a class="btn btn-info check"
                                   data-api="/cttask/task/check"
                                   data-param='{"id":"{%$task.id%}"}'
                                   href="javascript:void(0);">审核</a>
                                {%/if%}

                                {%if $is_op || $task.auth == 2%}
                                <button type="button" id="selectbtn-{%$task.id%}" class="btn btn-info" onclick="selectOption({%$task.id%})">操作</button>
                                {%/if%}

                                <!--<a class="btn btn-success operate"
                                   data-api="/cttask/task/task"
                                   data-param='{"song_id":"{%$task.id%}"}' href="javascript:void(0);">操作</a>-->

                                {%if $is_op || $task.auth == 2 || $task.auth == 3%}
                                <a href="/cttask/task/edit?id={%$task.id%}" class="btn btn-success">编辑</a>
                                {%/if%}

                                <button type="button" class="btn btn-warning delete" data-param='{"id": {%$task.id%}}'>删除</button>

                                <div class="modal-dialog" style="left: 80%;top:20%;position: fixed;width: 200px;height:200px;z-index: 99999;display: none;" id="modal-dialog-{%$task.id%}">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button class="close" aria-label="Close" data-dismiss="modal" type="button" onclick="closeOption({%$task.id%})">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <h4 id="myModalLabel" class="modal-title">操作</h4>
                                        </div>
                                        <div class="modal-body" style="height:180px;OVERFLOW-Y: auto; OVERFLOW-X:hidden;">
                                            <a href="javascript:void(0)" onclick="runTask(1,{%$task.id%})">任务上线</a><br>
                                            <a href="javascript:void(0)" onclick="runTask(2,{%$task.id%})">任务下线</a><br>
                                            <a href="javascript:void(0)" onclick="runTask(3,{%$task.id%})">任务暂停</a><br>
                                            <a href="javascript:void(0)" onclick="runTask(4,{%$task.id%})">任务恢复</a><br>
                                            <a href="javascript:void(0)" onclick="runTask(6,{%$task.id%})">立即执行</a>

                                            <!--
                                            //上线   	function:AddTask		action: ONLINE		1
                                            //下线		function:ModifyTask		action: OFFLINE		2
                                            //暂停   	function:ModifyTask 	action: PAUSE		3
                                            //恢复		function:ModifyTask		action: RESUME		4
                                            //删除		function:ModifyTask		action: REMOVE		5
                                            //立即执行 	function:Execute		action: EXECUTE		6
                                            -->

                                        </div>
                                    </div>
                                </div>

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