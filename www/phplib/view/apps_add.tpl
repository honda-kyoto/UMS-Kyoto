<?php include("view/head.tpl") ?>
        <table cellspacing="0" >
          <tr>
            <td>■必要事項を入力して「登録」ボタンをクリックしてください。</td>
          </tr>
          <tr>
            <td><div class="inputComment">(*)は必須項目です。</div></td>
          </tr>
          <tr>
            <td>
<?php include("view/apps/regist_tab.tpl") ?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
<?php if ($mgr->wire_kbn == WIRE_KBN_WLESS) { ?>
          <tr>
            <td>■無線LAN設定</td>
          </tr>
          <tr>
            <td>
<?php include("view/apps/wireless_regist_tab.tpl") ?>
            </td>
          </tr>
<?php } ?>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
          <tr>
            <td><input type="button" value="　　　登　　　録　　　" onclick="addApp();" />
              <input type="button" value="　　　リセット　　　" onclick="resetApp();" />
            </td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>
