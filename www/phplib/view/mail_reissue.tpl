<?php include("view/head.tpl") ?>
      <table border="0" cellspacing="3" cellpadding="3">
<!--
        <tr>
          <td><div class="inputComment">※現在のメールアカウントをそのまま利用する場合はこちらから先へお進みください。</div></td>
        </tr>
        <tr>
          <td align="right"><input type="button"  value="　　現在のメールアカウントをそのまま使用する＞＞　　" onclick="escapeReissue();" /></td>
        </tr>
        <tr>
          <td><img src="image/space.gif" width="1" height="15" /></td>
        </tr>
-->
        <tr>
          <td>■メールアカウント再設定</td>
        </tr>
        <tr>
          <td><span class="alartMsg">※一度再設定すると変更できません。<br />
            ※利用可能文字は以下の通りです。<br />
            「数字、英字大文字・小文字、-（ハイフン）、_（アンダースコア）、.（ピリオド）」<br />
          ※3文字以上30文字以内で記入して下さい。</span></td>
        </tr>
        <tr>
          <td><input name="mail_acc" type="text" id="mail_acc" value="<?php echo $mgr->getRequestData('mail_acc') ?>" size="30" maxlength="30" />
            <?php echo USER_MAIL_DOMAIN ?> 　
            <input type="button" value="　　　再　設　定　　　" / onclick="updateMailAcc();" ></td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>
