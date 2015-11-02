<?php include("view/users/detail_common_header.tpl") ?>
        <h3>■パスワードを入力して「更新」ボタンをクリックしてください。</h3>
        <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td><table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th width="105" scope="col">職員番号</th>
              <td scope="col"><?php echo $mgr->getOutputData('staff_id') ?></td>
            </tr>
            <tr>
              <th>パスワード</th>
              <td><input name="salary_passwd" type="text" id="salary_passwd" value="" size="18" maxlength="20" />
                [<a href="javascript:;" onclick="makePassword('salary_passwd');">自動生成</a>] <span class="inputRule">(半角数字、半角英字大文字、半角英字小文字　全て混在　6～20文字)</span></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
        <tbody id="hisDataList">
        </tbody>
        <tr>
          <td>
            <input type="button" value="　　　更　　　新　　　" onclick="editUser();" />
            <input type="button" value="　　　リセット　　　" onclick="resetUser();" />
            <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
            </td>
        </tr>
    </table>
