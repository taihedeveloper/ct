{%extends file="`$tplDir`/default.tpl"%}
{%block name="head" append%}
<script type="text/javascript" src="/static/js/page/group/edit.min.js"></script>

<link rel="stylesheet" type="text/css" href="/static/css/style.css" />
<script type="text/javascript" src="/static/js/modules/PagerView.js"></script>
<script type="text/javascript" src="/static/js/modules/TableView.js"></script>
<script type="text/javascript" src="/static/js/modules/SelectorView.js"></script>

<script type="text/javascript">
</script>
<style type="text/css">
    #sel_div{
        margin: 6px 2px;
        padding: 4px;
        border: 1px solid #999;
    }
</style>
{%/block%}
{%block name="content"%}
<div id="mis_content">
    <div class="row bread-row">
        <div class="col-lg-12">
            <h4 class="page-header">CT任务中心 > 编辑组</h4>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p>
                        说明：
                        双击条目即添加
                    </p>
                </div>
                <div class="panel-body">

                    <form class="form-horizontal" action="/cttask/group/update" method="POST">
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">组名</label>
                            <div class="col-sm-10">
                                <input type="text" style="width: 300px" class="form-control" name="group_name" id="group_name" placeholder="请输入组名" value="{%$group_info.group_name%}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="col-sm-2 control-label">机器名称</label>
                            <div class="col-sm-10">
                                <div id="sel_div"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="hidden" value="{%$group_info.id%}" name="id" id="group_id">
                                <button type="button" class="btn btn-success btn-bigger save">保存</button>
                                <button type="button" class="btn btn-bigger edit" onclick="location.href='/cttask/group/index'">取消</button>
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

