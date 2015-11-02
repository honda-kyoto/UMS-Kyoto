<?php include("view/head.tpl") ?>
      <h3>■必要事項を入力して「登録」ボタンをクリックしてください。</h3>
      <table border="0" cellpadding="3" cellspacing="3">
        <tr>
          <td><div class="inputComment">(*)は必須項目です。</div></td>
        </tr>
        <tr>
          <td>
<?php include("view/mlists/regist_tab.tpl") ?>
          </td>
        </tr>
        <tr>
          <td><input type="button" value="　　　登　　　録　　　" onclick="addMlist();" />
            <input type="button" value="　　　リセット　　　" onclick="resetMlist();" /></td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>

