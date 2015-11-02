<?php include("view/head.tpl") ?>
      <input type="hidden" name="mlist_id" id="mlist_id" value="<?php echo $mgr->getRequestData('mlist_id') ?>">
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
          <td>
<?php include("view/mlists/regist_tab.tpl") ?>
          </td>
        </tr>
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
            <input type="button" value="　　　更　　　新　　　" onclick="editMlist();" />
            <input type="button" value="　　　リセット　　　" onclick="resetMlist();" />
            <input type="button" value="　　一覧に戻る　　" onclick="returnList();" />
          </td>
        </tr>
      </table>
<?php include("view/foot.tpl") ?>

