            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th width="100" scope="col">名称</th>
              <td scope="col"><?php echo $mgr->getOutputData('mlist_name') ?></td>
              </tr>
            <tr>
              <th width="100">アカウント</th>
              <td><?php echo $mgr->getOutputData('mlist_acc') ?><?php echo MLIST_MAIL_DOMAIN ?></td>
            </tr>
            <tr>
              <th>管理者</th>
              <td>
                <table border="0" cellspacing="0" cellpadding="3">
                  <tbody id="adminList">
<?php if (is_array($mgr->request['admin_id'])) { ?>
<?php   foreach ($mgr->request['admin_id'] AS $key => $admin_id) { ?>
                  <tr>
                    <td style="padding:2px;"><?php echo $mgr->getOutputData('admin_name', $key) ?></td>
                  </tr>
<?php   } ?>
<?php } ?>
                  </tbody>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <th>制限設定</th>
              <td><?php echo $mgr->getOutputData('sender_kbn_name') ?></td>
            </tr>
            <tr>
              <th>種別</th>
              <td><?php echo $mgr->getOutputData('mlist_kbn_name') ?></td>
            </tr>
            <tr>
              <th width="120">用途</th>
              <td colspan="3"><?php echo nl2br($mgr->getOutputData('usage')) ?></td>
            </tr>
            <tr>
              <th>備考</th>
              <td><?php echo nl2br($mgr->getOutputData('note')) ?></td>
            </tr>
          </table>

