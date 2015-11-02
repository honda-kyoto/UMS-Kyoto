        <input type="hidden" name="list_no" id="list_no" value="">
        <input type="hidden" name="ident_code" id="ident_code" value="">
<?php include("view/users/detail_common_header.tpl") ?>
        <h3>■新規カード発行</h3>
      <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td><table border="0" cellpadding="5" cellspacing="0" class="listTab">
            <tr>
<?php if ($mgr->has_his_data) { ?>
              <th scope="col">電カル情報</th>
<?php } else { ?>
              <th scope="col">個人番号</th>
<?php } ?>
              <th scope="col">識別コード</th>
              <th scope="col">発行</th>
            </tr>
<?php if (is_array($mgr->aryCardBase)) { ?>
<?php   foreach ($mgr->aryCardBase AS $list_no => $data) { ?>
            <tr>
              <td><?php echo $data['base_no'] ?></td>
              <td><?php echo $data['ident_code'] ?></td>
<?php     if ($data['has_card_data']) { ?>
              <td align="center">発行済</td>
<?php     } else { ?>
              <td align="center">[<a href="javascript:;" onclick="addCardNo(<?php echo $list_no ?>, '<?php echo $data['ident_code'] ?>');">発行する</a>]</td>
<?php     } ?>
            </tr>
<?php   } ?>
<?php } ?>
            </table></td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
      </table>
        <h3>■発行カード一覧</h3>
      <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td>
<?php if (is_array($mgr->output['ident_code'])) { ?>
          <table border="0" cellpadding="5" cellspacing="0" class="listTab">
            <tr>
              <th rowspan="2" scope="col">識別コード</th>
              <th rowspan="2" scope="col">カードNo.</th>
              <th colspan="4" scope="col">発行日・回数</th>
              <th colspan="2" rowspan="2" scope="col">再発行</th>
              <th colspan="2" rowspan="2" scope="col">カード停止</th>
            </tr>
            <tr>
              <th scope="col">初回</th>
              <th scope="col">最終</th>
              <th colspan="2" scope="col">回数</th>
            </tr>
<?php   foreach ($mgr->output['ident_code'] AS $list_no => $dummy) { ?>
            <tr>
              <td align="center"><?php echo $mgr->getOutputData('ident_code', $list_no) ?></td>
              <td align="center"><?php echo $mgr->getOutputData('card_no', $list_no) ?></td>
              <td align="center"><?php echo $mgr->getOutputData('first_issue_date', $list_no) ?></td>
              <td align="center"><?php echo $mgr->getOutputData('last_issue_date', $list_no) ?></td>
              <td align="right"><?php echo $mgr->getOutputData('issue_cnt', $list_no) ?></td>
              <td align="center">[<a href="#">履歴</a>]</td>
<?php     if ($mgr->getOutputData('status', $list_no) != '9') { ?>
              <td align="center"><select name="reissue_kbn[<?php echo $list_no ?>]" id="reissue_kbn_<?php echo $list_no ?>">
                <option value="">--再発行理由--</option>
                <?php echo $mgr->getSelectList('reissue_kbn', $list_no) ?>
              </select></td>
              <td align="center">[<a href="javascript:;" onclick="reissueCard(<?php echo $list_no ?>);">再発行</a>]</td>
              <td align="center"><select name="disuse_kbn[<?php echo $list_no ?>]" id="disuse_kbn_<?php echo $list_no ?>">
                <option value="">--停止理由--</option>
                <?php echo $mgr->getSelectList('disuse_kbn', $list_no) ?>
              </select></td>
              <td align="center">[<a href="javascript:;" onclick="stopCard(<?php echo $list_no ?>);">停止</a>]</td>
<?php     } else { ?>
              <td align="center" colspan="4">停止中</td>
<?php     } ?>
            </tr>
<?php   } ?>
          </table>
<?php } else { ?>
            ※現在発行されているカードはありません。
<?php } ?>
          </td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
        <tr>
          <td><input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
            </td>
        </tr>
      </table>
