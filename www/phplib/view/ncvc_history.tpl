<?php include("view/win_head.tpl") ?>
      <input type="hidden" name="user_id" id="user_id" value="<?php echo $mgr->getRequestData('user_id') ?>">
      <input type="hidden" name="history_no" id="history_no" value="<?php echo $mgr->getRequestData('history_no') ?>" />
      <h3>■変更履歴</h3>
      <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td><table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th>履歴No.</th>
              <td><?php echo $mgr->getOutputData('list_no', $mgr->getRequestData('history_no')) ?></td>
              <th>変更日時</th>
              <td><?php echo $mgr->getOutputData('history_time', $mgr->getRequestData('history_no')) ?></td>
              <th>変更者</th>
              <td><?php echo $mgr->getOutputData('history_user_name', $mgr->getRequestData('history_no')) ?></td>
            </tr>
            <tr>
              <td colspan="6"><img src="image/space.gif" alt="" width="1" height="5" /></td>
            </tr>
          </table>
<?php include("view/users/ncvc_confirm_tab.tpl") ?>
          </td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
        <tr>
          <td><h3>●履歴一覧</h3></td>
        </tr>
        <tr>
          <td>
<?php if (is_array($mgr->output['list_no']) && count($mgr->output['list_no']) > 0) { ?>
            <table border="0" cellpadding="5" cellspacing="0" class="listTab">
            <tr>
              <th nowrap="nowrap" scope="col">No.</th>
              <th nowrap="nowrap">履歴作成日時</th>
              <th nowrap="nowrap">履歴作成者</th>
              <th nowrap="nowrap">参照</th>
            </tr>
<?php   foreach ($mgr->output['list_no'] AS $no => $dummy) { ?>
            <tr>
              <td align="right" nowrap="nowrap" scope="col"><?php echo $mgr->getOutputData('list_no', $no) ?></td>
              <td nowrap="nowrap"><?php echo $mgr->getOutputData('history_time', $no) ?></td>
              <td nowrap="nowrap"><?php echo $mgr->getOutputData('history_user_name', $no) ?></td>
<?php     if ($no == $mgr->getRequestData('history_no')) { ?>
              <td align="center" nowrap="nowrap">表示中</td>
<?php     } else { ?>
              <td align="center" nowrap="nowrap">[<a href="javascript:;" onclick="showHistory(<?php echo $no ?>);">参照</a>]</td>
<?php     } ?>
            </tr>
<?php   } ?>
          </table>
<?php } else { ?>
          ※変更履歴はありません。
<?php } ?>
         </td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td align="right"><input type="button" value="　　　閉じる　　　" onclick="javascript:window.close();" /></td>
        </tr>
        <tbody id="hisDataList">
        </tbody>
      </table>
<?php include("view/foot.tpl") ?>

