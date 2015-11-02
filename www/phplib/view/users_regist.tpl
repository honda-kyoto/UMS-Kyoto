<?php include("view/head.tpl") ?>
      <h3>■必要事項を入力して「登録」ボタンをクリックしてください。</h3>
      <table border="0" cellpadding="3" cellspacing="3">
        <tr>
          <td><div class="inputComment">(＊)は必須項目です。</div></td>
        </tr>
        <tr>
          <td>
<?php include($mgr->getViewDirName()."/users/base_regist_tab.tpl") ?>
          </td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" width="1" height="5" /></td>
        </tr>
        <tr>
          <td>
<?php include("view/users/ncvc_regist_tab.tpl") ?>
          </td>
        </tr>
          </td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" width="1" height="5" /></td>
        </tr>
        <tr>
          <td>        <tr>
          <td><input type="button" value="　　　登　　　録　　　" onclick="addUser();" />
            <input type="button" value="　　　リセット　　　" onclick="resetUser();" /></td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>

