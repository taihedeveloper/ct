{%extends file="`$tplDir`/default.tpl"%}
{%block name="head" append%}
<script type="text/javascript" src="/static/js/page/group/create.min.js"></script>

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
            <h4 class="page-header">CT任务中心 > 添加组</h4>
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
                    <form class="form-horizontal" action="/cttask/group/store" method="POST">
                        <div class="form-group">
                            <label for="firstname" class="col-sm-2 control-label">组名</label>
                            <div class="col-sm-10">
                                <input type="text" style="width: 300px" class="form-control" name="group_name" id="group_name" placeholder="请输入组名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lastname" class="col-sm-2 control-label">机器名称</label>

                            <div class="col-sm-10">
                                <!--
                                <input type="text" class="form-control" name="host_name" id="host_name" placeholder="请输入机器名称">
                                <button onclick="selecthost()" type="button" class="btn btn-success btn-bigger" data-toggle="modal" data-target="#myModal">选择服务器</button>
                                -->
                                <div id="sel_div"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <!--<button type="submit" class="btn btn-success btn-bigger edit">保存</button>-->
                                <button type="button" class="btn btn-success btn-bigger save">保存</button>
                                <button type="button" class="btn btn-bigger edit" onclick="location.href='/cttask/group/index'">取消</button>
                            </div>
                        </div>

                        <!--机器列表-->
                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document" style="left: 18%;position: fixed;top: 2%;width: 600px">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">选择机器</h4>
                                    </div>
                                    <div class="modal-body" id="hostList">
                                    </div>
                                    <div class="modal-footer">
                                        <button onclick="chooseHost()" type="button" class="btn btn-primary btn-bigger" data-dismiss="modal">确认</button>
                                    </div>
                                </div>
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

