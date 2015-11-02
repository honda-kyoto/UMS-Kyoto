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
            <tr>
                <th width="100" scope="col">個人番号</th>
                <td scope="col" colspan="5"><div class="CheckBoxTab">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td><?php echo $mgr->getOutputData('staff_id') ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                      <td><input type="checkbox" name="staff_id_flg" id="staff_id_flg" value="1" <?php echo $mgr->getCheckData('staff_id_flg', '1') ?> disabled /></td>
                      <td><label for="staff_id_flg">職員番号として登録</label></td>
                    </tr>
                  </table></div>
                </td>
              </tr>
              <tr>
                <th width="100">氏名</th>
                <td><?php echo $mgr->getOutputData('kanjisei') ?>　<?php echo $mgr->getOutputData('kanjimei') ?></td>
                <th width="100">氏名カナ</th>
                <td><?php echo $mgr->getOutputData('kanasei') ?>　<?php echo $mgr->getOutputData('kanamei') ?></td>
                <th width="100">氏名英字</th>
                <td><?php echo $mgr->getOutputData('eijisei') ?> <?php echo $mgr->getOutputData('eijimei') ?></td>
              </tr>
              <tr>
                <th width="100">戸籍上の氏名</th>
                <td><?php echo $mgr->getOutputData('kanjisei_real') ?> <?php echo $mgr->getOutputData('kanjimei_real') ?></td>
                <th width="100">戸籍氏名カナ</th>
                <td><?php echo $mgr->getOutputData('kanasei_real') ?> <?php echo $mgr->getOutputData('kanamei_real') ?></td>
                <th width="100">旧姓</th>
                <td><?php echo $mgr->getOutputData('kyusei') ?></td>
              </tr>
              <tr>
                <th width="100">性別</th>
                <td><?php echo $mgr->getOutputData('sex_name') ?></td>
                <th width="100">生年月日</th>
                <td colspan="3"><?php echo $mgr->getOutputData('birthday') ?></td>
              </tr>
              <tr>
                <th width="100">組織(メイン)</th>
                <td colspan="5"><?php echo $mgr->getOutputData('belong_name') ?>　<?php echo $mgr->getOutputData('job_name') ?>　<?php echo $mgr->getOutputData('post_name') ?></td>
              </tr>
              <tr>
                <th width="100">組織(サブ)</th>
                <td colspan="5">
                  <table border="0" cellpadding="0" cellspacing="2">
<?php if ($mgr->output['sub_data_cnt'] > 0) { ?>
                    <tbody id="subBelongList">

<?php   for ($key = 1 ; $key <= $mgr->output['sub_data_cnt'] ; $key++) { ?>
                      <tr>
                        <td align="center">(<?php echo $key ?>)&nbsp;</td>
                        <td><?php echo $mgr->getOutputData('sub_belong_name', $key) ?>　<?php echo $mgr->getOutputData('sub_job_name', $key) ?>　<?php echo $mgr->getOutputData('sub_post_name', $key) ?>　<?php echo ($mgr->getOutputData('sub_staff_id', $key) != "" ? "職員番号：".$mgr->getOutputData('sub_staff_id', $key) : "") ?></td>
                      </tr>
<?php   } ?>
                    </tbody>
<?php } ?>
                  </table></td>
              </tr>
              <tr>
                <th width="100">常勤／非常勤</th>
                <td colspan="5"><?php echo $mgr->getOutputData('joukin_kbn_name') ?></td>
              </tr>
              <tr>
                <th width="100">PHS番号</th>
                <td><?php echo $mgr->getOutputData('pbno') ?></td>
                <th width="100">内線</th>
                <td colspan="3"><?php echo $mgr->getOutputData('naisen') ?></td>
              </tr>
              <tr>
                <th width="100">備考</th>
                <td colspan="5"><?php echo nl2br($mgr->getOutputData('note')) ?></td>
              </tr>
          </table></td>
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

