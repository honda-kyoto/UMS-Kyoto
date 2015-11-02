              <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
                <tr>
                  <th width="120" scope="col">申請者所属</th>
                  <td colspan="3" scope="col"><?php echo $mgr->getOutputData('entry_user_belong_name') ?></td>
                </tr>
                <tr>
                  <th width="120" scope="col">申請者</th>
                  <td width="120" scope="col"><?php echo $mgr->getOutputData('entry_user_name') ?></td>
                  <th width="100" scope="col">申請日時</th>
                  <td scope="col"><?php echo $mgr->getOutputData('entry_time') ?></td>
                </tr>
<?php if ($mgr->getOutputData('entry_status') == ENTRY_STATUS_AGREE) { ?>
                <tr>
                  <th width="120" scope="col">承認者</th>
                  <td width="120" scope="col"><?php echo $mgr->getOutputData('agree_user_name') ?></td>
                  <th width="100" scope="col">承認日時</th>
                  <td scope="col"><?php echo $mgr->getOutputData('agree_time') ?></td>
                </tr>
<?php } ?>
                <tr>
                  <th width="120" scope="col">内容</th>
                  <td width="120" scope="col"><?php echo $mgr->getOutputData('entry_kbn_name') ?></td>
                  <th width="100" scope="col">状況</th>
                  <td scope="col"><?php echo $mgr->getOutputData('entry_status_name') ?></td>
                </tr>
<?php if ($mgr->getOutputData('entry_status') == ENTRY_STATUS_REJECT) { ?>
                <tr>
                  <th width="120" scope="col">却下理由</th>
                  <td colspan="3" scope="col"><?php echo nl2br($mgr->getOutputData('agree_note')) ?></td>
                </tr>
<?php } else if ($mgr->is_agree_mode) { ?>
                <tr>
                  <th width="120" scope="col">却下理由</th>
                  <td colspan="3" scope="col"><textarea name="agree_note" cols="40" rows="4" id="agree_note">
<?php echo $mgr->getRequestData('agree_note') ?></textarea></td>
                </tr>
<?php } ?>
              </table>
