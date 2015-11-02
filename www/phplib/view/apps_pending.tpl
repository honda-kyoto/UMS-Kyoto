<?php include("view/head.tpl") ?>
        <input type="hidden" name="app_id" id="app_id" value="<?php echo $mgr->getRequestData('app_id') ?>">
        <input type="hidden" name="entry_no" id="entry_no" value="<?php echo $mgr->getRequestData('entry_no') ?>">
        <table cellspacing="0" >
          <tr>
<?php if ($mgr->getOutputData('entry_status') == ENTRY_STATUS_REJECT) { ?>
<?php   if ($mgr->getOutputData('entry_kbn') == ENTRY_KBN_DEL) { ?>
            <td>■削除申請が却下されました。再申請される場合は「申請取消」後一覧より再度削除申請を行ってください。。</td>
<?php   } else { ?>
            <td>■申請が却下されました。却下理由をご確認のうえ「再申請」または「申請取消」を行ってください。</td>
<?php   } ?>
<?php } else { ?>
            <td>■現在以下の内容で申請中です。申請を取り消す場合は「申請取消」ボタンを押してください。</td>
<?php } ?>
          </tr>
          <tr>
          </tr>
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
          <tr>
            <td>
<?php if ($mgr->getOutputData('entry_status') == ENTRY_STATUS_REJECT && $mgr->getOutputData('entry_kbn') != ENTRY_KBN_DEL) { ?>
<?php   if ($mgr->getOutputData('entry_id') == $mgr->getLoginData('LOGIN_USER_ID')) { ?>
              <input type="button" value="　　　再　申　請　　　" onclick="retryApp();" />
<?php   } else { ?>
              <input type="button" value="　　　再　申　請　　　" disabled />
<?php   } ?>
<?php } ?>
<?php if ($mgr->getOutputData('entry_id') == $mgr->getLoginData('LOGIN_USER_ID')) { ?>
              <input type="button" value="　　　申　請　取　消　　　" onclick="cancelApp();" />
<?php } else { ?>
              <input type="button" value="　　　申　請　取　消　　　" disabled />
<?php } ?>
              <input type="button" value="　　一覧に戻る　　" onclick="returnList();" /></td>
          </tr>
        </table>
<?php include("view/foot.tpl") ?>
