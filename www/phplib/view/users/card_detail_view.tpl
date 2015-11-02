<?php include("view/users/detail_common_header.tpl") ?>
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
              <th rowspan="2" scope="col">状態</th>
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
              <td align="center">利用中</td>
<?php     } else { ?>
              <td align="center">停止中</td>
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