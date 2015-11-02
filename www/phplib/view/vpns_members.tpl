<?php include("view/head.tpl") ?>
      <input type="hidden" name="vpn_id" id="vpn_id" value="<?php echo $mgr->getRequestData('vpn_id') ?>">
      <input type="hidden" name="vpn_user_id" id="vpn_user_id" value="">
      <div id="topBtnArea">
        <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
      </div>
      <div style="clear:both;"></div>
      <table border="0" cellpadding="3" cellspacing="3">
        <tr>
          <td><div class="inputComment">(*)は必須項目です。</div></td>
        </tr>
        <tr>
          <td>
            <h3>■メンバー追加</h3>
            <table border="0" cellpadding="2" cellspacing="0" class="listTab">
              <tr>
                <th>メールアドレス<span class="hissu">*</span></th>
                <th>有効期限<span class="hissu">*</span></th>
                <th>氏名</th>
                <th>フリガナ</th>
                <th>会社</th>
                <th>連絡先</th>
                <th>備考</th>
                <th nowrap>追加</th>
              </tr>
              <tr>
                <td><input name="mail_addr" type="text" id="mail_addr" size="20" value="<?php echo $mgr->getRequestData('mail_addr') ?>"  /></td>
                <td><input name="expiry_date" type="text" id="expiry_date" onchange="cal_expiry.getFormValue(); cal_expiry.hide();" onclick="cal_expiry.write();" size="10" value="<?php echo $mgr->getRequestData('expiry_date') ?>"  /><br /><div id="caldiv_expiry"></div></td>
                <td><input name="kanjiname" type="text" id="kanjiname" size="12" value="<?php echo $mgr->getRequestData('kanjiname') ?>"  /></td>
                <td><input name="kananame" type="text" id="kananame" size="12" value="<?php echo $mgr->getRequestData('kananame') ?>"  /></td>
                <td><input name="company" type="text" id="company" size="10" value="<?php echo $mgr->getRequestData('company') ?>"  /></td>
                <td><input name="contact" type="text" id="contact" size="10" value="<?php echo $mgr->getRequestData('contact') ?>"  /></td>
                <td><input name="note" type="text" id="note" size="20" value="<?php echo $mgr->getRequestData('note') ?>"  /></td>
                <td align="center" nowrap>[<a href="javascript:;" onclick="addMember();">追加</a>]</td>
              </tr>
            </table>
<?php if (is_array(@$mgr->aryList)) { ?>
            <div class="resultBlock">
              <h3>■メンバー一覧</h3>
<?php   if (count($mgr->aryList) > 0) { ?>
              <table border="0" cellpadding="2" cellspacing="0" class="listTab">
                <tr>
                  <th><input type="checkbox" name="allcheck" value="1" onClick="clickAllCheckBox(this, 'checked_id[]')" /></th>
                  <th>ID</th>
                  <th>パスワード</th>
                  <th>メールアドレス</th>
                  <th nowrap>有効期限</th>
                  <th>氏名</th>
                  <th>会社</th>
                  <th nowrap>詳細</th>
                  <th nowrap>削除</th>
                </tr>
<?php     foreach ($mgr->aryList AS $id => $data) { ?>
                <tr>
                  <td align="center"><input type="checkbox" name="checked_id[]" value="<?php echo $id ?>" /></td>
                  <td><?php echo $id ?></td>
                  <td nowrap><span id="passwd_<?php echo $id ?>">********</span>&nbsp;&nbsp;[<a id="passwd_view_<?php echo $id ?>" href="javascript:;" onclick="viewPasswd('<?php echo $id ?>');">表示</a><a id="passwd_hide_<?php echo $id ?>" href="javascript:;" onclick="hidePasswd('<?php echo $id ?>');" style="display:none;">隠す</a>]</td>
                  <td><?php echo $data['mail_addr'] ?></td>
                  <td nowrap><?php echo $data['expiry_date'] ?></td>
                  <td width="100"><?php echo $data['kanjiname'] ?></td>
                  <td><?php echo $data['company'] ?></td>
                  <td align="center" nowrap>[<a href="javascript:;" onclick="editMembers('<?php echo $id ?>');">詳細</a>]</td>
                  <td align="center" nowrap>[<a href="javascript:;" onclick="deleteMember('<?php echo $id ?>');">削除</a>]</td>
                </tr>
<?php     } ?>
              </table>
              <table cellspacing="0" class="listTabB">
                <tr>
                  <td colspan="2"><div class="inputComment">※発行するVPN ID通知書を選択してください。</div></td>
                </tr>
                <tr>
                  <td colspan="2" align="right"><input type="button" name="btnPrint" value="　　　VPN ID通知書を発行　　　" OnClick="javascript:printVpns();"></td>
               </tr>
               <tr><td colspan="2">&nbsp;</td></tr>
               <tr>
                 <td colspan="2"><div class="inputComment">※更新するメンバーを選択してください。</div></td>
               </tr>
               <tr>
                 <td align="right">
                   <table>
                     <tr>
                       <td>有効期限(一括更新):</td>
                       <td><input type="text" name="update_expiry_date" onchange="cal_updexpiry.getFormValue(); cal_updexpiry.hide();" onclick="cal_updexpiry.write();" size="10" value="<?php echo $mgr->getRequestData('update_expiry_date') ?>" ><br /><div id="caldiv_updexpiry"></div></td>
                       <td align="right" style="width: 180px;">
                         <input type="button" name="btnUpdateExpiry" value="　　　有効期限を一括更新　　　" OnClick="javascript:updateExpiry();">
                       </td>
                     </tr>
                   </table>
                 </td>
               </tr>
             </table>
<?php   } else { ?>
        <table cellspacing="0">
          <tr>
            <td align="left">現在登録されているデータはありません</td>
          </tr>
        </table>
<?php   } ?>
            </div>
<?php } ?>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>
            <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
          </td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>

