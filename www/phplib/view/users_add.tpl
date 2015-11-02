<?php include("view/head.tpl") ?>
      <h3>■必要事項を入力して「登録」ボタンをクリックしてください。</h3>
      <table border="0" cellpadding="3" cellspacing="3">
        <tr>
          <td><div class="inputComment">(*)は必須項目です。</div></td>
        </tr>
        <tr>
          <td><div class="inputComment">(▲)は本システムを利用される方およびメール利用の場合は必ず入力してください。</div></td>
        </tr>
        <tr>
          <td><div class="inputComment">(★)は電カル連携される方は必ず入力してください。</div></td>
        </tr>
        <tr>
          <td>
<?php include("view/users/regist_tab.tpl") ?>
          </td>
        </tr>
        <tr>
          <td><input type="button" value="　　　登　　　録　　　" onclick="addUser();" />
            <input type="button" value="　　　リセット　　　" onclick="resetUser();" /></td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>

