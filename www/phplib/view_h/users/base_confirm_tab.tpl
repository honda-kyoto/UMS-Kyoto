            <table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
              <tr>
                <th colspan="6" scope="col">基本情報</th>
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
<?php if (is_array($mgr->output['sub_belong_chg_id'])) { ?>
                    <tbody id="subBelongList">
<?php   foreach ($mgr->output['sub_belong_chg_id'] AS $key => $dummy_id) { ?>
                      <tr>
                        <td align="center">(<?php echo $key ?>)&nbsp;</td>
                        <td><?php echo $mgr->getOutputData('sub_belong_name', $key) ?>　<?php echo $mgr->getOutputData('sub_job_name', $key) ?>　<?php echo $mgr->getOutputData('sub_post_name', $key) ?></td>
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
            </table>
