<?php include("view/head.tpl") ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td><img src="image/space.gif" width="1" height="100" /></td>
        </tr>
        <tr>
          <td align="center"><table border="0" cellspacing="3" cellpadding="3">
              <tr>
                <td>ID</td>
                <td>：</td>
                <td><input name="login_id" type="text" id="login_id" value="<?php echo $mgr->getRequestData('login_id') ?>" size="30" maxlength="50" /></td>
              </tr>
              <tr>
                <td>Password</td>
                <td>：</td>
                <td><input name="login_passwd" type="password" id="login_passwd" size="30" maxlength="20" onkeydown="callLogin(event);" /></td>
              </tr>
              <tr>
                <td colspan="3" align="center"><input type="button"  value="　　ログイン　　" onclick="login();" /></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td><img src="image/space.gif" alt="" width="1" height="100" /></td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>