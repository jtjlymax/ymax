<?php
/* Smarty version 4.3.1, created on 2024-08-19 12:33:16
  from 'C:\xampp\htdocs\ymax\ui\themes\nova\autoload.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.1',
  'unifunc' => 'content_66c2cb0c779764_96649349',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a08f9af738d0a61811f21b37ffeef35044e50c75' => 
    array (
      0 => 'C:\\xampp\\htdocs\\ymax\\ui\\themes\\nova\\autoload.tpl',
      1 => 1723885590,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66c2cb0c779764_96649349 (Smarty_Internal_Template $_smarty_tpl) {
?><option value="">Select Plans</option>
<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['d']->value, 'ds');
$_smarty_tpl->tpl_vars['ds']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['ds']->value) {
$_smarty_tpl->tpl_vars['ds']->do_else = false;
?>
<option value="<?php echo $_smarty_tpl->tpl_vars['ds']->value['id'];?>
"><?php if ($_smarty_tpl->tpl_vars['ds']->value['enabled'] != 1) {?>DISABLED PLAN &bull; <?php }
echo $_smarty_tpl->tpl_vars['ds']->value['name_plan'];?>
 &bull; <?php echo Lang::moneyFormat($_smarty_tpl->tpl_vars['ds']->value['price']);
if ($_smarty_tpl->tpl_vars['ds']->value['allow_purchase'] != 'yes') {?> &bull; HIDDEN PLAN  <?php }?></option>
<?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);
}
}
