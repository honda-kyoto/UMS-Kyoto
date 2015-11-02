      <h3>■検索条件</h3>
      <table border="0" cellpadding="2" cellspacing="0" class="searchTab">
        <tr>
          <th width="80">名称</th>
          <td width="200"><input name="mlist_name" type="text" id="mlist_name" size="30" value="<?php echo $mgr->getRequestData('mlist_name') ?>"  /></td>
          <th width="80">アカウント</th>
          <td width="200"><input name="mlist_acc" type="text" id="mlist_acc" size="30" value="<?php echo $mgr->getRequestData('mlist_acc') ?>" /></td>
          <th width="80">種別</th>
          <td><select name="mlist_kbn" id="mlist_kbn">
              <option value=""></option>
              <?php echo $mgr->getSelectList('mlist_kbn') ?>
            </select></td>
          <td class="emptycol">&nbsp;</td>
        </tr>
<?php if (!$mgr->req_only && !$mgr->isAdminUser()) { ?>
        <tr>
          <th width="80">オプション</th>
          <td colspan="5">
            <div class="CheckBoxTab">
              <?php echo $mgr->getCheckBoxList('entry_kbn_status') ?>
            </div>
          </td>
        </tr>
<?php } ?>
      </table>
      <div class="searchBlock">
        <a href="javascript:search();"  /><img src="image/btn_search.gif" title="検索" /></a>
        <a href="javascript:clearCond();" /><img src="image/btn_clear.gif" title="条件クリア" /></a>
      </div>
