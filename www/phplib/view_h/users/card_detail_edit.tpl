        <input type="hidden" name="list_no" id="list_no" value="">
<?php include("view/users/detail_common_header.tpl") ?>
        <h3>■新規カード発行</h3>
      <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td><table border="0" cellpadding="5" cellspacing="0" class="listTab">
            <tr>
              <th scope="col">カードNo.</th>
              <th scope="col">発行</th>
              </tr>
            <tr>
              <td><input name="card_no" type="text" id="card_no" value="<?php echo $mgr->getRequestData('card_no') ?>" size="10" maxlength="8" /></td>
              <td align="center">[<a href="javascript:;" onclick="addCardNo();">発行する</a>]</td>
              </tr>
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
<?php if (is_array($mgr->output['card_no'])) { ?>
            <table border="0" cellpadding="5" cellspacing="0" class="listTab">
              <tr>
                <th scope="col">カードNo.</th>
                <th colspan="2" scope="col">カード停止</th>
              </tr>
<?php   foreach ($mgr->output['card_no'] AS $list_no => $dummy) { ?>
              <tr>
                <td><?php echo $mgr->getOutputData('card_no', $list_no) ?></td>
<?php     if ($mgr->getOutputData('status', $list_no) != '9') { ?>
                <td align="center">
                  <select name="card_reason[<?php echo $list_no ?>]" id="card_reason_<?php echo $list_no ?>">
                    <option value="">--停止理由--</option>
                    <?php echo $mgr->getSelectList('card_reason', $list_no) ?>
                  </select></td>
                <td align="center">[<a href="javascript:;" onclick="stopCard(<?php echo $list_no ?>);">停止</a>]</td>
<?php     } else { ?>
                <td align="center"><?php echo $mgr->getOutputData('reason_text', $list_no) ?></td>
                <td align="center">-</td>
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
