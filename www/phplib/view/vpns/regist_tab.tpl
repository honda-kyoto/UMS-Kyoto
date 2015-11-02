            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th>種別</th>
              <td>
                <select name="vpn_kbn" id="vpn_kbn">
                  <?php echo $mgr->getSelectList('vpn_kbn') ?>
                </select>
              </td>
            </tr>
            <tr>
              <th width="160" scope="col">ネットワークポリシー名<span class="hissu">*</span></th>
              <td scope="col"><input name="vpn_name" type="text" id="vpn_name" size="30" value="<?php echo $mgr->getRequestData('vpn_name') ?>" /></td>
            </tr>
            <tr>
              <th width="160">グループ／プロジェクト名<span class="hissu">*</span></th>
              <td><input name="group_name" type="text" id="group_name" size="20" value="<?php echo $mgr->getRequestData('group_name') ?>" /></td>
            </tr>
            <tr>
              <th width="160">グループコード<span class="hissu">*</span></th>
              <td><input name="group_code" type="text" id="group_code" size="8" value="<?php echo $mgr->getRequestData('group_code') ?>" maxlength="2" /><span class="inputRule">(英字大文字2文字)</span></td>
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
              <th width="160">用途</th>
              <td colspan="3"><textarea name="usage" cols="40" rows="4" id="usage">
<?php echo $mgr->getRequestData('usage') ?></textarea></td>
            </tr>
            <tr>
              <th>備考</th>
              <td><textarea name="note" cols="40" rows="4" id="note">
<?php echo $mgr->getRequestData('note') ?></textarea></td>
            </tr>
          </table>

