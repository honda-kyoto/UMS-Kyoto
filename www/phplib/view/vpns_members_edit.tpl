<?php include("view/head.tpl") ?>
        <input type="hidden" name="vpn_id" id="vpn_id" value="<?php echo $mgr->getRequestData('vpn_id') ?>">
        <input type="hidden" name="vpn_user_id" id="vpn_user_id" value="<?php echo $mgr->getRequestData('vpn_user_id') ?>">
        <table cellspacing="0" >
          <tr>
            <td>■必要事項を入力して「更新」ボタンをクリックしてください。</td>
          </tr>
          <tr>
            <td><div class="inputComment">(*)は必須項目です。</div></td>
          </tr>
          <tr>
            <td>
<?php include("view/vpns/members_regist_tab.tpl") ?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td><input type="button" value="　　　更　　　新　　　" onclick="editMember();" />
              <input type="button" value="　　　リセット　　　" onclick="resetMember();" />
              <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
            </td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>
