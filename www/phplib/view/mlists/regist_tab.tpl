            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th width="100" scope="col">名称<span class="hissu">*</span></th>
              <td scope="col"><input name="mlist_name" type="text" id="mlist_name" size="30" value="<?php echo $mgr->getRequestData('mlist_name') ?>" /></td>
              </tr>
            <tr>
              <th width="100">アカウント<span class="hissu">*</span></th>
              <td><input name="mlist_acc" type="text" id="mlist_acc" size="20" value="<?php echo $mgr->getRequestData('mlist_acc') ?>" /><?php echo MLIST_MAIL_DOMAIN ?></td>
            </tr>
            <tr>
              <th>管理者<span class="hissu">*</span></th>
              <td>
                <table border="0" cellspacing="0" cellpadding="3">
                  <tbody id="adminList">
<?php if (is_array($mgr->request['admin_id'])) { ?>
<?php   foreach ($mgr->request['admin_id'] AS $key => $admin_id) { ?>
                  <tr>
                    <td style="padding:2px;"><input type="hidden" name="admin_id[<?php echo $key ?>]" id="admin_id_<?php echo $key ?>" value="<?php echo $mgr->getRequestData('admin_id', $key) ?>"><?php echo $mgr->getOutputData('admin_name', $key) ?></td>
                    <td>[<a href="javascript:;" onclick="removeAdminList(this);">削除</a>]</td>
                  </tr>
<?php   } ?>
<?php } ?>
                  </tbody>
                  </tr>
                  <tr>
                    <td colspan="2">
                     <input type="hidden" name="maxAdminListKey" id="maxAdminListKey" value="<?php echo ($key == "" ? 0 : $key) ?>">
                     [<a href="javascript:;" onclick="searchAdminUser();">検索</a>]
                     </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <th>制限設定</th>
              <td><div class="CheckBoxTab">
                <?php echo $mgr->getRadioButtonList('sender_kbn') ?>
              </div></td>
            </tr>
            <tr>
              <th>種別</th>
              <td>
                <table style="padding:0;margin:0">
                  <tr>
                    <td>
<?php if ($mgr->getRequestData('mlist_id') == "") { ?>
                      <select name="mlist_kbn" id="mlist_kbn">
<?php } else { ?>
                      <select name="mlist_kbn" id="mlist_kbn" disabled>
<?php } ?>
                        <?php echo $mgr->getSelectList('mlist_kbn') ?>
                      </select>
                    </td>
                    <td><span class="inputComment">　※変更できません。</span></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
<?php if ($mgr->isNormalUser()) { ?>
              <th width="120">用途<span class="hissu">*</span></th>
<?php } else { ?>
              <th width="120">用途</th>
<?php } ?>
              <td colspan="3"><textarea name="usage" cols="40" rows="4" id="usage">
<?php echo $mgr->getRequestData('usage') ?></textarea></td>
            </tr>
            <tr>
              <th>備考</th>
              <td><textarea name="note" cols="40" rows="4" id="note">
<?php echo $mgr->getRequestData('note') ?></textarea></td>
            </tr>
          </table>

