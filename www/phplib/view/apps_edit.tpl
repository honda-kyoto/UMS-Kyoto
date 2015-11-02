<?php include("view/head.tpl") ?>
        <input type="hidden" name="app_id" id="app_id" value="<?php echo $mgr->getRequestData('app_id') ?>">
        <table cellspacing="0" >
          <tr>
            <td>■必要事項を入力して「更新」ボタンをクリックしてください。</td>
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
<?php if ($mgr->getOutputData('entry_status') == ENTRY_STATUS_AGREE) { ?>
          <tr>
            <td>■申請状況</td>
          </tr>
          <tr>
            <td>
<?php include("view/common_entry_tab.tpl") ?>
            </td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
<?php } ?>
          <tr>
            <td><input type="button" value="　　　更　　　新　　　" onclick="editApp();" />
              <input type="button" value="　　　リセット　　　" onclick="resetApp();" />
              <input type="button" value="　　一覧に戻る　　" onclick="returnList();" /></td>
            </td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>
