        <h3>■現在以下の内容で登録されています。</h3>
      <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td><table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th colspan="4">基本情報</th>
              </tr>
            <tr>
              <th width="100">氏名</th>
              <td><?php echo $mgr->getOutputData('kanjisei') ?>　<?php echo $mgr->getOutputData('kanjimei') ?>（<?php echo $mgr->getOutputData('kanasei') ?>　<?php echo $mgr->getOutputData('kanamei') ?>）</td>
              <th width="90">氏名英字</th>
              <td><?php echo $mgr->getOutputData('eijisei') ?> <?php echo $mgr->getOutputData('eijimei') ?>
                </td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
        <tr>
          <td>
<?php include("view/users/ncvc_confirm_tab.tpl") ?>
          </td>
        </tr>


<!--
        <tr>
          <td align="left"><table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th colspan="6">予約情報　※予約は１件しか登録できません。既に予約データがある場合上書きされます。</th>
            </tr>
            <tr>
              <th>予約登録</th>
              <td colspan="5"><div class="CheckBoxTab">
                <table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><input type="checkbox" name="mail_disused_flg" id="mail_disused_flg" value="1"  onclick="changeAccText(this);" /></td>
                    <td><label for="mail_disused_flg">予約登録する</label></td>
                  </tr>
                </table>
              </div></td>
            </tr>
            <tr>
              <th width="105">反映日<span class="hissu"></span></th>
              <td colspan="5"><table border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td><input name="start_date" type="text" id="start_date" onchange="cal_sd2.getFormValue(); cal_sd2.hide();" onclick="cal_sd2.write();" size="12" maxlength="10" />
                    <br />
                    <div id="caldiv_sd"></div></td>
                  </tr>
                </table></td>
            </tr>
            <tr>
              <th>予約状況</th>
              <td colspan="5">予約データなし</td>
            </tr>
          </table></td>
        </tr>
-->

        <tr>
          <td align="left"><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
        <tr>
          <td>
            <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
            <!--<input type="button" value="　　変更履歴一覧を表示　　" onclick="javascript:window.open('base_history.html', '_blank', 'width=1024, height=600, menubar=no, toolbar=no, scrollbars=yes');" /></td>-->
          </td>
        </tr>
      </table>