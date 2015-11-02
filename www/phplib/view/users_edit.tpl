<?php include("view/head.tpl") ?>
      <input type="hidden" name="user_id" id="user_id" value="<?php echo $mgr->getRequestData('user_id') ?>">
      <input type="hidden" name="is_unknown_user" id="is_unknown_user" value="<?php echo $mgr->getRequestData('is_unknown_user') ?>">
      <input type="hidden" name="col_name" id="col_name" value="">
      <input type="hidden" name="list_no" id="list_no" value="">
      <div id="topBtnArea">
        <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
      </div>
      <div style="clear:both;"></div>
      <h3>■必要事項を入力して「更新」ボタンをクリックしてください。</h3>
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
          <td>
            <input type="button" value="　　　更　　　新　　　" onclick="editUser();" />
            <input type="button" value="　　　リセット　　　" onclick="resetUser();" />
            <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
          </td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>

