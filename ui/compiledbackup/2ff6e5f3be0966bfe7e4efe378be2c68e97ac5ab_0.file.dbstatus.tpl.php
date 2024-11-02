<?php
/* Smarty version 4.3.1, created on 2024-08-17 21:38:42
  from 'C:\xampp\htdocs\ymax\ui\themes\nova\dbstatus.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.1',
  'unifunc' => 'content_66c0a7e26270b7_60870052',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2ff6e5f3be0966bfe7e4efe378be2c68e97ac5ab' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ymax\\ui\\themes\\nova\\dbstatus.tpl',
      1 => 1723885591,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sections/header.tpl' => 1,
    'file:sections/footer.tpl' => 1,
  ),
),false)) {
function content_66c0a7e26270b7_60870052 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:sections/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="row">
    <div class="col-sm-7">
        <div class="panel panel-primary">
            <div class="panel-heading">Backup Database</div>
            <form method="post" action="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
settings/dbbackup">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="50%"><?php echo Lang::T('Table Name');?>
</th>
                                <th><?php echo Lang::T('Rows');?>
</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['tables']->value, 'tbl');
$_smarty_tpl->tpl_vars['tbl']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['tbl']->value) {
$_smarty_tpl->tpl_vars['tbl']->do_else = false;
?>
                                <tr>
                                    <td><?php echo $_smarty_tpl->tpl_vars['tbl']->value['name'];?>
</td>
                                    <td><?php echo $_smarty_tpl->tpl_vars['tbl']->value['rows'];?>
</td>
                                    <td><input type="checkbox" checked name="tables[]" value="<?php echo $_smarty_tpl->tpl_vars['tbl']->value['name'];?>
"></td>
                                </tr>
                            <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                        </tbody>
                    </table>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">Dont select logs if it failed</div>
                        <div class="col-md-4 text-right">
                            <button type="submit" class="btn btn-primary btn-xs btn-block"><i
                                    class="fa fa-download"></i>
                                <?php echo Lang::T('Download Database Backup');?>
</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="col-sm-5">
        <div class="panel panel-primary">
            <div class="panel-heading">Restore Database</div>
            <form method="post" action="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
settings/dbrestore" enctype="multipart/form-data">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-7"><input type="file" name="json" accept="application/json"></div>
                        <div class="col-md-5 text-right">
                            <button type="submit" class="btn btn-primary btn-block btn-xs"><i class="fa fa-upload"></i>
                                Restore Dabase</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="panel-footer">Restoring database will clean up data and then restore all the data</div>
        </div>
    </div>
</div>

<?php $_smarty_tpl->_subTemplateRender("file:sections/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
