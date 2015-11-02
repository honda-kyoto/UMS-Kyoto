<?php include("view/head.tpl") ?>
      <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td><div class="inputComment">※現在のパスワードをそのまま利用する場合はこちらから先へお進みください。</div></td>
        </tr>
        <tr>
          <td align="right"><input type="button"  value="　　現在のパスワードをそのまま使用する＞＞　　" onclick="escapePasswd();" /></td>
        </tr>
        <tr>
          <td><img src="image/space.gif" width="1" height="15" /></td>
        </tr>
        <tr>
          <td>■パスワード初期設定</td>
        </tr>
        <tr>
          <td><span class="alartMsg">※数字、英字大文字、英字小文字のみ使用可能です。<br />
          ※数字、英字大文字、英字小文字を各1文字以上必ず使用してください。<br />
          ※6文字以上20文字以内で記入して下さい。</span></td>
        </tr>
        <tr>
          <td><table border="0" cellspacing="2" cellpadding="0">
              <tr>
                <td>新しいパスワード</td>
                <td align="center">：</td>
                <td><input name="login_passwd" type="password" id="login_passwd" size="30" maxlength="20" /></td>
              </tr>
              <tr>
                <td>新しいパスワード（確認用）</td>
                <td align="center">：</td>
                <td><input name="login_passwd_conf" type="password" id="login_passwd_conf" size="30" maxlength="20" /></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><input type="button" value="　　入力したパスワードに変更　　" onclick="updatePasswd();" /></td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>
