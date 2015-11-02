<?php include("view/users/detail_common_header.tpl") ?>
        <h3>■現在の登録状況は以下の通りです。</h3>
        <table border="0" cellspacing="3" cellpadding="3">
        <tr>
          <td><table width="100%" border="0" cellpadding="5" cellspacing="0" class="inputTab">
            <tr>
              <th width="105" scope="col">職員番号</th>
              <td scope="col"><?php echo $mgr->getOutputData('staff_id') ?></td>
            </tr>
            <tr>
              <th>パスワード</th>
              <td><?php echo $mgr->getOutputData('salary_passwd_msg') ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td align="left"><img src="image/space.gif" alt="" width="1" height="5" /></td>
        </tr>
        <tr>
          <td>
            <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
            </td>
        </tr>
    </table>
