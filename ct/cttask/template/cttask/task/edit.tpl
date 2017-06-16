{%extends file="`$tplDir`/default.tpl"%}
{%block name="head" append%}
<script type="text/javascript" src="/static/js/page/task/edit.min.js"></script>

<link rel="stylesheet" type="text/css" href="/static/css/style.css" />
<script type="text/javascript" src="/static/js/modules/PagerView.js"></script>
<script type="text/javascript" src="/static/js/modules/TableView.js"></script>
<script type="text/javascript" src="/static/js/modules/SelectorView.js"></script

<script type="text/javascript">
</script>
<style type="text/css">
    .width-400{
        width: 400px;
    }
    .p-color{
        color: #999999;
    }
    .width-200{
        width: 200px;
    }
    .crontab_time{
        width: 100px;
        float: left;
        margin-right: 20px;
    }
    .alarm_email{
        width: 240px;
        float: left;
        margin-right: 20px;
    }
</style>
{%/block%}
{%block name="content"%}
<div id="mis_content">
    <div class="row bread-row">
        <div class="col-lg-12">
            <h4 class="page-header">CT任务中心 > 编辑任务</h4>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">

                    <form class="form-horizontal" action="/cttask/task/update" method="post" role="form">
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                任务名称
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control width-400" name="task_name" id="task_name" value="{%$task_info.task_name%}">
                                <p class="p-color">
                                    不能为空，最多100个字符。
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                任务等级
                            </label>
                            <div class="col-sm-10">
                                <select name="task_level" id="task_level" class="form-control width-200">
                                    <option value="1" {%if $task_info.task_level == 1%}selected{%/if%}>一级任务</option>
                                    <option value="2" {%if $task_info.task_level == 2%}selected{%/if%}>二级任务</option>
                                    <option value="3" {%if $task_info.task_level == 3%}selected{%/if%}>三级任务</option>
                                </select>
                                <p class="p-color">
                                    一级任务： 属于一级产品线，并立即对线上产生重要影响<br>
                                    二级任务： 属于一级和二级产品线，但不会对线上产生较大影响或小时级产生影响<br>
                                    三级任务： 线下类CT任务或对线上影响很小，不影响用户体验<br>
                                </p>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom:0;">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                报警邮箱
                            </label>
                            <div class="col-sm-10">
                                <label style="width:260px;color: #999999;">RD邮箱</label>
                                <label style="width:260px;color: #999999;">Leader邮箱</label>
                                <label style="width:260px;color: #999999;{%if $task_info.task_level == 3%} display: none; {%/if%}" id="alarm_email_op_label" >OP邮箱</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input name="alarm_email" type="text" class="form-control alarm_email" value="{%$task_info.alarm_email%}">
                                <input name="alarm_email_leader" type="text" class="form-control alarm_email" value="{%$task_info.alarm_email_leader%}">
                                <input name="alarm_email_op" id="alarm_email_op" {%if $task_info.task_level == 3%}style="display: none;" {%/if%} type="text" class="form-control alarm_email" value="{%$task_info.alarm_email_op%}">
                            </div>
                            <label for="firstname" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <p class="p-color">
                                    支持多个值，以","(逗号)隔开,例如:zhangsan,lisi,wangwu
                                </p>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                运行失败次数
                            </label>
                            <div class="col-sm-10">
                                <label style="width:260px;color: #999999;">RD接收</label>
                                <label style="width:260px;color: #999999;">Leader接收</label>
                                <label style="width:260px;color: #999999;{%if $task_info.task_level == 3%} display: none; {%/if%}" id="run_fail_num_op_label">OP接收</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control alarm_email" name="run_fail_num" value="{%$task_info.run_fail_num%}">
                                <input type="text" class="form-control alarm_email" name="run_fail_num_leader" value="{%$task_info.run_fail_num_leader%}">
                                <input type="text" class="form-control alarm_email" name="run_fail_num_op" {%if $task_info.task_level == 3%}style="display: none;" {%/if%} id="run_fail_num_op_fail" value="{%$task_info.run_fail_num_op%}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                任务分组
                            </label>
                            <div class="col-sm-10">
                                <select name="task_group" class="form-control width-200">
                                    {%foreach $task_group_list as $key => $item%}
                                    {%if $task_info.task_group == $item.id%}
                                    <option value="{%$item.id%}" selected>{%$item.group_name%}</option>
                                    {%else%}
                                    <option value="{%$item.id%}">{%$item.group_name%}</option>
                                    {%/if%}
                                    {%/foreach%}
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                运行命令
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="run_command" id="run_command" value="{%$task_info.run_command%}">
                                <p class="p-color">
                                    CT不支持后台进程，包括运行命令中启动的后台进程。CT调度的命令只能是以下情况：<br>
                                    1、不启动后台进程。<br>
                                    2、启动后台进程，但是前台进程等待后台进程都退出后再退出。<br>
                                    3、后台进程自身守护进程化。注意有些supervise有bug，无法完成此任务。<br>
                                    4、前台进程协助后台进程守护进程化，即nohup ./background_task.sh &lt;/dev/null >/dev/null 2>&1 &，之后sleep 1秒。
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                调度时间
                            </label>
                            <div class="col-sm-10">
                                <input name="crontab_time[1]" type="text" class="form-control crontab_time" value="{%$task_info.ct1%}">
                                <input name="crontab_time[2]" type="text" class="form-control crontab_time" value="{%$task_info.ct2%}">
                                <input name="crontab_time[3]" type="text" class="form-control crontab_time" value="{%$task_info.ct3%}">
                                <input name="crontab_time[4]" type="text" class="form-control crontab_time" value="{%$task_info.ct4%}">
                                <input name="crontab_time[5]" type="text" class="form-control crontab_time" value="{%$task_info.ct5%}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                执行账号
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control width-400" name="run_user" id="run_user" value="{%$task_info.run_user%}">
                                <p class="p-color">
                                    默认为work。请不要使用root帐号
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                服务节点
                            </label>
                            <div class="col-sm-10">

                                <input name="service_node" type="text" class="form-control width-400" id="service_node" value="{%$task_info.service_node%}" readonly="readonly"><br>
                                <input type="button" class="btn btn-success btn-bigger" name="choose_host_group_input" id="choose_host_group_input" value="选择机器组">
                                <input type="button" class="btn btn-success btn-bigger" name="shield_host" id="shield_host" value="屏蔽机器">


                                <div class="modal-dialog" style="left: 30%;position: fixed;top: 20%;width: 600px;z-index: 99999;display: none;" id="choose_host_group">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button class="close"  id="group_close" aria-label="Close" data-dismiss="modal" type="button">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <h4 id="myModalLabel" class="modal-title">选择机器</h4>
                                        </div>
                                        <div class="modal-body" style="height:250px;OVERFLOW-Y: auto; OVERFLOW-X:hidden;">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>&nbsp;</th>
                                                    <th>ID</th>
                                                    <th>机器组名</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {%foreach $group_list as $task%}
                                                <tr>
                                                    <td>
                                                        <input type="radio" name="group_id" value="{%$task.id%}">
                                                    </td>
                                                    <td>{%$task.id%}</td>
                                                    <td>{%$task.group_name%}</td>
                                                </tr>
                                                {%/foreach%}
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-bigger btn-primary" data-dismiss="modal" type="button" onclick="selectGroup()">确认</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- 屏蔽机器 -->
                                <div id="dialog-form" title="屏蔽机器">
                                    <br><br>
                                    <div class="form-group">
                                        <div id="sel_div"></div>
                                    </div>
                                    <span id="error_info" style="color:red"></span>
                                </div><br><br>
                                <!-- 屏蔽机器 -->
                                <textarea rows="5" class="form-control width-400" cols="10" name="shield_host_list" id="shield_host_list">{%$shield_host_list%}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                运行成功判断条件
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control width-400" name="run_condition" id="run_condition" value="{%$task_info.run_condition%}">
                                <p class="p-color">
                                    支持多返回值，以","(逗号)隔开，范围为0-63、192-255，如0,1，0,3,4
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                等待超时时间
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control width-400" name="wait_timeout_time" id="wait_timeout_time" value="{%$task_info.wait_timeout_time%}">
                                <p class="p-color">
                                    默认为0。
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                运行超时时间
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control width-400" name="run_timeout_time" id="run_timeout_time" value="{%$task_info.run_timeout_time%}">
                                <p class="p-color">
                                    默认为0。
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">
                                <span class="have-to red">*</span>
                                管理者
                            </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control width-400" name="manager" id="manager" value="{%$task_info.manager%}">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="hidden" value="{%$task_info.id%}" name="id" id="task_id">
                                <button type="submit" class="btn btn-success btn-bigger">保存</button>
                                <button type="button" class="btn btn-bigger edit" onclick="location.href='/cttask/task/index'">取消</button>
                            </div>
                        </div>

                    </form>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
{%/block%}
