{%extends file="`$tplDir`/default.tpl"%}
{%block name="head" append%}
<script type="text/javascript" src="/static/js/page/host/create.min.js"></script>
<script type="text/javascript">

</script>
{%/block%}
{%block name="content"%}
<div id="mis_content">
    <div class="row bread-row">
        <div class="col-lg-12">
            <h4 class="page-header">CT任务中心 > 添加机器</h4>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form class="form-horizontal" action="/cttask/host/store" method="POST" role="form">

                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">机器名称</label>
                            <div class="col-sm-10">
                                <input type="text" style="width: 300px" class="form-control" name="host_name" id="host_name" placeholder="请输入机器名称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="col-sm-2 control-label">IP地址</label>
                            <div class="col-sm-10">
                                <input type="text" style="width: 300px" class="form-control" name="ip" id="ip" placeholder="请输入IP地址">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-success btn-bigger edit">保存</button>
                                <button type="button" class="btn btn-bigger edit" onclick="location.href='/cttask/host/index'">取消</button>
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
