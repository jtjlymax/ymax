<?php
/* Smarty version 4.3.1, created on 2024-08-17 21:59:08
  from 'C:\xampp\htdocs\ymax\ui\themes\nova\reports-period-view.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.1',
  'unifunc' => 'content_66c0acac36d412_98812886',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1f5f7204916dded3a808c432362bd440aa8319ed' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ymax\\ui\\themes\\nova\\reports-period-view.tpl',
      1 => 1723885594,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:sections/header.tpl' => 1,
    'file:sections/footer.tpl' => 1,
  ),
),false)) {
function content_66c0acac36d412_98812886 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:sections/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<!-- reports-period-view -->

<div class="row">
    <div class="col-md-12">
        <div class="invoice-wrap">
            <div class="clearfix invoice-head">
                <h3 class="brand-logo text-uppercase text-bold left mt15">
                     <span class="text"><?php echo Lang::T('Daily Reports');?>
</span>
                </h3>
            </div>
            <div class="clearfix invoice-subhead mb20">
                <div class="group clearfix left">
     <p class="text-bold mb5"><?php echo Lang::T('All Transactions at Date');?>
:</p>
                    <p class="small"><?php echo $_smarty_tpl->tpl_vars['stype']->value;?>
 [<?php echo date($_smarty_tpl->tpl_vars['_c']->value['date_format'],strtotime($_smarty_tpl->tpl_vars['fdate']->value));?>
 -
                        <?php echo date($_smarty_tpl->tpl_vars['_c']->value['date_format'],strtotime($_smarty_tpl->tpl_vars['tdate']->value));?>
]</p>
                </div>
                <div class="group clearfix right">
                    <form method="post" action="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
export/print-by-period" target="_blank">
                        <input type="hidden" name="fdate" value="<?php echo $_smarty_tpl->tpl_vars['fdate']->value;?>
">
                        <input type="hidden" name="tdate" value="<?php echo $_smarty_tpl->tpl_vars['tdate']->value;?>
">
                        <input type="hidden" name="stype" value="<?php echo $_smarty_tpl->tpl_vars['stype']->value;?>
">
                        <button type="submit" class="btn btn-default"><i class="fa fa-print"></i>
                               <?php echo Lang::T('Export for Print');?>
</button>
                    </form>
                    <form method="post" action="<?php echo $_smarty_tpl->tpl_vars['_url']->value;?>
export/pdf-by-period" target="_blank">
                        <input type="hidden" name="fdate" value="<?php echo $_smarty_tpl->tpl_vars['fdate']->value;?>
">
                        <input type="hidden" name="tdate" value="<?php echo $_smarty_tpl->tpl_vars['tdate']->value;?>
">
                        <input type="hidden" name="stype" value="<?php echo $_smarty_tpl->tpl_vars['stype']->value;?>
">
                        <button type="submit" class="btn btn-default"><i class="fa fa-file-pdf-o"></i>
                            <?php echo Lang::T('Export To PDF');?>
</button>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th><?php echo Lang::T('Username');?>
</th>
                            <th><?php echo Lang::T('Type');?>
</th>
                            <th><?php echo Lang::T('Plan Name');?>
</th>
                            <th><?php echo Lang::T('Plan Price');?>
</th>
                            <th><?php echo Lang::T('Created On');?>
</th>
                            <th><?php echo Lang::T('Expires On');?>
</th>
                            <th><?php echo Lang::T('Method');?>
</th>
                            <th><?php echo Lang::T('Routers');?>
</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['d']->value, 'ds');
$_smarty_tpl->tpl_vars['ds']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['ds']->value) {
$_smarty_tpl->tpl_vars['ds']->do_else = false;
?>
                            <tr>
                                <td><?php echo $_smarty_tpl->tpl_vars['ds']->value['username'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['ds']->value['type'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['ds']->value['plan_name'];?>
</td>
                                <td class="text-right"><?php echo Lang::moneyFormat($_smarty_tpl->tpl_vars['ds']->value['price']);?>
</td>
                                <td><?php echo Lang::dateAndTimeFormat($_smarty_tpl->tpl_vars['ds']->value['recharged_on'],$_smarty_tpl->tpl_vars['ds']->value['recharged_time']);?>
</td>
                                <td><?php echo Lang::dateAndTimeFormat($_smarty_tpl->tpl_vars['ds']->value['expiration'],$_smarty_tpl->tpl_vars['ds']->value['time']);?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['ds']->value['method'];?>
</td>
                                <td><?php echo $_smarty_tpl->tpl_vars['ds']->value['routers'];?>
</td>
                            </tr>
                        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
                    </tbody>
                </table>
            </div>
            <div class="clearfix text-right total-sum mb10">
                <h4 class="text-uppercase text-bold"><?php echo Lang::T('Total Income');?>
:</h4>
                <h3 class="sum"><?php echo Lang::moneyFormat($_smarty_tpl->tpl_vars['dr']->value);?>
</h3>
            </div>
            <p class="text-center small text-info"><?php echo $_smarty_tpl->tpl_vars['stype']->value;?>
 [<?php echo date($_smarty_tpl->tpl_vars['_c']->value['date_format'],strtotime($_smarty_tpl->tpl_vars['fdate']->value));?>
 -
                <?php echo date($_smarty_tpl->tpl_vars['_c']->value['date_format'],strtotime($_smarty_tpl->tpl_vars['tdate']->value));?>
]</p>
        </div>
    </div>
</div>

<?php $_smarty_tpl->_subTemplateRender("file:sections/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
