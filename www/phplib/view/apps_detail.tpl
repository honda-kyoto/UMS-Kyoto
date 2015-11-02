<?php include("view/head.tpl") ?>
        <input type="hidden" name="app_id" id="app_id" value="<?php echo $mgr->getRequestData('app_id') ?>">
        <input type="hidden" name="entry_no" id="entry_no" value="<?php echo $mgr->getRequestData('entry_no') ?>">
        <table cellspacing="0" >
          <tr>
            <td>■現在以下の内容で登録されています。</td>
          </tr>
<?php if ($mgr->is_vdi_app && ($mgr->isVlanAdminUser())) { ?>
          <tr>
            <td><div class="inputComment">※仮想環境での利用を変更する場合はチェックボックスを１度クリックしてください。</div></td>
          </tr>
<?php } ?>
          <tr>
            <td>
<?php include("view/apps/view_tab.tpl") ?>
            </td>
          </tr>
          <tr>
            <td><img src="image/space.gif" alt="" width="1" height="20" /></td>
          </tr>
<?php if ($mgr->wire_kbn == WIRE_KBN_WLESS) { ?>
          <tr>
            <td>■無線LAN設定</td>
          </tr>
          <tr>
            <td>
<?php include("view/apps/wireless_view_tab.tpl") ?>
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
            <td>
<?php if ($mgr->isNormalUser()) { ?>
<?php   if ($mgr->getOutputData('is_other_user')) { ?>
              <input type="button" value="　　　変　　　更　　　" disabled />
<?php   } else { ?>
              <input type="button" value="　　　変　　　更　　　" onclick="appsRevise();" />
<?php   } ?>
<?php } ?>
              <input type="button" value="　　一覧に戻る　　" onclick="returnList();" /></td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>
